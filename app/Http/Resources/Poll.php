<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Choice as ChoiceResource;
use App\Http\Resources\Result as ResultResource;
use Carbon\Carbon;
use App\Models\Poll as Pollmodel;
use App\Models\Vote;
use App\Models\Choice as Choicemodel;

class Poll extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $array = [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'created_by' => $this->created_by,
            // 'creator' => new UserResource($this->whenLoaded('user')),
            'creator' => $this->whenLoaded('user')->username,
            'created_at' => Carbon::parse($this->created_at)->format('Y-m-d H:i:s'),
            'deadline' => $this->deadline,
            'choices' => ChoiceResource::collection($this->whenLoaded('choice')),
        ];

        if ($this->deadline <= Carbon::now()) {
            $poll = Pollmodel::find($this->id);
            $choices = $poll->choice()->get();
            $array['result'] = ResultResource::collection($choices);
        } else {
            $array['result'] = "this poll is still ongoing";
        }
        $poll = Pollmodel::find($this->id);
        if (auth()->user()->role == "user") {
            if ($this->deadline <= Carbon::now() || $poll->isVoted() > 0) {
                return $array;
            } else {
                return null;
            }
        } else {
            return $array;
        }
    }
}

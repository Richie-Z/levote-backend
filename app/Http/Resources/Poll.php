<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Choice as ChoiceResource;
use App\Http\Resources\Result as ResultResource;
use Carbon\Carbon;

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
            $array['result'] = new ResultResource($this->deadline);
        } else {
            $array['result'] = "this poll is still ongoing";
        }
        if (auth()->user()->role == "user") {
            if ($this->deadline <= Carbon::now()) {
                return $array;
            } else {
                return null;
            }
        } else {
            return $array;
        }
    }
}

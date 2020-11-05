<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Division;
use App\Models\Vote;
use App\Models\Poll;
use App\Models\Choice;
use Illuminate\Support\Facades\DB;

class Result extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function VoteOnly($id)
    {
        $choice = Vote::where('choice_id', $id)->count();
        return $choice;
    }
    public function DivVote()
    {
        $array = array($this->id);
        $choice = Vote::where('choice_id', $array)->get();
        foreach ($choice as $votes) {
        }
        $choices = Vote::where('poll_id', $votes->poll_id)
            ->where('division_id', $votes->division_id)
            ->where('choice_id', $this->id)
            ->count();
        $point = 1 / $choices;

        return $point;
    }
    public function toArray($request)
    {
        return [
            $this->choice => $this->DivVote(),
        ];
    }
}

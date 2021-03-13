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
    public function ElectionVote($id)
    {
        $array = array($id);
        $choice = Vote::whereIn('choice_id', $array)->get();
        // SELECT COUNT(1) AS total FROM votes WHERE division_id = 1 AND choice_id IN (3,4) GROUP BY choice_id
        $div = array_unique($choice->pluck('division_id')->toArray());
        $division = Division::whereIn('id', $div)->get();
        $count = 0;
        foreach ($choice as $votes) {
            $choices = Vote::where('poll_id', $votes->poll_id)
                ->whereIn('division_id', $div)
                ->whereIn('choice_id', $array)
                ->count();
        }
        // $point = 1 / $choices;
        // $ar = implode(" ", array(count($choice)));
        // return $ar;
        // return array_map('intval', explode(',', $ar));
        return $choices;
    }
    public function toArray($request)
    {
        return [
            $this->choice => $this->VoteOnly($this->id),
        ];
        // return $this->ElectionVote($this->id);
    }
}

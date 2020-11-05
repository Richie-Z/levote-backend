<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\Choice;
use Carbon\Carbon;
use App\Models\Vote;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Poll as PollResources;

class PollController extends Controller
{
    public function create(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'string|required',
            'description' => 'string|required',
            'deadline' => 'date|required',
            'choices' => 'array|required|min:2',
            'choices.*' => 'string|required|distinct'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'message' => 'the given data was invalid',
                'error' => $validate->messages()
            ], 422);
        }
        $deadline = $request->deadline;
        $date = Carbon::parse($deadline)->format('Y-m-d H:i:s');
        // $deadline = $date->toRfc3339String();

        $poll = new Poll;
        $poll->title = $request->title;
        $poll->description = $request->description;
        $poll->deadline = $date;
        $poll->created_by = Auth::user()->id;
        $poll->save();


        foreach ($request->choices as $ch) {
            $poll->choice()->create([
                'choice' => $ch
            ]);
        }
        return $poll;
    }
    public function get()
    {
        $query = Poll::all();
        return PollResources::collection($query);
        // return $query;
    }
    public function details($poll_id)
    {
        $query = Poll::where('id', $poll_id)->get();
        return PollResources::collection($query);
    }
    public function vote($poll_id, $choice_id)
    {
        $poll = Poll::find($poll_id);
        $choice = Choice::find($choice_id);
        $user = Auth::user();
        if ($poll->deadline <= Carbon::now()) {
            return response()->json(['message' => 'voting deadline'], 422);
        }
        if ($poll->isVoted() > 0) {
            return response()->json(['message' => 'already voted'], 422);
        }
        if ($choice == null) {
            return response()->json(['message' => 'invalid choice'], 422);
        }
        if ($poll == null) {
            return response()->json(['message' => 'invalid poll'], 422);
        }
        if ($poll->id != $choice->poll_id) {
            return response()->json(['message' => 'choice not match'], 422);
        }
        $vote = new Vote;
        $vote->choice_id = $choice->id;
        $vote->user_id = $user->id;
        $vote->poll_id = $poll->id;
        $vote->division_id = $user->division_id;
        $vote->save();
        return response()->json(['message' => 'voting success'], 200);
    }
    public function delete($poll_id)
    {
        $poll = Poll::find($poll_id);
        $poll->delete();
        return response()->json(['message' => 'delete poll success'], 200);
    }
}

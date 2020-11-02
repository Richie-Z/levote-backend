<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\Choice;
use Carbon\Carbon;
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
        return "test user";
    }
    public function delete($poll_id)
    {
        $poll = Poll::find($poll_id);
        $poll->delete();
        return response()->json(['message' => 'delete poll success'], 200);
    }
}

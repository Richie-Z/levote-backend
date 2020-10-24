<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Poll;
use App\Models\Choice;

class PollController extends Controller
{
    public function create(Request $request)
    {
        return "test admin";
    }
    public function get()
    {
        return "test admin";
    }
    public function details($poll_id)
    {
        return "test admin";
    }
    public function vote($poll_id, $choice_id)
    {
        return "test admin";
    }
    public function delete($poll_id)
    {
        $poll = Poll::find($poll_id);
        $poll->delete();
        return response()->json(['message' => 'delete poll success'], 200);
    }
}

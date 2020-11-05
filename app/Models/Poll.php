<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Poll extends Model
{
    use HasFactory;
    protected $table = "polls";
    protected $with = ['user', 'choice'];
    protected $fillable = ['title', 'description', 'deadline', 'created_by'];

    public function votes()
    {
        return $this->belongsToMany('App\Models\User', 'App\Model\Choice', 'App\Model\Division', 'votes');
    }
    public function choice()
    {
        return $this->hasMany('App\Models\Choice');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }
    public function isVoted()
    {
        $count = Vote::where('user_id', Auth::user()->id)
            ->where('poll_id', $this->id)
            ->count();
        return $count;
    }
}

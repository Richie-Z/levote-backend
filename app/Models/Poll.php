<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    use HasFactory;
    protected $table = "polls";
    protected $fillable = ['title', 'description', 'deadline', 'created_by'];

    public function votes()
    {
        return $this->belongsToMany('App\Models\User', 'App\Model\Choice', 'votes');
    }
    public function choice()
    {
        return $this->hasMany('App\Models\Choice');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    use HasFactory;
    protected $table = "choices";
    protected $fillable = ['choice', 'poll_id'];

    public function poll()
    {
        return $this->belongsTo('App\Models\Poll');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('choice_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('poll_id');
            $table->timestamps();
            $table->foreign('choice_id')->references('id')->on('choices');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('poll_id')->references('id')->on('polls');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('votes');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThreadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');           // Thread ID

            $table->unsignedInteger('author');  // Author ID
            $table->string('title');            // Thread title
            $table->text('body');               // Content
            $table->timestamp('last_post')->useCurrent();     // Last post
            $table->boolean('pinned')->default(false);  // If post is pinned or not

            $table->timestamps();               // Created/Updated at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}

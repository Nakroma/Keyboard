<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCallnamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('callnames', function (Blueprint $table) {
            $table->increments('id');           // Callname ID

            $table->unsignedInteger('author');  // Author ID
            $table->unsignedInteger('thread');  // Thread ID
            $table->string('callname');         // Callname

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
        Schema::dropIfExists('callnames');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keys', function (Blueprint $table) {
            $table->increments('id');

            $table->string('key_value')->unique();
            $table->boolean('used')->default(0);

            $table->rememberToken();
            $table->timestamps();
        });

        // Insert admin key
        DB::table('keys')->insert(
            array(
                'key_value' => 'ADMIN_KEY',
                'used' => false
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keys');
    }
}

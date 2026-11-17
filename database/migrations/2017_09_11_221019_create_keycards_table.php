<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeycardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keycards', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key_card_no');
            $table->integer('room_id')->unsigned();

            $table->integer('sort_no');

            $table->datetime('taken_date')->nullable();

            $table->foreign('room_id')
            ->references('id')
            ->on('rooms')
            ->onDelete('cascade')
            ->onUpdate('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keycards');
    }
}

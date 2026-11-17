<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomUtilityHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_utility_histories', function (Blueprint $table) {
            $table->increments('id');

            $table->boolean('is_use')->default(false); 
            $table->integer('room_stay_history_id')->unsigned();
            $table->foreign('room_stay_history_id')
            ->references('id')
            ->on('room_stay_histories')
            ->onDelete('restrict')
            ->onUpdate('cascade');

            $table->integer('setting_id')->unsigned();
            $table->foreign('setting_id')
            ->references('id')
            ->on('settings')
            ->onDelete('restrict')
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
        Schema::dropIfExists('room_utility_histories');
    }
}

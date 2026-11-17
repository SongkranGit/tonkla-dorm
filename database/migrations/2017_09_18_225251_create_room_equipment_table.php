<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_equipment', function (Blueprint $table) {
            $table->increments('id');
                        
            $table->integer('taken_quantity')->default(0);

            $table->datetime('taken_date')->nullable();

            $table->integer('room_id')->unsigned();
            $table->foreign('room_id')
            ->references('id')
            ->on('rooms')
            ->onDelete('restrict')
            ->onUpdate('cascade');

            $table->integer('sort_no');

            $table->integer('equipment_id')->unsigned();
            $table->foreign('equipment_id')
            ->references('id')
            ->on('equipments')
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
        Schema::dropIfExists('room_equipment');
    }
}

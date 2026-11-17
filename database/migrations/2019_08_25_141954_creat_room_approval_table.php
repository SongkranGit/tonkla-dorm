<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatRoomApprovalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_approval', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('room_id')->unsigned();
            $table->string('request_checking_type' , 20);
            $table->boolean('is_checkin_approved')->default(0);
            $table->boolean('is_checkout_approved')->default(0);
            $table->boolean('is_request_approve_checkin')->default(0);
            $table->boolean('is_request_approve_checkout')->default(0);
            $table->timestamps();

            $table->foreign('room_id')
                ->references('id')->on('rooms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_approval');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomStayHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('room_stay_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('status', ['checked_in', 'checked_out']);
            $table->datetime('check_in')->nullable();
            $table->datetime('check_out')->nullable();
 
            $table->string('renter_firstname')->nullable();
            $table->string('renter_lastname')->nullable();
            $table->string('renter_telephone')->nullable();
            $table->string('renter_id_card_no')->nullable(); 
            $table->string('renter_tax_payer_no')->nullable();
            $table->string('renter_school')->nullable();
            $table->string('renter_school_class')->nullable();
            $table->string('renter_parent_firstname')->nullable();
            $table->string('renter_parent_lastname')->nullable();
            $table->string('renter_parent_id_card_no')->nullable();
            $table->string('renter_parent_telephone')->nullable();
            $table->string('renter_current_address')->nullable();
 
            $table->double('deposit')->nullable();
            $table->double('deposit_keycard')->nullable();
            $table->double('deposit_other')->nullable();
            
            $table->double('rental_fee')->nullable();

            $table->double('discount')->nullable();
            $table->string('discount_remark')->nullable();

            $table->integer('electric_meter_start')->nullable();
            $table->integer('electric_meter_end')->nullable();
            $table->integer('electric_meter_unit')->nullable();
            $table->double('electric_meter_fee')->nullable();

            $table->integer('water_meter_start')->nullable();
            $table->integer('water_meter_end')->nullable();
            $table->integer('water_meter_unit')->nullable();
            $table->double('water_meter_fee')->nullable();
            
            $table->double('total_price')->nullable();

            $table->double('custom_rental_fee')->nullable();
            $table->boolean('is_use_custom_rental_fee')->default(false);
            $table->boolean('is_moved')->default(false);

            $table->integer('room_id')->unsigned();
            $table->foreign('room_id')
            ->references('id')
            ->on('rooms')
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
        Schema::dropIfExists('room_stay_histories');
    }
}

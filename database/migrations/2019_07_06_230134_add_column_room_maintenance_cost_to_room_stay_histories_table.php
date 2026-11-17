<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnRoomMaintenanceCostToRoomStayHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('room_stay_histories', function (Blueprint $table) {
            $table->double('room_maintenance_cost')->nullable()->after('water_meter_fee');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('room_stay_histories', function (Blueprint $table) {
            $table->dropColumn('room_maintenance_cost');
        });
    }
}

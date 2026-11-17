<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Room;

class AddSortOrderInRoomAndHouseRental extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->integer('sort_order')->default(0);
        });

        \DB::table('rooms')->insert(array (
            0 => 
            array (
                'room_no' => 'บ้านเช่า 14/68',
                'status' => 'available',
                'created_at' => '2018-08-04 01:25:09',
                'updated_at' => '2018-08-04 10:32:02',
            )
        ));
        foreach(Room::all() as $key => $item) {
            $item->sort_order = $key + 1;
            $item->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

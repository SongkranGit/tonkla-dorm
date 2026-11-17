<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->increments('id');
            $table->string('key');
            $table->string('value')->nullable();
            $table->string('locale')->nullable();
            $table->string('extra_key')->nullable();
            $table->integer('extra_sort_no')->unsigned()->nullable();
            $table->boolean('is_extra')->default(false);
            $table->boolean('is_use_calculate_vat')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('settings');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDamagesDisastersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damages_disasters', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('Date_report');
            $table->string('frequence');
            $table->integer('report_types_id')->unsigned();
            $table->foreign('report_types_id')->references('id')->on('report_types')->onDelete('cascade');
            $table->integer('hospitals_id')->unsigned();
            $table->foreign('hospitals_id')->references('id')->on('hospitals')->onDelete('cascade');
            $table->integer('formality');
            $table->integer('full_damages');
            $table->integer('very_heavy_damage');
            $table->integer('heavy_damage');
            $table->integer('apart_damage');
            $table->integer('under_water_less_1m');
            $table->integer('under_water_1_3m');
            $table->integer(' under_water_than_3m');
            $table->integer('damages_medicine');
            $table->integer('PCLB');
            $table->integer('ChloraminB');
            $table->integer('life_jacket');
            $table->string('details');
            $table->string('attachments');
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
        Schema::dropIfExists('damages_disasters');
    }
}

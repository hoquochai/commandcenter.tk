<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrendReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trend_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('date_trend_reports');
            $table->string('frequence');
            $table->integer('report_types_id')->unsigned();
            $table->foreign('report_types_id')->references('id')->on('report_types')->onDelete('cascade');
            $table->integer('hospitals_id')->unsigned();
            $table->foreign('hospitals_id')->references('id')->on('hospitals')->onDelete('cascade');
            $table->string('formality');
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
        Schema::dropIfExists('trend_reports');
    }
}

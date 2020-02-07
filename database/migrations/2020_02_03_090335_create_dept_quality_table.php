<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeptQualityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dept_quality', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('date_report');
            $table->string('frequence');
            $table->integer('report_types_id')->unsigned();
            $table->foreign('report_types_id')->references('id')->on('report_types')->onDelete('cascade');
            $table->integer('hospitals_id')->unsigned();
            $table->foreign('hospitals_id')->references('id')->on('hospitals')->onDelete('cascade');
            $table->string('formality');
            $table->text('note');
            $table->string('file');
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
        Schema::dropIfExists('dept_quality');
    }
}

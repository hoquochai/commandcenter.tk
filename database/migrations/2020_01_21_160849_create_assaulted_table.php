<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssaultedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assaulted', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('date_assaulted');
            $table->string('frequence');
            $table->integer('report_types_id')->unsigned();
            $table->foreign('report_types_id')->references('id')->on('report_types')->onDelete('cascade');
            $table->integer('assaulted_staffs_id')->unsigned();
            $table->foreign('assaulted_staffs_id')->references('id')->on('assaulted_staffs')->onDelete('cascade');
            $table->integer('hospitals_id')->unsigned();
            $table->foreign('hospitals_id')->references('id')->on('hospitals')->onDelete('cascade');
            $table->string('formality');
            $table->string('attachments');
            $table->string('assault_case');
            $table->string('reason');
            $table->string('information_person');
            $table->string('details');
            $table->string('resolution_no')->unique();
            $table->string('from_date');
            $table->string('to_date');
            $table->string('infomation_abuser');
            $table->string('verified_content');
            $table->string('conclude');
            $table->string('petition');
            $table->string('person_responsible');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('assaulted');
    }
}

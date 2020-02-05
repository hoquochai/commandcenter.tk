<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complains', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('date_complain');
            $table->string('frequence');
            $table->integer('report_types_id')->unsigned();
            $table->foreign('report_types_id')->references('id')->on('report_types')->onDelete('cascade');
            $table->integer('complainants_id')->unsigned();
            $table->foreign('complainants_id')->references('id')->on('complainants')->onDelete('cascade');
            $table->integer('hospitals_id')->unsigned();
            $table->foreign('hospitals_id')->references('id')->on('hospitals')->onDelete('cascade');
            $table->string('formality');
            $table->string('attachments');
            $table->string('content');
            $table->string('reason');
            $table->string('information_person');
            $table->string('note');
            $table->string('resolution_no')->unique();
            $table->string('from_date');
            $table->string('to_date');
            $table->string('requirement_of_complainant');
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
        Schema::dropIfExists('complains');
    }
}

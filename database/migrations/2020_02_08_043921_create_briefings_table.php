<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBriefingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('briefings', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('users_id')->unsigned()->nullable();
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('received_id')->unsigned()->nullable();
            $table->foreign('received_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('hospitals_id')->unsigned()->nullable();
            $table->foreign('hospitals_id')->references('id')->on('hospitals')->onDelete('cascade');
            $table->integer('report_types_id')->unsigned()->nullable();
            $table->foreign('report_types_id')->references('id')->on('report_types')->onDelete('cascade');
            $table->string('date_briefings');
            $table->string('title')->nullable();
            $table->integer('frequence')->nullable();
            $table->text('result');
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
        Schema::dropIfExists('briefings');
    }
}

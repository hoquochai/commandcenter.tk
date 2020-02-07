<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssaultedStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assaulted_staffs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('passport');
            $table->string('date_of_issue');
            $table->string('place_of_issue');
            $table->string('phone');
            $table->string('birthday');
            $table->integer('gender');
            $table->string('address');
            $table->integer('departments_id')->unsigned();
            $table->foreign('departments_id')->references('id')->on('departments')->onDelete('cascade');
            $table->integer('hospitals_id')->unsigned();
            $table->foreign('hospitals_id')->references('id')->on('hospitals')->onDelete('cascade');
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
        Schema::dropIfExists('assaulted_staffs');
    }
}

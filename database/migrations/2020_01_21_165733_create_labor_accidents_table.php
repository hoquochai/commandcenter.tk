<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLaborAccidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labor_accidents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('Date_report');
            $table->string('frequence');
            $table->integer('report_types_id')->unsigned();
            $table->foreign('report_types_id')->references('id')->on('report_types')->onDelete('cascade');
            $table->integer('hospitals_id')->unsigned();
            $table->foreign('hospitals_id')->references('id')->on('hospitals')->onDelete('cascade');
            $table->string('formality');
            $table->string('totals_accidents');
            $table->string('women_labor_accidents');
            $table->string('number_accidents');
            $table->string('number_labor_accidents');
            $table->string('number_died_people');
            $table->string('number_serious_people');
            $table->string('totals_salary_fund');
            $table->string('employer');
            $table->string('details');
            $table->string('damages');
            $table->string('total_fees');
            $table->string('salary_during_treatment');
            $table->string('Depts_specific_expenses');
            $table->string('indemnify');
            $table->string('demages_assets');
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
        Schema::dropIfExists('labor_accidents');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrgentReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('urgent_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('date_report');
            $table->integer('frequence');
            $table->integer('report_types_id')->unsigned();
            $table->foreign('report_types_id')->references('id')->on('report_types')->onDelete('cascade');
            $table->integer('patients_id')->unsigned();
            $table->foreign('patients_id')->references('id')->on('patients')->onDelete('cascade');
            $table->integer('hospitals_id')->unsigned();
            $table->foreign('hospitals_id')->references('id')->on('hospitals')->onDelete('cascade');
            $table->integer('departments_id')->unsigned();
            $table->foreign('departments_id')->references('id')->on('departments')->onDelete('cascade');
            $table->string('serious_problem_types_id');
            // $table->integer('areas_id')->unsigned();
            // $table->foreign('areas_id')->references('id')->on('areas')->onDelete('cascade');
            $table->bigInteger('users_id')->unsigned();
            $table->foreign('users_id')->references('id')->on('users')->onDelete('cascade');
            $table->bigInteger('received_id')->unsigned();
            $table->foreign('received_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('formality');
            $table->string('area');
            $table->string('report_number');
            $table->text('description');
            $table->text('firstAid');
            $table->text('proposed_solution');
            $table->integer('notify_doctor');
            $table->integer('notify_family');
            $table->integer('trouble_level');
            $table->integer('recorded_medical');
            $table->integer('notify_patient');
            $table->string('witnesses1');
            $table->string('witnesses2');
            $table->string('time_problem');
            $table->date('date_problem');
            $table->integer('problem_object');
            $table->text('note');
            $table->string('file');
            $table->string('name_reporter');
            $table->string('email_reporter');
            $table->string('phone_reporter');
            $table->integer('type_reporter');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('urgent_reports');
    }
}

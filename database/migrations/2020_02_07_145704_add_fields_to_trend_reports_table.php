<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToTrendReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trend_reports', function (Blueprint $table) {
            $table->string('date_urgent_report')->nullable();
            $table->string('date_assaulted_staff')->nullable();
            $table->string('date_complain')->nullable();
            $table->string('date_labor_accident')->nullable();
            $table->dropColumn('date_input');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trend_reports', function (Blueprint $table) {
            $table->string('date_input');
            $table->dropColumn(['date_urgent_report', 'date_assaulted_staff', 'date_complain', 'date_labor_accident']);
        });
    }
}

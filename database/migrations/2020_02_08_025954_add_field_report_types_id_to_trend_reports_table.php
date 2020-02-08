<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldReportTypesIdToTrendReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trend_reports', function (Blueprint $table) {
            $table->integer('report_types_id')->unsigned()->nullable();
            $table->foreign('report_types_id')->references('id')->on('report_types')->onDelete('cascade');
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
            $table->dropForeign('report_types_id');
            $table->dropColumn('report_types_id');
        });
    }
}

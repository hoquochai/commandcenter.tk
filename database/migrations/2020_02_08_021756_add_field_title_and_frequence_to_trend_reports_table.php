<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldTitleAndFrequenceToTrendReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trend_reports', function (Blueprint $table) {
            $table->string('title')->nullable();
            $table->integer('frequence')->nullable();
            $table->integer('hospitals_id')->unsigned()->nullable();
            $table->foreign('hospitals_id')->references('id')->on('hospitals')->onDelete('cascade');
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
            $table->dropForeign('hospitals_id');
            $table->dropColumn(['title', 'frequence', 'hospitals_id']);
        });
    }
}

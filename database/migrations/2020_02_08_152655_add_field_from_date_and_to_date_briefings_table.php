<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldFromDateAndToDateBriefingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('date_and_to_date_briefings', function (Blueprint $table) {
            $table->string('from_date')->nullable();
            $table->string('to_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('date_and_to_date_briefings', function (Blueprint $table) {
            $table->dropColumn(['from_date', 'to_date']);
        });
    }
}

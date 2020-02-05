<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->bigInteger('parent_id');
            $table->integer('hospitals_id')->nullable();
            $table->integer('positions_id')->unsigned();
            $table->foreign('positions_id')->references('id')->on('positions')->onDelete('cascade');
            $table->integer('account_types_id')->unsigned();
            $table->foreign('account_types_id')->references('id')->on('account_types')->onDelete('cascade');
            $table->integer('departments_id')->unsigned();
            $table->foreign('departments_id')->references('id')->on('departments')->onDelete('cascade');
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('status');
            $table->string('password');
            $table->string('image');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}

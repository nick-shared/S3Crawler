<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateS3crawlerprocessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s3crawlerprocesses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename')->nullable();
            $table->string('process_type')->nullable();
            $table->string('current_line_number')->nullable();
            $table->string('current_word')->nullable();
            $table->string('current_bucket')->nullable();
            $table->string('fail_exception')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::drop('s3crawlerprocesses');
    }
}

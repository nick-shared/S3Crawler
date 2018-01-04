<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateS3crawlerprocessTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s3crawlerprocess', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename')->nullable();
            $table->string('last_line_num')->nullable();
            $table->string('last_word')->nullable();
            $table->string('last_bucket')->nullable();
            $table->string('process_type')->nullable();
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
        Schema::drop('s3crawlerprocess');
    }
}

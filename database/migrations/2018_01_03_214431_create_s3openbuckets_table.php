<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateS3openbucketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('s3openbuckets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status')->nullable();
            $table->string('error_messages')->nullable();
            $table->string('bucketname')->nullable()->unique();
            $table->string('search_word')->nullable();
            $table->text('response')->nullable();
            $table->string('is_truncated')->nullable();
            $table->string('guzzle_response_state')->nullable();
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
        Schema::drop('s3openbuckets');
    }
}

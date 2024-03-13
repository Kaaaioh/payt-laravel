<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirect_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('redirect_id');
            $table->foreign('redirect_id')->references('id')->on('redirects')->onDelete('cascade');
            $table->string('user_ip');
            $table->string('user_agent');
            $table->string('header_refer')->nullable();
            $table->json('query_params')->nullable();
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
        Schema::dropIfExists('redirect_logs');
    }
};

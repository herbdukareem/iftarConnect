<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatitudeLongitude extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->string('phone_number')->nullable();
            $table->string('address');
            $table->string('landmark')->nullable();
            $table->string('address_url');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->string('phone_number')->nullable();
            $table->string('address');
            $table->string('landmark')->nullable();
            $table->string('address_url');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('organizers');
            $table->string('meal_type');
            $table->string('phone_number')->nullable();
            $table->string('address');
            $table->string('landmark')->nullable();
            $table->string('address_url');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('time_slot');
            $table->double('longitude');
            $table->double('latitude');
            $table->integer('maximum_capacity');
            $table->text('description')->nullable();
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
        Schema::dropIfExists('meals');
    }
}

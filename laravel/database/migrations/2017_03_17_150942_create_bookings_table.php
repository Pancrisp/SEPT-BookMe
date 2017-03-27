<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * **BOOKINGS**
     * -id (primary key)
     * -date
     * -start_time
     * -end_time
     * -customer_id (foreign key)
     * -business_id (foreign key)
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('booking_id');
            $table->string('date');
            $table->string('start_time');
            $table->string('end_time');
            $table->integer('customer_id')->unsigned();
            $table->integer('business_id')->unsigned();
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
        Schema::dropIfExists('bookings');
    }
}

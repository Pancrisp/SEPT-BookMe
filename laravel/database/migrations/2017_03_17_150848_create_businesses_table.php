<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * **BUSINESS**
     * -id (primary key)
     * -business_name
     * -owner_name
     * -username
     * -password
     * -email_address
     * -mobile_phone
     * -address
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->increments('business_id');
            $table->string('business_name');
            $table->string('owner_name');
            $table->string('username')->unique();
            $table->string('password');
            $table->string('mobile_phone');
            $table->string('email_address');
            $table->text('address');
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
        Schema::dropIfExists('businesses');
    }
}

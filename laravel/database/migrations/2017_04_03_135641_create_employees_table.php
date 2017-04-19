<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * employee_id (pk)
     * employee_name
     * TFN (unique)
     * mobile_phone
     * activity_id (fk)
     * available_days
     * business_id (fk)
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('employee_id');
            $table->string('employee_name');
            $table->integer('TFN')->unsigned()->unique();
            $table->string('mobile_phone');
            $table->integer('activity_id')->unsigned();
            $table->string('available_days');
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
        Schema::dropIfExists('employees');
    }
}

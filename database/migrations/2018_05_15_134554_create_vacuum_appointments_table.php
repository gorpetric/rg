<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacuumAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacuum_appointments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vacuum_appointment_group_id')->unsigned();
            $table->timestamp('appointment_at');
            $table->boolean('finished')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vacuum_appointment_group_id')->references('id')->on('vacuum_appointment_groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacuum_appointments');
    }
}

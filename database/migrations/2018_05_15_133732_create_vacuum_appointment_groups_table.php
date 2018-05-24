<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacuumAppointmentGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacuum_appointment_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vacuum_member_id')->unsigned();
            $table->integer('num_of_appointments')->unsigned();
            $table->integer('price_per_appointment')->unsigned();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vacuum_member_id')->references('id')->on('vacuum_members')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacuum_appointment_groups');
    }
}

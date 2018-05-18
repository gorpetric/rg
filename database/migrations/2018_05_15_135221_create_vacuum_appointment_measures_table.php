<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVacuumAppointmentMeasuresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vacuum_appointment_measures', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vacuum_appointment_id')->unsigned();
            $table->integer('vacuum_measure_body_part_id')->unsigned();
            $table->decimal('measure', 8, 2);
            $table->enum('before_or_after', ['B','A']);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('vacuum_appointment_id')->references('id')->on('vacuum_appointments')->onDelete('cascade');
            $table->foreign('vacuum_measure_body_part_id')->references('id')->on('vacuum_measure_body_parts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vacuum_appointment_measures');
    }
}

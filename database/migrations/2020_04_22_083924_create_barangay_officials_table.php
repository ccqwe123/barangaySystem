<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBarangayOfficialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('barangay_officials', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('position',array('kagawad','barangay captain','secretary','treasurer'))->default('barangay captain');
            $table->integer('barangay_id')->unsigned()->nullable();
            $table->foreign('barangay_id')->references('id')->on('barangay')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('barangay_officials');
    }
}

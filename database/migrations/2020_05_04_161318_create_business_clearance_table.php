<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBusinessClearanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_clearance', function (Blueprint $table) {
            $table->increments('id');
            $table->string('business_name');
            $table->string('location');
            $table->string('name');
            $table->string('address');
            $table->string('remarks');
             $table->enum('status',array('New Business','Renewal'))->default('New Business');
            $table->integer('requestor_resident_id')->unsigned()->nullable();
            $table->foreign('requestor_resident_id')->references('id')->on('residents')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('business_clearance');
    }
}

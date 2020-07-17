<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResidentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->date('birthdate');
            $table->date('birthplace')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender',array('male','female'))->default('male');
            $table->enum('civil_status',array('single','married','divorced','widowed','solo-parent'))->default('single');
            $table->string('mobile_no')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('employment_status');
            $table->string('occupation')->nullable();
            $table->string('address')->nullable();
            $table->integer('barangay_id')->unsigned();
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
        Schema::dropIfExists('residents');
    }
}

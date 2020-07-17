<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentCertificateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_certificate', function (Blueprint $table) {
            $table->increments('id');
            $table->string('school_name');
            $table->date('date');
            $table->string('student_name');
            $table->string('purpose');
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
        Schema::dropIfExists('student_certificate');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlotterPersonsofinterestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blotter_personsofinterest', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blotter_id')->unsigned()->nullable();
            $table->foreign('blotter_id')->references('id')->on('blotter')->onDelete('cascade');
            $table->enum('type',['victim','suspect','witness']);
            $table->string('name');
            $table->string('address');
            $table->string('telephone');
            $table->string('age');
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
        Schema::dropIfExists('blotter_personsofinterest');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlotterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blotter', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('provincial_id')->default(0);
            $table->integer('municipality_id')->default(0);
            $table->enum('type',['complaints','for_record','for_follow_up']);
            $table->string('lupon')->nullable();

            $table->dateTime('date_of_incident')->nullable();
            $table->string('address')->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->dateTime('time_reported')->nullable();
            $table->integer('type_of_crime')->nullable()->unsigned();
            $table->foreign('type_of_crime')
                ->references('id')
                ->on('crime_type')->onDelete('cascade')->onUpdate('cascade');
            $table->string('specify_crime')->nullable();
            $table->string('case_summary',10000)->nullable();
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('hearing_count')->default(0);
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
        Schema::dropIfExists('blotter');
    }
}

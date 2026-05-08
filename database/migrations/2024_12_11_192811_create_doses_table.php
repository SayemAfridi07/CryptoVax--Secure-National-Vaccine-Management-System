<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDosesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('doses', function (Blueprint $table) {
            $table->id();

            $table->foreignId('registration_id')->constrained();
            $table->foreignId('vaccine_id')->nullable()->constrained();

            $table->unsignedBigInteger('given_by')->nullable();
            $table->foreign('given_by')->references('id')->on('users');

            $table->string('dose_type');
            $table->date('scheduled_date')->nullable();
            $table->date('taken_date')->nullable();
            
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
        Schema::dropIfExists('doses');
    }
}

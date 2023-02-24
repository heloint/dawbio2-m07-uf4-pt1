<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // FIELDS
        // · id (disabled, autoincrement)
        // · first name (string)
        // · last name (string)
        // · date of birth (integer)
        // · salary (double)
        // · teamID (integer, unsigned,)
        // NOTE: Write it with camel case, because it will be used
        // by the model class to create object..
        Schema::create('players', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->integer('birth_year')->unsigned();
            $table->double('salary');
            $table->integer('team_id')->unsigned()->index()->nullable();
            $table->foreign('team_id')->references('id')->on('teams')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};

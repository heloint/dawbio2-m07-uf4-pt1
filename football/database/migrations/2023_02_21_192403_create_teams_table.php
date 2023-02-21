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
        //· id (disabled, autoincrement) -> In the edit form must contain the
        //                                  ID of the team which we are editing.
        //· name (string, unique)
        //· coach (string)
        //· category (string)
        //· budget (double)
        Schema::create('teams', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('coach');
            $table->string('category');
            $table->double('budget');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary(); // UUID as primary key
            $table->string('name'); // Employee name
            $table->string('phone')->nullable(); // Employee phone number
            $table->uuid('division_id'); // Foreign key to divisions table
            $table->string('position'); // Employee position
            $table->string('image')->nullable(); // URL to employee image
            $table->timestamps(); // Created at and updated at timestamps

            // Foreign key constraint
            $table->foreign('division_id')->references('id')->on('divisions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
}

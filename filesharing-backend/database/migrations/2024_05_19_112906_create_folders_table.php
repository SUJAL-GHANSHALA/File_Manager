<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folders', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Name of the folder
            $table->unsignedBigInteger('user_id'); // Foreign key referencing users table
            $table->string('path')->nullable(); // Path of the folder, can be null
            $table->unsignedBigInteger('parent_id')->nullable(); // Foreign key referencing parent folder, can be null
            $table->integer('nesting_level')->default(1); // Nesting level, default is 1
            $table->timestamps(); // Created_at and updated_at timestamps

            // Foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('folders'); // Drop the folders table if it exists
    }
}

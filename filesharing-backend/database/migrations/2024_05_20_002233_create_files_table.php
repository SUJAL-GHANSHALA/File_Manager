<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('name'); // Name of the file
            $table->string('extension'); // File extension (e.g., .jpg, .pdf)
            $table->unsignedBigInteger('size'); // File size in bytes
            $table->string('mime'); // MIME type of the file (e.g., image/jpeg)
            $table->string('path'); // Path where the file is stored
            $table->string('location'); // Location of the file, can be a URL or a local path
            $table->unsignedBigInteger('folder_id')->nullable(); // Foreign key referencing folders table, can be null
            $table->unsignedBigInteger('user_id'); // Foreign key referencing users table
            $table->boolean('is_starred')->default(false); // Boolean indicating if the file is starred
            $table->timestamps(); // Created_at and updated_at timestamps

            // Foreign key constraints
            $table->foreign('folder_id')->references('id')->on('folders')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files'); // Drop the files table if it exists
    }
}

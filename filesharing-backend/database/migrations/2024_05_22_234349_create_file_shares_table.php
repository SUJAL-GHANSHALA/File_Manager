<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileSharesTable extends Migration
{
    public function up()
    {
        Schema::create('file_shares', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Foreign key referencing users table, the user who shared the file
            $table->foreignId('file_id')->constrained()->onDelete('cascade'); // Foreign key referencing files table, the file being shared
            $table->foreignId('shared_with_user_id')->nullable()->constrained('users')->onDelete('cascade'); // Foreign key referencing users table, the user with whom the file is shared, can be null for public links
            $table->enum('access_control', ['public', 'private', 'restricted']); // Access control type
            $table->string('token')->unique(); // Unique token for the shared file
            $table->timestamps(); // Created_at and updated_at timestamps
        });
    }

    public function down()
    {
        Schema::dropIfExists('file_shares'); // Drop the file_shares table if it exists
    }
}

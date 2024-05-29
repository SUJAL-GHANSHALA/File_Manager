<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFileSharesTable extends Migration
{
    public function up()
    {
        Schema::create('file_shares', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  // The user who shared the file
            $table->foreignId('file_id')->constrained()->onDelete('cascade');  // The file being shared
            $table->foreignId('shared_with_user_id')->nullable()->constrained('users')->onDelete('cascade');  // The user with whom the file is shared, can be nullable for public links
            $table->enum('access_control', ['public', 'private', 'restricted']);
            $table->string('token')->unique(); // Add this line
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('file_shares');
    }
}


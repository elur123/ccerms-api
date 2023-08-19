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
        Schema::create('board_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('board_id')->constrained('boards');
            $table->foreignUuid('student_id')->constrained('users');
            $table->foreignId('status_id')->constrained('statuses');
            $table->integer('revision')->default(1);
            $table->tinyText('details');
            $table->string('file')->nullable();
            $table->tinyText('file_url')->nullable();
            $table->double('progress', 3, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('board_submissions');
    }
};

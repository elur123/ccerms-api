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
        Schema::create('research_archives', function (Blueprint $table) {
            $table->id();
            $table->string('group_name');
            $table->tinyText('title');
            $table->string('keywords')->nullable();
            $table->year('section_year_from');
            $table->year('section_year_to');
            $table->string('adviser');
            $table->foreignId('course_id')->constrained('courses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_archives');
    }
};

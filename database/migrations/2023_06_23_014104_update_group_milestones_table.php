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
        Schema::table('group_milestones', function (Blueprint $table) {
            $table->foreignId('group_id')->constrained('groups');
            $table->foreignId('milestone_id')->constrained('milestones');
            $table->foreignId('milestone_list_id')->constrained('milestone_lists')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group_milestones');
    }
};

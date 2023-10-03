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
        Schema::table('defense_schedules', function (Blueprint $table) {
            $table->foreignId('type_id')->constrained('defense_types');
            $table->foreignId('group_id')->constrained('groups');
            $table->foreignId('status_id')->constrained('statuses');
            $table->foreignId('step_id')->constrained('milestone_lists');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('defense_schedules');
    }
};

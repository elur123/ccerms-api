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
            $table->foreignId('milestone_list_id')->nullable()->constrained('milestone_lists');
            $table->foreignId('capstone_type_id')->constrained('capstone_types');
            $table->foreignId('endorse_list_id')->nullable()->constrained('milestone_lists');
            $table->boolean('is_open')->default(true);
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

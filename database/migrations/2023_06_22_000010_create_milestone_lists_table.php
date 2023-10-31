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
        Schema::create('milestone_lists', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->tinyText('description');
            $table->integer('percent');
            $table->integer('order_by');
            $table->boolean('adviser_first')->default(false);
            $table->boolean('has_adviser')->default(false);
            $table->boolean('has_panel')->default(false);
            $table->boolean('has_stat')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('milestone_lists');
    }
};

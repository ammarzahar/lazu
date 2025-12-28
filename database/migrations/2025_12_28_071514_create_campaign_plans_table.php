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
        Schema::create('campaign_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('marketing_event_id')->constrained()->cascadeOnDelete();
            $table->enum('objective', ['leads', 'sales', 'awareness']);
            $table->unsignedInteger('duration_days');
            $table->json('offer_plan')->nullable();
            $table->json('copy_pack')->nullable();
            $table->enum('status', ['draft', 'ready', 'launched', 'done'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaign_plans');
    }
};

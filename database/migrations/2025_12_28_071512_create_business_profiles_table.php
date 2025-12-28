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
        Schema::create('business_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('business_name');
            $table->enum('business_type', ['service', 'ecom', 'local']);
            $table->text('product_or_service');
            $table->decimal('price_min', 10, 2);
            $table->decimal('price_max', 10, 2);
            $table->unsignedInteger('gross_margin_pct')->nullable();
            $table->text('target_audience');
            $table->enum('main_channel', ['meta_ads', 'whatsapp', 'landing']);
            $table->enum('monthly_objective', ['leads', 'sales', 'awareness']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_profiles');
    }
};

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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('image_url');
            $table->decimal('percentage_raised')->default(0);
            $table->decimal('target_amount', 12);
            $table->string('currency', 3)->default('AED');
            $table->string('city_area');
            $table->string('country');
            $table->unsignedInteger('number_of_investors')->default(0);
            $table->decimal('investment_multiple');
            $table->decimal('current_amount', 12, 2)->default(0.00);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};

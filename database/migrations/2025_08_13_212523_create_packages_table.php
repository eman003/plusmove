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
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\Delivery::class)->nullable();
            $table->foreignIdFor(\App\Models\Address::class);;
            $table->unsignedInteger('status');
            $table->mediumText('delivery_note')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};

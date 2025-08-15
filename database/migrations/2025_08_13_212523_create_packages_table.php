<?php

use App\Models\V1\Address;
use App\Models\V1\Customer;
use App\Models\V1\Delivery;
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
            $table->foreignIdFor(Customer::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Delivery::class)->nullable();
            $table->foreignIdFor(Address::class)->nullable();
            $table->unsignedInteger('status');
            $table->uuid('tracking_number')->unique()->default(DB::raw('(uuid())'));
            $table->mediumText('delivery_note')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
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

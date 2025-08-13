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
        /*
         *
         * A driver could have multiple vehicles which would require a separate table.
         * But I think that's beyond the scope of this project.
         *
         */
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(App\Models\City::class)->nullable();
            $table->string('vehicle_make');
            $table->string('vehicle_model');
            $table->string('vehicle_colour');
            $table->string('vehicle_registration_number')->unique();
            $table->timestamp('driver_license_expiry_date');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};

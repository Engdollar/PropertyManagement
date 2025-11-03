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
        if (!Schema::hasTable('property_utilities')) {
            Schema::create('property_utilities', function (Blueprint $table) {
                $table->id();
                $table->string('property_id');
                $table->string('utility_type');
                $table->date('reading_date');
                $table->string('current_reading');
                $table->string('previous_reading');
                $table->integer('amount_due');
                $table->integer('workspace');
                $table->integer('created_by');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('property_utilities');
    }
};

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
        if (!Schema::hasTable('property_inspections')) {

            Schema::create('property_inspections', function (Blueprint $table) {
                $table->id();
                $table->string('property_id');
                $table->date('inspection_date');
                $table->string('inspector_name');
                $table->string('inspection_result');
                $table->longtext('comments');
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
        Schema::dropIfExists('property_inspections');
    }
};

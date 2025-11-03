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
        if (!Schema::hasTable('expense_trackings')) {
            Schema::create('expense_trackings', function (Blueprint $table) {
                $table->id();
                $table->string('property_id');
                $table->integer('amount');
                $table->string('category');
                $table->longtext('description');
                $table->date('expense_date');
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
        Schema::dropIfExists('expense_trackings');
    }
};

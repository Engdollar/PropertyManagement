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
        if(!Schema::hasTable('tenant_communications'))
        {
        Schema::create('tenant_communications', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->date('communication_date');
            $table->longText('message');
            $table->string('sender');
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
        Schema::dropIfExists('tenant_communications');
    }
};

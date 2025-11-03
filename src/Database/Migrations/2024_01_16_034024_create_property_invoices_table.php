<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('property_invoices'))
        {
            Schema::create('property_invoices', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable();
                $table->integer('property_id')->nullable();
                $table->integer('unit_id')->nullable();
                $table->date('issue_date')->nullable();
                $table->date('due_date')->nullable();
                $table->string('status')->nullable();
                $table->integer('total_amount')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('created_by')->default('0');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_invoices');
    }
};

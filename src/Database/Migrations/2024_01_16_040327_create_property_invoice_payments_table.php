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
        if(!Schema::hasTable('property_invoice_payments'))
        {
            Schema::create('property_invoice_payments', function (Blueprint $table) {
                $table->id();
                $table->integer('invoice_id')->nullable();
                $table->integer('user_id')->nullable();
                $table->date('date')->nullable();
                $table->integer('amount')->nullable();
                $table->string('receipt')->nullable();
                $table->string('payment_type')->nullable();
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
        Schema::dropIfExists('property_invoice_payments');
    }
};

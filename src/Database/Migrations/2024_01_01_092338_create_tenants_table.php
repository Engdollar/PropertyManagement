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
        if (!Schema::hasTable('tenants')) {
            Schema::create('tenants', function (Blueprint $table) {
                $table->id();
                $table->integer('user_id')->nullable();
                $table->integer('property_id')->nullable();
                $table->integer('unit_id')->nullable();
                $table->integer('total_family_member')->nullable();
                $table->string('address')->nullable();
                $table->string('country')->nullable();
                $table->string('state')->nullable();
                $table->string('city')->nullable();
                $table->integer('pincode')->nullable();
                $table->string('documents')->nullable();
                $table->date('lease_start_date')->nullable();
                $table->date('lease_end_date')->nullable();
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
        Schema::dropIfExists('Tenants');
    }
};

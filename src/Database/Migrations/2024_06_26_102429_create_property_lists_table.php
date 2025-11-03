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
        if(!Schema::hasTable('property_lists'))
        {
            Schema::create('property_lists', function (Blueprint $table) {
                $table->id();
                $table->string('property_id')->nullable();
                $table->string('unit')->nullable();
                $table->string('status')->nullable();
                $table->string('list_type');
                $table->integer('rent_amount')->default(0);
                $table->integer('tax')->nullable();
                $table->string('rent_type')->nullable();
                $table->integer('en_suites')->default(1);
                $table->integer('lounge')->default(1);
                $table->integer('garage_parking')->default(1);
                $table->integer('dining')->default(1);
                $table->integer('total_sq')->nullable();
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
        Schema::dropIfExists('property_lists');
    }
};


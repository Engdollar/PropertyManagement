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
        if(!Schema::hasTable('property_units'))
        {
            Schema::create('property_units', function (Blueprint $table) {
                $table->id();
                $table->integer('property_id');
                $table->text('name');
                $table->integer('bedroom')->default(1);
                $table->integer('baths')->default(1);
                $table->integer('kitchen')->default(1);
                $table->string('amenities')->nullable();
                $table->string('description')->nullable();
                $table->string('rentable_status')->default('Vacant');
                $table->string('rent_type')->nullable();
                $table->integer('rent')->default(0);
                $table->string('utilities_included')->nullable();
                $table->integer('workspace')->default(0);
                $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('property_units');
    }
};

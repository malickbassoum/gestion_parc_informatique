<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('serial_number')->unique();
            $table->string('category');
            $table->string('brand');
            $table->string('model');
            $table->string('status')->default('operational'); // operational, maintenance, out_of_service
            $table->text('specifications')->nullable();
            $table->date('purchase_date');
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->string('location');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('equipment');
    }
};
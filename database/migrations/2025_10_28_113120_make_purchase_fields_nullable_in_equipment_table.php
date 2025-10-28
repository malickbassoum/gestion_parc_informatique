<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->date('purchase_date')->nullable()->change();
            $table->decimal('purchase_price', 10, 2)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('equipment', function (Blueprint $table) {
            $table->date('purchase_date')->nullable(false)->change();
            $table->decimal('purchase_price', 10, 2)->nullable(false)->change();
        });
    }
};
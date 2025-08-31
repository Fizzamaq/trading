<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('inventory_lots', function (Blueprint $table) {
            $table->id();
            $table->string('lot_number')->unique();
            $table->string('product_name');
            $table->decimal('original_quantity', 10, 3);
            $table->decimal('remaining_quantity', 10, 3);
            $table->decimal('unit_cost', 15, 2);
            $table->decimal('total_cost', 15, 2);
            $table->date('purchase_date');
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade');
            $table->foreignId('purchase_invoice_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->index(['product_name', 'purchase_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('inventory_lots');
    }
};

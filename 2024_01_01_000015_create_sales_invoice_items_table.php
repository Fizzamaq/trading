<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales_invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('inventory_lot_id')->constrained()->onDelete('cascade');
            $table->string('product_name');
            $table->decimal('quantity_sold', 10, 3);
            $table->decimal('unit_selling_price', 15, 2);
            $table->decimal('unit_cost_price', 15, 2);
            $table->decimal('line_total', 15, 2);
            $table->decimal('line_cost', 15, 2);
            $table->decimal('line_profit', 15, 2);
            $table->timestamps();

            $table->index(['inventory_lot_id', 'sales_invoice_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_invoice_items');
    }
};

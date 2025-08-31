<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sales_payment_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained('sales_payments')->onDelete('cascade');
            $table->foreignId('sales_invoice_id')->constrained('sales_invoices')->onDelete('cascade');
            $table->decimal('allocated_amount', 15, 2);
            $table->timestamps();

            $table->index(['payment_id', 'sales_invoice_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sales_payment_allocations');
    }
};

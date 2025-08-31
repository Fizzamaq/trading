<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->date('transaction_date');
            $table->string('reference');
            $table->string('description')->nullable();
            $table->foreignId('debit_account_id')->constrained('chart_of_accounts')->onDelete('cascade');
            $table->foreignId('credit_account_id')->constrained('chart_of_accounts')->onDelete('cascade');
            $table->decimal('amount', 15, 2);
            $table->string('source_type')->nullable();
            $table->unsignedBigInteger('source_id')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('transaction_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};

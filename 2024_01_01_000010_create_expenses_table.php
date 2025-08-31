<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('expense_title');
            $table->decimal('amount', 15, 2);
            $table->date('expense_date');
            $table->foreignId('category_id')->constrained('expense_categories')->onDelete('set null');
            $table->foreignId('account_id')->constrained('chart_of_accounts')->onDelete('set null');
            $table->text('description')->nullable();
            $table->string('receipt_file')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            $table->index('expense_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};

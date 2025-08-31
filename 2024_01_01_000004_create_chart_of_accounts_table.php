<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('chart_of_accounts')) {
            Schema::create('chart_of_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('account_code', 20)->unique();
                $table->string('account_name');
                $table->enum('account_type', ['asset', 'liability', 'equity', 'revenue', 'expense']);
                $table->foreignId('parent_id')->nullable()->constrained('chart_of_accounts')->onDelete('set null');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->index('account_type');
            });
        }
    } // <-- Closing brace for up() method added here

    public function down()
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};

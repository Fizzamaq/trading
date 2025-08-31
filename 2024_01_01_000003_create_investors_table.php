<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('investors')) {
            Schema::create('investors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->decimal('investment_amount', 15, 2)->default(0);
                $table->integer('total_shares')->default(0);
                $table->decimal('profit_percentage', 5, 2)->default(50.00);
                $table->date('grace_period_start')->nullable();
                $table->string('bank_account_number', 50)->nullable();
                $table->string('bank_name', 100)->nullable();
                $table->string('account_holder_name')->nullable();
                $table->boolean('onboarding_completed')->default(false);
                $table->timestamp('onboarding_completed_at')->nullable();
                $table->boolean('declaration_signed')->default(false);
                $table->timestamp('declaration_signed_at')->nullable();
                $table->timestamps();
                $table->index('grace_period_start');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('investors');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfitDistributionsTable extends Migration
{
    public function up()
    {
        Schema::create('profit_distributions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 15, 2);
            $table->unsignedBigInteger('admin_user_id');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('admin_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('profit_distributions');
    }
}

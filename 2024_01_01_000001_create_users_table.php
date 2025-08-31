<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Check if the 'users' table does NOT exist before creating it
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->enum('role', ['owner', 'director', 'investor'])->default('investor');
                $table->enum('status', ['active', 'inactive', 'pending_approval'])->default('pending_approval');
                $table->string('phone', 20)->nullable();
                $table->text('address')->nullable();
                $table->rememberToken();
                $table->timestamps();
                $table->index('role');
                $table->index('status');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};

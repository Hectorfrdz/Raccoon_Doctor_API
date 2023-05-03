<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('second_name')->nullable();
            $table->string('lastname');
            $table->string('mother_lastname')->nullable();
            $table->string('RFC');
            $table->string('genre');
            $table->string('INE_Front')->nullable();
            $table->string('INE_Back')->nullable();
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('direction');
            $table->integer('status');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('rol')->default(1);

            $table->foreign('rol')->references('id')->on('roles')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

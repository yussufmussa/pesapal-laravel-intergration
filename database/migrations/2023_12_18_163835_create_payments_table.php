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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_method');
            $table->string('amount');
            $table->date('created_date');
            $table->string('confirmation_code');
            $table->string('payment_status_code');
            $table->string('payment_status_description');
            $table->string('description');
            $table->string('message');
            $table->string('payment_account');
            $table->integer('status_code');
            $table->string('merchant_reference');
            $table->string('currency');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

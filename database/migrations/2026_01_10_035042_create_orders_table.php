<?php

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');

            $table->text('address');
            $table->string('city');
            $table->string('zip_code');

            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', PaymentMethod::getValues())->default(PaymentMethod::STRIPE->value);
            $table->enum('payment_status', PaymentStatus::getValues())->default(PaymentStatus::PENDING->value);
            $table->enum('status', OrderStatus::getValues())->default(OrderStatus::NEW->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

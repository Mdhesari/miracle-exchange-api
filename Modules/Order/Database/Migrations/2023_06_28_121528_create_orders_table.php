<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Order\Enums\OrderStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->foreignUuid('market_id');
            $table->foreignId('user_id');
            $table->foreignId('account_id')->nullable();

            $table->float('original_market_price', 12);
            $table->float('executed_price', 12);
            $table->float('executed_quantity', 12);
            $table->float('cumulative_quote_quantity', 12);
            $table->float('original_cumulative_quote_quantity', 12);
            $table->tinyInteger('fill_percentage');

            $table->enum('status', array_column(OrderStatus::cases(), 'name'));

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

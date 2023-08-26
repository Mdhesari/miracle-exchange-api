<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Market\Enums\MarketStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('markets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name')->nullable();
            $table->string('persian_name')->nullable();
            $table->string('country_code', 8)->nullable();
            $table->string('symbol_char', 8)->nullable();
            $table->string('symbol', 64)->nullable();

            $table->float('price', 12, 0);

            $table->enum('status', array_column(MarketStatus::cases(), 'name'));

            $table->timestamp('price_updated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('markets');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Revenue\Enums\RevenueStatus;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenues', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->text('description')->nullable();

            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('wallet_id')->nullable()->constrained('users');
            $table->foreignId('admin_id')->nullable()->constrained('users');
            $table->uuidMorphs('revenuable');

            $table->float('quantity');

            $table->enum('status', array_column(RevenueStatus::cases(), 'name'));

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revenues');
    }
};

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Modules\Wallet\Entities\Transaction;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('gateway')->nullable();
            $table->string('reference')->nullable();
            $table->string('hash')->unique()->nullable();
            $table->string('callback_url')->nullable();

            $table->float('quantity', 16, 0);

            $table->enum('status', Transaction::getAvailableStatus())->default(Transaction::STATUS_PENDING);
            $table->enum('type', Transaction::getAvailableTypes())->default(Transaction::TYPE_DEPOSIT);

            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('admin_id')->nullable()->constrained('users');

            $table->nullableUuidMorphs('transactionable');

            $table->json('meta')->nullable();

            $table->timestamps();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('paid_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}

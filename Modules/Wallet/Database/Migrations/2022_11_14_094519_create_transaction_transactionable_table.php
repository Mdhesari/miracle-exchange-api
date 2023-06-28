<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionTransactionableTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_transactionable', function (Blueprint $table) {
            $table->foreignUuid('transaction_id');
            $table->foreign('transaction_id', 'trans_id')->references('id')->on('transactions');
            $table->nullableMorphs('transactionable', 'transable_id');

            $table->index(['transaction_id', 'created_at']);

            $table->float('quantity', 16, 0);

            $table->primary(['transaction_id', 'transactionable_id'], 'transaction_transactionable');

            $table->index('transaction_id', 'created_at');

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
        Schema::dropIfExists('transaction_transactionable');
    }
}

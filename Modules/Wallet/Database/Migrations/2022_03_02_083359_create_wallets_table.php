<?php

use App\Constants\WalletConstant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Wallet\Entities\Wallet;

class CreateWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('currency')->default(WalletConstant::DEFAULT_CURRENCY);
            $table->float('quantity', 16, 0);

            $table->foreignId('user_id')->constrained();

            $table->enum('status', Wallet::getAvailableStatus())->default(Wallet::STATUS_ACTIVE);

            $table->softDeletes();

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
        Schema::dropIfExists('wallets');
    }
}

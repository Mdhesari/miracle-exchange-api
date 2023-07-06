<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
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

            //TODO: discuss
//            $table->string('type')->nullable();

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

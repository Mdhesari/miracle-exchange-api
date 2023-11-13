<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('landing', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['market_id']);

            // Change the column type to UUID
            $table->uuid('market_id')->change();

            // Add a new foreign key constraint
            $table->foreign('market_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('landing', function (Blueprint $table) {
            $table->dropForeign(['market_id']);
            $table->integer('market_id')->change();
            $table->foreign('market_id');
        });
    }
};

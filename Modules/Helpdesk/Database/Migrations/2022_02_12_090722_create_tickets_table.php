<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->string('subject');
            $table->string('department')->nullable();
            $table->text('notes')->nullable();

            $table->enum('status', \Modules\Helpdesk\Entities\Ticket::getAvailableStatus())->default(\Modules\Helpdesk\Entities\Ticket::STATUS_PENDING_ADMIN);

            $table->foreignId('user_id')->constrained();

            $table->string('number')->nullable();

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
        Schema::dropIfExists('tickets');
    }
}

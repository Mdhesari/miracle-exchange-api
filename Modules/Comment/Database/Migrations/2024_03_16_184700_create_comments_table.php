<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->increments('id');
            $table->uuidMorphs('commentable');
            $table->string('title')->nullable();
            $table->text('comment');
            $table->boolean('is_approved')->default(false);
            $table->unsignedBigInteger('user_id')->nullable();
//            $table->tinyInteger('rate_quantity')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};

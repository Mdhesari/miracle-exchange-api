<?php

use App\Enums\UserGender;
use App\Enums\UserStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->uuid();

            $table->foreignId('inviter_id')->nullable()->constrained('users');

            $table->boolean('inviter_has_revenue')->nullable();

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('national_code')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('mobile')->unique();
            $table->string('password')->nullable();

            $table->enum('gender', array_column(UserGender::cases(), 'name'));
            $table->enum('status', array_column(UserStatus::cases(), 'name'));

            $table->date('birthday')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('mobile_verified_at')->nullable();
            $table->timestamp('banned_at')->nullable();
            $table->rememberToken();
            $table->timestamps();

            $table->json('meta')->nullable();

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

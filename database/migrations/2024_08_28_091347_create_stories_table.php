<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->text('content')->nullable();
            $table->string('media_url', 255)->nullable();
            $table->timestamp('posted_at')->useCurrent();
            $table->timestamp('expired_at')->nullable();
            $table->enum('status', ['active', 'expired', 'deleted'])->default('active');
            $table->integer('priority')->default(0);
            $table->enum('privacy', ['public', 'friends', 'private'])->default('public');
            $table->integer('views')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stories');
    }
};

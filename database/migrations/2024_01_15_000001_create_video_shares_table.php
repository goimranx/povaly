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
        Schema::create('video_shares', function (Blueprint $table) {
            $table->id();
            $table->string('video_id')->index();
            $table->text('video_url');
            $table->unsignedBigInteger('product_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('session_id')->nullable()->index();
            $table->string('ip_address')->nullable();
            $table->timestamps();
            
            // Foreign key constraints (optional, depends on your setup)
            // $table->foreign('product_id')->references('id')->on('ec_products')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_shares');
    }
};

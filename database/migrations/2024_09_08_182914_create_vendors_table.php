<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{

    public function up(): void{
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('shop_name_en')->unique();
            $table->string('shop_name_ar')->nullable()->unique();
            $table->string('shop_slug')->unique();
            $table->string('shop_email')->nullable()->unique();
            $table->string('shop_address')->nullable();
            $table->string('shop_phone')->nullable()->unique();
            $table->string('shop_website')->nullable();
            $table->string('shop_logo')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void{
        Schema::dropIfExists('vendors');
    }
};
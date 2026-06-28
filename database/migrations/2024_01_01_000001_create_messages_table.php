<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('sender_id');           // ID pengirim (firebase uid)
            $table->string('sender_name');         // Nama pengirim
            $table->string('sender_phone')->nullable(); // No WA pengirim
            $table->string('receiver_id');         // ID penerima (firebase uid penjual)
            $table->string('product_id')->nullable(); // ID produk yang ditanyakan
            $table->string('product_name')->nullable(); // Nama produk
            $table->text('content');               // Isi pesan
            $table->boolean('is_read')->default(false); // Sudah dibaca?
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
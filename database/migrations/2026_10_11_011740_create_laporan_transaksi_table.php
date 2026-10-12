<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi')->unique();
            $table->decimal('total_nominal', 15, 2);
            $table->string('status', 20);
            $table->dateTime('waktu_transaksi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_transaksi');
    }
};
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
        Schema::table('infos', function (Blueprint $table) {
            $table->string('nama_acara_3')->nullable()->after('mulai_resepsi');
            $table->time('mulai_acara_3')->nullable()->after('nama_acara_3');
            $table->time('selesai_acara_3')->nullable()->after('mulai_acara_3');
            $table->string('keterangan_acara_3')->nullable()->after('selesai_acara_3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('infos', function (Blueprint $table) {
            $table->dropColumn([
                'nama_acara_3',
                'mulai_acara_3',
                'selesai_acara_3',
                'keterangan_acara_3',
            ]);
        });
    }
};

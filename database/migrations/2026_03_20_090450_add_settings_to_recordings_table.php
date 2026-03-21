<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('recordings', function (Blueprint $table) {
            // 要約スタイル（箇条書き・文章・キーワード）
            $table->string('summary_style')->default('bullets');
            // 要約の長さ（short・medium・long）
            $table->string('summary_length')->default('medium');
            // 対象シーン（general・meeting・nursing・lecture）
            $table->string('summary_scene')->default('general');
        });
    }

    public function down(): void
    {
        Schema::table('recordings', function (Blueprint $table) {
            $table->dropColumn(['summary_style', 'summary_length', 'summary_scene']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adiciona os tipos adesivo, banner e faixa ao enum type da tabela campaign_miscellaneous.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE campaign_miscellaneous MODIFY COLUMN type ENUM('spot', 'tag', 'sticker', 'script', 'adesivo', 'banner', 'faixa') NOT NULL DEFAULT 'spot'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE campaign_miscellaneous MODIFY COLUMN type ENUM('spot', 'tag', 'sticker', 'script') NOT NULL DEFAULT 'spot'");
    }
};

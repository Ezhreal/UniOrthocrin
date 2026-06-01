<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * CNPJ da empresa (opcional, não único). Representante continua usando cpf_cnpj para CPF quando aplicável.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'cnpj')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('cnpj', 20)->nullable()->after('cpf_cnpj');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'cnpj')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('cnpj');
            });
        }
    }
};

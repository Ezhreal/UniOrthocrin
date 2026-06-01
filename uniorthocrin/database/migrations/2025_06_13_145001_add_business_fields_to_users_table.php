<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'representante_nome')) {
                $table->string('representante_nome')->nullable()->comment('Nome do Representante');
            }
            if (!Schema::hasColumn('users', 'nome_fantasia')) {
                $table->string('nome_fantasia')->nullable()->comment('Nome Fantasia');
            }
            if (!Schema::hasColumn('users', 'razao_social')) {
                $table->string('razao_social')->nullable()->comment('Razão Social');
            }
            if (!Schema::hasColumn('users', 'cpf_cnpj')) {
                $table->string('cpf_cnpj')->nullable()->comment('CPF ou CNPJ');
            }
        });

        // Add index on cpf_cnpj
        $hasIdx1 = collect(DB::select("SHOW INDEXES FROM users WHERE Key_name = 'users_cpf_cnpj_index'"))->isNotEmpty();
        if (!$hasIdx1 && Schema::hasColumn('users', 'cpf_cnpj')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('cpf_cnpj');
            });
        }

        // Add index on nome_fantasia
        $hasIdx2 = collect(DB::select("SHOW INDEXES FROM users WHERE Key_name = 'users_nome_fantasia_index'"))->isNotEmpty();
        if (!$hasIdx2 && Schema::hasColumn('users', 'nome_fantasia')) {
            Schema::table('users', function (Blueprint $table) {
                $table->index('nome_fantasia');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $hasIdx1 = collect(DB::select("SHOW INDEXES FROM users WHERE Key_name = 'users_cpf_cnpj_index'"))->isNotEmpty();
            if ($hasIdx1) {
                $table->dropIndex(['cpf_cnpj']);
            }
            
            $hasIdx2 = collect(DB::select("SHOW INDEXES FROM users WHERE Key_name = 'users_nome_fantasia_index'"))->isNotEmpty();
            if ($hasIdx2) {
                $table->dropIndex(['nome_fantasia']);
            }

            $colsToDrop = [];
            if (Schema::hasColumn('users', 'representante_nome')) $colsToDrop[] = 'representante_nome';
            if (Schema::hasColumn('users', 'nome_fantasia')) $colsToDrop[] = 'nome_fantasia';
            if (Schema::hasColumn('users', 'razao_social')) $colsToDrop[] = 'razao_social';
            if (Schema::hasColumn('users', 'cpf_cnpj')) $colsToDrop[] = 'cpf_cnpj';

            if (!empty($colsToDrop)) {
                $table->dropColumn($colsToDrop);
            }
        });
    }
};

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ClearDataExceptUsers extends Command
{
    protected $signature = 'db:clear-except-users
                            {--force : Executar sem confirmação}';

    protected $description = 'Limpa todos os dados das tabelas, mantendo apenas users e user_types';

    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Isso vai APAGAR todos os dados exceto usuários e tipos de usuário. Continuar?')) {
            return self::FAILURE;
        }

        $driver = config('database.default');
        $connection = DB::connection();

        if ($driver !== 'mysql') {
            $this->warn('Este comando foi feito para MySQL. Para outro driver, adapte o código.');
            return self::FAILURE;
        }

        $keep = ['users', 'user_types'];
        $tables = $this->getTableNames($connection);

        $toTruncate = array_values(array_diff($tables, $keep));

        if (empty($toTruncate)) {
            $this->info('Nenhuma tabela para limpar (apenas users e user_types existem).');
            return self::SUCCESS;
        }

        $this->info('Tabelas que serão esvaziadas: ' . implode(', ', $toTruncate));

        try {
            $connection->statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($toTruncate as $table) {
                if (! Schema::hasTable($table)) {
                    continue;
                }
                $connection->table($table)->truncate();
                $this->line("  Limpo: {$table}");
            }

            $connection->statement('SET FOREIGN_KEY_CHECKS=1');
        } catch (\Throwable $e) {
            $connection->statement('SET FOREIGN_KEY_CHECKS=1');
            $this->error('Erro: ' . $e->getMessage());
            return self::FAILURE;
        }

        $this->info('Pronto. Apenas usuários e user_types foram mantidos.');
        return self::SUCCESS;
    }

    private function getTableNames($connection): array
    {
        $db = $connection->getDatabaseName();
        $result = $connection->select("SHOW TABLES FROM `{$db}`");
        $key = "Tables_in_{$db}";
        $tables = [];
        foreach ($result as $row) {
            $tables[] = $row->{$key};
        }
        return $tables;
    }
}

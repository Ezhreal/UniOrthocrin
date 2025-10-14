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
        // Criar nova tabela otimizada
        Schema::create('notifications_optimized', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->enum('type', ['info', 'success', 'warning', 'error']);
            $table->enum('target_type', ['all', 'user_types', 'specific_users']);
            $table->json('target_ids')->nullable(); // IDs dos tipos de usuário ou usuários específicos
            $table->json('read_by')->nullable(); // IDs dos usuários que leram
            $table->string('related_type')->nullable();
            $table->unsignedBigInteger('related_id')->nullable();
            $table->timestamps();
            
            $table->index(['target_type', 'created_at']);
            $table->index(['related_type', 'related_id']);
        });

        // Migrar dados existentes
        $this->migrateExistingNotifications();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications_optimized');
    }

    /**
     * Migrar notificações existentes para o novo formato
     */
    private function migrateExistingNotifications(): void
    {
        // Agrupar notificações existentes por título e tipo
        $existingNotifications = DB::table('user_notifications')
            ->select('title', 'message', 'type', 'created_at')
            ->groupBy('title', 'message', 'type', 'created_at')
            ->get();

        foreach ($existingNotifications as $notification) {
            // Buscar todos os usuários que receberam esta notificação
            $userIds = DB::table('user_notifications')
                ->where('title', $notification->title)
                ->where('message', $notification->message)
                ->where('type', $notification->type)
                ->where('created_at', $notification->created_at)
                ->pluck('user_id')
                ->toArray();

            // Buscar usuários que já leram
            $readBy = DB::table('user_notifications')
                ->where('title', $notification->title)
                ->where('message', $notification->message)
                ->where('type', $notification->type)
                ->where('created_at', $notification->created_at)
                ->whereNotNull('read_at')
                ->pluck('user_id')
                ->toArray();

            // Determinar target_type baseado nos usuários
            $targetType = 'specific_users';
            if (count($userIds) > 50) { // Se muitos usuários, provavelmente é "all"
                $targetType = 'all';
            }

            // Inserir na nova tabela
            DB::table('notifications_optimized')->insert([
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'target_type' => $targetType,
                'target_ids' => json_encode($userIds),
                'read_by' => json_encode($readBy),
                'created_at' => $notification->created_at,
                'updated_at' => $notification->created_at,
            ]);
        }
    }
};
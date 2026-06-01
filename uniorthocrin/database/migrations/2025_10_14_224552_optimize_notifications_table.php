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
        if (!Schema::hasTable('notifications_optimized')) {
            Schema::create('notifications_optimized', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('message');
                $table->enum('type', ['info', 'success', 'warning', 'error']);
                $table->enum('target_type', ['all', 'user_types', 'specific_users']);
                $table->json('target_ids')->nullable();
                $table->json('read_by')->nullable();
                $table->string('related_type')->nullable();
                $table->unsignedBigInteger('related_id')->nullable();
                $table->timestamps();

                $table->index(['target_type', 'created_at']);
                $table->index(['related_type', 'related_id']);
            });
        }

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
        if (!Schema::hasTable('user_notifications')) {
            return;
        }

        $existingNotifications = DB::table('user_notifications')
            ->select('title', 'message', 'type', 'created_at')
            ->groupBy('title', 'message', 'type', 'created_at')
            ->get();

        foreach ($existingNotifications as $notification) {
            $userIds = DB::table('user_notifications')
                ->where('title', $notification->title)
                ->where('message', $notification->message)
                ->where('type', $notification->type)
                ->where('created_at', $notification->created_at)
                ->pluck('user_id')
                ->toArray();

            $readBy = DB::table('user_notifications')
                ->where('title', $notification->title)
                ->where('message', $notification->message)
                ->where('type', $notification->type)
                ->where('created_at', $notification->created_at)
                ->whereNotNull('read_at')
                ->pluck('user_id')
                ->toArray();

            $targetType = 'specific_users';
            if (count($userIds) > 50) {
                $targetType = 'all';
            }

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

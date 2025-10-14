<?php

namespace App\Observers;

use App\Models\Training;
use App\Models\UserNotification;
use App\Models\User;

class TrainingObserver
{
    /**
     * Handle the Training "created" event.
     */
    public function created(Training $training): void
    {
        $this->createNotificationsForTraining($training);
    }

    /**
     * Handle the Training "updated" event.
     */
    public function updated(Training $training): void
    {
        // Only create notifications if the training was just activated
        if ($training->wasChanged('is_active') && $training->is_active) {
            $this->createNotificationsForTraining($training);
        }
    }

    /**
     * Create notifications for users who have permission to view this training
     */
    private function createNotificationsForTraining(Training $training): void
    {
        // Get all user types that have permission to view this training
        $permissions = $training->permissions()->where('can_view', true)->get();
        
        foreach ($permissions as $permission) {
            // Get all users of this type
            $users = User::where('user_type_id', $permission->user_type_id)->get();
            
            foreach ($users as $user) {
                UserNotification::create([
                    'user_id' => $user->id,
                    'type' => 'new_content',
                    'title' => 'Novo Treinamento Disponível',
                    'message' => "Um novo treinamento foi adicionado: {$training->name}",
                    'data' => json_encode([
                        'content_type' => 'training',
                        'content_id' => $training->id,
                        'content_name' => $training->name,
                        'url' => "/treinamentos/{$training->id}"
                    ]),
                    'is_read' => false,
                ]);
            }
        }
    }
}

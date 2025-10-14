<?php

namespace App\Observers;

use App\Models\Library;
use App\Models\UserNotification;
use App\Models\User;

class LibraryObserver
{
    /**
     * Handle the Library "created" event.
     */
    public function created(Library $library): void
    {
        $this->createNotificationsForLibrary($library);
    }

    /**
     * Handle the Library "updated" event.
     */
    public function updated(Library $library): void
    {
        // Only create notifications if the library item was just activated
        if ($library->wasChanged('status') && $library->status === 'active') {
            $this->createNotificationsForLibrary($library);
        }
    }

    /**
     * Create notifications for users who have permission to view this library item
     */
    private function createNotificationsForLibrary(Library $library): void
    {
        // Get all user types that have permission to view this library item
        $permissions = $library->permissions()->where('can_view', true)->get();
        
        foreach ($permissions as $permission) {
            // Get all users of this type
            $users = User::where('user_type_id', $permission->user_type_id)->get();
            
            foreach ($users as $user) {
                UserNotification::create([
                    'user_id' => $user->id,
                    'type' => 'new_content',
                    'title' => 'Novo Material na Biblioteca',
                    'message' => "Um novo material foi adicionado à biblioteca: {$library->name}",
                    'data' => json_encode([
                        'content_type' => 'library',
                        'content_id' => $library->id,
                        'content_name' => $library->name,
                        'url' => "/biblioteca/{$library->id}"
                    ]),
                    'is_read' => false,
                ]);
            }
        }
    }
}

<?php

namespace App\Observers;

use App\Models\Media;
use App\Models\UserNotification;
use App\Models\User;

class MediaObserver
{
    /**
     * Handle the Media "created" event.
     */
    public function created(Media $media): void
    {
        $this->createNotificationsForMedia($media);
    }

    /**
     * Handle the Media "updated" event.
     */
    public function updated(Media $media): void
    {
        // Only create notifications if the media item was just activated
        if ($media->wasChanged('status') && $media->status === 'active') {
            $this->createNotificationsForMedia($media);
        }
    }

    /**
     * Create notifications for users who have permission to view this media item
     */
    private function createNotificationsForMedia(Media $media): void
    {
        // Get all user types that have permission to view this media item
        $permissions = $media->permissions()->where('can_view', true)->get();
        
        foreach ($permissions as $permission) {
            // Get all users of this type
            $users = User::where('user_type_id', $permission->user_type_id)->get();
            
            foreach ($users as $user) {
                UserNotification::create([
                    'user_id' => $user->id,
                    'type' => 'new_content',
                    'title' => 'Novo Material em Na Mídia',
                    'message' => "Um novo material foi adicionado em Na Mídia: {$media->name}",
                    'data' => json_encode([
                        'content_type' => 'media',
                        'content_id' => $media->id,
                        'content_name' => $media->name,
                        'url' => "/na-midia/{$media->id}"
                    ]),
                    'is_read' => false,
                ]);
            }
        }
    }
}

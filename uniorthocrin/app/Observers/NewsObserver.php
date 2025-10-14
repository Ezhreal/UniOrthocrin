<?php

namespace App\Observers;

use App\Models\News;
use App\Models\UserNotification;
use App\Models\User;

class NewsObserver
{
    /**
     * Handle the News "created" event.
     */
    public function created(News $news): void
    {
        $this->createNotificationsForNews($news);
    }

    /**
     * Handle the News "updated" event.
     */
    public function updated(News $news): void
    {
        // Only create notifications if the news was just activated
        if ($news->wasChanged('status') && $news->status === 'active') {
            $this->createNotificationsForNews($news);
        }
    }

    /**
     * Create notifications for users who have permission to view this news
     */
    private function createNotificationsForNews(News $news): void
    {
        // Get all user types that have permission to view this news
        $permissions = $news->permissions()->where('can_view', true)->get();
        
        foreach ($permissions as $permission) {
            // Get all users of this type
            $users = User::where('user_type_id', $permission->user_type_id)->get();
            
            foreach ($users as $user) {
                UserNotification::create([
                    'user_id' => $user->id,
                    'type' => 'new_content',
                    'title' => 'Nova Notícia Disponível',
                    'message' => "Uma nova notícia foi publicada: {$news->title}",
                    'data' => json_encode([
                        'content_type' => 'news',
                        'content_id' => $news->id,
                        'content_name' => $news->title,
                        'url' => "/noticias/{$news->id}"
                    ]),
                    'is_read' => false,
                ]);
            }
        }
    }
}

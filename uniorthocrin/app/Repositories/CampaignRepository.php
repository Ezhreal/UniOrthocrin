<?php

namespace App\Repositories;

use App\Models\Campaign;
use App\Models\User;
use Illuminate\Support\Collection;

class CampaignRepository
{
    private $model;

    public function __construct(Campaign $model)
    {
        $this->model = $model;
    }

    public function getAllForUser(User $user)
    {
        // Marketing é exclusivo para Admin (ID 1) e Franqueado (ID 2)
        if (!in_array($user->user_type_id, [1, 2])) {
            return collect(); // Retorna coleção vazia para Lojistas/Representantes
        }
        
        return $this->model->active()
            ->when($user->isFranqueado(), function($q) {
                // Franqueado vê campanhas exclusivas para franqueados
                $q->where('visible_franchise_only', true);
            })
            ->when($user->isAdmin(), function($q) {
                // Admin vê todas as campanhas
            })
            ->get();
    }

    public function findByIdForUser($id, User $user)
    {
        // Marketing é exclusivo para Admin (ID 1) e Franqueado (ID 2)
        if (!in_array($user->user_type_id, [1, 2])) {
            abort(403, 'Acesso negado ao marketing');
        }
        
        return $this->model->active()
            ->where('id', $id)
            ->when($user->isFranqueado(), function($q) {
                // Franqueado vê campanhas exclusivas para franqueados
                $q->where('visible_franchise_only', true);
            })
            ->when($user->isAdmin(), function($q) {
                // Admin vê todas as campanhas
            })
            ->firstOrFail();
    }

    /**
     * Get the featured campaign (latest created with valid end date)
     */
    public function getFeaturedCampaign(User $user)
    {
        // Marketing é exclusivo para Admin (ID 1) e Franqueado (ID 2)
        if (!in_array($user->user_type_id, [1, 2])) {
            return null; // Não retorna campanha em destaque para Lojistas/Representantes
        }
        
        return $this->model->active()
            ->where('is_featured', true)
            ->where(function($query) {
                $query->whereNull('end_date')
                    ->orWhere('end_date', '>=', now()->toDateString());
            })
            ->when($user->isFranqueado(), function($q) {
                // Franqueado vê campanhas exclusivas para franqueados
                $q->where('visible_franchise_only', true);
            })
            ->when($user->isAdmin(), function($q) {
                // Admin vê todas as campanhas
            })
            ->orderBy('created_at', 'desc')
            ->with(['posts', 'folders', 'videos', 'miscellaneous'])
            ->first();
    }

    /**
     * Get other campaigns (excluding the featured one)
     */
    public function getOtherCampaigns(User $user, $excludeId = null)
    {
        // Marketing é exclusivo para Admin (ID 1) e Franqueado (ID 2)
        if (!in_array($user->user_type_id, [1, 2])) {
            return collect(); // Retorna coleção vazia para Lojistas/Representantes
        }
        
        $query = $this->model->active()
            ->when($user->isFranqueado(), function($q) {
                // Franqueado vê campanhas exclusivas para franqueados
                $q->where('visible_franchise_only', true);
            })
            ->when($user->isAdmin(), function($q) {
                // Admin vê todas as campanhas
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->with(['posts', 'folders', 'videos', 'miscellaneous'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get campaigns with content statistics
     */
    public function getCampaignsWithStats(User $user): Collection
    {
        return $this->getAllForUser($user)->map(function($campaign) {
            $campaign->content_stats = [
                'posts' => $campaign->posts()->active()->count(),
                'folders' => $campaign->folders()->active()->count(),
                'videos' => $campaign->videos()->active()->count(),
                'miscellaneous' => $campaign->miscellaneous()->active()->count(),
                'total_files' => $this->getTotalFilesForCampaign($campaign)
            ];
            
            return $campaign;
        });
    }

    /**
     * Get total files for a campaign
     */
    private function getTotalFilesForCampaign(Campaign $campaign): int
    {
        $total = 0;
        $total += $campaign->posts()->with('files')->get()->sum(function($post) {
            return $post->files->count();
        });
        $total += $campaign->folders()->with('files')->get()->sum(function($folder) {
            return $folder->files->count();
        });
        $total += $campaign->videos()->with('files')->get()->sum(function($video) {
            return $video->files->count();
        });
        $total += $campaign->miscellaneous()->with('files')->get()->sum(function($misc) {
            return $misc->files->count();
        });
        
        return $total;
    }

    /**
     * Get campaigns by content type
     */
    public function getCampaignsByContentType(User $user, string $contentType): Collection
    {
        return $this->getAllForUser($user)->filter(function($campaign) use ($contentType) {
            return match($contentType) {
                'posts' => $campaign->posts()->active()->count() > 0,
                'folders' => $campaign->folders()->active()->count() > 0,
                'videos' => $campaign->videos()->active()->count() > 0,
                'miscellaneous' => $campaign->miscellaneous()->active()->count() > 0,
                default => true
            };
        });
    }

    /**
     * Search campaigns by name or description
     */
    public function searchCampaigns(User $user, string $query): Collection
    {
        // Marketing é exclusivo para Admin (ID 1) e Franqueado (ID 2)
        if (!in_array($user->user_type_id, [1, 2])) {
            return collect(); // Retorna coleção vazia para Lojistas/Representantes
        }
        
        return $this->model->where(function($q) use ($query) {
            $q->where('name', 'like', "%{$query}%")
              ->orWhere('description', 'like', "%{$query}%");
        })
        ->active()
        ->when($user->isFranqueado(), function($q) {
            // Franqueado vê campanhas exclusivas para franqueados
            $q->where('visible_franchise_only', true);
        })
        ->when($user->isAdmin(), function($q) {
            // Admin vê todas as campanhas
        })
        ->with(['posts', 'folders', 'videos', 'miscellaneous'])
        ->get();
    }
} 
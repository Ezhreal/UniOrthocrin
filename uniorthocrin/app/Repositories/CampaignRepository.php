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
        
        return $this->model->current()
            ->active()
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
        
        return $this->model->current()
            ->active()
            ->where('id', $id)
            ->when($user->isFranqueado(), function($q) {
                // Franqueado vê campanhas exclusivas para franqueados
                $q->where('visible_franchise_only', true);
            })
            ->when($user->isAdmin(), function($q) {
                // Admin vê todas as campanhas
            })
            ->with([
                'posts.files',
                'folders.files',
                'videos.files',
                'miscellaneous.files',
            ])
            ->firstOrFail();
    }

    /**
     * Lista marketing (usuário): uma query; destaque único = entre is_featured, maior data fim (end_date desc;
     * sem data fim conta como prazo “maior”). Lista ordenada pelo mesmo critério de fim.
     *
     * @return array{featured: ?Campaign, others: Collection<int, Campaign>}
     */
    public function getMarketingListForUser(User $user): array
    {
        if (! in_array($user->user_type_id, [1, 2], true)) {
            return ['featured' => null, 'others' => collect()];
        }

        $all = $this->model->query()
            ->current()
            ->active()
            ->when($user->isFranqueado(), function ($q) {
                $q->where('visible_franchise_only', true);
            })
            ->with(['posts', 'folders', 'videos', 'miscellaneous'])
            ->orderByRaw('end_date IS NULL DESC')
            ->orderByDesc('end_date')
            ->orderByDesc('id')
            ->get();

        $featured = $all
            ->filter(fn (Campaign $c) => $c->is_featured)
            ->sort(function (Campaign $a, Campaign $b) {
                $ta = $a->end_date ? $a->end_date->getTimestamp() : PHP_INT_MAX;
                $tb = $b->end_date ? $b->end_date->getTimestamp() : PHP_INT_MAX;
                $cmp = $tb <=> $ta;

                return $cmp !== 0 ? $cmp : $b->id <=> $a->id;
            })
            ->first();

        $others = $featured === null
            ? $all
            : $all->filter(fn (Campaign $c) => (int) $c->id !== (int) $featured->id)->values();

        return ['featured' => $featured, 'others' => $others];
    }

    /**
     * Campanha em destaque (hero): ver getMarketingListForUser — só uma “vence” se várias tiverem is_featured.
     */
    public function getFeaturedCampaign(User $user)
    {
        return $this->getMarketingListForUser($user)['featured'];
    }

    /**
     * Demais campanhas; por defeito já exclui só o destaque principal. $excludeId permite excluir outro id extra.
     */
    public function getOtherCampaigns(User $user, $excludeId = null)
    {
        $others = $this->getMarketingListForUser($user)['others'];

        if ($excludeId !== null) {
            $excludeId = (int) $excludeId;

            return $others->filter(fn (Campaign $c) => (int) $c->id !== $excludeId)->values();
        }

        return $others;
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
        ->current()
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
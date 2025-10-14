<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignPost;
use App\Models\CampaignFolder;
use App\Models\CampaignVideo;
use App\Models\CampaignMiscellaneous;
use App\Models\User;
use App\Repositories\CampaignRepository;
use Illuminate\Support\Collection;

class CampaignService
{
    private $campaignRepository;

    public function __construct(CampaignRepository $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * Get all campaigns for a user.
     */
    public function getAllCampaigns(User $user)
    {
        return $this->campaignRepository->getAllForUser($user);
    }

    /**
     * Get a campaign by ID for a user.
     */
    public function getCampaignById($id, User $user)
    {
        return $this->campaignRepository->findByIdForUser($id, $user);
    }

    /**
     * Get featured campaign for marketing list.
     */
    public function getFeaturedCampaign(User $user)
    {
        return $this->campaignRepository->getFeaturedCampaign($user);
    }

    /**
     * Get other campaigns (excluding featured).
     */
    public function getOtherCampaigns(User $user, $excludeId = null)
    {
        return $this->campaignRepository->getOtherCampaigns($user, $excludeId);
    }

    /**
     * Get campaigns with statistics for list view.
     */
    public function getCampaignsForList(User $user)
    {
        $featured = $this->getFeaturedCampaign($user);
        $others = $this->getOtherCampaigns($user, $featured ? $featured->id : null);
        
        return [
            'featured' => $featured,
            'others' => $others
        ];
    }

    /**
     * Get active campaigns.
     */
    public function getActiveCampaigns(): Collection
    {
        return Campaign::active()->current()->get();
    }

    /**
     * Get campaigns visible only to franchises.
     */
    public function getFranchiseOnlyCampaigns(): Collection
    {
        return Campaign::active()->current()->franchiseOnly()->get();
    }

    /**
     * Get campaigns by date range.
     */
    public function getCampaignsByDateRange($startDate, $endDate): Collection
    {
        return Campaign::whereBetween('start_date', [$startDate, $endDate])
            ->orWhereBetween('end_date', [$startDate, $endDate])
            ->orWhere(function($query) use ($startDate, $endDate) {
                $query->where('start_date', '<=', $startDate)
                    ->where('end_date', '>=', $endDate);
            })
            ->get();
    }

    /**
     * Get campaign content statistics.
     */
    public function getCampaignStats(Campaign $campaign): array
    {
        return [
            'total_content' => $campaign->getContentCount(),
            'posts' => $campaign->posts()->count(),
            'folders' => $campaign->folders()->count(),
            'videos' => $campaign->videos()->count(),
            'miscellaneous' => $campaign->miscellaneous()->count(),
            'active_content' => $campaign->getActiveContent()->count(),
            'total_files' => $this->getTotalFilesForCampaign($campaign)
        ];
    }

    /**
     * Get total files for a campaign.
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
     * Get campaign content by type.
     */
    public function getCampaignContentByType(Campaign $campaign, string $type): Collection
    {
        return match($type) {
            'posts' => $campaign->posts()->active()->get(),
            'folders' => $campaign->folders()->active()->get(),
            'videos' => $campaign->videos()->active()->get(),
            'miscellaneous' => $campaign->miscellaneous()->active()->get(),
            default => collect()
        };
    }

    /**
     * Get campaign content filtered by state (for folders).
     */
    public function getCampaignFoldersByState(Campaign $campaign, string $state): Collection
    {
        return $campaign->folders()->active()->ofState($state)->get();
    }

    /**
     * Get campaign posts by type.
     */
    public function getCampaignPostsByType(Campaign $campaign, string $type): Collection
    {
        return $campaign->posts()->active()->ofType($type)->get();
    }

    /**
     * Get campaign videos by type.
     */
    public function getCampaignVideosByType(Campaign $campaign, string $type): Collection
    {
        return $campaign->videos()->active()->ofType($type)->get();
    }

    /**
     * Get campaign miscellaneous by type.
     */
    public function getCampaignMiscellaneousByType(Campaign $campaign, string $type): Collection
    {
        return $campaign->miscellaneous()->active()->ofType($type)->get();
    }

    /**
     * Search campaigns by name or description.
     */
    public function searchCampaigns(string $query): Collection
    {
        return Campaign::where('name', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->active()
            ->get();
    }

    /**
     * Get campaigns with content count.
     */
    public function getCampaignsWithContentCount(): Collection
    {
        return Campaign::with(['posts', 'folders', 'videos', 'miscellaneous'])
            ->active()
            ->get()
            ->map(function($campaign) {
                $campaign->content_count = $campaign->getContentCount();
                return $campaign;
            });
    }

    /**
     * Get recent campaigns.
     */
    public function getRecentCampaigns(int $limit = 10): Collection
    {
        return Campaign::active()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get upcoming campaigns.
     */
    public function getUpcomingCampaigns(): Collection
    {
        return Campaign::where('start_date', '>', now()->toDateString())
            ->where('status', 'active')
            ->orderBy('start_date')
            ->get();
    }

    /**
     * Get ending campaigns.
     */
    public function getEndingCampaigns(int $days = 30): Collection
    {
        $endDate = now()->addDays($days)->toDateString();
        return Campaign::where('end_date', '<=', $endDate)
            ->where('end_date', '>=', now()->toDateString())
            ->where('status', 'active')
            ->orderBy('end_date')
            ->get();
    }

    /**
     * Get campaign content with files.
     */
    public function getCampaignContentWithFiles(Campaign $campaign): array
    {
        return [
            'posts' => $campaign->posts()->active()->with('files')->get(),
            'folders' => $campaign->folders()->active()->with('files')->get(),
            'videos' => $campaign->videos()->active()->with('files')->get(),
            'miscellaneous' => $campaign->miscellaneous()->active()->with('files')->get()
        ];
    }

    /**
     * Get campaign content by file type.
     */
    public function getCampaignContentByFileType(Campaign $campaign, string $fileType): Collection
    {
        $content = collect();
        
        // Get posts with specific file type
        $posts = $campaign->posts()->with(['files' => function($query) use ($fileType) {
            $query->wherePivot('file_type', $fileType);
        }])->get()->filter(function($post) {
            return $post->files->count() > 0;
        });
        
        // Get folders with specific file type
        $folders = $campaign->folders()->with(['files' => function($query) use ($fileType) {
            $query->wherePivot('file_type', $fileType);
        }])->get()->filter(function($folder) {
            return $folder->files->count() > 0;
        });
        
        // Get videos with specific file type
        $videos = $campaign->videos()->with(['files' => function($query) use ($fileType) {
            $query->wherePivot('file_type', $fileType);
        }])->get()->filter(function($video) {
            return $video->files->count() > 0;
        });
        
        // Get miscellaneous with specific file type
        $miscellaneous = $campaign->miscellaneous()->with(['files' => function($query) use ($fileType) {
            $query->wherePivot('file_type', $fileType);
        }])->get()->filter(function($misc) {
            return $misc->files->count() > 0;
        });
        
        return $content->merge($posts)->merge($folders)->merge($videos)->merge($miscellaneous);
    }
} 
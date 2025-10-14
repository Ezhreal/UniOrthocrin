<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CampaignService;

class MarketingController extends Controller
{
    private $campaignService;

    public function __construct(CampaignService $campaignService)
    {
        $this->campaignService = $campaignService;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $campaigns = $this->campaignService->getCampaignsForList($user);
        
        return view('marketing-list', compact('campaigns'));
    }

    public function show($id, Request $request)
    {
        $user = $request->user();
        $campaign = $this->campaignService->getCampaignById($id, $user);
        
        // Preparar dados para JavaScript organizados por tipo
        $postsByType = $campaign->posts()->active()->with('files')->get()->groupBy('type')->map(function($posts) {
            return $posts->flatMap(function($post) {
                return $post->files->where('type', 'image')->map(function($file) {
                    return [
                        'id' => $file->id,
                        'src' => url('/' . $file->path),
                        'alt' => $file->name
                    ];
                });
            });
        });
        
        $videosByType = $campaign->videos()->active()->with('files')->get()->groupBy('type')->map(function($videos) {
            return $videos->map(function($video) {
                $videoFile = $video->files->where('type', 'video')->first();
                $videoThumb = $video->files->where('type', 'image')->first();
                
                return [
                    'id' => $video->id,
                    'title' => $video->name,
                    'type' => $video->type,
                    'video_url' => $videoFile ? url('/' . $videoFile->path) : '',
                    'thumbnail' => $videoThumb ? url('/' . $videoThumb->path) : '',
                    'description' => $video->description
                ];
            });
        });
        
        return view('marketing-detail', compact('campaign', 'postsByType', 'videosByType'));
    }

    /**
     * Download all files from a campaign as ZIP
     */
    public function downloadCampaign($id, Request $request)
    {
        // Redirecionar para o DownloadController via POST
        $downloadController = new \App\Http\Controllers\DownloadController();
        $request->merge([
            'content_type' => 'marketing',
            'content_id' => $id,
            'type' => 'all'
        ]);
        return $downloadController->download($request);
    }

    /**
     * Download files by content type
     */
    public function downloadByType($id, $type, Request $request)
    {
        // Redirecionar para o DownloadController via POST
        $downloadController = new \App\Http\Controllers\DownloadController();
        $request->merge([
            'content_type' => 'marketing',
            'content_id' => $id,
            'type' => $type
        ]);
        return $downloadController->download($request);
    }
} 
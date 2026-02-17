<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\CampaignRepository;
use App\Repositories\ProductRepository;
use App\Repositories\TrainingRepository;
use App\Repositories\LibraryRepository;
use App\Repositories\NewsRepository;

class GlobalSearchService
{
    protected CampaignRepository $campaignRepository;
    protected ProductRepository $productRepository;
    protected TrainingRepository $trainingRepository;
    protected LibraryRepository $libraryRepository;
    protected NewsRepository $newsRepository;

    public function __construct(
        CampaignRepository $campaignRepository,
        ProductRepository $productRepository,
        TrainingRepository $trainingRepository,
        LibraryRepository $libraryRepository,
        NewsRepository $newsRepository
    ) {
        $this->campaignRepository = $campaignRepository;
        $this->productRepository = $productRepository;
        $this->trainingRepository = $trainingRepository;
        $this->libraryRepository = $libraryRepository;
        $this->newsRepository = $newsRepository;
    }

    /**
     * Busca em todos os conteúdos que o usuário tem permissão de ver.
     * Retorna array de itens: thumbnail_url, title, type_label, url
     */
    public function search(User $user, string $query): array
    {
        $q = trim($query);
        if ($q === '') {
            return [];
        }

        $results = collect();

        // Marketing (apenas user_type 1 e 2)
        if (in_array($user->user_type_id, [1, 2])) {
            $campaigns = $this->campaignRepository->searchCampaigns($user, $q);
            foreach ($campaigns as $c) {
                $thumb = $c->getMainThumbnailAttribute();
                $results->push([
                    'thumbnail_url' => $thumb ? url($thumb) : null,
                    'title' => $c->name,
                    'type_label' => 'Marketing',
                    'url' => route('marketing.detail', $c->id),
                ]);
            }
        }

        // Produtos
        $products = $this->productRepository->getFilteredForUser($user, [
            'search' => $q,
            'per_page' => 20,
        ]);
        foreach ($products->items() as $p) {
            $thumb = $p->thumbnail_path
                ? url('/' . ltrim($p->thumbnail_path, '/'))
                : ($p->images->first() ? $p->images->first()->url : null);
            $results->push([
                'thumbnail_url' => $thumb,
                'title' => $p->name,
                'type_label' => 'Produto',
                'url' => route('produtos.detail', $p->id),
            ]);
        }

        // Treinamentos
        $trainings = $this->trainingRepository->getFilteredForUser($user, [
            'search' => $q,
            'per_page' => 20,
        ]);
        foreach ($trainings->items() as $t) {
            $thumb = $t->thumbnail_path
                ? url('/private/' . ltrim($t->thumbnail_path, '/'))
                : ($t->files->first() ? $t->files->first()->url : null);
            $results->push([
                'thumbnail_url' => $thumb,
                'title' => $t->name,
                'type_label' => 'Treinamento',
                'url' => route('treinamentos.detail', $t->id),
            ]);
        }

        // Biblioteca
        $libraries = $this->libraryRepository->getFilteredForUser($user, [
            'search' => $q,
            'per_page' => 20,
        ]);
        foreach ($libraries->items() as $lib) {
            $thumb = !empty($lib->thumbnail_path)
                ? url('/' . ltrim($lib->thumbnail_path, '/'))
                : null;
            $results->push([
                'thumbnail_url' => $thumb,
                'title' => $lib->name,
                'type_label' => 'Biblioteca',
                'url' => route('biblioteca.detail', $lib->id),
            ]);
        }

        // Radar (News)
        $news = $this->newsRepository->getFilteredForUser($user, [
            'search' => $q,
            'per_page' => 20,
        ]);
        foreach ($news->items() as $n) {
            $thumb = $n->mainFile ? $n->mainFile->url : null;
            $results->push([
                'thumbnail_url' => $thumb,
                'title' => $n->title,
                'type_label' => 'Radar',
                'url' => route('news.detail', $n->id),
            ]);
        }

        return $results->all();
    }
}

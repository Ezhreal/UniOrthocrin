<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Support\Facades\Log;

class AutoNotificationService
{
    /**
     * Cria notificação para todos os usuários quando um novo produto é adicionado
     */
    public function notifyNewProduct(string $productName, int $productId): void
    {
        try {
            $users = User::where('status', 'active')->get();
            
            foreach ($users as $user) {
                UserNotification::createNotification(
                    $user->id,
                    'Novo Produto Disponível',
                    "Um novo produto foi adicionado: {$productName}",
                    'info',
                    'App\Models\Product',
                    $productId
                );
            }
            
            Log::info("Notificações de novo produto criadas para {$users->count()} usuários", [
                'product_name' => $productName,
                'product_id' => $productId
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificações de novo produto', [
                'product_name' => $productName,
                'product_id' => $productId,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Cria notificação para todos os usuários quando um novo treinamento é adicionado
     */
    public function notifyNewTraining(string $trainingName, int $trainingId): void
    {
        try {
            $users = User::where('status', 'active')->get();
            
            foreach ($users as $user) {
                UserNotification::createNotification(
                    $user->id,
                    'Novo Treinamento Disponível',
                    "Um novo treinamento foi adicionado: {$trainingName}",
                    'info',
                    'App\Models\Training',
                    $trainingId
                );
            }
            
            Log::info("Notificações de novo treinamento criadas para {$users->count()} usuários", [
                'training_name' => $trainingName,
                'training_id' => $trainingId
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificações de novo treinamento', [
                'training_name' => $trainingName,
                'training_id' => $trainingId,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Cria notificação para todos os usuários quando uma nova campanha é adicionada
     */
    public function notifyNewCampaign(string $campaignName, int $campaignId): void
    {
        try {
            $users = User::where('status', 'active')->get();
            
            foreach ($users as $user) {
                UserNotification::createNotification(
                    $user->id,
                    'Nova Campanha Disponível',
                    "Uma nova campanha foi adicionada: {$campaignName}",
                    'success',
                    'App\Models\Campaign',
                    $campaignId
                );
            }
            
            Log::info("Notificações de nova campanha criadas para {$users->count()} usuários", [
                'campaign_name' => $campaignName,
                'campaign_id' => $campaignId
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificações de nova campanha', [
                'campaign_name' => $campaignName,
                'campaign_id' => $campaignId,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Cria notificação para todos os usuários quando um novo item da biblioteca é adicionado
     */
    public function notifyNewLibraryItem(string $itemName, int $itemId): void
    {
        try {
            $users = User::where('status', 'active')->get();
            
            foreach ($users as $user) {
                UserNotification::createNotification(
                    $user->id,
                    'Novo Item na Biblioteca',
                    "Um novo item foi adicionado à biblioteca: {$itemName}",
                    'info',
                    'App\Models\Library',
                    $itemId
                );
            }
            
            Log::info("Notificações de novo item da biblioteca criadas para {$users->count()} usuários", [
                'item_name' => $itemName,
                'item_id' => $itemId
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificações de novo item da biblioteca', [
                'item_name' => $itemName,
                'item_id' => $itemId,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Cria notificação para todos os usuários quando uma nova notícia é publicada
     */
    public function notifyNewNews(string $newsTitle, int $newsId): void
    {
        try {
            $users = User::where('status', 'active')->get();
            
            foreach ($users as $user) {
                UserNotification::createNotification(
                    $user->id,
                    'Nova Notícia Publicada',
                    "Uma nova notícia foi publicada: {$newsTitle}",
                    'info',
                    'App\Models\News',
                    $newsId
                );
            }
            
            Log::info("Notificações de nova notícia criadas para {$users->count()} usuários", [
                'news_title' => $newsTitle,
                'news_id' => $newsId
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificações de nova notícia', [
                'news_title' => $newsTitle,
                'news_id' => $newsId,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Cria notificação personalizada para todos os usuários
     */
    public function notifyAllUsers(string $title, string $message, string $type = 'info', ?string $relatedType = null, ?int $relatedId = null): void
    {
        try {
            $users = User::where('status', 'active')->get();
            
            foreach ($users as $user) {
                UserNotification::createNotification(
                    $user->id,
                    $title,
                    $message,
                    $type,
                    $relatedType,
                    $relatedId
                );
            }
            
            Log::info("Notificações personalizadas criadas para {$users->count()} usuários", [
                'title' => $title,
                'type' => $type,
                'related_type' => $relatedType,
                'related_id' => $relatedId
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar notificações personalizadas', [
                'title' => $title,
                'error' => $e->getMessage()
            ]);
        }
    }
}


<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\ProductPermission;
use App\Models\TrainingPermission;
use App\Models\NewsPermission;
use App\Models\LibraryPermission;

class NotificationService
{
    /**
     * Cria notificação para usuários com permissão de visualizar um produto
     */
    public static function notifyNewProduct($productId, $productName)
    {
        // Buscar tipos de usuário que têm permissão para ver este produto
        $userTypeIds = ProductPermission::where('product_id', $productId)
            ->where('can_view', true)
            ->pluck('user_type_id')
            ->toArray();

        if (empty($userTypeIds)) {
            return;
        }

        // Criar uma única notificação para todos os tipos de usuário
        Notification::create([
            'title' => 'Novo Produto Disponível',
            'message' => "Um novo produto foi adicionado: {$productName}",
            'type' => 'info',
            'target_type' => 'user_types',
            'target_ids' => $userTypeIds,
            'related_type' => 'App\Models\Product',
            'related_id' => $productId,
        ]);
    }

    /**
     * Cria notificação para usuários com permissão de visualizar um treinamento
     */
    public static function notifyNewTraining($trainingId, $trainingName)
    {
        // Buscar tipos de usuário que têm permissão para ver este treinamento
        $userTypeIds = TrainingPermission::where('training_id', $trainingId)
            ->where('can_view', true)
            ->pluck('user_type_id')
            ->toArray();

        if (empty($userTypeIds)) {
            return;
        }

        // Criar uma única notificação para todos os tipos de usuário
        Notification::create([
            'title' => 'Novo Treinamento Disponível',
            'message' => "Um novo treinamento foi adicionado: {$trainingName}",
            'type' => 'info',
            'target_type' => 'user_types',
            'target_ids' => $userTypeIds,
            'related_type' => 'App\Models\Training',
            'related_id' => $trainingId,
        ]);
    }

    /**
     * Cria notificação para usuários com permissão de visualizar uma notícia
     */
    public static function notifyNewNews($newsId, $newsTitle)
    {
        // Buscar tipos de usuário que têm permissão para ver esta notícia
        $userTypeIds = NewsPermission::where('news_id', $newsId)
            ->where('can_view', true)
            ->pluck('user_type_id')
            ->toArray();

        if (empty($userTypeIds)) {
            return;
        }

        // Criar uma única notificação para todos os tipos de usuário
        Notification::create([
            'title' => 'Nova Notícia Disponível',
            'message' => "Uma nova notícia foi publicada: {$newsTitle}",
            'type' => 'info',
            'target_type' => 'user_types',
            'target_ids' => $userTypeIds,
            'related_type' => 'App\Models\News',
            'related_id' => $newsId,
        ]);
    }

    /**
     * Cria notificação para usuários com permissão de visualizar uma biblioteca
     */
    public static function notifyNewLibrary($libraryId, $libraryName)
    {
        // Buscar tipos de usuário que têm permissão para ver esta biblioteca
        $userTypeIds = LibraryPermission::where('library_id', $libraryId)
            ->where('can_view', true)
            ->pluck('user_type_id')
            ->toArray();

        if (empty($userTypeIds)) {
            return;
        }

        // Criar uma única notificação para todos os tipos de usuário
        Notification::create([
            'title' => 'Nova Biblioteca Disponível',
            'message' => "Um novo item foi adicionado à biblioteca: {$libraryName}",
            'type' => 'info',
            'target_type' => 'user_types',
            'target_ids' => $userTypeIds,
            'related_type' => 'App\Models\Library',
            'related_id' => $libraryId,
        ]);
    }

    /**
     * Cria notificação para usuários com permissão de visualizar uma campanha
     */
    public static function notifyNewCampaign($campaignId, $campaignName)
    {
        // Para campanhas, notificar apenas Franqueados (user_type_id = 2)
        Notification::create([
            'title' => 'Nova Campanha Disponível',
            'message' => "Uma nova campanha foi lançada: {$campaignName}",
            'type' => 'info',
            'target_type' => 'user_types',
            'target_ids' => [2], // Apenas Franqueados
            'related_type' => 'App\Models\Campaign',
            'related_id' => $campaignId,
        ]);
    }

    /**
     * Cria notificação manual para todos os usuários
     */
    public static function notifyAllUsers($title, $message, $type = 'info')
    {
        Notification::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'target_type' => 'all',
            'target_ids' => null,
        ]);
    }

    /**
     * Cria notificação manual para tipos de usuário específicos
     */
    public static function notifyUserTypes($userTypeIds, $title, $message, $type = 'info')
    {
        Notification::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'target_type' => 'user_types',
            'target_ids' => $userTypeIds,
        ]);
    }

    /**
     * Cria notificação manual para usuários específicos
     */
    public static function notifySpecificUsers($userIds, $title, $message, $type = 'info')
    {
        Notification::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'target_type' => 'specific_users',
            'target_ids' => $userIds,
        ]);
    }

}
<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\UserNotification;
use App\Models\User;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->createNotificationsForProduct($product);
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        // Only create notifications if the product was just activated
        if ($product->wasChanged('status') && $product->status === 'active') {
            $this->createNotificationsForProduct($product);
        }
    }

    /**
     * Create notifications for users who have permission to view this product
     */
    private function createNotificationsForProduct(Product $product): void
    {
        // Get all user types that have permission to view this product
        $permissions = $product->permissions()->where('can_view', true)->get();
        
        foreach ($permissions as $permission) {
            // Get all users of this type
            $users = User::where('user_type_id', $permission->user_type_id)->get();
            
            foreach ($users as $user) {
                UserNotification::create([
                    'user_id' => $user->id,
                    'type' => 'new_content',
                    'title' => 'Novo Produto Disponível',
                    'message' => "Um novo produto foi adicionado: {$product->name}",
                    'data' => json_encode([
                        'content_type' => 'product',
                        'content_id' => $product->id,
                        'content_name' => $product->name,
                        'url' => "/produtos/{$product->id}"
                    ]),
                    'is_read' => false,
                ]);
            }
        }
    }
}

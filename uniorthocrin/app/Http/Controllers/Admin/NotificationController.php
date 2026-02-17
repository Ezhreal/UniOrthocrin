<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::query();

        if ($search = $request->get('search')) {
            $query->where('title', 'like', '%' . $search . '%')
                  ->orWhere('message', 'like', '%' . $search . '%');
        }

        if ($type = $request->get('type')) {
            $query->where('type', $type);
        }

        if ($targetType = $request->get('target_type')) {
            $query->where('target_type', $targetType);
        }

        $notifications = $query->orderBy('created_at', 'desc')->paginate(15);
        $userTypes = \App\Models\UserType::orderBy('name')->get();

        return view('admin.notifications.index', compact('notifications', 'userTypes'));
    }

    public function recent()
    {
        $userId = Auth::id();
        $notifications = Notification::forUser($userId)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function (Notification $n) use ($userId) {
                $readAt = $n->isReadBy($userId);
                return [
                    'id' => $n->id,
                    'title' => $n->title,
                    'message' => $n->message,
                    'type' => $n->type,
                    'created_at' => $n->created_at->toIso8601String(),
                    'read_at' => $readAt ? $n->updated_at->toIso8601String() : null,
                    'related_url' => $this->relatedUrlForNotification($n),
                ];
            });

        $unreadCount = Notification::unreadCountForUser($userId);

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * URL do admin para o conteúdo relacionado à notificação (quando houver).
     */
    private function relatedUrlForNotification(Notification $n): ?string
    {
        if (!$n->related_type || !$n->related_id) {
            return null;
        }
        switch ($n->related_type) {
            case 'App\Models\Campaign':
                return route('admin.campaigns.show', $n->related_id);
            case 'App\Models\Product':
                return route('admin.products.show', $n->related_id);
            case 'App\Models\Training':
                return route('admin.training.show', $n->related_id);
            case 'App\Models\Library':
                return route('admin.library.show', $n->related_id);
            default:
                return null;
        }
    }

    /**
     * Marca uma notificação como lida pelo usuário atual (para o dropdown do header).
     */
    public function markAsRead(Notification $notification)
    {
        $notification->markAsReadBy(Auth::id());
        return response()->json(['ok' => true, 'unread_count' => Notification::unreadCountForUser(Auth::id())]);
    }


    public function destroy(Notification $notification)
    {
        $notification->delete();
        return redirect()->route('admin.notifications.index')->with('success', 'Notificação deletada com sucesso!');
    }

    public function create()
    {
        $userTypes = \App\Models\UserType::orderBy('name')->get();
        return view('admin.notifications.create', compact('userTypes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'target_type' => 'required|in:all,user_types,specific_users',
            'user_types' => 'required_if:target_type,user_types|array',
            'user_types.*' => 'exists:user_types,id',
            'specific_users' => 'required_if:target_type,specific_users|array',
            'specific_users.*' => 'exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'type' => 'required|in:info,success,warning,error',
        ]);

        $targetIds = null;
        $estimatedCount = 0;

        switch ($request->target_type) {
            case 'all':
                $estimatedCount = User::where('status', 'active')->count();
                break;
            case 'user_types':
                $targetIds = $request->user_types;
                $estimatedCount = User::whereIn('user_type_id', $targetIds)
                    ->where('status', 'active')
                    ->count();
                break;
            case 'specific_users':
                $targetIds = $request->specific_users;
                $estimatedCount = count($targetIds);
                break;
        }

        Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'target_type' => $request->target_type,
            'target_ids' => $targetIds,
        ]);

        return redirect()->route('admin.notifications.index')->with('success', "Notificação criada para aproximadamente {$estimatedCount} usuários!");
    }

}

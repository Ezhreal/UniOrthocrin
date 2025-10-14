<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Library;
use App\Models\Training;
use App\Models\Campaign;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'products' => Product::count(),
            'library' => Library::count(),
            'training' => Training::count(),
            'campaigns' => Campaign::count(),
            'news' => News::count(),
            'users' => User::count(),
        ];

        // Usuários recentes (últimos 5 criados)
        $recentUsers = User::with('userType')
            ->latest()
            ->limit(5)
            ->get();

        // Últimos acessos (últimos 5 que fizeram login)
        $recentAccesses = User::with('userType')
            ->whereNotNull('last_access')
            ->orderBy('last_access', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentAccesses'));
    }
}

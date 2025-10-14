<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Library;
use App\Models\Training;
use App\Models\News;
use App\Models\Campaign;
use App\Models\File;
use App\Models\UserView;
use App\Models\DownloadOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::where('status', 'active')->count(),
            'total_products' => Product::count(),
            'total_library_items' => Library::count(),
            'total_trainings' => Training::count(),
            'total_news' => News::count(),
            'total_campaigns' => Campaign::count(),
            'total_files' => File::count(),
        ];

        // Usuários por perfil
        $usersByType = User::select('user_types.name as type_name', DB::raw('count(*) as count'))
            ->join('user_types', 'users.user_type_id', '=', 'user_types.id')
            ->groupBy('user_types.id', 'user_types.name')
            ->get();

        // Conteúdo por status (apenas total, sem ativos/inativos)
        $contentByStatus = collect([
            ['type' => 'Produtos', 'total' => Product::count()],
            ['type' => 'Biblioteca', 'total' => Library::count()],
            ['type' => 'Treinamentos', 'total' => Training::count()],
            ['type' => 'Radar', 'total' => News::count()],
            ['type' => 'Campanhas', 'total' => Campaign::count()],
        ]);

        // Atividade recente (últimos 30 dias)
        $recentActivity = [
            'new_users' => User::where('created_at', '>=', now()->subDays(30))->count(),
            'new_products' => Product::where('created_at', '>=', now()->subDays(30))->count(),
            'new_library_items' => Library::where('created_at', '>=', now()->subDays(30))->count(),
            'new_trainings' => Training::where('created_at', '>=', now()->subDays(30))->count(),
            'new_news' => News::where('created_at', '>=', now()->subDays(30))->count(),
            'new_campaigns' => Campaign::where('created_at', '>=', now()->subDays(30))->count(),
        ];

        return view('admin.reports.index', compact('stats', 'usersByType', 'contentByStatus', 'recentActivity'));
    }

    public function users(Request $request)
    {
        $query = User::with('userType');

        // Filtros
        if ($search = $request->get('search')) {
            $query->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
        }

        if ($userType = $request->get('user_type')) {
            $query->where('user_type_id', $userType);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->where('created_at', '<=', $dateTo . ' 23:59:59');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Buscar tipos de usuário de forma segura
        try {
            $userTypes = \App\Models\UserType::orderBy('name')->get();
        } catch (\Exception $e) {
            $userTypes = collect();
        }

        // Estatísticas dos usuários
        $userStats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'new_this_month' => User::where('created_at', '>=', now()->startOfMonth())->count(),
            'new_this_week' => User::where('created_at', '>=', now()->startOfWeek())->count(),
        ];

        return view('admin.reports.users', compact('users', 'userTypes', 'userStats'));
    }

    public function downloads(Request $request)
    {
        try {
            // Verificar se o modelo DownloadOption existe
            if (!class_exists('App\Models\DownloadOption')) {
                // Se não existir, retornar dados vazios
                $downloads = new \Illuminate\Pagination\LengthAwarePaginator(
                    collect(),
                    0,
                    20,
                    1,
                    ['path' => request()->url(), 'pageName' => 'page']
                );
                $downloadStats = [
                    'total' => 0,
                    'this_month' => 0,
                    'this_week' => 0,
                    'today' => 0,
                ];
                $downloadsByType = collect();
                $downloadsByModule = collect();
                $topDownloads = collect();
                
                return view('admin.reports.downloads', compact('downloads', 'downloadStats', 'downloadsByType', 'downloadsByModule', 'topDownloads'));
            }

            $query = DownloadOption::with(['user', 'resource']);

            // Filtros
            if ($search = $request->get('search')) {
                $query->whereHas('user', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('email', 'like', '%' . $search . '%');
                });
            }

            if ($resourceType = $request->get('resource_type')) {
                $query->where('resource_type', $resourceType);
            }

            if ($dateFrom = $request->get('date_from')) {
                $query->where('created_at', '>=', $dateFrom);
            }

            if ($dateTo = $request->get('date_to')) {
                $query->where('created_at', '<=', $dateTo . ' 23:59:59');
            }

            $downloads = $query->orderBy('created_at', 'desc')->paginate(20);

            // Estatísticas dos downloads
            $downloadStats = [
                'total' => DownloadOption::count(),
                'this_month' => DownloadOption::where('created_at', '>=', now()->startOfMonth())->count(),
                'this_week' => DownloadOption::where('created_at', '>=', now()->startOfWeek())->count(),
                'today' => DownloadOption::where('created_at', '>=', now()->startOfDay())->count(),
            ];

            // Downloads por tipo de recurso
            $downloadsByType = DownloadOption::select('resource_type', DB::raw('count(*) as count'))
                ->groupBy('resource_type')
                ->get()
                ->mapWithKeys(function ($item) {
                    $type = class_basename($item->resource_type);
                    return [$type => $item->count];
                });

            // Downloads por módulo (últimos 30 dias)
            $downloadsByModule = DownloadOption::select('resource_type', DB::raw('count(*) as count'))
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('resource_type')
                ->get()
                ->mapWithKeys(function ($item) {
                    $type = class_basename($item->resource_type);
                    return [$type => $item->count];
                });

            // Top 10 recursos mais baixados
            $topDownloads = DownloadOption::select('resource_id', 'resource_type', DB::raw('count(*) as download_count'))
                ->with(['resource'])
                ->groupBy('resource_id', 'resource_type')
                ->orderBy('download_count', 'desc')
                ->limit(10)
                ->get();

            return view('admin.reports.downloads', compact('downloads', 'downloadStats', 'downloadsByType', 'downloadsByModule', 'topDownloads'));
        } catch (\Exception $e) {
            // Se houver erro, retornar dados vazios
            $downloads = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                20,
                1,
                ['path' => request()->url(), 'pageName' => 'page']
            );
            $downloadStats = [
                'total' => 0,
                'this_month' => 0,
                'this_week' => 0,
                'today' => 0,
            ];
            $downloadsByType = collect();
            $downloadsByModule = collect();
            $topDownloads = collect();
            
            return view('admin.reports.downloads', compact('downloads', 'downloadStats', 'downloadsByType', 'downloadsByModule', 'topDownloads'));
        }
    }

    public function files(Request $request)
    {
        try {
            $query = File::with(['fileable']);

            // Filtros
            if ($search = $request->get('search')) {
                $query->where('name', 'like', '%' . $search . '%');
            }

            if ($fileType = $request->get('file_type')) {
                $query->where('file_type', $fileType);
            }

            if ($resourceType = $request->get('resource_type')) {
                $query->where('fileable_type', $resourceType);
            }

            if ($dateFrom = $request->get('date_from')) {
                $query->where('created_at', '>=', $dateFrom);
            }

            if ($dateTo = $request->get('date_to')) {
                $query->where('created_at', '<=', $dateTo . ' 23:59:59');
            }

            $files = $query->orderBy('created_at', 'desc')->paginate(20);

            // Estatísticas dos arquivos
            $fileStats = [
                'total' => File::count(),
                'total_size' => File::sum('size') ?? 0,
                'this_month' => File::where('created_at', '>=', now()->startOfMonth())->count(),
                'this_week' => File::where('created_at', '>=', now()->startOfWeek())->count(),
            ];

            // Arquivos por tipo
            $filesByType = File::select('file_type', DB::raw('count(*) as count'), DB::raw('sum(size) as total_size'))
                ->groupBy('file_type')
                ->get();

            // Arquivos por módulo
            $filesByModule = File::select('fileable_type', DB::raw('count(*) as count'), DB::raw('sum(size) as total_size'))
                ->groupBy('fileable_type')
                ->get()
                ->mapWithKeys(function ($item) {
                    $type = class_basename($item->fileable_type);
                    return [$type => ['count' => $item->count, 'size' => $item->total_size ?? 0]];
                });

            // Arquivos por módulo (últimos 30 dias)
            $filesByModuleRecent = File::select('fileable_type', DB::raw('count(*) as count'), DB::raw('sum(size) as total_size'))
                ->where('created_at', '>=', now()->subDays(30))
                ->groupBy('fileable_type')
                ->get()
                ->mapWithKeys(function ($item) {
                    $type = class_basename($item->fileable_type);
                    return [$type => ['count' => $item->count, 'size' => $item->total_size ?? 0]];
                });

            // Top 10 arquivos maiores
            $topFiles = File::with(['fileable'])
                ->orderBy('size', 'desc')
                ->limit(10)
                ->get();

            return view('admin.reports.files', compact('files', 'fileStats', 'filesByType', 'filesByModule', 'filesByModuleRecent', 'topFiles'));
        } catch (\Exception $e) {
            // Se houver erro, retornar dados vazios
            $files = new \Illuminate\Pagination\LengthAwarePaginator(
                collect(),
                0,
                20,
                1,
                ['path' => request()->url(), 'pageName' => 'page']
            );
            $fileStats = [
                'total' => 0,
                'total_size' => 0,
                'this_month' => 0,
                'this_week' => 0,
            ];
            $filesByType = collect();
            $filesByModule = collect();
            $filesByModuleRecent = collect();
            $topFiles = collect();

            return view('admin.reports.files', compact('files', 'fileStats', 'filesByType', 'filesByModule', 'filesByModuleRecent', 'topFiles'));
        }
    }

    public function access(Request $request)
    {
        $query = UserView::with(['user', 'viewable']);

        // Filtros
        if ($search = $request->get('search')) {
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($resourceType = $request->get('resource_type')) {
            $query->where('viewable_type', $resourceType);
        }

        if ($dateFrom = $request->get('date_from')) {
            $query->where('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->get('date_to')) {
            $query->where('created_at', '<=', $dateTo . ' 23:59:59');
        }

        $accesses = $query->orderBy('created_at', 'desc')->paginate(20);

        // Estatísticas de acesso
        $accessStats = [
            'total' => UserView::count(),
            'this_month' => UserView::where('created_at', '>=', now()->startOfMonth())->count(),
            'this_week' => UserView::where('created_at', '>=', now()->startOfWeek())->count(),
            'today' => UserView::where('created_at', '>=', now()->startOfDay())->count(),
        ];

        // Acessos por tipo de recurso
        $accessByType = UserView::select('viewable_type', DB::raw('count(*) as count'))
            ->groupBy('viewable_type')
            ->get()
            ->mapWithKeys(function ($item) {
                $type = class_basename($item->viewable_type);
                return [$type => $item->count];
            });

        // Acessos por usuário (top 10)
        $topUsers = UserView::select('user_id', DB::raw('count(*) as count'))
            ->with('user')
            ->groupBy('user_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();

        // Acessos por módulo (últimos 30 dias)
        $accessByModuleRecent = UserView::select('viewable_type', DB::raw('count(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('viewable_type')
            ->get()
            ->mapWithKeys(function ($item) {
                $type = class_basename($item->viewable_type);
                return [$type => $item->count];
            });

        // Top 10 recursos mais acessados
        $topResources = UserView::select('viewable_id', 'viewable_type', DB::raw('count(*) as access_count'))
            ->with(['viewable'])
            ->groupBy('viewable_id', 'viewable_type')
            ->orderBy('access_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.reports.access', compact('accesses', 'accessStats', 'accessByType', 'topUsers', 'accessByModuleRecent', 'topResources'));
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'users');
        $format = $request->get('format', 'csv');

        switch ($type) {
            case 'users':
                return $this->exportUsers($format);
            case 'downloads':
                return $this->exportDownloads($format);
            case 'files':
                return $this->exportFiles($format);
            case 'access':
                return $this->exportAccess($format);
            default:
                return redirect()->back()->with('error', 'Tipo de relatório inválido');
        }
    }

    private function exportUsers($format)
    {
        $users = User::with('userType')->get();
        
        if ($format === 'csv') {
            $filename = 'usuarios_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($users) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Nome', 'Email', 'Perfil', 'Status', 'Empresa', 'Criado em']);

                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->name,
                        $user->email,
                        $user->userType->name ?? 'N/A',
                        $user->status,
                        $user->nome_fantasia ?? $user->razao_social ?? 'N/A',
                        $user->created_at->format('d/m/Y H:i'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'Formato não suportado');
    }

    private function exportDownloads($format)
    {
        $downloads = DownloadOption::with(['user', 'resource'])->get();
        
        if ($format === 'csv') {
            $filename = 'downloads_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($downloads) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Usuário', 'Email', 'Recurso', 'Tipo', 'Data do Download']);

                foreach ($downloads as $download) {
                    fputcsv($file, [
                        $download->user->name ?? 'N/A',
                        $download->user->email ?? 'N/A',
                        $download->resource->name ?? 'N/A',
                        class_basename($download->resource_type),
                        $download->created_at->format('d/m/Y H:i'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'Formato não suportado');
    }

    private function exportFiles($format)
    {
        $files = File::with(['fileable'])->get();
        
        if ($format === 'csv') {
            $filename = 'arquivos_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($files) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Nome', 'Tipo', 'Módulo', 'Tamanho (MB)', 'Data de Upload']);

                foreach ($files as $fileItem) {
                    fputcsv($file, [
                        $fileItem->name,
                        $fileItem->file_type,
                        class_basename($fileItem->fileable_type),
                        number_format($fileItem->size / 1024 / 1024, 2),
                        $fileItem->created_at->format('d/m/Y H:i'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'Formato não suportado');
    }

    private function exportAccess($format)
    {
        $accesses = UserView::with(['user', 'viewable'])->get();
        
        if ($format === 'csv') {
            $filename = 'acessos_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($accesses) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Usuário', 'Email', 'Recurso', 'Tipo', 'Data do Acesso']);

                foreach ($accesses as $access) {
                    fputcsv($file, [
                        $access->user->name ?? 'N/A',
                        $access->user->email ?? 'N/A',
                        $access->viewable->name ?? 'N/A',
                        class_basename($access->viewable_type),
                        $access->created_at->format('d/m/Y H:i'),
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return redirect()->back()->with('error', 'Formato não suportado');
    }
}

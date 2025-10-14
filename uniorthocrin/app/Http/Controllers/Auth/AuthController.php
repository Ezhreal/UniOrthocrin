<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Mostra o formulário de login
     */
    public function showLoginForm()
    {
        // Se já estiver logado, redireciona para dashboard
        if (Auth::check()) {
            return $this->redirectBasedOnUserType();
        }
        
        // Detectar se é rota admin
        $request = request();
        if ($request->is('admin/*') || $request->path() === 'admin/login') {
            return view('admin.auth.login');
        }
        return view('auth.login');
    }

    /**
     * Processa o login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            Log::info('Usuário logado com sucesso', [
                'user_id' => Auth::id(),
                'email' => Auth::user()->email,
                'user_type' => Auth::user()->user_type_id ?? 'N/A'
            ]);

            // Redireciona baseado no tipo de usuário
            return $this->redirectBasedOnUserType();
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não correspondem aos nossos registros.',
        ])->onlyInput('email');
    }

    /**
     * Processa o logout
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();
        $userType = Auth::user()?->user_type_id;
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Usuário fez logout', ['user_id' => $userId, 'user_type' => $userType]);

        // Redireciona baseado na rota de origem
        if ($request->is('admin/*') || $request->routeIs('admin.*')) {
            return redirect()->route('admin.login');
        }
        
        return redirect('/');
    }

    /**
     * Redireciona baseado no tipo de usuário
     */
    protected function redirectBasedOnUserType()
    {
        $user = Auth::user();
        
        Log::info('Redirecionando usuário após login', [
            'user_id' => $user->id,
            'user_type_id' => $user->user_type_id ?? 'N/A',
            'email' => $user->email
        ]);
        
        if ((int) ($user->user_type_id ?? 0) === 1) {
            Log::info('Redirecionando admin para /admin', ['user_id' => $user->id]);
            return redirect()->route('admin.dashboard');
        }
        
        Log::info('Redirecionando usuário para home', ['user_id' => $user->id]);
        return redirect()->route('home');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PasswordResetController extends Controller
{
    public function showRequestForm()
    {
        return view('auth.password-email');
    }

    public function sendRandomPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $validated['email'])->first();

        // Para segurança, sempre retornamos a mesma resposta,
        // mesmo que o e-mail não exista.
        if ($user) {
            $plain = Str::random(12);

            $user->password = Hash::make($plain);
            $user->save();

            Mail::raw(
                "Olá {$user->name},\n\n" .
                "Sua nova senha de acesso à plataforma UniOrthocrin é:\n\n" .
                $plain . "\n\n" .
                "Recomendamos que você altere essa senha após o primeiro login.\n\n" .
                "Atenciosamente,\nEquipe UniOrthocrin",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Nova senha de acesso - UniOrthocrin');
                }
            );
        }

        return back()->with('status', 'Se o e-mail informado estiver cadastrado, uma nova senha foi enviada.');
    }
}

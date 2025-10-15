@extends('admin.layouts.auth')

@section('title', 'Login Admin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-md py-10">
        <div class="mb-8 flex flex-col items-center">
            <img src="{{ asset('images/logo.png') }}" alt="UniOrthocrin Logo" class="mb-2 w-32 h-16 object-contain" />
        </div>
        <h1 class="text-2xl font-bold text-[#910039] mb-2 text-center">Acesso do Administrador</h1>
        <p class="text-[#747474] text-sm text-center mb-8 max-w-xs mx-auto">Entre com suas credenciais para acessar o painel administrativo.</p>

        <form action="{{ route('admin.login.post') }}" method="POST" class="flex flex-col gap-4">
            @csrf
            <div>
                <label for="email" class="block text-[#747474] text-xs font-semibold mb-1">E-mail</label>
                <input id="email" name="email" type="email" autocomplete="email" required
                    class="w-full px-3 py-2 border border-[#DDDDDD] rounded-md text-[#747474] placeholder-[#747474] focus:outline-none focus:ring-2 focus:ring-[#910039] focus:border-[#910039] bg-white" />
            </div>
            <div>
                <label for="password" class="block text-[#747474] text-xs font-semibold mb-1">Senha</label>
                <input id="password" name="password" type="password" autocomplete="current-password" required
                    class="w-full px-3 py-2 border border-[#DDDDDD] rounded-md text-[#747474] placeholder-[#747474] focus:outline-none focus:ring-2 focus:ring-[#910039] focus:border-[#910039] bg-white" />
            </div>

            @if ($errors->any())
                <div class="text-red-500 text-xs mb-2">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-[#910039] focus:ring-[#910039] border-gray-300 rounded">
                <label for="remember_me" class="ml-2 block text-sm text-gray-900">Lembrar-me</label>
            </div>

            <button type="submit"
                class="w-full py-3 bg-[#910039] text-white font-bold rounded-full hover:bg-[#FEAD00] transition-colors duration-200 uppercase tracking-wider text-sm">
                Acessar
            </button>
        </form>
    </div>
  </div>
@endsection



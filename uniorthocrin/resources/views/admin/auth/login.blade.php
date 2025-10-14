@extends('admin.layouts.auth')

@section('title', 'Login Admin')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900">Acesso do Administrador</h2>
            <p class="mt-2 text-sm text-gray-600">Entre com suas credenciais</p>
        </div>
        <form class="mt-8 space-y-6" action="{{ route('admin.login.post') }}" method="POST">
            @csrf
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">E-mail</label>
                    <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-[#910039] focus:border-[#910039] focus:z-10 sm:text-sm" placeholder="E-mail">
                </div>
                <div>
                    <label for="password" class="sr-only">Senha</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-[#910039] focus:border-[#910039] focus:z-10 sm:text-sm" placeholder="Senha">
                </div>
            </div>

            @if ($errors->any())
                <div class="text-red-600 text-sm">{{ $errors->first() }}</div>
            @endif

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox" class="h-4 w-4 text-[#910039] focus:ring-[#910039] border-gray-300 rounded">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">Lembrar-me</label>
                </div>
            </div>

            <div>
                <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-[#910039] hover:bg-[#7A0030] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#910039]">
                    Entrar
                </button>
            </div>
        </form>
    </div>
  </div>
@endsection



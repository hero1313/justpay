@include('layouts.head')
@include('layouts.js')


<x-guest-layout>
    <div>
        <div class="dropdown ml-2 log-drop">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                {{ Config::get('app.locale') }}
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="locale/en">en</a>
                <a class="dropdown-item" href="locale/ge">ge</a>
                <a class="dropdown-item" href="locale/az">az</a>
                <a class="dropdown-item" href="locale/am">arm</a>
                <a class="dropdown-item" href="locale/de">ger</a>
                <a class="dropdown-item" href="locale/kz">kz</a>
                <a class="dropdown-item" href="locale/ru">ru</a>
                <a class="dropdown-item" href="locale/tj">tj</a>
                <a class="dropdown-item" href="locale/tr">tr</a>
                <a class="dropdown-item" href="locale/ua">ua</a>
                <a class="dropdown-item" href="locale/uz">uz</a>
            </div>
        </div>
    </div>
    <x-jet-authentication-card>

        <x-slot name="logo">
            <img class="auth-logo" style="width: 250px" src="../assets/img/logo.png" alt="">
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        @if (session('status'))
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ session('status') }}
        </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <label for="email">@lang('public.email')</label>
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="mt-4">
                <label for="password">@lang('public.password')</label>
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <div class=" mt-4 flex">
                <label for="remember_me" class="flex items-center">
                    <x-jet-checkbox id="remember_me" name="remember" />
                    <span class="ml-2 text-sm text-gray-600">@lang('public.remember')</span>
                </label>

                <label for="remember_me" style="margin-left:auto" class="flex items-center">
                    <a href="/register">@lang('public.register')</a>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    @lang('public.forgot_password')
                </a>
                @endif

                <x-jet-button class="ml-4">
                    @lang('public.login')
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
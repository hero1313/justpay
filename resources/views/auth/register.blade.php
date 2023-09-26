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
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <label for="name">@lang('public.name')</label>
                <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <label for="email">@lang('public.email')</label>
                <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <label for="password">@lang('public.password')</label>
                <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <label for="password_confirmation">@lang('public.confirm_password')</label>
                <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
            <div class="mt-4">
                <x-jet-label for="terms">
                    <div class="flex items-center">
                        <x-jet-checkbox name="terms" id="terms" />

                        <div class="ml-2">
                            {!! __('I agree to the :terms_of_service and :privacy_policy', [
                            'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                            'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                            ]) !!}
                        </div>
                    </div>
                </x-jet-label>
            </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    @lang('public.login')
                </a>

                <x-jet-button class="ml-4">
                    @lang('public.register')
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
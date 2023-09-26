<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <img style="width: 250px" src="../assets/img/logo.png" alt="">
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            @lang('public.verification_sms')
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600">
                @lang('public.resend_success')
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-jet-button type="submit">
                        @lang('public.resend_verification')
                    </x-jet-button>
                </div>
            </form>

            <div>
                <a href="{{ route('logout') }}">
                    <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 ml-2">
                        @lang('public.Logout')
                    </button>
                </a>

            </div>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>

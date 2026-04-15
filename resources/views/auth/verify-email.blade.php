@extends('layouts.default')

@section('content')
    <!-- Body -->
    <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
        <!-- Card -->
        <div
            class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm bg-white dark:bg-gray-800 p-8 rounded-[10px] shadow-[0_0_10px_#d1d5db] dark:shadow-none dark:border dark:border-gray-700 transition-colors duration-300">

            <h2 class="mt-3 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-white">
                Verify Email</h2>

            <p class="mt-4 text-sm text-gray-600 dark:text-gray-400 text-center">
                Thank you for signing up! Before getting started, please verify your email address by clicking the link
                we just sent to you. If you didn't receive the email, we'll gladly send you a new one.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="mt-4 text-sm font-medium text-green-600 dark:text-green-400 text-center">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            @endif

            <div class="mt-6 flex items-center justify-between gap-4">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit"
                        class="flex justify-center rounded-md bg-gradient-to-r from-[#4a00e0] via-[#8e2de2] to-[#4a00e0] bg-[length:200%_auto] hover:bg-[position:right_center] transition-all duration-300 px-4 py-2 text-sm font-semibold leading-6 text-white shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600">
                        Resend Email
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="text-sm font-medium text-[#8e2de2] hover:text-[#4a00e0] dark:text-[#a855f7] dark:hover:text-[#d8b4fe] transition-colors underline">
                        Log Out
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
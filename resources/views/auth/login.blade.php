@extends('layouts.default')

@section('content')
<!-- Body -->
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <!-- Card -->
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm bg-white dark:bg-gray-800 p-8 rounded-[10px] shadow-[0_0_10px_#d1d5db] dark:shadow-none dark:border dark:border-gray-700 transition-colors duration-300">

        <h2 class="mt-3 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-white">Sign in to Tixxy</h2>

        <form class="space-y-6 mb-5 mt-6" action="{{ route('login') }}" method="POST">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email Address</label>
                <input type="email" name="email" id="email" placeholder="Your Email Address" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" required>
            </div>
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <!-- Password -->
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                <input type="password" name="password" id="password" placeholder="Your Password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" required>
            </div>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror

            <!-- Lupa Password -->
            @if (Route::has('password.request'))
                <div class="text-sm text-end">
                    <a href="{{ route('password.request') }}" class="font-semibold text-[#8e2de2] hover:text-[#4a00e0] dark:text-[#a855f7] dark:hover:text-[#d8b4fe] transition-colors">Forgot password?</a>
                </div>
            @endif

            <!-- Submit -->
            <button type="submit" class="flex w-full justify-center rounded-md bg-gradient-to-r from-[#4a00e0] via-[#8e2de2] to-[#4a00e0] bg-[length:200%_auto] hover:bg-[position:right_center] transition-all duration-300 px-3 py-2 text-sm font-semibold leading-6 text-white shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600">Sign In</button>
            <div class="text-sm font-light text-gray-500 dark:text-gray-400 mt-4 text-center">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-[#8e2de2] hover:text-[#4a00e0] dark:text-[#a855f7] dark:hover:text-[#d8b4fe] transition-colors">Register here.</a>
            </div>

        </form>
    </div>
</div>
@endsection

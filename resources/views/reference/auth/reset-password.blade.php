<x-layouts.guest>
    <x-slot name="title">Reset Password</x-slot>

    <h1 class="sr-only">Reset password</h1>
    <p class="text-sm font-medium text-center text-gray-500 dark:text-gray-400">
        You are only one step a way from your new password, recover your password now.
    </p>
    <form action="{{ route('password.store') ?? '#' }}" method="POST" class="space-y-6 mt-6">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') ?? 'dummy' }}">
        <input
            class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
            type="email"
            name="email"
            placeholder="Email address"
            value="{{ old('email', $request->email ?? '') }}"
            required
            autofocus
        />
        <input
            class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
            type="password"
            name="password"
            placeholder="Password"
            required
        />
        <input
            class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
            type="password"
            name="password_confirmation"
            placeholder="Confirm Password"
            required
        />
        <div>
            <button
                type="submit"
                class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
            >
                Reset password
            </button>
        </div>
    </form>
</x-layouts.guest>

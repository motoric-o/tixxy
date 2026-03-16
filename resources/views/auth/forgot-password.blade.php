<x-layouts.guest>
    <x-slot name="title">Forgot Password</x-slot>

    <h1 class="sr-only">Request new password</h1>
    <p class="text-sm font-medium text-center text-gray-500 dark:text-gray-400">
        You forgot your password? Here you can easily retrieve a new password.
    </p>
    <form action="{{ route('password.email') ?? '#' }}" method="POST" class="space-y-6 mt-6">
        @csrf
        <input
            class="w-full px-4 py-2 border rounded-md dark:bg-darker dark:border-gray-700 focus:outline-none focus:ring focus:ring-primary-100 dark:focus:ring-primary-darker"
            type="email"
            name="email"
            placeholder="Email address"
            required
            autofocus
        />

        <div>
            <button
                type="submit"
                class="w-full px-4 py-2 font-medium text-center text-white transition-colors duration-200 rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-1 dark:focus:ring-offset-darker"
            >
                Request new password
            </button>
        </div>
    </form>

    <!-- Reset password link -->
    <div class="mt-6 text-sm text-gray-600 dark:text-gray-400">
        <a href="{{ route('password.reset', ['token' => 'dummy']) ?? 'reset-password.html' }}" class="text-blue-600 hover:underline">Reset password</a>
    </div>
</x-layouts.guest>

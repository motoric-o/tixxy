@extends ('layouts.default')

@section('content')
<!-- Body -->
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <!-- Card -->
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm bg-white dark:bg-gray-800 p-8 rounded-[10px] shadow-[0_0_10px_#d1d5db] dark:shadow-none dark:border dark:border-gray-700 transition-colors duration-300">

        <h2 class="mt-3 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900 dark:text-white">Daftar Akun Tixxy</h2>

        <form class="space-y-6 mb-5 mt-8" action="{{ route('register') }}" method="POST">
            @csrf

                <!-- Nama -->
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                    <input type="text" name="name" id="name" placeholder="Nama Lengkap Anda" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" required>
                </div>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <!-- Email -->
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat Email</label>
                    <input type="email" name="email" id="email" placeholder="Alamat Email Anda" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" required>
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <!-- Tanggal Lahir -->
                <div>
                    <label for="date_of_birth" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Lahir</label>
                    <input type="date" name="date_of_birth" id="date_of_birth" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" required>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password Anda" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" required>
                </div>
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Konfirmasi Password Anda" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-purple-600 focus:border-purple-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-purple-500 dark:focus:border-purple-500 transition-colors duration-300" required>
                </div>
                @error('password_confirmation')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <!-- <div class="flex items-start">
                    <div class="flex items-center h-5">
                        <input id="terms" aria-describedby="terms" type="checkbox" class="w-4 h-4 border border-gray-300 rounded bg-gray-50 focus:ring-3 focus:ring-purple-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-purple-600 dark:ring-offset-gray-800" required="">
                    </div>
                    <div class="ml-3 text-sm">
                        <label for="terms" class="font-light text-gray-500 dark:text-gray-300">I accept the <a class="font-medium text-purple-600 hover:underline dark:text-purple-500" href="#">Terms and Conditions</a></label>
                    </div>
                </div> -->

            <!-- Submit -->
            <button type="submit" class="flex w-full justify-center rounded-md bg-gradient-to-r from-[#4a00e0] via-[#8e2de2] to-[#4a00e0] bg-[length:200%_auto] hover:bg-[position:right_center] transition-all duration-300 px-3 py-2 text-sm font-semibold leading-6 text-white shadow-md focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600">Daftar</button>
            <div class="text-sm font-light text-gray-500 dark:text-gray-400 mt-4 text-center">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-medium text-[#8e2de2] hover:text-[#4a00e0] dark:text-[#a855f7] dark:hover:text-[#d8b4fe] transition-colors">Masuk disini.</a>
            </div>

        </form>
    </div>
</div>
@endsection

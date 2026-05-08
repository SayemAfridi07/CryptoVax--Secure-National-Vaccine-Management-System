<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-orange-50 to-orange-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Update Profile Information -->
            <div class="p-6 sm:p-8 bg-white shadow-lg sm:rounded-lg transform transition duration-500 hover:scale-105 hover:shadow-2xl">
                <h3 class="text-lg font-bold text-orange-600 mb-4">
                    {{ __('Update Profile Information') }}
                </h3>
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="p-6 sm:p-8 bg-white shadow-lg sm:rounded-lg transform transition duration-500 hover:scale-105 hover:shadow-2xl">
                <h3 class="text-lg font-bold text-orange-600 mb-4">
                    {{ __('Update Password') }}
                </h3>
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete Account -->
            <div class="p-6 sm:p-8 bg-white shadow-lg sm:rounded-lg transform transition duration-500 hover:scale-105 hover:shadow-2xl">
                <h3 class="text-lg font-bold text-red-600 mb-4">
                    {{ __('Delete Account') }}
                </h3>
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #fdf6ec;
        }

        .bg-gradient-to-br {
            background: linear-gradient(to bottom right, #fffaf3, #fde8cd);
        }

        h3 {
            animation: fadeIn 1s ease-out;
        }

        .transform:hover {
            transition: all 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</x-app-layout>

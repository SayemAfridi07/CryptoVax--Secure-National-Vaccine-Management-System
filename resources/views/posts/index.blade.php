<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Vaccine Diary') }}</h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Write a Secure Note</h3>
            <form method="POST" action="{{ route('posts.store') }}">
                @csrf
                <x-text-input name="title" placeholder="Title" class="w-full mb-3" required />
                <textarea name="content" placeholder="How are you feeling today?" class="w-full border-gray-300 rounded-md shadow-sm mb-3" rows="3" required></textarea>
                <x-primary-button>Save Encrypted Note</x-primary-button>
            </form>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Your Diary Entries</h3>
            @foreach ($posts as $post)
                <div class="mb-4 p-4 border rounded-lg">
                    <h4 class="font-bold text-lg">{{ $post->title }}</h4>
                    <p class="text-gray-600 mt-2">{{ $post->content }}</p>
                    <a href="{{ route('posts.edit', $post) }}" class="text-indigo-600 text-sm mt-3 inline-block">Edit (Rotates to ECC)</a>
                </div>
            @endforeach
            @if($posts->isEmpty()) <p>No entries yet.</p> @endif
        </div>
    </div>
</x-app-layout>
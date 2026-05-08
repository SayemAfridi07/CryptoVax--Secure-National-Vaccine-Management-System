<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Diary Entry') }}</h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <form method="POST" action="{{ route('posts.update', $post) }}">
                @csrf
                @method('PATCH')
                <x-text-input name="title" value="{{ $post->title }}" class="w-full mb-3" required />
                <textarea name="content" class="w-full border-gray-300 rounded-md shadow-sm mb-3" rows="5" required>{{ $post->content }}</textarea>
                <x-primary-button>Update & Re-encrypt</x-primary-button>
            </form>
        </div>
    </div>
</x-app-layout>
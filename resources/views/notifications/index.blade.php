<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Notifikasi Saya') }}
            </h2>
            @if(count($notifications) > 0)
                <form action="{{ route('notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg text-sm transition shadow-sm">
                        Tandai Semua Dibaca
                    </button>
                </form>
            @endif
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-emerald-100 text-emerald-700 border border-emerald-300 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @forelse ($notifications as $notification)
                        <div class="flex items-center justify-between p-4 mb-3 rounded-lg border {{ $notification->is_read ? 'bg-gray-50 border-gray-200' : 'bg-indigo-50/50 border-indigo-200' }}">
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <h4 class="font-bold text-gray-900 text-base">{{ $notification->title }}</h4>
                                    @if(!$notification->is_read)
                                        <span class="inline-block w-2.5 h-2.5 bg-indigo-600 rounded-full" title="Belum dibaca"><span class="sr-only">Belum dibaca</span></span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                                <span class="text-xs text-gray-400 mt-2 block">{{ $notification->created_at->diffForHumans() }}</span>
                            </div>
                            @if(!$notification->is_read)
                                <form action="{{ route('notifications.read', $notification->id) }}" method="POST" class="ml-4">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold underline">
                                        Tandai Dibaca
                                    </button>
                                </form>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            Belum ada notifikasi.
                        </div>
                    @endforelse

                    <div class="mt-4">
                        {{ $notifications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@props(['unreadCount' => 0])

<div x-data="notificationDropdown()" class="relative" @click.outside="open = false">
    <!-- Notification Bell Button -->
    <button @click="toggleDropdown()"
        class="relative p-2 text-slate-500 hover:text-slate-700 rounded-full hover:bg-slate-100 transition focus:outline-none">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 01-6 0v-1m6 0H9" />
        </svg>

        <!-- Unread Badge -->
        <span x-show="unreadCount > 0" x-text="unreadCount" x-cloak
            class="absolute top-1 right-1 bg-rose-500 text-white font-extrabold text-[9px] w-4 h-4 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
        </span>
    </button>

    <!-- Dropdown Popover -->
    <div x-show="open" style="display: none !important;" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-1" x-cloak
        class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden">
        
        <!-- Header -->
        <div class="p-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <h4 class="font-extrabold text-xs text-slate-900">Notifikasi</h4>
                <span x-show="unreadCount > 0" x-text="unreadCount + ' baru'"
                    class="bg-rose-100 text-rose-600 text-[10px] font-bold px-2 py-0.5 rounded-full"></span>
            </div>
            <template x-if="unreadCount > 0">
                <button @click="markAllAsRead()"
                    class="text-[11px] font-semibold text-blue-900 hover:underline">
                    Tandai dibaca
                </button>
            </template>
        </div>

        <!-- Notification List -->
        <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
            <template x-if="loading">
                <div class="p-6 text-center text-xs text-slate-400">
                    Memuat notifikasi...
                </div>
            </template>

            <template x-if="!loading && notifications.length === 0">
                <div class="p-8 text-center space-y-2">
                    <div class="w-10 h-10 bg-slate-100 rounded-full flex items-center justify-center mx-auto text-slate-400">
                        🔔
                    </div>
                    <p class="text-xs font-semibold text-slate-600">Tidak ada notifikasi</p>
                </div>
            </template>

            <template x-for="item in notifications" :key="item.id">
                <div @click="markAsRead(item)"
                    :class="!item.is_read ? 'bg-blue-50/40 hover:bg-blue-50/70' : 'bg-white hover:bg-slate-50'"
                    class="p-4 transition cursor-pointer flex items-start gap-3 relative group">
                    
                    <div class="w-2 h-2 rounded-full mt-1.5 shrink-0"
                        :class="!item.is_read ? 'bg-blue-950' : 'bg-transparent'">
                    </div>

                    <div class="flex-1 space-y-1">
                        <div class="flex items-center justify-between">
                            <h5 class="text-xs font-bold text-slate-900" x-text="item.title"></h5>
                            <span class="text-[10px] text-slate-400" x-text="formatTime(item.created_at)"></span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-snug" x-text="item.message"></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="p-3 border-t border-slate-100 bg-slate-50/50 text-center">
            <a href="{{ route('notifications.index') }}" class="text-xs font-bold text-blue-900 hover:underline">
                Lihat Semua Notifikasi &rsaquo;
            </a>
        </div>
    </div>
</div>

<script>
    function notificationDropdown() {
        return {
            open: false,
            loading: false,
            unreadCount: 0,
            notifications: [],

            init() {
                this.fetchNotifications();
            },

            toggleDropdown() {
                this.open = !this.open;
                if (this.open) {
                    this.fetchNotifications();
                }
            },

            async fetchNotifications() {
                try {
                    const res = await fetch("{{ route('notifications.index') }}", {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (res.ok) {
                        const data = await res.json();
                        this.notifications = data.data.data || [];
                        this.unreadCount = data.unread_count || 0;
                    }
                } catch (e) {
                    console.error('Failed to fetch notifications:', e);
                }
            },

            async markAsRead(item) {
                if (item.is_read) return;
                try {
                    const res = await fetch(`/notifications/${item.id}/read`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (res.ok) {
                        item.is_read = true;
                        if (this.unreadCount > 0) this.unreadCount--;
                    }
                } catch (e) {
                    console.error('Failed to mark notification as read:', e);
                }
            },

            async markAllAsRead() {
                try {
                    const res = await fetch("{{ route('notifications.read-all') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });
                    if (res.ok) {
                        this.notifications.forEach(n => n.is_read = true);
                        this.unreadCount = 0;
                    }
                } catch (e) {
                    console.error('Failed to mark all as read:', e);
                }
            },

            formatTime(dateStr) {
                if (!dateStr) return '';
                const d = new Date(dateStr);
                return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
            }
        }
    }
</script>

{{-- resources/views/components/admin/notification-bell.blade.php --}}
<div class="relative" x-data="adminNotificationBell()" @click.outside="open = false">

    <!-- Bell Action Button -->
    <button type="button" 
            @click="open = !open; if(open) fetchFeed(false);"
            class="relative w-10 h-10 rounded-2xl bg-orange-50/60 hover:bg-orange-100/80 text-gray-700 hover:text-red-700 border border-orange-100 flex items-center justify-center transition-all shadow-2xs cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-500/20"
            title="الإشعارات والتنبيهات الفورية">
        
        <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'scale-110 text-red-600': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <!-- Unread Badge & Pulse Dot -->
        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -end-1 flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-gradient-to-r from-red-600 to-rose-500 text-white text-[10px] font-black shadow-md border-2 border-white animate-pulse">
                <span x-text="unreadCount > 99 ? '99+' : unreadCount"></span>
            </span>
        </template>
    </button>

    <!-- Dropdown Panel -->
    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="absolute end-0 mt-3 w-80 sm:w-96 bg-white border border-orange-100 rounded-3xl shadow-2xl z-50 overflow-hidden text-xs">
        
        <!-- Header -->
        <div class="px-5 py-4 bg-canvas-base border-b border-orange-100 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-xl bg-red-100 text-red-700 flex items-center justify-center font-bold text-xs">
                    🔔
                </div>
                <div>
                    <h4 class="font-black text-xs sm:text-sm text-gray-900 leading-tight">التنبيهات الفورية</h4>
                    <span class="text-[10px] text-gray-500" x-text="unreadCount > 0 ? `${unreadCount} إشعار غير مقروء` : 'لا توجد تنبيهات جديدة'"></span>
                </div>
            </div>

            <template x-if="unreadCount > 0">
                <button type="button" 
                        @click="markAllAsRead()"
                        class="text-[11px] font-bold text-red-600 hover:text-red-800 hover:underline cursor-pointer">
                    تحديد الكل كمقروء
                </button>
            </template>
        </div>

        <!-- Notification List -->
        <div class="max-h-80 overflow-y-auto divide-y divide-gray-50 admin-scrollbar">
            
            <!-- Loading skeleton -->
            <template x-if="loading && notifications.length === 0">
                <div class="p-4 space-y-3">
                    <div class="h-10 bg-gray-100 rounded-xl animate-pulse"></div>
                    <div class="h-10 bg-gray-100 rounded-xl animate-pulse"></div>
                    <div class="h-10 bg-gray-100 rounded-xl animate-pulse"></div>
                </div>
            </template>

            <!-- Notifications Loop -->
            <template x-for="item in notifications" :key="item.id">
                <div class="p-3.5 sm:p-4 transition-colors flex items-start justify-between gap-3 cursor-pointer group"
                     :class="item.is_read ? 'bg-white hover:bg-gray-50/60' : 'bg-red-50/40 hover:bg-red-50/70 border-s-4 border-red-500'"
                     @click="markAsRead(item.id, item.link)">
                    
                    <div class="flex items-start gap-3 min-w-0 flex-1">
                        <!-- Type Icon -->
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center text-sm shrink-0 border"
                             :class="{
                                 'bg-red-50 text-red-600 border-red-100': item.type === 'order',
                                 'bg-blue-50 text-blue-600 border-blue-100': item.type === 'message',
                                 'bg-purple-50 text-purple-600 border-purple-100': item.type === 'job',
                                 'bg-amber-50 text-amber-600 border-amber-100': item.type === 'review',
                                 'bg-gray-50 text-gray-600 border-gray-100': !['order','message','job','review'].includes(item.type)
                             }">
                            <span x-text="item.type === 'order' ? '🛒' : (item.type === 'message' ? '💬' : (item.type === 'job' ? '💼' : (item.type === 'review' ? '⭐' : '🔔')))"></span>
                        </div>

                        <!-- Content -->
                        <div class="min-w-0 flex-1 space-y-0.5">
                            <div class="flex items-center justify-between gap-2">
                                <h5 class="font-bold text-xs text-gray-900 truncate" x-text="item.title"></h5>
                                <span class="text-[10px] text-gray-400 shrink-0 font-medium" x-text="item.time_ago"></span>
                            </div>
                            <p class="text-[11px] text-gray-600 leading-snug line-clamp-2" x-text="item.message"></p>
                        </div>
                    </div>

                    <!-- Unread dot -->
                    <template x-if="!item.is_read">
                        <span class="w-2 h-2 rounded-full bg-red-500 shrink-0 mt-1.5"></span>
                    </template>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="!loading && notifications.length === 0">
                <div class="p-8 text-center space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-gray-50 border border-gray-100 text-gray-400 flex items-center justify-center text-xl mx-auto">
                        🔕
                    </div>
                    <p class="text-xs font-bold text-gray-700">لا توجد إشعارات حتى الآن</p>
                    <p class="text-[11px] text-gray-400">ستصلك تنبيهات فورية عند ورود طلبات أو رسائل جديدة.</p>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="p-2.5 bg-canvas-base border-t border-orange-100 text-center">
            <a href="{{ route('admin.notifications.index') }}" 
               @click="open = false"
               class="block py-1.5 text-[11px] font-bold text-red-600 hover:text-red-800 transition-colors">
                عرض كل الإشعارات والتنبيهات ←
            </a>
        </div>
    </div>
</div>

<script>
    (function() {
        const componentDefinition = () => ({
            open: false,
            unreadCount: 0,
            latestId: 0,
            notifications: [],
            loading: false,
            pollingInterval: null,

            init() {
                this.fetchFeed(true);
                this.startPolling();
                window.refreshAdminNotifications = () => this.fetchFeed(false);
            },

            startPolling() {
                if (this.pollingInterval) clearInterval(this.pollingInterval);
                this.pollingInterval = setInterval(() => {
                    this.fetchFeed(false);
                }, 15000);
            },

            fetchFeed(isInitial = false) {
                if (isInitial) this.loading = true;

                fetch('/admin/notifications/feed?last_id=' + this.latestId + '&limit=8', {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.loading = false;
                    if (data.status === 'success') {
                        if (!isInitial && data.has_new && data.new_items && data.new_items.length > 0) {
                            this.playChime();
                            data.new_items.forEach(item => {
                                if (typeof showToast === 'function') {
                                    showToast(item.title + ': ' + item.message, 'success');
                                }
                            });
                        }

                        this.unreadCount = data.unread_count;
                        this.latestId = data.latest_id;
                        this.notifications = data.notifications;
                    }
                })
                .catch(() => {
                    this.loading = false;
                });
            },

            markAsRead(id, link = null) {
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                fetch('/admin/notifications/' + id + '/read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.unreadCount = data.unread_count;
                        const target = this.notifications.find(n => n.id === id);
                        if (target) target.is_read = true;
                        if (link && link !== '#') {
                            window.location.href = link;
                        }
                    }
                });
            },

            markAllAsRead() {
                const token = document.querySelector('meta[name="csrf-token"]')?.content;
                fetch('/admin/notifications/read-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        this.unreadCount = 0;
                        this.notifications.forEach(n => n.is_read = true);
                        if (typeof showToast === 'function') {
                            showToast('تم تحديد جميع الإشعارات كمقروءة', 'success');
                        }
                    }
                });
            },

            playChime() {
                try {
                    const AudioContext = window.AudioContext || window.webkitAudioContext;
                    if (!AudioContext) return;
                    const ctx = new AudioContext();
                    const osc = ctx.createOscillator();
                    const gain = ctx.createGain();

                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(587.33, ctx.currentTime);
                    osc.frequency.setValueAtTime(880, ctx.currentTime + 0.1);

                    gain.gain.setValueAtTime(0.2, ctx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.4);

                    osc.connect(gain);
                    gain.connect(ctx.destination);

                    osc.start();
                    osc.stop(ctx.currentTime + 0.4);
                } catch(e) {}
            }
        });

        if (window.Alpine) {
            window.Alpine.data('adminNotificationBell', componentDefinition);
        } else {
            document.addEventListener('alpine:init', () => {
                Alpine.data('adminNotificationBell', componentDefinition);
            });
        }
    })();
</script>

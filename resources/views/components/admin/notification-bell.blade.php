{{-- resources/views/components/admin/notification-bell.blade.php --}}
<div class="relative" x-data="adminNotificationBell()" @click.outside="open = false">

    <!-- Bell Action Button -->
    <button type="button" 
            @click="open = !open; if(open) fetchFeed(false);"
            class="relative w-10 h-10 rounded-xl bg-linen/50 hover:bg-beige text-espresso hover:text-action border border-sand flex items-center justify-center transition-all shadow-xs cursor-pointer focus:outline-none focus:ring-2 focus:ring-action/20"
            title="الإشعارات والتنبيهات الفورية">
        
        <svg class="w-5 h-5 transition-transform duration-200" :class="{ 'scale-110 text-clay': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>

        <!-- Unread Badge & Pulse Dot -->
        <template x-if="unreadCount > 0">
            <span class="absolute -top-1 -end-1 flex items-center justify-center min-w-[20px] h-5 px-1.5 rounded-full bg-clay text-cream text-[10px] font-bold shadow-md border-2 border-cream animate-pulse">
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
         class="absolute end-0 mt-3 w-80 sm:w-96 bg-cream border border-sand rounded-2xl shadow-xl z-50 overflow-hidden text-xs">
        
        <!-- Header -->
        <div class="px-5 py-4 bg-linen/60 border-b border-sand flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-7 h-7 rounded-lg bg-beige text-espresso flex items-center justify-center font-bold text-xs border border-sand/50">
                    <svg class="w-4 h-4 text-mocha" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                </div>
                <div>
                    <h4 class="font-bold text-xs sm:text-sm text-espresso leading-tight">التنبيهات الفورية</h4>
                    <span class="text-[10px] text-taupe" x-text="unreadCount > 0 ? `${unreadCount} إشعار غير مقروء` : 'لا توجد تنبيهات جديدة'"></span>
                </div>
            </div>

            <template x-if="unreadCount > 0">
                <button type="button" 
                        @click="markAllAsRead()"
                        class="text-[11px] font-bold text-clay hover:text-action hover:underline cursor-pointer">
                    تحديد الكل كمقروء
                </button>
            </template>
        </div>

        <!-- Notification List -->
        <div class="max-h-80 overflow-y-auto divide-y divide-sand/30 admin-scrollbar">
            
            <!-- Loading skeleton -->
            <template x-if="loading && notifications.length === 0">
                <div class="p-4 space-y-3">
                    <div class="h-10 bg-linen/50 rounded-xl animate-pulse"></div>
                    <div class="h-10 bg-linen/50 rounded-xl animate-pulse"></div>
                    <div class="h-10 bg-linen/50 rounded-xl animate-pulse"></div>
                </div>
            </template>

            <!-- Notifications Loop -->
            <template x-for="item in notifications" :key="item.id">
                <div class="p-3.5 sm:p-4 transition-colors flex items-start justify-between gap-3 cursor-pointer group"
                     :class="item.is_read ? 'bg-cream hover:bg-linen/40' : 'bg-beige/40 hover:bg-beige/70 border-s-4 border-clay'"
                     @click="markAsRead(item.id, item.link)">
                    
                    <div class="flex items-start gap-3 min-w-0 flex-1">
                        <!-- Type Icon (Clean SVGs) -->
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center text-sm shrink-0 border border-sand/50 bg-linen text-espresso">
                            <template x-if="item.type === 'order'">
                                <svg class="w-4 h-4 text-clay" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                            </template>
                            <template x-if="item.type === 'message'">
                                <svg class="w-4 h-4 text-mocha" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                            </template>
                            <template x-if="item.type === 'job'">
                                <svg class="w-4 h-4 text-action" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            </template>
                            <template x-if="item.type === 'review'">
                                <svg class="w-4 h-4 text-[#CBA367]" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </template>
                            <template x-if="item.type !== 'order' && item.type !== 'message' && item.type !== 'job' && item.type !== 'review'">
                                <svg class="w-4 h-4 text-mocha" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                            </template>
                        </div>

                        <!-- Content -->
                        <div class="min-w-0 flex-1 space-y-0.5">
                            <div class="flex items-center justify-between gap-2">
                                <h5 class="font-bold text-xs text-espresso truncate" x-text="item.title"></h5>
                                <span class="text-[10px] text-taupe shrink-0 font-medium" x-text="item.time_ago"></span>
                            </div>
                            <p class="text-[11px] text-mocha leading-snug line-clamp-2" x-text="item.message"></p>
                        </div>
                    </div>

                    <!-- Unread dot -->
                    <template x-if="!item.is_read">
                        <span class="w-2 h-2 rounded-full bg-clay shrink-0 mt-1.5"></span>
                    </template>
                </div>
            </template>

            <!-- Empty State -->
            <template x-if="!loading && notifications.length === 0">
                <div class="p-8 text-center space-y-2">
                    <div class="w-12 h-12 rounded-xl bg-linen border border-sand text-taupe flex items-center justify-center text-xl mx-auto">
                        <svg class="w-6 h-6 text-taupe" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9M3 3l18 18"/></svg>
                    </div>
                    <p class="text-xs font-bold text-espresso">لا توجد إشعارات حتى الآن</p>
                    <p class="text-[11px] text-taupe">ستصلك تنبيهات فورية عند ورود طلبات أو رسائل جديدة.</p>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="p-2.5 bg-linen/50 border-t border-sand text-center">
            <a href="{{ route('admin.notifications.index') }}" 
               @click="open = false"
               class="block py-1.5 text-[11px] font-bold text-clay hover:text-action transition-colors">
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

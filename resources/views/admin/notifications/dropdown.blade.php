<div class="relative" id="notificationsContainer">
    <button 
        class="p-3 rounded-xl hover:bg-blue-500/10 dark:hover:bg-green-500/10 transition-all duration-300 ripple relative"
        id="notificationsBtn"
        onclick="toggleNotifications()">
        <i class="fas fa-bell text-blue-600 dark:text-green-400 text-xl"></i>
        <span 
            class="notification-badge absolute -top-1 -left-1 rounded-full w-5 h-5 text-xs flex items-center justify-center shadow-lg bg-red-500 text-white font-bold"
            id="notificationBadge"
            style="display: none;">
            0
        </span>
    </button>

    <!-- Dropdown الإشعارات -->
    <div 
        class="notifications-dropdown hidden absolute left-0 top-full mt-2 w-96 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 z-50"
        id="notificationsDropdown">
        <!-- Header -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
            <h3 class="font-bold text-gray-900 dark:text-gray-100">الإشعارات</h3>
            <div class="flex items-center gap-2">
                <button 
                    onclick="markAllAsRead()"
                    class="text-sm text-blue-600 dark:text-blue-400 hover:underline"
                    id="markAllReadBtn">
                    تحديد الكل كمقروء
                </button>
                <button 
                    onclick="closeNotifications()"
                    class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <!-- قائمة الإشعارات -->
        <div class="max-h-96 overflow-y-auto" id="notificationsList">
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                <p>جاري التحميل...</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-3 border-t border-gray-200 dark:border-gray-700 text-center">
            <a href="#" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                عرض جميع الإشعارات
            </a>
        </div>
    </div>
</div>

<style>
.notifications-dropdown {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.notification-item {
    transition: background-color 0.2s;
}

.notification-item:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.dark .notification-item:hover {
    background-color: rgba(255, 255, 255, 0.05);
}

.notification-item.unread {
    background-color: rgba(59, 130, 246, 0.05);
    border-right: 3px solid #3b82f6;
}

.dark .notification-item.unread {
    background-color: rgba(59, 130, 246, 0.1);
}
</style>

<script>
let notificationsOpen = false;

function toggleNotifications() {
    const dropdown = document.getElementById('notificationsDropdown');
    notificationsOpen = !notificationsOpen;
    
    if (notificationsOpen) {
        dropdown.classList.remove('hidden');
        loadNotifications();
    } else {
        dropdown.classList.add('hidden');
    }
}

function closeNotifications() {
    notificationsOpen = false;
    document.getElementById('notificationsDropdown').classList.add('hidden');
}

// إغلاق عند النقر خارج القائمة
document.addEventListener('click', function(event) {
    const container = document.getElementById('notificationsContainer');
    if (!container.contains(event.target)) {
        closeNotifications();
    }
});

function loadNotifications() {
    fetch('{{ route("admin.notifications.index") }}', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        },
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            updateNotificationsList(data.notifications);
            updateBadge(data.unread_count);
        })
        .catch(error => {
            console.error('Error loading notifications:', error);
            document.getElementById('notificationsList').innerHTML = `
                <div class="p-8 text-center text-red-500">
                    <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                    <p>حدث خطأ في تحميل الإشعارات</p>
                </div>
            `;
        });
}

function updateNotificationsList(notifications) {
    const list = document.getElementById('notificationsList');
    
    if (notifications.length === 0) {
        list.innerHTML = `
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-bell-slash text-3xl mb-2"></i>
                <p>لا توجد إشعارات</p>
            </div>
        `;
        return;
    }

    let html = '<div class="divide-y divide-gray-200 dark:divide-gray-700">';
    
        notifications.forEach(notification => {
            const colorClasses = {
                'info': 'bg-blue-100 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400',
                'success': 'bg-green-100 dark:bg-green-900/20 text-green-600 dark:text-green-400',
                'warning': 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-600 dark:text-yellow-400',
                'danger': 'bg-red-100 dark:bg-red-900/20 text-red-600 dark:text-red-400',
            };
            
            const colorClass = colorClasses[notification.color] || colorClasses.info;
            const unreadClass = !notification.is_read ? 'unread' : '';
            const timeAgo = getTimeAgo(notification.created_at);
            const link = notification.link ? notification.link.replace(/'/g, "\\'") : '#';
            
            html += `
                <div class="notification-item ${unreadClass} p-4 cursor-pointer" 
                     onclick="handleNotificationClick(${notification.id}, '${link}')">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full ${colorClass} flex items-center justify-center">
                            <i class="${notification.icon || 'fas fa-bell'}"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm mb-1">
                                ${escapeHtml(notification.title)}
                            </p>
                            <p class="text-gray-600 dark:text-gray-400 text-xs mb-2">
                                ${escapeHtml(notification.message)}
                            </p>
                            <p class="text-gray-400 dark:text-gray-500 text-xs">
                                ${timeAgo}
                            </p>
                        </div>
                        ${!notification.is_read ? '<div class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-2"></div>' : ''}
                    </div>
                </div>
            `;
        });
    
    html += '</div>';
    list.innerHTML = html;
}

function updateBadge(count) {
    const badge = document.getElementById('notificationBadge');
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = 'flex';
    } else {
        badge.style.display = 'none';
    }
}

function handleNotificationClick(id, link) {
    // تحديد الإشعار كمقروء
    fetch(`{{ url('/admin/notifications') }}/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(() => {
        // تحديث القائمة
        loadNotifications();
    });

    // الانتقال إلى الرابط
    if (link && link !== '#' && link !== 'null') {
        window.location.href = link;
    }
    
    // إغلاق القائمة
    closeNotifications();
}

function markAllAsRead() {
    fetch('{{ route("admin.notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(response => response.json())
    .then(() => {
        loadNotifications();
    })
    .catch(error => {
        console.error('Error marking all as read:', error);
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function getTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diff = now - date;
    const minutes = Math.floor(diff / 60000);
    const hours = Math.floor(diff / 3600000);
    const days = Math.floor(diff / 86400000);

    if (minutes < 1) return 'الآن';
    if (minutes < 60) return `منذ ${minutes} دقيقة`;
    if (hours < 24) return `منذ ${hours} ساعة`;
    if (days < 7) return `منذ ${days} يوم`;
    return date.toLocaleDateString('ar-SA');
}

// تحديث الإشعارات كل 30 ثانية
setInterval(() => {
    loadNotifications();
}, 30000);

// تحميل الإشعارات عند تحميل الصفحة
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
    });
} else {
    loadNotifications();
}
</script>


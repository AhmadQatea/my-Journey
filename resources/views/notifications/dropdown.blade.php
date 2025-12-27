<div class="notification-dropdown" id="notificationsContainer">
    <button class="notification-btn" id="notificationBtn" onclick="toggleUserNotifications()">
        <i class="fas fa-bell"></i>
        <span class="notification-badge" id="notificationBadge" style="display: none;">0</span>
    </button>
    <div class="notification-dropdown-content" id="notificationsDropdown" style="display: none;">
        <div class="notification-header">
            <h4>الإشعارات</h4>
            <div class="flex items-center gap-2">
                <button class="mark-all-read" onclick="markAllAsRead()">تحديد الكل كمقروء</button>
                <button onclick="closeUserNotifications()" style="background: none; border: none; cursor: pointer; padding: 5px;">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="notification-list" id="notificationsList">
            <div class="p-8 text-center text-gray-500">
                <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
                <p>جاري التحميل...</p>
            </div>
        </div>
        <div class="notification-footer">
            <a href="#" class="view-all">عرض كل الإشعارات</a>
        </div>
    </div>
</div>

<script>
let userNotificationsOpen = false;

function toggleUserNotifications() {
    const dropdown = document.getElementById('notificationsDropdown');
    userNotificationsOpen = !userNotificationsOpen;
    
    if (userNotificationsOpen) {
        dropdown.style.display = 'block';
        loadUserNotifications();
    } else {
        dropdown.style.display = 'none';
    }
}

function closeUserNotifications() {
    userNotificationsOpen = false;
    document.getElementById('notificationsDropdown').style.display = 'none';
}

// إغلاق عند النقر خارج القائمة
document.addEventListener('click', function(event) {
    const container = document.getElementById('notificationsContainer');
    if (!container.contains(event.target)) {
        closeUserNotifications();
    }
});

function loadUserNotifications() {
    fetch('{{ route("notifications.index") }}', {
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
            updateUserNotificationsList(data.notifications);
            updateUserBadge(data.unread_count);
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

function updateUserNotificationsList(notifications) {
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

    let html = '';
    
    notifications.forEach(notification => {
        const colorClasses = {
            'info': 'text-info',
            'success': 'text-success',
            'warning': 'text-warning',
            'danger': 'text-danger',
        };
        
        const colorClass = colorClasses[notification.color] || colorClasses.info;
        const unreadClass = !notification.is_read ? 'unread' : '';
        const timeAgo = getTimeAgo(notification.created_at);
        const link = notification.link ? notification.link.replace(/'/g, "\\'") : '#';
        
        html += `
            <a href="#" class="notification-item ${unreadClass}" 
               onclick="handleUserNotificationClick(${notification.id}, '${link}'); return false;">
                <div class="notification-icon">
                    <i class="${notification.icon || 'fas fa-bell'} ${colorClass}"></i>
                </div>
                <div class="notification-content">
                    <p class="notification-text">${escapeHtml(notification.title)}</p>
                    <p class="notification-message">${escapeHtml(notification.message)}</p>
                    <span class="notification-time">${timeAgo}</span>
                </div>
            </a>
        `;
    });
    
    list.innerHTML = html;
}

function updateUserBadge(count) {
    const badge = document.getElementById('notificationBadge');
    if (count > 0) {
        badge.textContent = count > 99 ? '99+' : count;
        badge.style.display = 'inline-block';
    } else {
        badge.style.display = 'none';
    }
}

function handleUserNotificationClick(id, link) {
    // تحديد الإشعار كمقروء
    fetch(`{{ url('/notifications') }}/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(() => {
        loadUserNotifications();
    });

    // الانتقال إلى الرابط
    if (link && link !== '#' && link !== 'null') {
        window.location.href = link;
    }
    
    // إغلاق القائمة
    closeUserNotifications();
}

function markAllAsRead() {
    fetch('{{ route("notifications.mark-all-read") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
    .then(response => response.json())
    .then(() => {
        loadUserNotifications();
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
    loadUserNotifications();
}, 30000);

// تحميل الإشعارات عند تحميل الصفحة
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        loadUserNotifications();
    });
} else {
    loadUserNotifications();
}
</script>


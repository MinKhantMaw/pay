import './bootstrap';

document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('realtime-notifications');

    if (!root || !window.Echo || !root.dataset.channelId) {
        return;
    }

    const badge = document.getElementById('notificationUnreadCount') || document.querySelector('.unread_noti_count');
    const list = document.getElementById('notificationDropdownList');
    const markUrlTemplate = root.dataset.markUrlTemplate;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

    const setUnreadCount = (count) => {
        if (!badge) {
            return;
        }

        badge.textContent = count;
        badge.style.display = count > 0 ? '' : 'none';
    };

    const currentUnreadCount = () => parseInt(badge?.textContent || '0', 10) || 0;

    const escapeHtml = (value) => String(value ?? '').replace(/[&<>"']/g, (character) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;',
    }[character]));

    const notificationItem = (notification) => {
        const item = document.createElement('a');
        item.href = notification.url || '#';
        item.className = 'list-group-item list-group-item-action notification-item font-weight-bold';
        item.dataset.notificationId = notification.id;
        item.innerHTML = `
            <div class="small text-muted">${escapeHtml(notification.created_at || '')}</div>
            <div>${escapeHtml(notification.title || 'Notification')}</div>
            <div class="small text-muted">${escapeHtml(notification.message || '')}</div>
        `;

        return item;
    };

    const prependNotification = (notification) => {
        if (list) {
            document.getElementById('notificationEmptyState')?.remove();
            list.prepend(notificationItem(notification));
        }

        setUnreadCount(currentUnreadCount() + 1);
    };

    window.Echo
        .private(`private-user.${root.dataset.channelId}`)
        .listen('.NotificationBroadcastEvent', (event) => {
            prependNotification(event.notification);
        });

    list?.addEventListener('click', async (event) => {
        const item = event.target.closest('[data-notification-id]');

        if (!item) {
            return;
        }

        event.preventDefault();

        const notificationId = item.dataset.notificationId;
        const markUrl = markUrlTemplate.replace('__ID__', notificationId);

        try {
            const response = await fetch(markUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });
            const data = await response.json();

            item.classList.remove('font-weight-bold');
            setUnreadCount(data.unread_count ?? Math.max(currentUnreadCount() - 1, 0));

            if (data.redirect_url) {
                window.location.href = data.redirect_url;
            }
        } catch (error) {
            window.location.href = item.href;
        }
    });
});

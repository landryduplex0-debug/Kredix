/**
 * KREDIX — JavaScript Principal
 */

// Sidebar toggle (mobile)
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    sidebar.classList.toggle('show');
    overlay.classList.toggle('show');
}

// Close sidebar on overlay click (already set in HTML onclick)

// Close sidebar on navigation (mobile)
document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 991) {
            toggleSidebar();
        }
    });
});

// Auto-dismiss alerts after 5 seconds
document.querySelectorAll('.alert-k').forEach(alert => {
    setTimeout(() => {
        alert.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
        alert.style.opacity = '0';
        alert.style.transform = 'translateY(-10px)';
        setTimeout(() => alert.remove(), 300);
    }, 5000);
});

// Format number inputs as currency on blur
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('focus', function() {
        this.select();
    });
});

// Confirm before dangerous actions
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function(e) {
        if (!confirm(this.dataset.confirm)) {
            e.preventDefault();
        }
    });
});

// Service Worker registration for offline support (PWA-ready)
if ('serviceWorker' in navigator) {
    // Will be registered when SW file is created
    // navigator.serviceWorker.register('/saas/sw.js');
}

// Prevent double form submissions
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function() {
        const btn = this.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.7';
            setTimeout(() => {
                btn.disabled = false;
                btn.style.opacity = '1';
            }, 3000);
        }
    });
});

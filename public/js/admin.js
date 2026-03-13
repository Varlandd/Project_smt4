// ============================================
//  RumahKu — Admin Panel JS
//  public/js/admin.js
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    // ── Sidebar toggle (mobile) ──
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('adminSidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });

        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 968 && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }


    // ── Dark / Light theme toggle ──
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;
    const STORAGE_KEY = 'rumahku-admin-theme';

    // Load saved theme or default to light
    const savedTheme = localStorage.getItem(STORAGE_KEY) || 'light';
    if (savedTheme === 'dark') {
        body.classList.add('dark-mode');
    }

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            body.classList.toggle('dark-mode');
            const isDark = body.classList.contains('dark-mode');
            localStorage.setItem(STORAGE_KEY, isDark ? 'dark' : 'light');
        });
    }

});

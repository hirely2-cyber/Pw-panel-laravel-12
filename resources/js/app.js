import './bootstrap';
import Alpine from 'alpinejs';
import flatpickr from 'flatpickr';

window.Alpine = Alpine;

Alpine.start();

// Auto-init Flatpickr on all .pw-datepicker elements
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.pw-datepicker').forEach(el => {
        flatpickr(el, {
            enableTime: el.dataset.enableTime !== undefined ? el.dataset.enableTime !== 'false' : true,
            dateFormat: el.dataset.format || 'Y-m-d H:i',
            time_24hr: true,
            allowInput: true,
            disableMobile: true,
        });
    });
});

// ── Admin Sidebar Toggle (mobile) ──
window.toggleSidebar = function() {
    const sidebar = document.getElementById('adminSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    if (!sidebar || !overlay) return;
    sidebar.classList.toggle('is-open');
    overlay.classList.toggle('is-active');
    document.body.classList.toggle('sidebar-open');
};

// ── Theme Toggle (light / dark) ──
window.pwToggleTheme = function() {
    const html = document.documentElement;
    const current = html.getAttribute('data-theme') || 'light';
    const next = current === 'light' ? 'dark' : 'light';
    html.setAttribute('data-theme', next);
    localStorage.setItem('pw-theme', next);
};

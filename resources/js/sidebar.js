/**
 * Sidebar mobile / desktop toggle
 *
 * Sélecteurs data-attribute:
 *   - [data-sidebar]         : l'élément <aside> du sidebar
 *   - [data-sidebar-overlay] : le fond noir qui se ferme au clic
 *   - [data-sidebar-toggle]  : le bouton qui ouvre
 *   - [data-sidebar-close]   : le bouton X à l'intérieur
 */
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.querySelector('[data-sidebar]');
    const overlay = document.querySelector('[data-sidebar-overlay]');
    const toggle = document.querySelector('[data-sidebar-toggle]');
    const close = document.querySelector('[data-sidebar-close]');

    if (!sidebar || !overlay || !toggle) {
        return;
    }

    const setSidebarOpen = (isOpen) => {
        sidebar.classList.toggle('-translate-x-full', !isOpen);
        sidebar.classList.toggle('translate-x-0', isOpen);
        overlay.classList.toggle('hidden', !isOpen);
        document.body.classList.toggle('overflow-hidden', isOpen);
        toggle.setAttribute('aria-expanded', String(isOpen));
    };

    toggle.addEventListener('click', () => setSidebarOpen(true));
    close?.addEventListener('click', () => setSidebarOpen(false));
    overlay.addEventListener('click', () => setSidebarOpen(false));

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setSidebarOpen(false);
        }
    });

    sidebar.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setSidebarOpen(false));
    });
});

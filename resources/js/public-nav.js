document.addEventListener('DOMContentLoaded', () => {
    const toggle = document.querySelector('[data-public-nav-toggle]');
    const navigation = document.querySelector('[data-public-nav]');

    if (!toggle || !navigation) {
        return;
    }

    const setNavigationOpen = (isOpen) => {
        navigation.classList.toggle('hidden', !isOpen);
        toggle.setAttribute('aria-expanded', String(isOpen));
        toggle.setAttribute('aria-label', isOpen ? 'Fermer le menu' : 'Ouvrir le menu');
    };

    toggle.addEventListener('click', () => {
        setNavigationOpen(toggle.getAttribute('aria-expanded') !== 'true');
    });

    navigation.querySelectorAll('a').forEach((link) => {
        link.addEventListener('click', () => setNavigationOpen(false));
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            setNavigationOpen(false);
        }
    });

    window.addEventListener('resize', () => {
        if (window.matchMedia('(min-width: 768px)').matches) {
            setNavigationOpen(true);
        } else if (toggle.getAttribute('aria-expanded') === 'true') {
            setNavigationOpen(false);
        }
    });
});
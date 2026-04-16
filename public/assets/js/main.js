// ===== AUTO-DISMISS FLASH =====
setTimeout(() => {
    const flash = document.getElementById('flash');
    if (flash) flash.style.animation = 'slideOut .3s ease forwards';
    setTimeout(() => flash?.remove(), 300);
}, 4000);

// ===== NAVBAR SCROLL =====
window.addEventListener('scroll', () => {
    const nav = document.querySelector('.nav');
    if (nav) nav.classList.toggle('scrolled', window.scrollY > 20);
});

// ===== AJAX ADD TO CART =====
document.querySelectorAll('.js-add-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        const btn = this.querySelector('.card__btn-cart');
        if (!btn) return;
        e.preventDefault();
        btn.textContent = '✓';
        btn.style.background = '#1B5E20';

        try {
            const res = await fetch(this.action, {
                method: 'POST',
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                body: new FormData(this)
            });
            const data = await res.json();
            if (data.success) {
                const count = data.total ?? data.count ?? 0;
                const badge = document.querySelector('.nav__badge');
                if (badge) {
                    badge.textContent = count;
                    badge.style.animation = 'none';
                    badge.offsetHeight;
                    badge.style.animation = 'pulse .3s ease';
                } else {
                    const cartLink = document.querySelector('.nav__cart');
                    if (cartLink) {
                        const b = document.createElement('span');
                        b.className = 'nav__badge';
                        b.textContent = count;
                        cartLink.appendChild(b);
                    }
                }
            }
        } catch {
            this.submit();
        }

        setTimeout(() => {
            btn.textContent = '🛒';
            btn.style.background = '';
        }, 1200);
    });
});

// ===== CONFIRM DELETE =====
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
        if (!confirm(el.dataset.confirm)) e.preventDefault();
    });
});

// ===== FRAIS LIVRAISON DYNAMIC =====
const zones = {
    'Dakar': 1500, 'Pikine': 1500, 'Guediawaye': 2000, 'Thiès': 3000,
    'Saint-Louis': 5000, 'Ziguinchor': 7000, 'Kaolack': 4000,
    'Mbour': 3500, 'Touba': 4500, 'Diourbel': 4000
};
document.querySelectorAll('input[name="zone"]').forEach(radio => {
    radio.addEventListener('change', () => {
        const frais = zones[radio.value] || 2000;
        const el = document.getElementById('frais-display');
        if (el) el.textContent = frais.toLocaleString('fr-FR') + ' FCFA';
    });
});

// ===== SMOOTH SCROLL =====
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        const t = document.querySelector(a.getAttribute('href'));
        if (t) { e.preventDefault(); t.scrollIntoView({ behavior: 'smooth' }); }
    });
});

// ===== TABLE ROW CLICK =====
document.querySelectorAll('.data-table tr[data-href]').forEach(row => {
    row.style.cursor = 'pointer';
    row.addEventListener('click', () => window.location = row.dataset.href);
});

// ===== SLIDE OUT ANIMATION =====
const style = document.createElement('style');
style.textContent = '@keyframes slideOut { to { transform: translateX(110%); opacity: 0; } } @keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.4)} }';
document.head.appendChild(style);

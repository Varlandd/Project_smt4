// ============================================
//  RumahKu — rumah.js
//  public/js/rumah.js
// ============================================

document.addEventListener('DOMContentLoaded', function () {

    // ── Scroll reveal animation ──
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(el => {
            if (el.isIntersecting) el.target.classList.add('visible');
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-up').forEach(el => observer.observe(el));


    // ── Mobile hamburger menu ──
    const hamburger = document.getElementById('hamburgerBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            mobileMenu.classList.toggle('open');
        });

        mobileMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => mobileMenu.classList.remove('open'));
        });
    }


    // ── Navbar shadow on scroll ──
    const navbar = document.querySelector('.navbar');
    window.addEventListener('scroll', () => {
        if (window.scrollY > 20) {
            navbar.style.boxShadow = '0 4px 20px rgba(0,0,0,.08)';
        } else {
            navbar.style.boxShadow = 'none';
        }
    });


    // ── Close mobile menu on scroll ──
    window.addEventListener('scroll', () => {
        if (mobileMenu) mobileMenu.classList.remove('open');
    });

});

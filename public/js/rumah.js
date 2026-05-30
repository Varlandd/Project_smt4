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


    // ── User dropdown toggle ──
    const userDropdownBtn = document.getElementById('userDropdownBtn');
    const navUser = document.getElementById('navUser');

    if (userDropdownBtn && navUser) {
        userDropdownBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            navUser.classList.toggle('open');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!navUser.contains(e.target)) {
                navUser.classList.remove('open');
            }
        });
    }


    // ── Budget Calculator ──
    const hitungBtn = document.getElementById('hitungBtn');
    if (hitungBtn) {
        hitungBtn.addEventListener('click', () => {
            const penghasilan = parseFloat(document.getElementById('penghasilan')?.value) || 0;
            const uangMuka    = parseFloat(document.getElementById('uang_muka')?.value) || 0;
            const cicilanLain = parseFloat(document.getElementById('cicilan_lain')?.value) || 0;
            const tenor       = parseInt(document.getElementById('tenor')?.value) || 15;

            if (penghasilan <= 0) {
                alert('Masukkan penghasilan terlebih dahulu!');
                return;
            }

            // Max cicilan = 30% penghasilan - cicilan lain
            const maxCicilan = (penghasilan * 0.30) - cicilanLain;

            if (maxCicilan <= 0) {
                alert('Cicilan bulanan lain melebihi 30% penghasilan. Kurangi cicilan terlebih dahulu.');
                return;
            }

            // Bunga KPR ~8.5% per tahun (fixed rate simulation)
            const bungaTahunan = 8.5 / 100;
            const bungaBulanan = bungaTahunan / 12;
            const totalBulan   = tenor * 12;

            // Hitung maksimum pinjaman dengan rumus anuitas
            const maxPinjaman = maxCicilan * ((Math.pow(1 + bungaBulanan, totalBulan) - 1) / (bungaBulanan * Math.pow(1 + bungaBulanan, totalBulan)));

            const budgetRumah = maxPinjaman + uangMuka;
            const sisaPendapatan = penghasilan - maxCicilan - cicilanLain;

            // Format Rupiah
            const formatRp = (num) => 'Rp ' + Math.round(num).toLocaleString('id-ID');

            document.getElementById('resultBudget').textContent  = formatRp(budgetRumah);
            document.getElementById('resultCicilan').textContent  = formatRp(maxCicilan);
            document.getElementById('resultSisa').textContent     = formatRp(sisaPendapatan);

            const resultEl = document.getElementById('calculatorResult');
            if (resultEl) {
                resultEl.style.display = 'block';
                resultEl.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        });
    }

});
function togglePassword(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const isHidden = input.type === 'password';

    input.type = isHidden ? 'text' : 'password';
    btn.classList.toggle('active', isHidden);

    btn.innerHTML = isHidden
        ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
               <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/>
               <path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
               <line x1="1" y1="1" x2="23" y2="23"/>
           </svg>`
        : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
               <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
               <circle cx="12" cy="12" r="3"/>
           </svg>`;
}
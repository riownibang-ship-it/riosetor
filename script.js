/* ═══════════════════════════════════════════════════════════════
   NOKOS DASHBOARD - INTERACTIVE LOGIC
   Navigation, animations, toast notifications, and interactions
   ═══════════════════════════════════════════════════════════════ */

document.addEventListener('DOMContentLoaded', () => {

  // ─── ELEMENTS ───
  const pages = {
    home: document.getElementById('home-page'),
    history: document.getElementById('history-page'),
    deposit: document.getElementById('deposit-page'),
    aktivitas: document.getElementById('aktivitas-page'),
    profil: document.getElementById('profil-page')
  };

  const navButtons = document.querySelectorAll('.nav-item');
  const depositSpecialBtn = document.getElementById('depositNavBtn');
  const toastEl = document.getElementById('toast');

  // ─── TOAST NOTIFICATION ───
  let toastTimeout = null;

  function showToast(message, duration = 2500) {
    if (toastTimeout) clearTimeout(toastTimeout);
    toastEl.textContent = message;
    toastEl.classList.add('show');
    toastTimeout = setTimeout(() => {
      toastEl.classList.remove('show');
    }, duration);
  }

  // ─── PAGE NAVIGATION ───
  function setActivePage(pageId) {
    // Deactivate all pages
    Object.values(pages).forEach(page => {
      if (page) {
        page.classList.remove('active-page');
        page.style.display = 'none';
      }
    });

    // Activate target page with animation
    if (pages[pageId]) {
      pages[pageId].style.display = 'block';
      // Force reflow for animation restart
      void pages[pageId].offsetWidth;
      pages[pageId].classList.add('active-page');
    }

    // Update nav button states
    navButtons.forEach(btn => {
      const btnPage = btn.getAttribute('data-page');
      if (btnPage === pageId) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });

    // Deposit button state
    if (pageId === 'deposit') {
      depositSpecialBtn.classList.add('active-deposit');
    } else {
      depositSpecialBtn.classList.remove('active-deposit');
    }

    // Scroll content to top
    document.querySelector('.content').scrollTop = 0;
  }

  // ─── NAV BUTTON EVENTS ───
  navButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const page = btn.getAttribute('data-page');
      if (page) {
        // Add haptic-like feedback
        btn.style.transform = 'scale(0.9)';
        setTimeout(() => { btn.style.transform = ''; }, 150);
        setActivePage(page);
      }
    });
  });

  // Deposit special button
  depositSpecialBtn.addEventListener('click', () => {
    depositSpecialBtn.style.transform = 'scale(0.88)';
    setTimeout(() => { depositSpecialBtn.style.transform = ''; }, 200);
    setActivePage('deposit');
  });

  // ─── ACTION CARD EVENTS ───
  document.getElementById('beliNokosBtn')?.addEventListener('click', () => {
    showToast('✨ Fitur BELI NOKOS segera hadir!');
  });

  document.getElementById('suntikSosmedBtn')?.addEventListener('click', () => {
    showToast('📈 Layanan Suntik Sosmed siap digunakan!');
  });

  // ─── PROVIDER ITEM EVENTS ───
  const providerItems = document.querySelectorAll('.provider-item');
  providerItems.forEach(prov => {
    prov.addEventListener('click', () => {
      const nama = prov.getAttribute('data-provider') || 'Dompet';
      showToast(`💰 Topup via ${nama} akan diproses cepat`);
    });
  });

  // ─── SALDO CARD INTERACTION ───
  const saldoCard = document.getElementById('saldoCard');
  let saldoVisible = true;

  saldoCard?.addEventListener('click', () => {
    const nominal = saldoCard.querySelector('.saldo-nominal');
    if (saldoVisible) {
      nominal.textContent = '••••••••';
      nominal.style.letterSpacing = '2px';
    } else {
      nominal.textContent = 'Rp 2.450.000';
      nominal.style.letterSpacing = '-0.2px';
    }
    saldoVisible = !saldoVisible;
    showToast(saldoVisible ? '👁️ Saldo ditampilkan' : '🔒 Saldo disembunyikan', 1500);
  });

  // ─── BANK ITEM INTERACTIONS ───
  document.querySelectorAll('.bank-item').forEach(item => {
    item.addEventListener('click', () => {
      const bankName = item.querySelector('strong')?.textContent || 'Bank';
      showToast(`🏦 Membuka ${bankName}...`);
    });
  });

  // ─── DISABLE PINCH ZOOM ───
  document.addEventListener('touchmove', (e) => {
    if (e.touches.length > 1) {
      e.preventDefault();
    }
  }, { passive: false });

  window.addEventListener('gesturestart', (e) => e.preventDefault());

  // ─── INITIAL STATE ───
  setActivePage('home');

});

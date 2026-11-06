export function initHeroSlider() {
  const slider = document.getElementById('fSlider');
  const track = document.getElementById('fTrack');
  if (!slider || !track) return;

  let isDown = false;
  let startX = 0;
  let scrollLeft = 0;
  let isHovered = false;
  const speed = 0.85;

  function step() {
    if (!isDown && !isHovered) {
      slider.scrollLeft += speed;
      const half = track.scrollWidth / 2;
      if (slider.scrollLeft >= half) {
        slider.scrollLeft -= half;
      }
    }
    requestAnimationFrame(step);
  }
  requestAnimationFrame(step);

  slider.addEventListener('mouseenter', () => isHovered = true);
  slider.addEventListener('mouseleave', () => {
    isHovered = false;
    isDown = false;
  });

  // Mouse Drag
  slider.addEventListener('mousedown', (e) => {
    isDown = true;
    startX = e.pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
  });
  window.addEventListener('mouseup', () => {
    if (isDown) isDown = false;
  });
  slider.addEventListener('mousemove', (e) => {
    if (!isDown) return;
    e.preventDefault();
    const x = e.pageX - slider.offsetLeft;
    const walk = (x - startX) * 1.6;
    slider.scrollLeft = scrollLeft - walk;
    const half = track.scrollWidth / 2;
    if (slider.scrollLeft >= half) slider.scrollLeft -= half;
    if (slider.scrollLeft < 0) slider.scrollLeft += half;
  });

  // Touch Swipe
  slider.addEventListener('touchstart', (e) => {
    isDown = true;
    startX = e.touches[0].pageX - slider.offsetLeft;
    scrollLeft = slider.scrollLeft;
  }, { passive: true });

  slider.addEventListener('touchmove', (e) => {
    if (!isDown) return;
    const x = e.touches[0].pageX - slider.offsetLeft;
    const walk = (x - startX) * 1.6;
    slider.scrollLeft = scrollLeft - walk;
    const half = track.scrollWidth / 2;
    if (slider.scrollLeft >= half) slider.scrollLeft -= half;
    if (slider.scrollLeft < 0) slider.scrollLeft += half;
  }, { passive: true });

  slider.addEventListener('touchend', () => {
    isDown = false;
  });
}

export function initTypewriter() {
  const box = document.getElementById('code');
  if (!box) return;

  const paras = [
    { t: "“Saya percaya pendidikan adalah segalanya. Allah ciptakan setiap manusia dengan bakatnya — tugas kita mengarahkannya agar bermanfaat dan tidak bertentangan dengan agama." },
    { t: "Bayangkan seorang pengusaha yang membuka lapangan kerja. Berapa banyak keluarga yang ia hidupi. Berapa banyak pahala yang ia kumpulkan hanya dari satu usaha yang ia tekuni dengan niat karena Allah." },
    { t: "Dari situlah Matla berdiri — untuk mewujudkan generasi yang ilmunya bermanfaat, dunia dan akhirat.”", cls: "gold" }
  ];

  let started = false;
  let skipped = false;
  let rows = [];

  function build() {
    box.innerHTML = '';
    rows = paras.map((p, i) => {
      const r = document.createElement('div');
      r.className = 'ln';
      r.innerHTML = `<span class="n">${i + 1}</span><span class="tx ${p.cls || ''}"></span>`;
      box.appendChild(r);
      return r.querySelector('.tx');
    });
  }

  const caret = document.createElement('span');
  caret.className = 'caret';

  function full() {
    skipped = true;
    build();
    rows.forEach((el, i) => {
      el.textContent = paras[i].t;
    });
    if (rows.length > 0) {
      rows[rows.length - 1].appendChild(caret);
    }
  }

  function run() {
    if (started) return;
    started = true;
    build();
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      full();
      return;
    }
    let p = 0, c = 0;
    function tick() {
      if (skipped) return;
      if (p >= paras.length) return;
      rows[p].textContent = paras[p].t.slice(0, ++c);
      rows[p].appendChild(caret);
      if (c >= paras[p].t.length) {
        p++;
        c = 0;
        setTimeout(tick, 450);
      } else {
        setTimeout(tick, 18);
      }
    }
    tick();
  }

  build();

  const skipBtn = document.getElementById('skip');
  if (skipBtn) {
    skipBtn.addEventListener('click', () => {
      started = true;
      full();
    });
  }

  if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries, obs) => {
      if (entries[0].isIntersecting) {
        run();
        obs.disconnect();
      }
    }, { threshold: 0.3 });
    observer.observe(box);
  } else {
    run();
  }
}



(function () {
  'use strict';

  const $ = (sel, ctx) => (ctx || document).querySelector(sel);
  const $$ = (sel, ctx) => [...(ctx || document).querySelectorAll(sel)];

  const COLORS = [
    '#2563eb', '#059669', '#d97706', '#dc2626', '#7c3aed',
    '#0891b2', '#db2777', '#65a30d', '#0d9488', '#ca8a04',
    '#4f46e5', '#c2410c', '#16a34a', '#6366f1', '#b91c1c',
  ];

  // Render homepage
  function renderHomepage() {
    const main = $('#main-content');
    if (!main) return;

    const cats = Object.entries(CATEGORIES).sort(
      (a, b) => a[1].order - b[1].order
    );

    let html = `<section class="hero">
      <h1>Phần mềm miễn phí</h1>
      <p>Tổng hợp những phần mềm miễn phí tốt nhất cho văn phòng, học sinh, sinh viên và người dùng phổ thông.</p>
    </section>`;

    let colorIdx = 0;

    for (const [catId, cat] of cats) {
      const items = SOFTWARE.filter((s) => s.cat === catId);
      if (items.length === 0) continue;

      html += `<section class="category-section" id="${catId}">
        <div class="category-header">
          <div class="category-icon" style="background: ${COLORS[colorIdx % COLORS.length]}">${cat.name.charAt(0)}</div>
          <h2>${cat.name}</h2>
        </div>
        <div class="software-grid">`;

      for (const sw of items) {
        colorIdx++;
        html += `<a href="p/${sw.id}.html" class="software-card">
          <img class="software-icon" src="images/icons/${sw.id}.webp" alt="${sw.name}" width="48" height="48" loading="lazy">
          <div class="software-info">
            <h3>${sw.name}</h3>
            <p>${sw.desc}</p>
          </div>
          <span class="arrow">→</span>
        </a>`;
      }

      html += `</div></section>`;
    }

    main.innerHTML = html;
  }

  // Render detail page
  function renderDetail() {
    const app = $('#app');
    if (!app || typeof PAGE_ID === 'undefined') return;

    const sw = SOFTWARE.find((s) => s.id === PAGE_ID);
    if (!sw) {
      app.innerHTML = '<p>Không tìm thấy phần mềm.</p>';
      return;
    }

    const cat = CATEGORIES[sw.cat];

    // Update page title & meta
    document.title = `${sw.name} - Phần mềm miễn phí`;
    const metaDesc = document.querySelector('meta[name="description"]');
    if (metaDesc) metaDesc.content = sw.desc;

    const paragraphs = sw.intro
      .split('\n\n')
      .filter(Boolean)
      .map((p) => `<p>${p.replace(/\n/g, '<br>')}</p>`)
      .join('');

    app.innerHTML = `
      <div class="detail-header">
        <div class="breadcrumb">
          <a href="../index.html">Trang chủ</a>
          <span class="separator">/</span>
          <a href="../index.html#${sw.cat}">${cat.name}</a>
          <span class="separator">/</span>
          <span>${sw.name}</span>
        </div>
        <div class="detail-title">
          <img class="detail-icon" src="../images/icons/${sw.id}.webp" alt="${sw.name}" width="72" height="72">
          <h1 class="detail-name">${sw.name}</h1>
        </div>
        <p class="detail-description">${sw.desc}</p>
      </div>
      <div class="detail-content">
        <div class="detail-body">
          <img class="screenshot-placeholder" src="../images/screenshots/${sw.id}.webp" alt="Ảnh chụp màn hình ${sw.name}" loading="lazy">
          ${paragraphs}
        </div>
        <aside class="detail-sidebar">
          <div class="sidebar-card">
            <h3>Liên kết</h3>
            <div class="btn-group">
              <a href="${sw.url}" class="btn btn-primary" target="_blank" rel="noopener">
                Trang chủ →
              </a>
              ${sw.download ? `<a href="${sw.download}" class="btn btn-secondary" target="_blank" rel="noopener">
                Tải xuống ↓
              </a>` : ''}
            </div>
          </div>
        </aside>
      </div>
    `;
  }

  // Init
  if (typeof PAGE_ID !== 'undefined') {
    renderDetail();
  } else {
    renderHomepage();
  }
})();

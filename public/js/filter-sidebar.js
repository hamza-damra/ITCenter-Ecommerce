/**
 * Filter Sidebar — Unified JavaScript
 * Single source of truth for all filter interactions on products & category pages.
 * Handles: AJAX filtering, accordion toggles, category tree, price slider,
 *          brand view-more, mobile drawer, sort/per-page, pagination, clear all.
 */
(function () {
    'use strict';

    // ── State ──────────────────────────────────────────────
    let isFiltering = false;
    let debounceTimer = null;

    // ── DOM refs (resolved once on DOMContentLoaded) ──────
    let filterForm, rangeMin, rangeMax, highlight,
        minPriceInput, maxPriceInput, minPriceHidden, maxPriceHidden;

    // ── Helpers ────────────────────────────────────────────
    function showLoading() {
        const el = document.getElementById('productsLoading');
        if (el) el.classList.add('active');
        isFiltering = true;
    }
    function hideLoading() {
        const el = document.getElementById('productsLoading');
        if (el) el.classList.remove('active');
        isFiltering = false;
    }

    // ── Apply filters via AJAX ─────────────────────────────
    window.applyFilters = function () {
        if (isFiltering) return;
        showLoading();

        const form = document.getElementById('filterForm');
        if (!form) { hideLoading(); return; }

        const formData = new FormData(form);
        const params = new URLSearchParams();

        for (const [key, value] of formData.entries()) {
            if (value && String(value).trim() !== '') {
                params.append(key, value);
            }
        }

        // Preserve sort/per_page from current URL
        const cur = new URLSearchParams(window.location.search);
        ['sort', 'order', 'per_page'].forEach(function (p) {
            if (cur.has(p) && !params.has(p)) params.set(p, cur.get(p));
        });

        // Preserve search
        const search = cur.get('search');
        if (search && !params.has('search')) params.set('search', search);

        const url = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
        window.history.pushState({ path: url }, '', url);

        fetch(url, {
            method: 'GET',
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html', 'Cache-Control': 'no-cache' }
        })
            .then(function (res) { if (!res.ok) throw new Error('HTTP ' + res.status); return res.text(); })
            .then(function (html) {
                var parser = new DOMParser();
                var doc = parser.parseFromString(html, 'text/html');

                var newGrid = doc.querySelector('.product-grid');
                var newNoResults = doc.querySelector('.no-results');
                var newPagination = doc.querySelector('.pagination-wrapper');
                var curGrid = document.querySelector('.product-grid');
                var curNoResults = document.querySelector('.no-results');
                var curPagination = document.querySelector('.pagination-wrapper');

                if (newGrid) {
                    if (curNoResults) curNoResults.remove();
                    if (curGrid) {
                        curGrid.style.opacity = '0';
                        curGrid.style.transition = 'opacity 0.15s ease';
                        setTimeout(function () {
                            curGrid.innerHTML = newGrid.innerHTML;
                            curGrid.style.opacity = '';
                            curGrid.style.transition = '';
                            curGrid.style.display = '';
                            if (typeof initializeWishlistButtons === 'function') initializeWishlistButtons();
                            if (typeof initializeCartButtons === 'function') initializeCartButtons();
                        }, 150);
                    } else {
                        var pc = document.getElementById('productsContent');
                        if (pc) {
                            var ld = document.getElementById('productsLoading');
                            if (ld) ld.insertAdjacentHTML('afterend', newGrid.outerHTML);
                        }
                    }
                } else if (newNoResults) {
                    if (curGrid) curGrid.style.display = 'none';
                    if (curNoResults) { curNoResults.innerHTML = newNoResults.innerHTML; curNoResults.style.display = ''; }
                    else {
                        var pc2 = document.getElementById('productsContent');
                        if (pc2) { var ld2 = document.getElementById('productsLoading'); if (ld2) ld2.insertAdjacentHTML('afterend', newNoResults.outerHTML); }
                    }
                }

                // Pagination
                if (newPagination && curPagination) { curPagination.innerHTML = newPagination.innerHTML; handlePaginationLinks(); }
                else if (newPagination && !curPagination) { var pc3 = document.getElementById('productsContent'); if (pc3) { pc3.insertAdjacentHTML('beforeend', newPagination.outerHTML); handlePaginationLinks(); } }
                else if (!newPagination && curPagination) curPagination.remove();

                setTimeout(function () {
                    var sec = document.querySelector('.products-section, .category-section');
                    if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    hideLoading();
                }, 200);
            })
            .catch(function () { hideLoading(); window.location.href = url; });
    };

    window.debouncedApplyFilters = function (delay) {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(window.applyFilters, delay || 300);
    };

    // ── Pagination AJAX ────────────────────────────────────
    function handlePaginationLinks() {
        document.querySelectorAll('.pagination a, .pagination-wrapper a').forEach(function (link) {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                var href = this.getAttribute('href');
                if (!href || href === '#') return;
                showLoading();
                window.history.pushState({ path: href }, '', href);
                fetch(href, { headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' } })
                    .then(function (r) { return r.text(); })
                    .then(function (html) {
                        var doc = new DOMParser().parseFromString(html, 'text/html');
                        var ng = doc.querySelector('.product-grid');
                        var np = doc.querySelector('.pagination-wrapper');
                        var cg = document.querySelector('.product-grid');
                        var cp = document.querySelector('.pagination-wrapper');
                        if (ng && cg) cg.innerHTML = ng.innerHTML;
                        if (np && cp) { cp.innerHTML = np.innerHTML; handlePaginationLinks(); }
                        var sec = document.querySelector('.products-section, .category-section');
                        if (sec) sec.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        hideLoading();
                        if (typeof initializeWishlistButtons === 'function') initializeWishlistButtons();
                        if (typeof initializeCartButtons === 'function') initializeCartButtons();
                    })
                    .catch(function () { window.location.href = href; });
            });
        });
    }

    // ── Accordion toggle ───────────────────────────────────
    function toggleAccordion(button) {
        if (!button) return;
        var content = button.nextElementSibling;
        if (!content || !content.classList.contains('filter-accordion-content')) {
            var parent = button.closest('.filter-accordion');
            if (parent) content = parent.querySelector('.filter-accordion-content');
        }
        if (!content) return;
        var isExpanded = button.getAttribute('aria-expanded') === 'true';
        button.setAttribute('aria-expanded', !isExpanded);
        content.hidden = isExpanded;
        // Update plus/minus icon
        var iconContainer = button.querySelector('.filter-accordion-icon');
        if (iconContainer) {
            var icon = iconContainer.querySelector('i');
            if (icon) {
                icon.className = isExpanded ? 'fas fa-plus' : 'fas fa-minus';
            }
        }
    }

    // ── Category tree expand/collapse ──────────────────────
    window.toggleCatNav = function (btn) {
        var item = btn.closest('.cat-nav-item');
        var childList = item ? item.querySelector(':scope > .cat-nav-children') : null;
        if (!childList) return;
        var isOpen = childList.classList.contains('open');
        if (isOpen) { childList.classList.remove('open'); btn.classList.remove('expanded'); }
        else { childList.classList.add('open'); btn.classList.add('expanded'); }
    };

    // ── "More" button for category list ────────────────────
    function initCatNavMore() {
        var btn = document.getElementById('catNavMoreBtn');
        if (!btn) return;
        var expanded = false;
        btn.addEventListener('click', function () {
            var items = document.querySelectorAll('.cat-nav-hidden');
            expanded = !expanded;
            items.forEach(function (li) { li.style.display = expanded ? '' : 'none'; });
            btn.classList.toggle('expanded', expanded);
            var textEl = document.getElementById('catNavMoreText');
            var isRtl = document.dir === 'rtl' || document.documentElement.dir === 'rtl';
            if (expanded) textEl.textContent = isRtl ? 'عرض أقل' : 'Show less';
            else textEl.textContent = (isRtl ? 'المزيد' : 'More') + ' (' + (parseInt(btn.dataset.total) - parseInt(btn.dataset.limit)) + ')';
        });
    }

    // ── Price slider ───────────────────────────────────────
    function updateSliderHighlight() {
        if (!rangeMin || !rangeMax || !highlight) return;
        var min = parseInt(rangeMin.value), max = parseInt(rangeMax.value);
        var total = parseInt(rangeMin.max) - parseInt(rangeMin.min);
        if (total <= 0) return;
        var minPct = ((min - parseInt(rangeMin.min)) / total) * 100;
        var maxPct = ((max - parseInt(rangeMin.min)) / total) * 100;
        highlight.style.left = minPct + '%';
        highlight.style.width = (maxPct - minPct) + '%';
    }
    function syncSlider() {
        if (!rangeMin || !rangeMax) return;
        if (minPriceInput) minPriceInput.value = rangeMin.value;
        if (maxPriceInput) maxPriceInput.value = rangeMax.value;
        if (minPriceHidden) minPriceHidden.value = rangeMin.value;
        if (maxPriceHidden) maxPriceHidden.value = rangeMax.value;
        updateSliderHighlight();
    }
    function initPriceSlider() {
        rangeMin = document.getElementById('rangeMin');
        rangeMax = document.getElementById('rangeMax');
        highlight = document.querySelector('.dual-range-highlight');
        minPriceInput = document.getElementById('minPriceInput');
        maxPriceInput = document.getElementById('maxPriceInput');
        minPriceHidden = document.getElementById('minPrice');
        maxPriceHidden = document.getElementById('maxPrice');
        if (!rangeMin || !rangeMax) return;

        rangeMin.addEventListener('input', function () {
            if (parseInt(rangeMin.value) > parseInt(rangeMax.value)) rangeMin.value = rangeMax.value;
            syncSlider();
        });
        rangeMax.addEventListener('input', function () {
            if (parseInt(rangeMax.value) < parseInt(rangeMin.value)) rangeMax.value = rangeMin.value;
            syncSlider();
        });
        rangeMin.addEventListener('change', function () { window.debouncedApplyFilters(400); });
        rangeMax.addEventListener('change', function () { window.debouncedApplyFilters(400); });

        if (minPriceInput) {
            minPriceInput.addEventListener('change', function () {
                var v = parseInt(this.value) || parseInt(rangeMin.min);
                v = Math.max(parseInt(rangeMin.min), Math.min(v, parseInt(rangeMax.value)));
                this.value = v;
                if (rangeMin) rangeMin.value = v;
                if (minPriceHidden) minPriceHidden.value = v;
                updateSliderHighlight();
                window.debouncedApplyFilters(500);
            });
        }
        if (maxPriceInput) {
            maxPriceInput.addEventListener('change', function () {
                var v = parseInt(this.value) || parseInt(rangeMax.max);
                v = Math.max(parseInt(rangeMin.value), Math.min(v, parseInt(rangeMax.max)));
                this.value = v;
                if (rangeMax) rangeMax.value = v;
                if (maxPriceHidden) maxPriceHidden.value = v;
                updateSliderHighlight();
                window.debouncedApplyFilters(500);
            });
        }
        syncSlider();
    }

    // ── Brand view-more ────────────────────────────────────
    function initBrandViewMore() {
        var btn = document.getElementById('brandViewMoreBtn');
        if (!btn) return;
        var expanded = false;
        btn.addEventListener('click', function () {
            expanded = !expanded;
            var items = document.querySelectorAll('.brand-filter-item');
            items.forEach(function (item, idx) {
                if (idx >= 10) item.style.display = expanded ? '' : 'none';
            });
            btn.classList.toggle('expanded', expanded);
            var icon = document.getElementById('brandViewMoreIcon');
            if (icon) icon.style.transform = expanded ? 'rotate(180deg)' : '';
            var textEl = document.getElementById('brandViewMoreText');
            var isRtl = document.dir === 'rtl' || document.documentElement.dir === 'rtl';
            if (expanded) textEl.textContent = isRtl ? 'عرض أقل' : 'View less';
            else textEl.textContent = (isRtl ? 'عرض المزيد' : 'View more') + ' (' + (parseInt(btn.dataset.totalCount) - 10) + ')';
        });
    }

    // ── Brand search ───────────────────────────────────────
    function initBrandSearch() {
        var searchInput = document.getElementById('brandSearchInput');
        if (!searchInput) return;
        searchInput.addEventListener('input', function () {
            var val = this.value.toLowerCase();
            var items = document.querySelectorAll('.brand-filter-item');
            items.forEach(function (item) {
                var labelText = item.querySelector('label').textContent.toLowerCase();
                var countText = item.querySelector('.item-count').textContent.toLowerCase();
                // Find label without count
                var pureLabel = labelText.replace(countText, '').trim();
                if (pureLabel.indexOf(val) > -1) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
            var viewMoreBtn = document.getElementById('brandViewMoreBtn');
            if (viewMoreBtn) {
                viewMoreBtn.style.display = (val !== '' || items.length <= 10) ? 'none' : '';
                if (val === '') {
                    viewMoreBtn.classList.remove('expanded');
                    var isRtl = document.dir === 'rtl' || document.documentElement.dir === 'rtl';
                    var textEl = document.getElementById('brandViewMoreText');
                    if (textEl) textEl.textContent = (isRtl ? 'عرض المزيد' : 'View more') + ' (' + (parseInt(viewMoreBtn.dataset.totalCount) - 10) + ')';
                    items.forEach(function (item, idx) {
                        item.style.display = (idx < 10 || item.querySelector('input').checked) ? '' : 'none';
                    });
                }
            }
        });
    }

    // ── Sort & per-page toolbar ────────────────────────────
    function initToolbar() {
        var sortSelect = document.getElementById('sortSelect');
        var perPageSelect = document.getElementById('perPageSelect');
        if (sortSelect) {
            sortSelect.addEventListener('change', function () {
                var cur = new URLSearchParams(window.location.search);
                var parts = this.value.split('|');
                cur.set('sort', parts[0]);
                cur.set('order', parts[1] || 'desc');
                cur.delete('page');
                window.location.href = window.location.pathname + '?' + cur.toString();
            });
        }
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function () {
                var cur = new URLSearchParams(window.location.search);
                cur.set('per_page', this.value);
                cur.delete('page');
                window.location.href = window.location.pathname + '?' + cur.toString();
            });
        }
    }

    // ── Clear all filters ──────────────────────────────────
    window.clearAllFilters = function () {
        window.closeMobileFilters && window.closeMobileFilters();
        var cur = new URLSearchParams(window.location.search);
        var keepSearch = cur.get('search');
        var url = window.location.pathname;
        if (keepSearch) url += '?search=' + encodeURIComponent(keepSearch);
        window.location.href = url;
    };

    // ── Mobile drawer ──────────────────────────────────────
    window.openMobileFilters = function () {
        var s = document.getElementById('filterSidebar');
        var o = document.getElementById('mobileFilterOverlay');
        if (s && o) { s.classList.add('active'); o.classList.add('active'); document.body.style.overflow = 'hidden'; }
    };
    window.closeMobileFilters = function () {
        var s = document.getElementById('filterSidebar');
        var o = document.getElementById('mobileFilterOverlay');
        if (s && o) { s.classList.remove('active'); o.classList.remove('active'); document.body.style.overflow = ''; }
    };
    window.toggleMobileFilters = function () {
        var s = document.getElementById('filterSidebar');
        if (s && s.classList.contains('active')) window.closeMobileFilters();
        else window.openMobileFilters();
    };
    window.applyAndCloseMobileFilters = function () {
        window.debouncedApplyFilters(0);
        window.closeMobileFilters();
    };

    // ── Init on DOMContentLoaded ───────────────────────────
    function init() {
        filterForm = document.getElementById('filterForm');

        // Prevent native form submission
        if (filterForm) {
            filterForm.addEventListener('submit', function (e) {
                e.preventDefault();
                window.applyFilters();
                return false;
            }, true);
        }

        // Accordion buttons
        document.querySelectorAll('.filter-accordion-button').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();
                toggleAccordion(this);
            });
        });

        // Auto-expand brand accordion if any brand is checked
        var brandToggle = document.getElementById('brandAccordionToggle');
        if (brandToggle && document.querySelector('input[name="brands[]"]:checked')) {
            brandToggle.setAttribute('aria-expanded', 'true');
            var bc = document.getElementById('brandAccordionContent');
            if (bc) bc.hidden = false;
        }

        // Filter change listeners
        document.querySelectorAll('#filterForm input[type="checkbox"], #filterForm input[type="radio"]').forEach(function (input) {
            input.addEventListener('change', function () { window.debouncedApplyFilters(300); });
        });

        // Dynamic filter range inputs
        document.querySelectorAll('#filterForm input[name^="f["][type="number"]').forEach(function (input) {
            input.addEventListener('change', function () { window.debouncedApplyFilters(500); });
        });

        // Inits
        initPriceSlider();
        initBrandViewMore();
        initBrandSearch();
        initCatNavMore();
        initToolbar();
        handlePaginationLinks();

        // Browser back/forward
        window.addEventListener('popstate', function () { window.applyFilters(); });

        // Escape key closes mobile drawer
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') window.closeMobileFilters(); });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();

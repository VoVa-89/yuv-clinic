/**
 * Страница отзывов: синхронизация звёзд с select[name=rating], счётчик символов textarea,
 * разворот длинного текста отзыва («Читать ещё» / «Свернуть»).
 */
function initReviewExpandable(root) {
    root.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-review-toggle]');
        if (!(btn instanceof HTMLElement) || !root.contains(btn)) {
            return;
        }

        const card = btn.closest('.home-reviews__card');
        if (!(card instanceof HTMLElement)) {
            return;
        }

        const excerpt = card.querySelector('[data-review-excerpt]');
        const full = card.querySelector('[data-review-full]');
        const labelExpand = btn.dataset.labelExpand ?? 'Читать ещё';
        const labelCollapse = btn.dataset.labelCollapse ?? 'Свернуть';
        const expanded = btn.getAttribute('aria-expanded') === 'true';
        const nextExpanded = !expanded;

        btn.setAttribute('aria-expanded', nextExpanded ? 'true' : 'false');
        btn.textContent = nextExpanded ? labelCollapse : labelExpand;

        if (excerpt instanceof HTMLElement) {
            excerpt.toggleAttribute('hidden', nextExpanded);
        }
        if (full instanceof HTMLElement) {
            full.toggleAttribute('hidden', !nextExpanded);
        }
    });
}

export function initReviewsPage() {
    const root = document.querySelector('[data-reviews-page]');
    if (!root) {
        return;
    }

    initReviewExpandable(root);

    const ratingSelect = root.querySelector('#rev-rating');
    const starBtns = root.querySelectorAll('[data-star-value]');

    function syncStarsFromRating(value) {
        const n = parseInt(String(value), 10);
        if (Number.isNaN(n) || n < 1 || n > 5) {
            return;
        }
        starBtns.forEach((btn) => {
            const v = parseInt(btn.getAttribute('data-star-value') ?? '0', 10);
            const active = v <= n;
            btn.classList.toggle('review-form__star-btn--active', active);
            btn.setAttribute('aria-pressed', active ? 'true' : 'false');
        });
    }

    starBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            const val = btn.getAttribute('data-star-value');
            if (!ratingSelect || val === null) {
                return;
            }
            ratingSelect.value = val;
            ratingSelect.dispatchEvent(new Event('change', { bubbles: true }));
            syncStarsFromRating(val);
        });
    });

    ratingSelect?.addEventListener('change', () => syncStarsFromRating(ratingSelect.value));
    syncStarsFromRating(ratingSelect?.value ?? '5');

    const textarea = root.querySelector('#rev-body');
    const counter = root.querySelector('[data-char-count]');
    const counterCurrent = root.querySelector('[data-char-count-current]');

    function updateCounter() {
        if (!textarea || !counterCurrent) {
            return;
        }
        counterCurrent.textContent = String(textarea.value.length);
        const max = parseInt(textarea.getAttribute('maxlength') ?? '5000', 10);
        if (counter) {
            counter.classList.toggle('review-form__counter--warn', textarea.value.length > max * 0.95);
        }
    }

    textarea?.addEventListener('input', updateCounter);
    updateCounter();
}

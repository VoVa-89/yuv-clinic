/**
 * Горизонтальная лента отзывов на главной: scroll-snap + кнопки.
 */
export function initHomeReviewsCarousel() {
    document.querySelectorAll('[data-home-reviews]').forEach((root) => {
        const viewport = root.querySelector('[data-home-reviews-track]');
        const prev = root.querySelector('[data-home-reviews-prev]');
        const next = root.querySelector('[data-home-reviews-next]');

        if (!(viewport instanceof HTMLElement)) {
            return;
        }

        const scrollStep = () => Math.round(Math.max(viewport.clientWidth * 0.72, 260));

        prev?.addEventListener('click', () => {
            viewport.scrollBy({ left: -scrollStep(), behavior: 'smooth' });
        });

        next?.addEventListener('click', () => {
            viewport.scrollBy({ left: scrollStep(), behavior: 'smooth' });
        });
    });
}

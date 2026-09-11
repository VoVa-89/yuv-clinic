import './bootstrap';
import '../scss/app.scss';
import { initHomeReviewsCarousel } from './home-reviews-carousel';
import { initReviewsPage } from './reviews-page';
import { initCookieConsent } from './cookie-consent';

document.addEventListener('DOMContentLoaded', () => {
    initHomeReviewsCarousel();
    initReviewsPage();
    initCookieConsent();
});

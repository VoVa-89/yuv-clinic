/**
 * Баннер согласия на аналитические cookies (GTM).
 * Не ломает страницы без data-cookie-consent.
 */
export function initCookieConsent() {
    const root = document.querySelector('[data-cookie-consent]');
    if (!(root instanceof HTMLElement)) {
        return;
    }

    const storageKey = 'yuv_cookie_consent';
    let stored = null;
    try {
        stored = localStorage.getItem(storageKey);
    } catch (e) {
        stored = null;
    }

    if (stored === '1') {
        if (typeof window.__yuvLoadGtm === 'function') {
            window.__yuvLoadGtm();
        }
        return;
    }

    if (stored === '0') {
        return;
    }

    root.hidden = false;

    const accept = root.querySelector('[data-cookie-accept]');
    const decline = root.querySelector('[data-cookie-decline]');

    const persist = (value) => {
        try {
            localStorage.setItem(storageKey, value);
        } catch (e) {}
        root.hidden = true;
    };

    if (accept instanceof HTMLElement) {
        accept.addEventListener('click', () => {
            persist('1');
            if (typeof window.__yuvLoadGtm === 'function') {
                window.__yuvLoadGtm();
            }
        });
    }

    if (decline instanceof HTMLElement) {
        decline.addEventListener('click', () => {
            persist('0');
        });
    }
}

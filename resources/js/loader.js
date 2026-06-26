const MIN_VISIBLE_MS = 250;
const SAFETY_TIMEOUT_MS = 15000;

let loaderEl = null;
let shownAt = 0;
let hideTimer = null;
let safetyTimer = null;
let pendingRequests = 0;

function getLoader() {
    if (!loaderEl) {
        loaderEl = document.querySelector('[data-global-loader]');
    }
    return loaderEl;
}

function detectFontAwesome(el) {
    const probe = el.querySelector('.global-loader-ball');
    if (!probe) return;
    const family = window.getComputedStyle(probe).fontFamily || '';
    if (!/font awesome/i.test(family)) {
        el.classList.add('fa-missing');
    }
}

function showGlobalLoader() {
    const el = getLoader();
    if (!el) return;

    clearTimeout(hideTimer);
    clearTimeout(safetyTimer);

    if (!el.classList.contains('is-visible')) {
        shownAt = Date.now();
    }

    el.classList.add('is-visible');
    el.setAttribute('aria-hidden', 'false');

    safetyTimer = setTimeout(() => {
        pendingRequests = 0;
        hideGlobalLoader(true);
    }, SAFETY_TIMEOUT_MS);
}

function hideGlobalLoader(force = false) {
    const el = getLoader();
    if (!el) return;

    if (!force && pendingRequests > 0) {
        return;
    }

    clearTimeout(hideTimer);
    clearTimeout(safetyTimer);

    const elapsed = Date.now() - shownAt;
    const wait = Math.max(MIN_VISIBLE_MS - elapsed, 0);

    hideTimer = setTimeout(() => {
        el.classList.remove('is-visible');
        el.setAttribute('aria-hidden', 'true');
    }, wait);
}

function isInternalNavigableLink(link) {
    if (!link || !link.href) return false;
    if (link.target && link.target !== '' && link.target !== '_self') return false;
    if (link.hasAttribute('download')) return false;

    const href = link.getAttribute('href') || '';
    if (href.startsWith('#')) return false;

    let url;
    try {
        url = new URL(link.href, window.location.href);
    } catch {
        return false;
    }

    if (url.protocol === 'mailto:' || url.protocol === 'tel:' || url.protocol === 'javascript:') {
        return false;
    }

    if (url.origin !== window.location.origin) {
        return false;
    }

    if (url.hostname.includes('wa.me') || url.hostname.includes('whatsapp.com')) {
        return false;
    }

    if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash !== '') {
        return false;
    }

    return true;
}

function bindNavigationTriggers() {
    document.addEventListener('click', (event) => {
        if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
            return;
        }

        const link = event.target.closest('a');
        if (isInternalNavigableLink(link)) {
            showGlobalLoader();
        }
    });

    document.addEventListener('submit', (event) => {
        const form = event.target;
        if (!(form instanceof HTMLFormElement) || form.hasAttribute('data-no-loader')) {
            return;
        }
        if (event.defaultPrevented) return;
        showGlobalLoader();
    });

    window.addEventListener('beforeunload', () => {
        showGlobalLoader();
    });

    window.addEventListener('pageshow', () => {
        hideGlobalLoader(true);
    });

    window.addEventListener('load', () => {
        hideGlobalLoader(true);
    });
}

function wrapFetch() {
    if (!window.fetch || window.fetch.__globalLoaderWrapped) return;

    const originalFetch = window.fetch.bind(window);

    const wrapped = function (...args) {
        pendingRequests += 1;
        showGlobalLoader();

        return originalFetch(...args).finally(() => {
            pendingRequests = Math.max(0, pendingRequests - 1);
            hideGlobalLoader();
        });
    };

    wrapped.__globalLoaderWrapped = true;
    window.fetch = wrapped;
}

function wrapXhr() {
    if (!window.XMLHttpRequest || XMLHttpRequest.prototype.__globalLoaderWrapped) return;

    const originalOpen = XMLHttpRequest.prototype.open;
    const originalSend = XMLHttpRequest.prototype.send;

    XMLHttpRequest.prototype.open = function (...args) {
        return originalOpen.apply(this, args);
    };

    XMLHttpRequest.prototype.send = function (...args) {
        pendingRequests += 1;
        showGlobalLoader();

        const settle = () => {
            pendingRequests = Math.max(0, pendingRequests - 1);
            hideGlobalLoader();
        };

        this.addEventListener('loadend', settle);

        return originalSend.apply(this, args);
    };

    XMLHttpRequest.prototype.__globalLoaderWrapped = true;
}

function init() {
    const el = getLoader();
    if (!el) return;

    detectFontAwesome(el);
    showGlobalLoader();
    bindNavigationTriggers();
    wrapFetch();
    wrapXhr();

    if (document.readyState === 'complete') {
        hideGlobalLoader(true);
    }
}

window.showGlobalLoader = showGlobalLoader;
window.hideGlobalLoader = hideGlobalLoader;

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

/**
 * 고정 UI 문자열만 KO/EN 전환 (localStorage). 게시판·동적 본문은 브라우저 번역에 맡김.
 */
(function () {
    var WIDGET_ID = 'rb-translate-widget';
    var STORAGE_KEY = 'rb-translate-lang';

    /** 영문 문자열 (한국어는 마크업 기본값에서 스냅샷) */
    var STRINGS_EN = {
        'hdr.logout': 'Log out',
        'hdr.login': 'Log in',
        'hdr.register': 'Sign up',
        'hdr.my': 'My',
        'hero.badge': 'Cebu admin & filing services',
        'hero.title1': 'Cebu visa · incorporation · tax',
        'hero.title2': '<span class="hp-text-deep">One-stop admin solutions</span> Han-Phil',
        'hero.lead':
            'From visa extensions to local incorporation and bookkeeping—our experienced team handles it hands-on.',
        'hero.kakao': 'Chat on KakaoTalk',
        'hero.kakao_copy': 'Copy KakaoTalk ID',
        'hero.phone': 'Call us',
        'hero.contact_view': 'Contact',
        'hero.float_title': 'Trusted service',
        'hero.float_sub': 'Thorough document review',
        'dash.weather_label': "Today's Cebu weather",
        'dash.weather_hint': 'Mostly clear (sample)',
        'dash.rate_label': "Today's FX rate",
        'dash.rate_live': 'LIVE',
        'dash.rate_refresh_title': 'Refresh',
        'dash.rate_refresh_aria': 'Refresh exchange rate',
        'dash.rate_approx': '100 PHP ≈',
        'dash.won': 'KRW',
        'dash.policy_label': 'Latest visa policy',
        'dash.policy_new': 'NEW',
        'dash.policy_text': 'e-Travel required; stronger proof for tourist visa extensions',
        'consult.eyebrow': 'Built for long-term growth',
        'consult.h2': 'Han-Phil 1:1 consulting',
        'consult.prose':
            'Struggling with complex admin and legal issues while running a business in the Philippines? We stay close so you can focus on the business.',
        'consult.li1': '<strong>Tax consulting</strong> — savings and incentive guidance',
        'consult.li2': '<strong>Legal consulting</strong> — articles & contracts, risk control',
        'consult.li3': '<strong>HR consulting</strong> — employment contracts & DOLE support',
        'news.title': 'Headlines',
        'news.sub': 'Han-Phil News',
        'news.dots_label': 'News slides',
        'services.eyebrow': 'Professional agency services',
        'services.h2': 'Han-Phil Cebu integrated business solutions',
        'services.lead':
            'Visa, tax, and incorporation—local experts manage what you need on the ground in Cebu.',
        'service.visa_h3': 'Visa services',
        'service.visa_b1': 'Tourist visa extension',
        'service.visa_b2': '9G work visa',
        'service.visa_b3': 'Retirement visa (SRRV)',
        'service.visa_b4': 'Spouse visa & downgrading',
        'service.visa_b5': 'ECC & BI escort',
        'service.biz_h3': 'Business support',
        'service.biz_b1': 'Incorporation (SEC/DTI)',
        'service.biz_b2': 'Business registration & permit renewal',
        'service.biz_b3': 'Notarization & document prep',
        'service.biz_b4': 'Labor law consulting (DOLE)',
        'service.acct_h3': 'Accounting & tax',
        'service.acct_b1': 'Dedicated Han-Phil bookkeeper',
        'service.acct_b2': 'Monthly BIR filing',
        "service.acct_b3": "Mayor's Permit renewal support",
        'service.acct_b4': 'Audit prep & AFS',
        'service.inquiry': 'Inquire',
        'kakao.eyebrow': 'The easiest way',
        'kakao.h2': 'Real-time chat on KakaoTalk',
        'kakao.prose': 'Continue complex visa and tax questions on a channel you already use.',
        'kakao.center_name': 'Han-Phil Cebu desk',
        'kakao.center_sub': 'Active chat',
        'kakao.bubble1':
            'Hello! An update on your <strong>9G work visa</strong>. Please confirm your visit schedule.',
        'kakao.bubble2': 'Yes, I can visit tomorrow at 10 a.m.',
        'kakao.reviews_h3': 'Client feedback',
        'kakao.quote':
            '“Step-by-step guidance on documents and deposits via Kakao made dealing with Philippine admin much easier.”',
        'kakao.meta': '<strong>3rd year in Cebu</strong> · Lee○',
        'kakao.cta': 'Start 1:1 chat',
        'pricing.eyebrow': 'Straightforward packages',
        'pricing.h2': 'Main service lines',
        'price.tourist_h3': 'Tourist visa extension',
        'price.tourist_b1': 'Step-by-step tourist extensions',
        'price.tourist_b2': 'BI visit accompaniment & proxy',
        'price.tourist_b3': 'Express processing support',
        'price.tourist_b4': 'Passport pickup & delivery guidance',
        'price.tourist_b5': 'ECC processing',
        'price.biz_h3': 'Business & special visas',
        'price.biz_b1': '9G work visa (new/renewal)',
        'price.biz_b2': 'Retirement visa (SRRV) consulting',
        'price.biz_b3': 'Incorporation agency (SEC/DTI)',
        'price.biz_b4': 'Visa downgrading',
        'price.biz_b5': 'Permit renewal support',
        'price.book_h3': 'Bookkeeping & tax',
        'price.book_b1': 'Monthly BIR filing',
        'price.book_b2': 'Dedicated bookkeeper matching',
        'price.book_b3': 'Ledger prep & management',
        'price.book_b4': 'Audit & AFS prep',
        "price.book_b5": "Mayor's Permit renewal",
        'price.cta_dark': 'Request consultation',
        'price.cta_outline': 'Request consultation',
        'life.h2': 'Living in Cebu & tips',
        'life.muted': 'See the latest posts in the community.',
        'life.view_all': 'View all',
        'life.card1_h3': 'Cebu right now',
        'life.card1_p': 'Traffic, weather, and admin updates appear on the free board and notices.',
        'life.card1_link': 'Go to free board',
        'life.card2_h3': 'Life hacks',
        'life.tip1_t': 'Try it this way!',
        'life.tip1_d': 'Local admin tips',
        'life.tip2_t': 'Shop here!',
        'life.tip2_d': 'Shopping & mart info',
        'reviews.section_h2': 'What clients say about Han-Phil',
        'reviews.section_sub': 'Feedback from real consulting and filing experiences.',
        'faq.h2': 'FAQ',
        'faq.lead': 'Find answers to common questions.',
        'cta.title': 'Years of local experience, meticulous document review',
        'cta.sub': 'Accurate information and fast processing for your business.',
        'cta.kakao': 'KakaoTalk',
        'boards.h2': 'Community',
        'boards.muted': 'Latest from notices and the free board.',
        'boards.browser_hint':
            'We do not auto-translate post bodies here. Use your browser’s translate (e.g. Chrome) when needed.',
        'float.kakao': 'KakaoTalk chat',
        'float.kakao_copy': 'Copy KakaoTalk ID',
        'float.telegram': 'Telegram',
        'float.extra': 'More channels',
        'copy.done': 'Copied: ',
        'copy.fail': 'KakaoTalk ID: ',
        'rate.fallback_api': 'Default (API error)',
        'rate.fallback_offline': 'Offline default',
        'rate.suffix': ' updated'
    };

    var koCaptured = false;

    function getStoredLanguage() {
        try {
            var stored = window.localStorage.getItem(STORAGE_KEY);
            if (stored === 'ko' || stored === 'en') {
                return stored;
            }
        } catch (e) {}
        return 'ko';
    }

    function persistLanguage(lang) {
        try {
            window.localStorage.setItem(STORAGE_KEY, lang);
        } catch (e) {}
    }

    function clearLegacyGoogTrans() {
        var path = '; path=/; max-age=0';
        document.cookie = 'googtrans=' + path;
        var host = window.location.hostname;
        var parts = host.split('.');
        if (parts.length > 2 && !/^\d+\.\d+\.\d+\.\d+$/.test(host) && host !== 'localhost') {
            document.cookie = 'googtrans=; path=/; domain=.' + parts.slice(-2).join('.') + '; max-age=0';
        }
    }

    function captureKoStrings() {
        if (koCaptured) {
            return;
        }
        koCaptured = true;
        document.querySelectorAll('[data-i18n]').forEach(function (el) {
            if (!el.dataset.rbI18nKo) {
                el.dataset.rbI18nKo = el.textContent;
            }
        });
        document.querySelectorAll('[data-i18n-html]').forEach(function (el) {
            if (!el.dataset.rbI18nKoHtml) {
                el.dataset.rbI18nKoHtml = el.innerHTML;
            }
        });
        document.querySelectorAll('[data-i18n-title]').forEach(function (el) {
            if (!el.dataset.rbI18nKoTitle) {
                el.dataset.rbI18nKoTitle = el.getAttribute('title') || '';
            }
        });
        document.querySelectorAll('[data-i18n-aria]').forEach(function (el) {
            if (!el.dataset.rbI18nKoAria) {
                el.dataset.rbI18nKoAria = el.getAttribute('aria-label') || '';
            }
        });
        document.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
            if (!el.dataset.rbI18nKoPh) {
                el.dataset.rbI18nKoPh = el.getAttribute('placeholder') || '';
            }
        });
    }

    function applyExtras(lang) {
        var extra = window.__RB_STATIC_EXTRA__;
        if (!extra || !extra.news) {
            return;
        }
        function setText(sel, arr, key) {
            document.querySelectorAll(sel).forEach(function (el) {
                var i = el.getAttribute(key);
                if (i === null || i === '') {
                    return;
                }
                var idx = parseInt(i, 10);
                if (isNaN(idx) || !arr || !arr[idx]) {
                    return;
                }
                if (!el.dataset.rbI18nKo) {
                    el.dataset.rbI18nKo = el.textContent;
                }
                el.textContent = lang === 'en' ? arr[idx] : el.dataset.rbI18nKo;
            });
        }
        setText('[data-i18n-news-index]', extra.news, 'data-i18n-news-index');
        document.querySelectorAll('[data-i18n-faq-q]').forEach(function (el) {
            var i = parseInt(el.getAttribute('data-i18n-faq-q'), 10);
            if (isNaN(i) || !extra.faq || !extra.faq[i]) {
                return;
            }
            if (!el.dataset.rbI18nKo) {
                el.dataset.rbI18nKo = el.textContent;
            }
            el.textContent = lang === 'en' ? extra.faq[i].q : el.dataset.rbI18nKo;
        });
        document.querySelectorAll('[data-i18n-faq-a]').forEach(function (el) {
            var i = parseInt(el.getAttribute('data-i18n-faq-a'), 10);
            if (isNaN(i) || !extra.faq || !extra.faq[i]) {
                return;
            }
            if (!el.dataset.rbI18nKo) {
                el.dataset.rbI18nKo = el.textContent;
            }
            el.textContent = lang === 'en' ? extra.faq[i].a : el.dataset.rbI18nKo;
        });
        document.querySelectorAll('[data-i18n-review-t]').forEach(function (el) {
            var i = parseInt(el.getAttribute('data-i18n-review-t'), 10);
            if (isNaN(i) || !extra.reviews || !extra.reviews[i]) {
                return;
            }
            if (!el.dataset.rbI18nKo) {
                el.dataset.rbI18nKo = el.textContent;
            }
            el.textContent = lang === 'en' ? extra.reviews[i].t : el.dataset.rbI18nKo;
        });
        document.querySelectorAll('[data-i18n-review-who]').forEach(function (el) {
            var i = parseInt(el.getAttribute('data-i18n-review-who'), 10);
            if (isNaN(i) || !extra.reviews || !extra.reviews[i]) {
                return;
            }
            if (!el.dataset.rbI18nKoHtml) {
                el.dataset.rbI18nKoHtml = el.innerHTML;
            }
            if (lang === 'en') {
                var r = extra.reviews[i];
                el.innerHTML = '<strong>' + escapeHtml(r.c) + '</strong><br>' + escapeHtml(r.n);
            } else {
                el.innerHTML = el.dataset.rbI18nKoHtml;
            }
        });
    }

    function escapeHtml(s) {
        if (!s) {
            return '';
        }
        return String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    function applyStaticLanguage(lang) {
        captureKoStrings();
        document.documentElement.lang = lang === 'en' ? 'en' : 'ko';

        document.querySelectorAll('[data-i18n]').forEach(function (el) {
            var key = el.getAttribute('data-i18n');
            if (!key) {
                return;
            }
            if (lang === 'en' && STRINGS_EN[key]) {
                el.textContent = STRINGS_EN[key];
            } else if (el.dataset.rbI18nKo !== undefined) {
                el.textContent = el.dataset.rbI18nKo;
            }
        });

        document.querySelectorAll('[data-i18n-html]').forEach(function (el) {
            var key = el.getAttribute('data-i18n-html');
            if (!key) {
                return;
            }
            if (lang === 'en' && STRINGS_EN[key]) {
                el.innerHTML = STRINGS_EN[key];
            } else if (el.dataset.rbI18nKoHtml !== undefined) {
                el.innerHTML = el.dataset.rbI18nKoHtml;
            }
        });

        document.querySelectorAll('[data-i18n-title]').forEach(function (el) {
            var key = el.getAttribute('data-i18n-title');
            if (!key) {
                return;
            }
            if (lang === 'en' && STRINGS_EN[key]) {
                el.setAttribute('title', STRINGS_EN[key]);
            } else if (el.dataset.rbI18nKoTitle !== undefined) {
                el.setAttribute('title', el.dataset.rbI18nKoTitle);
            }
        });

        document.querySelectorAll('[data-i18n-aria]').forEach(function (el) {
            var key = el.getAttribute('data-i18n-aria');
            if (!key) {
                return;
            }
            if (lang === 'en' && STRINGS_EN[key]) {
                el.setAttribute('aria-label', STRINGS_EN[key]);
            } else if (el.dataset.rbI18nKoAria !== undefined) {
                el.setAttribute('aria-label', el.dataset.rbI18nKoAria);
            }
        });

        document.querySelectorAll('[data-i18n-placeholder]').forEach(function (el) {
            var key = el.getAttribute('data-i18n-placeholder');
            if (!key) {
                return;
            }
            if (lang === 'en' && STRINGS_EN[key]) {
                el.setAttribute('placeholder', STRINGS_EN[key]);
            } else if (el.dataset.rbI18nKoPh !== undefined) {
                el.setAttribute('placeholder', el.dataset.rbI18nKoPh);
            }
        });

        applyExtras(lang);

        try {
            document.dispatchEvent(new CustomEvent('rb-static-lang', { detail: { lang: lang } }));
        } catch (e) {}
    }

    function updateActiveState(lang) {
        var widget = document.getElementById(WIDGET_ID);
        if (!widget) {
            return;
        }
        widget.querySelectorAll('[data-rb-lang]').forEach(function (button) {
            var on = button.getAttribute('data-rb-lang') === lang;
            button.classList.toggle('is-active', on);
            button.setAttribute('aria-pressed', on ? 'true' : 'false');
        });
    }

    function setLanguage(lang) {
        if (lang !== 'ko' && lang !== 'en') {
            return;
        }
        persistLanguage(lang);
        clearLegacyGoogTrans();
        applyStaticLanguage(lang);
        updateActiveState(lang);
    }

    function createWidget() {
        if (document.getElementById(WIDGET_ID)) {
            return;
        }

        var widget = document.createElement('div');
        widget.id = WIDGET_ID;
        widget.className = 'rb-translate-widget notranslate';
        widget.setAttribute('translate', 'no');
        widget.setAttribute('role', 'group');
        widget.setAttribute('aria-label', 'Language switcher');
        widget.innerHTML =
            '<div class="rb-translate-actions">' +
            '<button type="button" class="rb-translate-button" data-rb-lang="ko" aria-pressed="false">KO</button>' +
            '<button type="button" class="rb-translate-button" data-rb-lang="en" aria-pressed="false">EN</button>' +
            '</div>';

        widget.addEventListener('click', function (event) {
            var button = event.target.closest('[data-rb-lang]');
            if (!button) {
                return;
            }
            setLanguage(button.getAttribute('data-rb-lang'));
        });

        var slot = document.getElementById('rb-header-translate-slot');
        if (slot) {
            slot.appendChild(widget);
            widget.classList.add('rb-translate-widget--header');
        } else {
            document.body.appendChild(widget);
        }
    }

    function boot() {
        clearLegacyGoogTrans();
        createWidget();
        var lang = getStoredLanguage();
        applyStaticLanguage(lang);
        updateActiveState(lang);
    }

    window.RB_STATIC_I18N = {
        getLang: getStoredLanguage,
        setLang: setLanguage,
        apply: applyStaticLanguage,
        t: function (key) {
            return getStoredLanguage() === 'en' && STRINGS_EN[key] ? STRINGS_EN[key] : '';
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', boot);
    } else {
        boot();
    }
})();

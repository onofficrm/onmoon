<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (!function_exists('boon_build_icon')) {
    function boon_build_icon($name)
    {
        switch ($name) {
            case 'kakao':
                return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3C6.2 3 1.5 6.7 1.5 11.28c0 2.96 1.97 5.56 4.94 7.02l-1.1 4.04c-.08.29.24.52.5.36l4.83-3.14c.44.04.88.06 1.33.06 5.8 0 10.5-3.71 10.5-8.28S17.8 3 12 3z" fill="currentColor"/></svg>';
            case 'telegram':
                return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M21.18 4.57a1.2 1.2 0 0 0-1.3-.17L3.1 11.85a1.05 1.05 0 0 0 .11 1.96l4.27 1.42 1.66 5.08a1.05 1.05 0 0 0 1.89.22l2.4-3.47 4.67 3.42a1.2 1.2 0 0 0 1.9-.72l2.4-14.06a1.2 1.2 0 0 0-.42-1.13z" fill="currentColor"/></svg>';
            case 'line':
                return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3C6.2 3 1.5 6.9 1.5 11.7c0 4.31 3.75 7.92 8.82 8.58l-.45 2.76c-.05.28.25.49.5.35l3.16-2.18c5.07-.47 8.97-4.13 8.97-8.63C22.5 6.9 17.8 3 12 3z" fill="currentColor"/></svg>';
            default:
                return '';
        }
    }
}

$boon_build_site_title = isset($config['cf_title']) && $config['cf_title'] ? $config['cf_title'] : 'ONLY CEBU';
$boon_build_company_name = !empty($rb_builder['bu_1']) ? $rb_builder['bu_1'] : $boon_build_site_title;
$boon_build_owner = !empty($rb_builder['bu_2']) ? $rb_builder['bu_2'] : '대표자 정보 준비중';
$boon_build_phone = !empty($rb_builder['bu_3']) ? $rb_builder['bu_3'] : '';
$boon_build_biz = !empty($rb_builder['bu_5']) ? $rb_builder['bu_5'] : '사업자등록번호 준비중';
$boon_build_address = !empty($rb_builder['bu_10']) ? $rb_builder['bu_10'] : 'Cebu City, Philippines';
$boon_build_privacy = !empty($rb_builder['bu_11']) ? $rb_builder['bu_11'] : '';
$boon_build_footer_note = !empty($rb_builder['bu_12']) ? strip_tags($rb_builder['bu_12']) : '본 정보는 안내 목적이며, 현지 법령 변동에 따라 달라질 수 있습니다. 상세는 개별 상담을 이용해 주세요.';
$boon_build_logo_url = !empty($rb_builder['bu_logo_pc']) ? G5_URL.'/data/logos/pc?ver='.G5_SERVER_TIME : '';
$boon_build_kakao_url = !empty($rb_builder['bu_sns2']) ? $rb_builder['bu_sns2'] : (!empty($rb_builder['bu_sns1']) ? $rb_builder['bu_sns1'] : '');
$boon_build_telegram_url = !empty($rb_builder['bu_sns8']) ? $rb_builder['bu_sns8'] : 'https://t.me/';
$boon_build_extra_url = !empty($rb_builder['bu_sns10']) ? $rb_builder['bu_sns10'] : '';

$hanphil_news = array(
    '필리핀 이민국, 관광비자 연장 심사 강화 및 e-Travel 등록 의무화 안내',
    '2026년 필리핀 최저임금 인상안 발표 - 세부 지역 사업장 적용 가이드',
    '현지 법인 설립 절차 간소화 - SEC 온라인 등록 시스템 업데이트 소식',
    '필리핀 세무국(BIR) 전자 세금 계산서(EIS) 도입 및 시행 일정 공유',
    '세부 막탄 신규 도로 개통에 따른 물류 및 교통 흐름 개선 전망',
);

$hanphil_faq = array(
    array(
        'q' => '비자 연장 기간은 얼마나 걸리나요?',
        'a' => '일반적으로 관광비자 연장은 접수 후 영업일 기준 3~5일 정도 소요됩니다. 급행 서비스 이용 시 당일 또는 익일 처리도 가능합니다.',
    ),
    array(
        'q' => '북키퍼 대행 비용은 어떻게 되나요?',
        'a' => '법인 규모와 매월 발생하는 전표 수에 따라 차등 적용됩니다. 기본 패키지는 상담을 통해 상세 견적을 안내해 드립니다.',
    ),
    array(
        'q' => '법인 설립 시 한국인이 100% 지분을 가질 수 있나요?',
        'a' => '업종에 따라 다릅니다. 소매업 등 일부 업종은 필리핀인 지분이 필요하지만, 수출형 제조나 IT 서비스 등은 100% 외자 법인 설립이 가능합니다.',
    ),
    array(
        'q' => 'ECC(범죄기록증명)는 언제 발급받아야 하나요?',
        'a' => '필리핀에 6개월 이상 체류한 외국인이 출국할 때 반드시 필요합니다. 출국 최소 3~5일 전에는 신청하시는 것이 안전합니다.',
    ),
    array(
        'q' => '세부 외에 다른 지역 업무도 가능한가요?',
        'a' => '네, 가능합니다. 세부를 본점으로 하고 있으나 마닐라, 클락 등 주요 지역의 이민국 및 BIR 업무도 네트워크를 통해 지원해 드리고 있습니다.',
    ),
);

$hanphil_news_en = array(
    'Philippine Bureau of Immigration: stricter tourist visa extension review and mandatory e-Travel registration',
    '2026 Philippines minimum wage increase announced — guide for Cebu-area workplaces',
    'Local corporation incorporation simplified — SEC online registration system update',
    'Philippine BIR electronic sales invoice (EIS) rollout and implementation timeline',
    'New Mactan Cebu road opening — logistics and traffic flow outlook',
);

$hanphil_faq_en = array(
    array(
        'q' => 'How long does a tourist visa extension take?',
        'a' => 'Usually about 3–5 business days after submission. Express service may be same day or next day.',
    ),
    array(
        'q' => 'How much does bookkeeping agency service cost?',
        'a' => 'It varies by company size and monthly transaction volume. We provide a detailed quote after consultation.',
    ),
    array(
        'q' => 'Can a Korean national own 100% of a corporation?',
        'a' => 'It depends on the industry. Some sectors require Filipino equity; export manufacturing or IT services may allow 100% foreign ownership.',
    ),
    array(
        'q' => 'When do I need an ECC (criminal record clearance)?',
        'a' => 'Foreigners who stayed in the Philippines 6 months or more need it when leaving. Apply at least 3–5 days before departure.',
    ),
    array(
        'q' => 'Do you handle regions outside Cebu?',
        'a' => 'Yes. Cebu is our base, but we support BI and BIR matters in Manila, Clark, and other major hubs through our network.',
    ),
);

$hp_reviews_ko = array(
    array('t' => '세부에서 사업하며 세무 신고를 한필에 맡긴 뒤 벌금 걱정 없이 운영 중입니다. 카톡 소통이 특히 편했습니다.', 'c' => '막탄 다이빙 샵', 'n' => '박○ 사장님'),
    array('t' => '법인 설립부터 BIR 등록까지 단계별로 안내해 주셔서 외국인 법인도 무리 없이 오픈했습니다.', 'c' => '세부 무역 법인', 'n' => '최○ 이사님'),
    array('t' => '관광비자 연장을 대행해 주셔서 이민국 대기 시간을 크게 줄였습니다. 서류 검토가 꼼꼼합니다.', 'c' => '세부 거주 교민', 'n' => '김○ 님'),
    array('t' => '은퇴비자(SRRV) 준비 시 예치금·공증 절차를 한 번에 정리해 주셔서 안심하고 진행했습니다.', 'c' => '은퇴 이민', 'n' => '정○ 님'),
);

$hp_reviews_en = array(
    array('t' => 'Running a business in Cebu—we use Han-Phil for tax filings with no penalty worries. Kakao updates were especially convenient.', 'c' => 'Mactan dive shop', 'n' => 'Mr. Park○'),
    array('t' => 'From incorporation to BIR registration, step-by-step guidance let our foreign-owned company open smoothly.', 'c' => 'Cebu trading company', 'n' => 'Director Choi○'),
    array('t' => 'They handled our tourist visa extensions and cut BI waiting time a lot. Document review is thorough.', 'c' => 'Cebu resident', 'n' => 'Kim○'),
    array('t' => 'For retirement visa (SRRV), they organized deposit and notarization in one flow so we could proceed with confidence.', 'c' => 'Retirement migration', 'n' => 'Jung○'),
);

$hp_free_url = function_exists('get_pretty_url') ? get_pretty_url('free') : (G5_BBS_URL.'/board.php?bo_table=free');
$lux_sfl = !empty($sfl) ? $sfl : 'wr_subject||wr_content';
$lux_sop = (isset($sop) && $sop) ? $sop : 'and';
$lux_search_url = G5_BBS_URL.'/search.php';
?>

<div class="boon-build boon-build--hanphil">
<script>
window.__RB_STATIC_EXTRA__ = <?php echo json_encode(
    array(
        'news' => array_values($hanphil_news_en),
        'faq' => $hanphil_faq_en,
        'reviews' => $hp_reviews_en,
    ),
    JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
); ?>;
</script>
    <section class="hp-hero hp-lux-hero boon-build__full-bleed">
        <div class="boon-build__container hp-lux-hero__inner">
            <div class="hp-lux-hero__grid">
                <div class="hp-lux-hero__copy">
                    <span class="hp-lux-badge">Premium Home Care &amp; Wellness</span>
                    <h1 class="hp-lux-title">
                        <span class="hp-lux-title__rose">신뢰할 수 있는</span>
                        <span class="hp-lux-title__rose">프리미엄 홈케어</span>
                        <span class="hp-lux-title__ink">공감마사지</span>
                    </h1>
                    <p class="hp-lux-lead">
                        엄격한 기준으로 선별된 최고의 테라피스트들이<br class="hp-lux-br">
                        당신의 공간으로 직접 찾아가는 프리미엄 힐링 서비스입니다.
                    </p>
                    <form class="hp-lux-search" method="get" action="<?php echo $lux_search_url; ?>" onsubmit="return fsearchbox_submit(this);">
                        <input type="hidden" name="sfl" value="<?php echo htmlspecialchars($lux_sfl, ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="sop" value="<?php echo $lux_sop === 'or' ? 'or' : 'and'; ?>">
                        <div class="hp-lux-search__shell">
                            <span class="hp-lux-search__icon" aria-hidden="true">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                            </span>
                            <input type="text" name="stx" class="hp-lux-search__input" maxlength="20" placeholder="지역명 또는 업체명을 검색하세요" value="<?php echo isset($stx) ? get_text($stx) : ''; ?>" autocomplete="off">
                            <button type="submit" class="hp-lux-search__submit">검색하기</button>
                        </div>
                    </form>
                    <div class="hp-lux-quick">
                        <span class="hp-lux-quick__label">인기지역</span>
                        <div class="hp-lux-quick__links">
                            <?php
                            $lux_cities = array('강남', '인천', '수원', '용인');
                            foreach ($lux_cities as $city) {
                                $q = urlencode($city);
                                echo '<a href="'.$lux_search_url.'?stx='.$q.'&amp;sfl='.urlencode($lux_sfl).'&amp;sop='.urlencode($lux_sop === 'or' ? 'or' : 'and').'" class="hp-lux-quick__link">'.get_text($city).'</a>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="hp-hero__actions hp-lux-hero__actions">
                        <?php if ($boon_build_kakao_url) { ?>
                        <a href="<?php echo $boon_build_kakao_url; ?>" class="hp-btn hp-btn--kakao" target="_blank" rel="noopener noreferrer">
                            <svg class="hp-icon" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3C6.2 3 1.5 6.7 1.5 11.28c0 2.96 1.97 5.56 4.94 7.02l-1.1 4.04c-.08.29.24.52.5.36l4.83-3.14c.44.04.88.06 1.33.06 5.8 0 10.5-3.71 10.5-8.28S17.8 3 12 3z"/></svg>
                            <span data-i18n="hero.kakao">카카오톡 상담하기</span>
                        </a>
                        <?php } else { ?>
                        <button type="button" class="hp-btn hp-btn--kakao" data-copy-text="HanPhilCebu">
                            <?php echo boon_build_icon('kakao'); ?> <span data-i18n="hero.kakao_copy">카카오톡 ID 복사</span>
                        </button>
                        <?php } ?>
                        <?php if ($boon_build_phone && preg_match('/[0-9+]/', $boon_build_phone)) { ?>
                        <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $boon_build_phone); ?>" class="hp-btn hp-btn--outline">
                            <svg class="hp-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            <span data-i18n="hero.phone">전화 상담하기</span>
                        </a>
                        <?php } else { ?>
                        <a href="#hp-contact" class="hp-btn hp-btn--outline"><span data-i18n="hero.contact_view">연락처 보기</span></a>
                        <?php } ?>
                    </div>
                </div>
                <div class="hp-lux-hero__visual" aria-hidden="true">
                    <div class="hp-lux-hero__frame">
                        <img src="https://enjoytokyo.co.kr/wp-content/uploads/2025/09/%EC%B6%9C%EC%9E%A5%EB%A7%88%EC%82%AC%EC%A7%80_%EB%B0%B0%EB%84%882.png" alt="" loading="lazy" width="1000" height="500" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&amp;fit=crop&amp;q=80&amp;w=1000';">
                        <div class="hp-lux-hero__frame-shade"></div>
                        <div class="hp-lux-hero__stat">
                            <div class="hp-lux-hero__stat-ic">
                                <svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                            </div>
                            <div>
                                <p class="hp-lux-hero__stat-title">평균 만족도 4.9/5.0</p>
                                <p class="hp-lux-hero__stat-sub">실제 이용 고객들의 생생한 리뷰</p>
                            </div>
                        </div>
                    </div>
                    <div class="hp-lux-hero__pill">
                        <div class="hp-lux-hero__pill-ic">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        </div>
                        <div>
                            <p class="hp-lux-hero__pill-t">검증된 테라피스트</p>
                            <p class="hp-lux-hero__pill-s">100% 자격증 보유</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hp-lux-hero__foot">
                <div class="hp-lux-hero__foot-head">
                    <span class="hp-lux-gold-bar" aria-hidden="true"></span>
                    <h2 class="hp-lux-hero__foot-title">지역별 카테고리</h2>
                </div>
                <a href="<?php echo $lux_search_url; ?>" class="hp-lux-hero__foot-more">전체보기 <span aria-hidden="true">→</span></a>
            </div>
        </div>
    </section>

    <section class="hp-dash boon-build__full-bleed">
        <div class="boon-build__container">
            <div class="hp-dash__grid">
                <div class="hp-dash__card">
                    <div class="hp-dash__icon hp-dash__icon--amber">
                        <svg class="hp-icon hp-icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    </div>
                    <div>
                        <p class="hp-dash__label" data-i18n="dash.weather_label">오늘의 세부 날씨</p>
                        <p class="hp-dash__value">☀️ 32°C / 26°C</p>
                        <p class="hp-dash__hint" data-i18n="dash.weather_hint">대체로 맑음 (예시)</p>
                    </div>
                </div>
                <div class="hp-dash__card hp-dash__card--rate" id="hp-rate-card">
                    <div class="hp-dash__icon hp-dash__icon--emerald">
                        <svg class="hp-icon hp-icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
                    </div>
                    <div class="hp-dash__rate-body">
                        <div class="hp-dash__rate-head">
                            <span class="hp-dash__label"><span data-i18n="dash.rate_label">오늘의 환율</span> <span class="hp-dash__live" data-i18n="dash.rate_live">LIVE</span></span>
                            <button type="button" class="hp-rate-refresh" id="hp-rate-refresh" data-i18n-title="dash.rate_refresh_title" data-i18n-aria="dash.rate_refresh_aria" title="새로고침" aria-label="환율 새로고침">
                                <svg class="hp-icon hp-icon--sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M23 4v6h-6"/><path d="M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></svg>
                            </button>
                        </div>
                        <p class="hp-dash__value"><span class="hp-muted" data-i18n="dash.rate_approx">100 PHP ≒</span> <strong id="hp-rate-value">—</strong><span class="hp-muted" data-i18n="dash.won">원</span></p>
                        <p class="hp-dash__hint" id="hp-rate-meta">open.er-api.com 연동</p>
                    </div>
                </div>
                <div class="hp-dash__card hp-dash__card--accent">
                    <div class="hp-dash__icon hp-dash__icon--light">
                        <svg class="hp-icon hp-icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </div>
                    <div>
                        <p class="hp-dash__label hp-dash__label--light"><span data-i18n="dash.policy_label">최신 비자 정책</span> <span class="hp-tag-new" data-i18n="dash.policy_new">NEW</span></p>
                        <p class="hp-dash__policy" data-i18n="dash.policy_text">e-Travel 등록 필수 및 관광비자 연장 증빙 강화 안내</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section">
        <div class="boon-build__container hp-split">
            <div class="hp-split__text">
                <p class="hp-eyebrow" data-i18n="consult.eyebrow">장기적인 성장을 위한</p>
                <h2 class="hp-h2" data-i18n="consult.h2">한필 1:1 맞춤 컨설팅</h2>
                <p class="hp-prose" data-i18n="consult.prose">
                    필리핀 현지 비즈니스 운영 중 발생하는 복잡한 행정 및 법률 문제로 어려움을 겪고 계신가요?
                    한필의 전문가들이 비즈니스에만 집중하실 수 있도록 밀착 지원해 드립니다.
                </p>
                <ul class="hp-checklist">
                    <li><span class="hp-check-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span><span data-i18n-html="consult.li1"><strong>세무 컨설팅</strong> — 절세 및 세제혜택 안내</span></li>
                    <li><span class="hp-check-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span><span data-i18n-html="consult.li2"><strong>법무 컨설팅</strong> — 정관·계약 검토 및 리스크 관리</span></li>
                    <li><span class="hp-check-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span><span data-i18n-html="consult.li3"><strong>노무 컨설팅</strong> — 근로계약 및 DOLE 대응 지원</span></li>
                </ul>
            </div>
            <div class="hp-split__panel">
                <div class="hp-news-panel">
                    <div class="hp-news-panel__head">
                        <span class="hp-news-dot"></span>
                        <span class="hp-news-panel__title" data-i18n="news.title">실시간 주요 소식</span>
                        <span class="hp-news-panel__sub" data-i18n="news.sub">Han-Phil News</span>
                    </div>
                    <div class="hp-news-panel__viewport">
                        <ul class="hp-news-panel__list" id="hp-news-list">
                            <?php foreach ($hanphil_news as $i => $line) { ?>
                            <li class="hp-news-item<?php echo $i === 0 ? ' is-active' : ''; ?>" data-index="<?php echo $i; ?>">
                                <span class="hp-news-item__no"><?php echo $i + 1; ?></span>
                                <span class="hp-news-item__text" data-i18n-news-index="<?php echo $i; ?>"><?php echo get_text($line); ?></span>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="hp-news-panel__dots" id="hp-news-dots" role="tablist" data-i18n-aria="news.dots_label" aria-label="뉴스 슬라이드">
                        <?php foreach ($hanphil_news as $i => $line) { ?>
                        <button type="button" class="hp-news-dot-btn<?php echo $i === 0 ? ' is-active' : ''; ?>" data-index="<?php echo $i; ?>" aria-label="소식 <?php echo $i + 1; ?>"></button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section hp-section--white" id="hp-services">
        <div class="boon-build__container">
            <div class="hp-section__head">
                <div>
                    <p class="hp-eyebrow" data-i18n="services.eyebrow">전문적인 대행 서비스</p>
                    <h2 class="hp-h2 hp-h2--tight" data-i18n="services.h2">한필 세부 비즈니스 통합 솔루션</h2>
                </div>
                <p class="hp-section__lead" data-i18n="services.lead">비자부터 세무, 법인 설립까지 세부 현지에서 필요한 행정 업무를 전문가가 직접 관리합니다.</p>
            </div>
            <div class="hp-services">
                <article class="hp-service-card">
                    <div class="hp-service-card__icon hp-service-card__icon--blue">
                        <svg class="hp-icon hp-icon--xl" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <h3 class="hp-h3" data-i18n="service.visa_h3">비자 서비스 (Visa)</h3>
                    <ul class="hp-bullets">
                        <li data-i18n="service.visa_b1">관광비자 연장</li>
                        <li data-i18n="service.visa_b2">9G 워킹비자</li>
                        <li data-i18n="service.visa_b3">은퇴비자 (SRRV)</li>
                        <li data-i18n="service.visa_b4">결혼비자 &amp; 다운그레이딩</li>
                        <li data-i18n="service.visa_b5">ECC 발급 &amp; 이민국 에스코트</li>
                    </ul>
                    <a href="#hp-contact" class="hp-link-arrow"><span data-i18n="service.inquiry">상담 문의</span></a>
                </article>
                <article class="hp-service-card">
                    <div class="hp-service-card__icon hp-service-card__icon--emerald">
                        <svg class="hp-icon hp-icon--xl" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                    </div>
                    <h3 class="hp-h3" data-i18n="service.biz_h3">비즈니스 지원</h3>
                    <ul class="hp-bullets">
                        <li data-i18n="service.biz_b1">법인 설립 대행 (SEC/DTI)</li>
                        <li data-i18n="service.biz_b2">사업자 등록 &amp; Permit 갱신</li>
                        <li data-i18n="service.biz_b3">공증 및 서류 작성 대행</li>
                        <li data-i18n="service.biz_b4">노동법 컨설팅 (DOLE)</li>
                    </ul>
                    <a href="#hp-contact" class="hp-link-arrow"><span data-i18n="service.inquiry">상담 문의</span></a>
                </article>
                <article class="hp-service-card">
                    <div class="hp-service-card__icon hp-service-card__icon--amber">
                        <svg class="hp-icon hp-icon--xl" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="4" y="2" width="16" height="20" rx="2"/><line x1="8" y1="6" x2="16" y2="6"/><line x1="8" y1="10" x2="16" y2="10"/><line x1="8" y1="14" x2="12" y2="14"/></svg>
                    </div>
                    <h3 class="hp-h3" data-i18n="service.acct_h3">회계/세무 서비스</h3>
                    <ul class="hp-bullets">
                        <li data-i18n="service.acct_b1">한필 전담 북키퍼 서비스</li>
                        <li data-i18n="service.acct_b2">매월 BIR 세무 신고 대행</li>
                        <li data-i18n="service.acct_b3">Mayor's Permit 갱신 지원</li>
                        <li data-i18n="service.acct_b4">회계 감사 및 AFS 준비</li>
                    </ul>
                    <a href="#hp-contact" class="hp-link-arrow"><span data-i18n="service.inquiry">상담 문의</span></a>
                </article>
            </div>
        </div>
    </section>

    <section class="hp-section hp-section--muted">
        <div class="boon-build__container">
            <div class="hp-kakao-head">
                <p class="hp-eyebrow" data-i18n="kakao.eyebrow">가장 편리한 방식은</p>
                <h2 class="hp-h2" data-i18n="kakao.h2">카카오톡으로 실시간 소통</h2>
                <p class="hp-prose hp-prose--center" data-i18n="kakao.prose">복잡한 비자·세무 상담을 고객이 익숙한 채널에서 이어갑니다.</p>
            </div>
            <div class="hp-kakao-grid">
                <div class="hp-phone">
                    <div class="hp-phone__status">
                        <span>10:44</span>
                        <span class="hp-phone__notch"></span>
                        <span class="hp-phone__icons">●</span>
                    </div>
                    <div class="hp-phone__screen">
                        <div class="hp-kakao-top">
                            <div class="hp-kakao-avatar">한필</div>
                            <div>
                                <p class="hp-kakao-name" data-i18n="kakao.center_name">한필 세부 지원센터</p>
                                <p class="hp-kakao-sub" data-i18n="kakao.center_sub">대화 중</p>
                            </div>
                        </div>
                        <div class="hp-kakao-msgs">
                            <div class="hp-kakao-row">
                                <div class="hp-kakao-avatar hp-kakao-avatar--sm">한필</div>
                                <div class="hp-kakao-bubble hp-kakao-bubble--white" data-i18n-html="kakao.bubble1">
                                    안녕하세요! 요청하신 <strong>9G 워킹비자</strong> 진행 상황을 안내드립니다. 방문 일정 확인 부탁드려요.
                                </div>
                            </div>
                            <div class="hp-kakao-row hp-kakao-row--end">
                                <div class="hp-kakao-bubble hp-kakao-bubble--yellow" data-i18n="kakao.bubble2">네, 내일 오전 10시 방문 가능합니다.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hp-review-card">
                    <h3 class="hp-h3" data-i18n="kakao.reviews_h3">고객 후기</h3>
                    <p class="hp-review-quote" data-i18n="kakao.quote">“서류 준비부터 예치금 송금까지 단계별로 카톡으로 안내해 주셔서 필리핀 행정이 훨씬 수월했습니다.”</p>
                    <p class="hp-review-meta" data-i18n-html="kakao.meta"><strong>세부 거주 3년차</strong> · 이○ 님</p>
                    <a href="<?php echo $boon_build_kakao_url ? $boon_build_kakao_url : '#hp-contact'; ?>" class="hp-btn hp-btn--deep"<?php echo $boon_build_kakao_url ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>><span data-i18n="kakao.cta">1:1 상담 시작하기</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section hp-section--white">
        <div class="boon-build__container">
            <div class="hp-pricing-head">
                <p class="hp-eyebrow" data-i18n="pricing.eyebrow">합리적인 패키지</p>
                <h2 class="hp-h2" data-i18n="pricing.h2">한필의 주요 서비스 라인</h2>
            </div>
            <div class="hp-pricing">
                <article class="hp-price-card hp-price-card--dark">
                    <h3 class="hp-h3 hp-h3--light" data-i18n="price.tourist_h3">관광비자 연장</h3>
                    <ul class="hp-price-list">
                        <li data-i18n="price.tourist_b1">관광비자 단계별 연장</li>
                        <li data-i18n="price.tourist_b2">이민국 방문 동행·대행</li>
                        <li data-i18n="price.tourist_b3">급행(Express) 처리 지원</li>
                        <li data-i18n="price.tourist_b4">여권 수령·배송 안내</li>
                        <li data-i18n="price.tourist_b5">ECC 발급 대행</li>
                    </ul>
                    <a href="#hp-contact" class="hp-btn hp-btn--deep hp-btn--block"><span data-i18n="price.cta_dark">상담 신청하기</span></a>
                </article>
                <article class="hp-price-card">
                    <h3 class="hp-h3" data-i18n="price.biz_h3">비즈니스 &amp; 특수 비자</h3>
                    <ul class="hp-price-list">
                        <li data-i18n="price.biz_b1">9G 워킹비자 (신규/갱신)</li>
                        <li data-i18n="price.biz_b2">은퇴비자 (SRRV) 컨설팅</li>
                        <li data-i18n="price.biz_b3">법인 설립 대행 (SEC/DTI)</li>
                        <li data-i18n="price.biz_b4">비자 다운그레이딩</li>
                        <li data-i18n="price.biz_b5">Permit 갱신 지원</li>
                    </ul>
                    <a href="#hp-contact" class="hp-btn hp-btn--outline hp-btn--block"><span data-i18n="price.cta_outline">상담 신청하기</span></a>
                </article>
                <article class="hp-price-card">
                    <h3 class="hp-h3" data-i18n="price.book_h3">북키퍼 &amp; 세무</h3>
                    <ul class="hp-price-list">
                        <li data-i18n="price.book_b1">매월 BIR 세무 신고</li>
                        <li data-i18n="price.book_b2">전담 북키퍼 매칭</li>
                        <li data-i18n="price.book_b3">장부 작성·관리</li>
                        <li data-i18n="price.book_b4">회계 감사·AFS 준비</li>
                        <li data-i18n="price.book_b5">Mayor's Permit 갱신</li>
                    </ul>
                    <a href="#hp-contact" class="hp-btn hp-btn--outline hp-btn--block"><span data-i18n="price.cta_outline">상담 신청하기</span></a>
                </article>
            </div>
        </div>
    </section>

    <section class="hp-section hp-section--muted" id="hp-life">
        <div class="boon-build__container">
            <div class="hp-life-head">
                <div>
                    <h2 class="hp-h2 hp-h2--tight" data-i18n="life.h2">세부 생활 &amp; 꿀팁</h2>
                    <p class="hp-muted" data-i18n="life.muted">커뮤니티에서 최신 글을 확인해 보세요.</p>
                </div>
                <a href="<?php echo $hp_free_url; ?>" class="hp-link-arrow"><span data-i18n="life.view_all">전체보기</span></a>
            </div>
            <div class="hp-life-grid">
                <div class="hp-life-card">
                    <div class="hp-life-card__title">
                        <span class="hp-life-ic hp-life-ic--blue">
                            <svg class="hp-icon hp-icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        </span>
                        <h3 class="hp-h3" data-i18n="life.card1_h3">현재 세부는</h3>
                    </div>
                    <p class="hp-prose" data-i18n="life.card1_p">교통·날씨·행정 소식은 자유게시판과 공지에서 업데이트됩니다.</p>
                    <a href="<?php echo $hp_free_url; ?>" class="hp-text-deep hp-link-arrow"><span data-i18n="life.card1_link">자유게시판으로 이동</span></a>
                </div>
                <div class="hp-life-card">
                    <div class="hp-life-card__title">
                        <span class="hp-life-ic hp-life-ic--green">
                            <svg class="hp-icon hp-icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
                        </span>
                        <h3 class="hp-h3" data-i18n="life.card2_h3">생활 꿀팁</h3>
                    </div>
                    <div class="hp-life-tips">
                        <a href="<?php echo $hp_free_url; ?>" class="hp-tip"><span class="hp-tip__t" data-i18n="life.tip1_t">이건 이렇게 해봐!</span><span class="hp-tip__d" data-i18n="life.tip1_d">현지 행정 팁</span></a>
                        <a href="<?php echo $hp_free_url; ?>" class="hp-tip"><span class="hp-tip__t" data-i18n="life.tip2_t">여기서 사면 돼!</span><span class="hp-tip__d" data-i18n="life.tip2_d">쇼핑·마트 정보</span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section hp-reviews-wrap">
        <div class="boon-build__container">
            <h2 class="hp-h2 hp-h2--tight" data-i18n="reviews.section_h2">한필과 함께한 고객 후기</h2>
            <p class="hp-muted hp-reviews-sub" data-i18n="reviews.section_sub">실제 상담·대행 경험을 바탕으로 한 피드백입니다.</p>
            <div class="hp-reviews-scroll">
                <?php foreach ($hp_reviews_ko as $ri => $rv) { ?>
                <article class="hp-review-tile">
                    <p class="hp-review-tile__text" data-i18n-review-t="<?php echo $ri; ?>"><?php echo get_text($rv['t']); ?></p>
                    <p class="hp-review-tile__who" data-i18n-review-who="<?php echo $ri; ?>"><strong><?php echo get_text($rv['c']); ?></strong><br><?php echo get_text($rv['n']); ?></p>
                </article>
                <?php } ?>
            </div>
        </div>
    </section>

    <section class="hp-section hp-faq-wrap">
        <div class="boon-build__container hp-faq-inner">
            <h2 class="hp-h2 hp-text-center" data-i18n="faq.h2">자주 묻는 질문</h2>
            <p class="hp-muted hp-text-center hp-faq-lead" data-i18n="faq.lead">궁금하신 사항을 미리 확인해 보세요.</p>
            <div class="hp-faq">
                <?php foreach ($hanphil_faq as $fi => $faq) { ?>
                <div class="hp-faq__item">
                    <button type="button" class="hp-faq__q" aria-expanded="false" aria-controls="hp-faq-a-<?php echo $fi; ?>" id="hp-faq-q-<?php echo $fi; ?>">
                        <span data-i18n-faq-q="<?php echo $fi; ?>"><?php echo get_text($faq['q']); ?></span>
                        <span class="hp-faq__toggle" aria-hidden="true"></span>
                    </button>
                    <div class="hp-faq__a" id="hp-faq-a-<?php echo $fi; ?>" role="region" aria-labelledby="hp-faq-q-<?php echo $fi; ?>" hidden>
                        <span data-i18n-faq-a="<?php echo $fi; ?>"><?php echo get_text($faq['a']); ?></span>
                    </div>
                </div>
                <?php } ?>
            </div>
            <div class="hp-cta-banner" id="hp-contact">
                <h3 class="hp-cta-banner__title" data-i18n="cta.title">다년간의 현지 경험으로 빈틈없는 서류 검토</h3>
                <p class="hp-cta-banner__sub" data-i18n="cta.sub">정확한 정보와 신속한 처리로 비즈니스를 지원합니다.</p>
                <div class="hp-cta-banner__actions">
                    <?php if ($boon_build_kakao_url) { ?>
                    <a href="<?php echo $boon_build_kakao_url; ?>" class="hp-btn hp-btn--white" target="_blank" rel="noopener noreferrer"><span data-i18n="cta.kakao">카카오톡 상담</span></a>
                    <?php } ?>
                    <?php if ($boon_build_phone) { ?>
                    <span class="hp-cta-banner__phone"><?php echo get_text($boon_build_phone); ?></span>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>

    <section class="boon-build__section boon-build__section--boards boon-build__full-bleed" id="boon-build-boards">
        <div class="boon-build__container">
            <div class="hp-boards-intro">
                <h2 class="hp-h2 hp-h2--tight" data-i18n="boards.h2">커뮤니티</h2>
                <p class="hp-muted" data-i18n="boards.muted">공지사항과 자유게시판 최신 글입니다.</p>
                <p class="hp-muted hp-boards-browser-hint" data-i18n="boards.browser_hint">게시글 본문은 여기서 자동 번역하지 않습니다. 필요 시 브라우저 번역(Chrome 등)을 이용해 주세요.</p>
            </div>
            <div class="boon-build__boards-grid">
                <?php echo latest('theme/boon_build_board', 'notice', 5, 80); ?>
                <?php echo latest('theme/boon_build_board', 'free', 5, 80); ?>
            </div>
        </div>
    </section>

    <div class="boon-build__floating">
        <?php if ($boon_build_kakao_url) { ?>
        <a href="<?php echo $boon_build_kakao_url; ?>" target="_blank" rel="noopener noreferrer" class="boon-build__floating-button boon-build__floating-button--kakao" data-i18n-title="float.kakao" title="카카오톡 상담">
            <?php echo boon_build_icon('kakao'); ?>
        </a>
        <?php } else { ?>
        <button type="button" class="boon-build__floating-button boon-build__floating-button--kakao" data-copy-text="HanPhilCebu" data-i18n-title="float.kakao_copy" title="카카오톡 ID 복사">
            <?php echo boon_build_icon('kakao'); ?>
        </button>
        <?php } ?>
        <?php if ($boon_build_telegram_url && $boon_build_telegram_url !== 'https://t.me/') { ?>
        <a href="<?php echo $boon_build_telegram_url; ?>" target="_blank" rel="noopener noreferrer" class="boon-build__floating-button boon-build__floating-button--telegram" data-i18n-title="float.telegram" title="텔레그램">
            <?php echo boon_build_icon('telegram'); ?>
        </a>
        <?php } ?>
        <?php if ($boon_build_extra_url) { ?>
        <a href="<?php echo $boon_build_extra_url; ?>" target="_blank" rel="noopener noreferrer" class="boon-build__floating-button boon-build__floating-button--line" data-i18n-title="float.extra" title="추가 채널">
            <?php echo boon_build_icon('line'); ?>
        </a>
        <?php } ?>
    </div>
</div>

<script>
(function() {
    var root = document.querySelector('.boon-build--hanphil');
    var body = document.body;

    if (!root) return;

    body.classList.add('boon-build-home');

    function hpUiLang() {
        if (document.documentElement.lang === 'en') return 'en';
        try {
            if (window.localStorage.getItem('rb-translate-lang') === 'en') return 'en';
        } catch (e) {}
        return 'ko';
    }
    function hpCopyMsg(key, value) {
        var g = window.RB_STATIC_I18N;
        if (g && g.getLang() === 'en') {
            var prefix = g.t(key) || '';
            return prefix + value;
        }
        if (key === 'copy.done') return '복사되었습니다: ' + value;
        return '카카오톡 ID: ' + value;
    }
    root.querySelectorAll('[data-copy-text]').forEach(function(button) {
        button.addEventListener('click', function() {
            var value = this.getAttribute('data-copy-text');
            if (!value) return;
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(value).then(function() {
                    alert(hpCopyMsg('copy.done', value));
                }).catch(function() {
                    alert(hpCopyMsg('copy.fail', value));
                });
            } else {
                alert(hpCopyMsg('copy.fail', value));
            }
        });
    });

    function hpFetchRate() {
        var el = document.getElementById('hp-rate-value');
        var meta = document.getElementById('hp-rate-meta');
        var card = document.getElementById('hp-rate-card');
        if (!el) return;
        el.classList.add('is-loading');
        if (card) card.classList.add('is-rate-loading');
        var loc = hpUiLang() === 'en' ? 'en-US' : 'ko-KR';
        var g = window.RB_STATIC_I18N;
        fetch('https://open.er-api.com/v6/latest/PHP')
            .then(function(r) { return r.json(); })
            .then(function(data) {
                if (data && data.rates && data.rates.KRW) {
                    var n = Math.round(Number(data.rates.KRW) * 100);
                    el.textContent = n.toLocaleString(loc);
                    if (meta) {
                        meta.textContent = new Date().toLocaleString(loc, { hour: '2-digit', minute: '2-digit' }) +
                            (hpUiLang() === 'en' && g ? (g.t('rate.suffix') || ' updated') : ' 기준');
                    }
                } else {
                    el.textContent = '41.50';
                    if (meta) meta.textContent = hpUiLang() === 'en' && g ? (g.t('rate.fallback_api') || '') : '기본값 (API 오류)';
                }
            })
            .catch(function() {
                el.textContent = '41.50';
                if (meta) meta.textContent = hpUiLang() === 'en' && g ? (g.t('rate.fallback_offline') || '') : '오프라인 시 기본값';
            })
            .finally(function() {
                el.classList.remove('is-loading');
                if (card) card.classList.remove('is-rate-loading');
            });
    }
    hpFetchRate();
    var refreshBtn = document.getElementById('hp-rate-refresh');
    if (refreshBtn) refreshBtn.addEventListener('click', hpFetchRate);
    setInterval(hpFetchRate, 30 * 60 * 1000);
    document.addEventListener('rb-static-lang', function() { hpFetchRate(); });

    var newsItems = root.querySelectorAll('.hp-news-item');
    var newsDots = root.querySelectorAll('.hp-news-dot-btn');
    var newsIndex = 0;
    function setNews(i) {
        newsIndex = (i + newsItems.length) % newsItems.length;
        newsItems.forEach(function(item, idx) {
            item.classList.toggle('is-active', idx === newsIndex);
        });
        newsDots.forEach(function(dot, idx) {
            dot.classList.toggle('is-active', idx === newsIndex);
        });
    }
    if (newsItems.length) {
        setInterval(function() { setNews(newsIndex + 1); }, 3000);
        newsDots.forEach(function(dot) {
            dot.addEventListener('click', function() {
                var i = parseInt(this.getAttribute('data-index'), 10);
                if (!isNaN(i)) setNews(i);
            });
        });
    }

    root.querySelectorAll('.hp-faq__q').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var expanded = this.getAttribute('aria-expanded') === 'true';
            var id = this.getAttribute('aria-controls');
            var panel = id ? document.getElementById(id) : null;
            this.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            if (panel) panel.hidden = expanded;
        });
    });
})();
</script>

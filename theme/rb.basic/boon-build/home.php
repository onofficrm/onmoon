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

$boon_build_site_title = isset($config['cf_title']) && $config['cf_title'] ? $config['cf_title'] : '공감마사지';
$boon_build_company_name = !empty($rb_builder['bu_1']) ? $rb_builder['bu_1'] : $boon_build_site_title;
$boon_build_owner = !empty($rb_builder['bu_2']) ? $rb_builder['bu_2'] : '대표자 정보 준비중';
$boon_build_phone = !empty($rb_builder['bu_3']) ? $rb_builder['bu_3'] : '';
$boon_build_biz = !empty($rb_builder['bu_5']) ? $rb_builder['bu_5'] : '사업자등록번호 준비중';
$boon_build_address = !empty($rb_builder['bu_10']) ? $rb_builder['bu_10'] : '서울특별시';
$boon_build_privacy = !empty($rb_builder['bu_11']) ? $rb_builder['bu_11'] : '';
$boon_build_footer_note = !empty($rb_builder['bu_12']) ? strip_tags($rb_builder['bu_12']) : '이용 전 코스·지역·요금은 상담을 통해 안내됩니다.';
$boon_build_logo_url = !empty($rb_builder['bu_logo_pc']) ? G5_URL.'/data/logos/pc?ver='.G5_SERVER_TIME : '';
$boon_build_kakao_url = !empty($rb_builder['bu_sns2']) ? $rb_builder['bu_sns2'] : (!empty($rb_builder['bu_sns1']) ? $rb_builder['bu_sns1'] : '');
$boon_build_telegram_url = !empty($rb_builder['bu_sns8']) ? $rb_builder['bu_sns8'] : 'https://t.me/';
$boon_build_extra_url = !empty($rb_builder['bu_sns10']) ? $rb_builder['bu_sns10'] : '';

$massage_ticker = array(
    '예약비 없는 100% 후불제 · 유류비 포함 정찰제로 안내해 드립니다.',
    '365일 24시간 상담 가능 · 평균 30분 이내 방문을 목표로 합니다.',
    '전원 실력파 관리사 · 전문 교육 이수 및 위생 관리를 철저히 합니다.',
    '타이 · 아로마 · 감성 힐링 · 스페셜 코스까지 맞춤 상담 가능합니다.',
    '집·호텔·오피스텔·모텔 등 고객님이 계신 곳으로 찾아갑니다.',
);

$massage_ticker_en = array(
    'No booking fee, pay after service · transparent pricing including travel.',
    '24/7 consultation · we aim to arrive within about 30 minutes.',
    'Trained therapists · hygiene and professional standards come first.',
    'Thai, aroma, healing, and special courses available after consultation.',
    'We visit your home, hotel, officetel, or motel.',
);

$massage_faq = array(
    array(
        'q' => '예약은 어떻게 하나요?',
        'a' => '카카오톡 또는 전화로 원하시는 날짜·시간·코스·지역을 말씀해 주시면 상담 후 예약을 잡아 드립니다.',
    ),
    array(
        'q' => '결제는 언제 하면 되나요?',
        'a' => '서비스 완료 후 후불제로 진행됩니다. 현금·카드·계좌이체 등 가능 여부는 상담 시 안내해 드립니다.',
    ),
    array(
        'q' => '방문까지 얼마나 걸리나요?',
        'a' => '지역과 교통 상황에 따라 다르지만, 보통 30분 이내 방문을 목표로 합니다. 일부 지역은 유류비가 추가될 수 있습니다.',
    ),
    array(
        'q' => '취소나 노쇼는 어떻게 되나요?',
        'a' => '예약 시간 10분 이상 연락이 닿지 않으면 예약이 취소될 수 있습니다. 일정 변경은 미리 연락 부탁드립니다.',
    ),
    array(
        'q' => '어떤 코스가 있는지 알고 싶어요.',
        'a' => '타이·아로마·감성 힐링·스페셜 등 코스가 준비되어 있으며, 시간대별 요금은 홈페이지 코스 안내 또는 상담 시 안내됩니다.',
    ),
);

$massage_faq_en = array(
    array(
        'q' => 'How do I book?',
        'a' => 'Contact us via KakaoTalk or phone with your preferred date, time, course, and area. We confirm after consultation.',
    ),
    array(
        'q' => 'When do I pay?',
        'a' => 'Payment is after the service. Cash, card, or transfer may be available—ask during booking.',
    ),
    array(
        'q' => 'How long until arrival?',
        'a' => 'It depends on area and traffic, but we typically aim for within about 30 minutes. Some areas may add a travel fee.',
    ),
    array(
        'q' => 'What about cancel or no-show?',
        'a' => 'If we cannot reach you for more than about 10 minutes after the appointment time, the booking may be cancelled. Please contact us early for changes.',
    ),
    array(
        'q' => 'What courses do you offer?',
        'a' => 'We offer Thai, aroma, healing, special packages, and more. See the course section or ask during consultation for times and prices.',
    ),
);

$hp_free_url = function_exists('get_pretty_url') ? get_pretty_url('free') : (G5_BBS_URL.'/board.php?bo_table=free');
$massage_board_url = function_exists('get_pretty_url') ? get_pretty_url('massage') : (G5_BBS_URL.'/board.php?bo_table=massage');
$lux_sfl = !empty($sfl) ? $sfl : 'wr_subject||wr_content';
$lux_sop = (isset($sop) && $sop) ? $sop : 'and';
$lux_search_url = G5_BBS_URL.'/search.php';
$gg_home_items = array(
    array('region' => '인천', 'short' => '연수구', 'title' => '연수구 프리미엄 케어', 'phone' => '0503-6982-1032', 'url' => 'https://onmoon.co.kr/bbs/board.php?bo_table=massage3&wr_id=1', 'rating' => '4.9'),
    array('region' => '인천', 'short' => '부평', 'title' => '부평 감성 스웨디시', 'phone' => '0503-6982-1033', 'url' => 'https://onmoon.co.kr/bbs/board.php?bo_table=massage3&wr_id=2', 'rating' => '4.8'),
    array('region' => '인천', 'short' => '남동구', 'title' => '남동구 힐링 아로마', 'phone' => '0503-6982-1034', 'url' => 'https://onmoon.co.kr/bbs/board.php?bo_table=massage3&wr_id=3', 'rating' => '4.7'),
    array('region' => '인천', 'short' => '미추홀', 'title' => '미추홀 스웨디시 라운지', 'phone' => '0503-6982-1035', 'url' => 'https://onmoon.co.kr/bbs/board.php?bo_table=massage3&wr_id=4', 'rating' => '4.8'),
    array('region' => '인천', 'short' => '서구', 'title' => '서구 프리미엄 힐링', 'phone' => '0503-6982-1036', 'url' => 'https://onmoon.co.kr/bbs/board.php?bo_table=massage3&wr_id=5', 'rating' => '4.9'),
    array('region' => '인천', 'short' => '동구', 'title' => '동구 아로마 테라피', 'phone' => '0503-6982-1037', 'url' => 'https://onmoon.co.kr/bbs/board.php?bo_table=massage3&wr_id=6', 'rating' => '4.6'),
    array('region' => '인천', 'short' => '계양', 'title' => '계양 스웨디시 케어', 'phone' => '0503-6982-1152', 'url' => 'https://onmoon.co.kr/bbs/board.php?bo_table=massage3&wr_id=7', 'rating' => '4.8'),
    array('region' => '인천', 'short' => '송도', 'title' => '송도 프리미엄 마사지', 'phone' => '0503-6982-1153', 'url' => 'https://onmoon.co.kr/bbs/board.php?bo_table=massage3&wr_id=8', 'rating' => '4.9'),
    array('region' => '인천', 'short' => '주안', 'title' => '주안 감성 스웨디시', 'phone' => '0503-6982-1154', 'url' => 'https://onmoon.co.kr/bbs/board.php?bo_table=massage3&wr_id=9', 'rating' => '4.8'),
);
$gg_home_images = array(
    'https://images.unsplash.com/photo-1544161515-4ab6ce6db874?q=80&w=600&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?q=80&w=600&auto=format&fit=crop',
    'https://images.unsplash.com/photo-1519823551278-64ac92734fb1?q=80&w=600&auto=format&fit=crop',
);
?>

<div class="boon-build boon-build--massage">
<script>
window.__RB_STATIC_EXTRA__ = <?php echo json_encode(
    array(
        'news' => array_values($massage_ticker_en),
        'faq' => $massage_faq_en,
    ),
    JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
); ?>;
</script>
    <section class="gg-home gg-home-section boon-build__full-bleed">
        <div class="gg-home__container">
            <div class="gg-home__hero">
                <p class="gg-home__eyebrow">GONGGAM MASSAGE</p>
                <h1 class="gg-home__title">진심으로 <span>GONGGAM</span>하는<br>완벽한 휴식의 순간</h1>
                <p class="gg-home__lead">
                    공감마사지는 단순한 케어를 넘어 고객님의 컨디션을 최상으로 끌어올립니다.<br>
                    출장마사지 · 스웨디시 · 출장안마 전문가가 찾아갑니다.
                </p>
                <form class="gg-search" method="get" action="<?php echo $lux_search_url; ?>" onsubmit="return false;">
                    <span class="gg-search__icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                    </span>
                    <input type="search" id="gg-home-search" class="gg-search__input" placeholder="마사지/에스테틱 검색" autocomplete="off">
                    <button type="button" class="gg-search__button">검색</button>
                </form>
                <div class="gg-benefits" aria-label="서비스 안내">
                    <article class="gg-benefit">
                        <span class="gg-benefit__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span>
                        <h3>출장마사지 전문</h3>
                        <p>숙련된 관리사가 직접 방문하여 지친 몸의 피로를 해소합니다.</p>
                    </article>
                    <article class="gg-benefit">
                        <span class="gg-benefit__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2.5S5 10 5 15a7 7 0 0 0 14 0c0-5-7-12.5-7-12.5z"/></svg></span>
                        <h3>스웨디시 케어</h3>
                        <p>부드러운 아로마 핸들링으로 편안한 휴식을 제공합니다.</p>
                    </article>
                    <article class="gg-benefit">
                        <span class="gg-benefit__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M16 21v-2a4 4 0 0 0-8 0v2"/><circle cx="12" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg></span>
                        <h3>출장안마 서비스</h3>
                        <p>24시간 예약상담으로 안전하고 프라이빗한 홈 케어를 돕습니다.</p>
                    </article>
                </div>
            </div>

            <div class="gg-region-filter" aria-label="지역 필터">
                <?php
                $gg_regions = array('전체지역', '서울', '경기', '인천', '기타');
                foreach ($gg_regions as $idx => $region_name) {
                    echo '<button type="button" class="gg-region-filter__button'.($idx === 0 ? ' is-active' : '').'" data-region="'.get_text($region_name).'">';
                    echo '<span class="gg-region-filter__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9"><path d="M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11z"/><circle cx="12" cy="10" r="2.5"/></svg></span>';
                    echo '<span>'.get_text($region_name).'</span></button>';
                }
                ?>
            </div>

            <div class="gg-list-head">
                <div>
                    <p class="gg-list-head__eyebrow">RECOMMENDED</p>
                    <h2 id="gg-list-title">추천 마사지 업체</h2>
                </div>
                <p class="gg-list-count">총 <span id="gg-list-count"><?php echo count($gg_home_items); ?></span>개 결과</p>
            </div>

            <div class="gg-card-grid" id="gg-card-grid">
                <?php foreach ($gg_home_items as $i => $item) {
                    $image = $gg_home_images[$i % count($gg_home_images)];
                    $search_text = $item['region'].' '.$item['short'].' '.$item['title'];
                ?>
                <article class="gg-card" data-region="<?php echo get_text($item['region']); ?>" data-search="<?php echo get_text($search_text); ?>">
                    <div class="gg-card__image" style="background-image:url('<?php echo $image; ?>')">
                        <div class="gg-card__shade"></div>
                        <div class="gg-card__badges">
                            <?php if ($i % 3 === 0) { ?><span class="gg-badge gg-badge--hot">HOT 출장</span><?php } ?>
                            <?php if ($i % 5 === 0) { ?><span class="gg-badge gg-badge--new">NEW 업체</span><?php } ?>
                        </div>
                        <div class="gg-card__overlay">
                            <p><?php echo get_text($item['region']); ?> 출장마사지</p>
                            <h3><span><?php echo get_text($item['short']); ?></span> 프리미엄</h3>
                        </div>
                    </div>
                    <div class="gg-card__body">
                        <div class="gg-card__top">
                            <h4><?php echo get_text($item['title']); ?></h4>
                            <span class="gg-rating">★ <?php echo get_text($item['rating']); ?></span>
                        </div>
                        <div class="gg-tags">
                            <span>출장 전문</span><span>방문예약</span><span>100% 예약제</span>
                        </div>
                        <p class="gg-card__meta"><?php echo get_text($item['short']); ?> 전지역 · 주차 가능 · 24시 예약</p>
                        <div class="gg-card__contact">
                            <strong><?php echo get_text($item['phone']); ?></strong>
                            <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $item['phone']); ?>" aria-label="전화 상담">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.12.9.33 1.77.63 2.6a2 2 0 0 1-.45 2.11L8.1 9.9a16 16 0 0 0 6 6l1.47-1.19a2 2 0 0 1 2.11-.45c.83.3 1.7.51 2.6.63A2 2 0 0 1 22 16.92z"/></svg>
                            </a>
                        </div>
                        <a href="<?php echo $item['url']; ?>" class="gg-card__more">코스 안내 및 상세 정보 보기 <span>›</span></a>
                    </div>
                </article>
                <?php } ?>
            </div>
            <div class="gg-empty" id="gg-empty" hidden>검색 결과가 없습니다.</div>
        </div>
    </section>

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
                        <button type="button" class="hp-btn hp-btn--kakao" data-copy-text="공감마사지">
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
                        <svg class="hp-icon hp-icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </div>
                    <div>
                        <p class="hp-dash__label">프리미엄 출장</p>
                        <p class="hp-dash__value">365일 24시간</p>
                        <p class="hp-dash__hint">언제든 상담·예약 가능</p>
                    </div>
                </div>
                <div class="hp-dash__card">
                    <div class="hp-dash__icon hp-dash__icon--emerald">
                        <svg class="hp-icon hp-icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                    <div>
                        <p class="hp-dash__label">빠른 방문</p>
                        <p class="hp-dash__value">약 30분 이내</p>
                        <p class="hp-dash__hint">지역·교통에 따라 달라질 수 있음</p>
                    </div>
                </div>
                <div class="hp-dash__card hp-dash__card--accent">
                    <div class="hp-dash__icon hp-dash__icon--light">
                        <svg class="hp-icon hp-icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    </div>
                    <div>
                        <p class="hp-dash__label hp-dash__label--light">후불제 · 정찰제 <span class="hp-tag-new">TIP</span></p>
                        <p class="hp-dash__policy">예약비 없이 이용 후 결제. 유류비 포함 금액을 상담 시 안내합니다.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section hp-massage-region">
        <div class="boon-build__container">
            <div class="hp-massage-region__grid">
                <?php
                $lux_regions = array(
                    array('서울', '서울'),
                    array('경기', '경기'),
                    array('인천', '인천'),
                    array('기타', '출장마사지'),
                );
                foreach ($lux_regions as $lr) {
                    $rq = urlencode($lr[1]);
                    $href = $lux_search_url.'?stx='.$rq.'&amp;sfl='.urlencode($lux_sfl).'&amp;sop='.urlencode($lux_sop === 'or' ? 'or' : 'and');
                    echo '<a class="hp-massage-region__cell" href="'.$href.'"><span class="hp-massage-region__ic"><svg class="hp-icon hp-icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></span><span class="hp-massage-region__name">'.get_text($lr[0]).'</span></a>';
                }
                ?>
            </div>
        </div>
    </section>

    <section class="hp-section">
        <div class="boon-build__container hp-split">
            <div class="hp-split__text">
                <p class="hp-eyebrow">프리미엄 홈케어</p>
                <h2 class="hp-h2">공감마사지 1:1 맞춤 케어</h2>
                <p class="hp-prose">
                    엄격한 기준으로 선별된 테라피스트가 고객님의 공간으로 찾아갑니다.
                    컨디션과 일정에 맞춰 코스·시간을 상담해 드립니다.
                </p>
                <ul class="hp-checklist">
                    <li><span class="hp-check-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span><span><strong>출장 방문</strong> — 집·호텔·오피스텔 등 원하시는 장소로</span></li>
                    <li><span class="hp-check-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span><span><strong>다양한 코스</strong> — 타이·아로마·힐링·스페셜까지</span></li>
                    <li><span class="hp-check-ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></span><span><strong>투명한 안내</strong> — 정찰제·후불제로 부담 없이</span></li>
                </ul>
            </div>
            <div class="hp-split__panel">
                <div class="hp-news-panel">
                    <div class="hp-news-panel__head">
                        <span class="hp-news-dot"></span>
                        <span class="hp-news-panel__title">이용 안내</span>
                        <span class="hp-news-panel__sub">Gonggam Guide</span>
                    </div>
                    <div class="hp-news-panel__viewport">
                        <ul class="hp-news-panel__list" id="hp-news-list">
                            <?php foreach ($massage_ticker as $i => $line) { ?>
                            <li class="hp-news-item<?php echo $i === 0 ? ' is-active' : ''; ?>" data-index="<?php echo $i; ?>">
                                <span class="hp-news-item__no"><?php echo $i + 1; ?></span>
                                <span class="hp-news-item__text" data-i18n-news-index="<?php echo $i; ?>"><?php echo get_text($line); ?></span>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="hp-news-panel__dots" id="hp-news-dots" role="tablist" aria-label="안내 슬라이드">
                        <?php foreach ($massage_ticker as $i => $line) { ?>
                        <button type="button" class="hp-news-dot-btn<?php echo $i === 0 ? ' is-active' : ''; ?>" data-index="<?php echo $i; ?>" aria-label="안내 <?php echo $i + 1; ?>"></button>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section hp-section--white" id="hp-services">
        <div class="boon-build__container">
            <div class="hp-massage-features">
                <div class="hp-massage-features__head">
                    <span class="hp-lux-gold-bar" aria-hidden="true"></span>
                    <h2 class="hp-h2 hp-h2--tight">저희 서비스만의 특별함</h2>
                </div>
                <div class="hp-massage-features__grid">
                    <?php
                    $feat = array(
                        array('최고의 관리사', '전원 실력파 관리사가 직접 방문합니다.'),
                        array('24시간 서비스', '365일 언제든지 예약 가능하며 빠른 방문을 지향합니다.'),
                        array('다양한 결제방법', '현금, 카드결제, 계좌이체 등 상담 시 안내해 드립니다.'),
                        array('맞춤형 케어', '고객 컨디션에 맞춘 마사지를 제공합니다.'),
                        array('전문 교육 이수', '전문 교육을 이수한 테라피스트로 구성됩니다.'),
                        array('편안한 출장', '집, 호텔, 오피스 등 고객님이 계신 곳으로 찾아갑니다.'),
                    );
                    foreach ($feat as $f) {
                        echo '<div class="hp-massage-feature"><span class="hp-massage-feature__ic"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M20 6L9 17l-5-5"/></svg></span><div><h3 class="hp-massage-feature__t">'.get_text($f[0]).'</h3><p class="hp-massage-feature__d">'.get_text($f[1]).'</p></div></div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section hp-section--muted">
        <div class="boon-build__container">
            <div class="hp-kakao-head">
                <p class="hp-eyebrow">가장 편리한 방식은</p>
                <h2 class="hp-h2">카카오톡으로 실시간 소통</h2>
                <p class="hp-prose hp-prose--center">예약·코스·방문 시간을 익숙한 카톡으로 빠르게 상담해 드립니다.</p>
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
                            <div class="hp-kakao-avatar">공감</div>
                            <div>
                                <p class="hp-kakao-name">공감마사지 상담</p>
                                <p class="hp-kakao-sub">대화 중</p>
                            </div>
                        </div>
                        <div class="hp-kakao-msgs">
                            <div class="hp-kakao-row">
                                <div class="hp-kakao-avatar hp-kakao-avatar--sm">공감</div>
                                <div class="hp-kakao-bubble hp-kakao-bubble--white">
                                    안녕하세요! 오늘 저녁 <strong>아로마 90분</strong> 예약 도와드릴게요. 방문 주소만 알려 주세요.
                                </div>
                            </div>
                            <div class="hp-kakao-row hp-kakao-row--end">
                                <div class="hp-kakao-bubble hp-kakao-bubble--yellow">네, 강남구 ○○동으로 부탁드립니다.</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hp-review-card">
                    <h3 class="hp-h3">고객 한마디</h3>
                    <p class="hp-review-quote">“집에서 편하게 받을 수 있어서 좋았고, 시간 약속도 잘 지켜주셨어요. 다음에도 이용할게요!”</p>
                    <p class="hp-review-meta"><strong>강남 거주</strong> · 김○ 님</p>
                    <a href="<?php echo $boon_build_kakao_url ? $boon_build_kakao_url : '#hp-contact'; ?>" class="hp-btn hp-btn--deep"<?php echo $boon_build_kakao_url ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>1:1 상담 시작하기</a>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section hp-section--white">
        <div class="boon-build__container">
            <div class="hp-pricing-head">
                <p class="hp-eyebrow">Service Menu</p>
                <h2 class="hp-h2">프리미엄 <span class="hp-text-rose">힐링 코스</span> 안내</h2>
                <p class="hp-muted hp-text-center" style="max-width:36rem;margin:0.5rem auto 0;">합리적인 구성으로, 상세 요금은 상담 시 지역·시간에 맞춰 안내합니다.</p>
            </div>
            <div class="hp-pricing hp-pricing--quad">
                <article class="hp-price-card">
                    <h3 class="hp-h3">타이 코스</h3>
                    <p class="hp-course-desc">전통 스트레칭과 압을 이용한 전신 케어</p>
                    <ul class="hp-price-list hp-price-list--plain">
                        <li><span>60분</span><span>7만원~</span></li>
                        <li><span>90분</span><span>8만원~</span></li>
                        <li><span>120분</span><span>10만원~</span></li>
                    </ul>
                    <a href="<?php echo $lux_search_url; ?>" class="hp-btn hp-btn--outline hp-btn--block">지역별 상담</a>
                </article>
                <article class="hp-price-card">
                    <h3 class="hp-h3">아로마 코스</h3>
                    <p class="hp-course-desc">오일 테라피로 심신 안정을 돕는 케어</p>
                    <ul class="hp-price-list hp-price-list--plain">
                        <li><span>60분</span><span>8만원~</span></li>
                        <li><span>90분</span><span>9만원~</span></li>
                        <li><span>120분</span><span>11만원~</span></li>
                    </ul>
                    <a href="<?php echo $lux_search_url; ?>" class="hp-btn hp-btn--outline hp-btn--block">지역별 상담</a>
                </article>
                <article class="hp-price-card hp-price-card--featured">
                    <span class="hp-price-tag">인기</span>
                    <h3 class="hp-h3">감성 힐링 코스</h3>
                    <p class="hp-course-desc">섬세한 터치와 릴렉싱 전문 케어</p>
                    <ul class="hp-price-list hp-price-list--plain">
                        <li><span>60분</span><span>9만원~</span></li>
                        <li><span>90분</span><span>11만원~</span></li>
                        <li><span>120분</span><span>13만원~</span></li>
                    </ul>
                    <a href="<?php echo $lux_search_url; ?>" class="hp-btn hp-btn--deep hp-btn--block">지역별 상담</a>
                </article>
                <article class="hp-price-card">
                    <h3 class="hp-h3">스페셜 코스</h3>
                    <p class="hp-course-desc">타이·힐링·풋 등 결합 프리미엄 패키지</p>
                    <ul class="hp-price-list hp-price-list--plain">
                        <li><span>60분</span><span>10만원~</span></li>
                        <li><span>90분</span><span>12만원~</span></li>
                        <li><span>120분</span><span>14만원~</span></li>
                        <li><span>150분</span><span>16만원~</span></li>
                    </ul>
                    <a href="<?php echo $lux_search_url; ?>" class="hp-btn hp-btn--outline hp-btn--block">지역별 상담</a>
                </article>
            </div>
            <div class="hp-price-note">
                <div class="hp-price-note__icon"><svg class="hp-icon hp-icon--lg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg></div>
                <div>
                    <h4 class="hp-price-note__t">모든 코스 공통</h4>
                    <p class="hp-price-note__p">유류비·출장비 포함 여부는 상담 시 안내 · 정찰제 운영</p>
                </div>
                <ul class="hp-price-note__chips">
                    <li>카드결제 가능</li>
                    <li>현금영수증</li>
                    <li>정찰제</li>
                    <li>후불제</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="hp-section hp-section--muted">
        <div class="boon-build__container">
            <div class="hp-massage-gallery">
                <div class="hp-massage-gallery__head">
                    <span class="hp-lux-gold-bar" aria-hidden="true"></span>
                    <h2 class="hp-h2 hp-h2--tight">마사지 갤러리</h2>
                </div>
                <div class="hp-massage-gallery__grid">
                    <div class="hp-massage-gallery__item">
                        <div class="hp-massage-gallery__img"><img src="https://onmoon.co.kr/data/editor/2512/thumb-6be7981a180c0dbd5c6299d45aba48da_1765260954_5087_835x470.jpg" alt="태국식 마사지" loading="lazy" width="835" height="470"></div>
                        <div class="hp-massage-gallery__cap">태국식 마사지</div>
                    </div>
                    <div class="hp-massage-gallery__item">
                        <div class="hp-massage-gallery__img"><img src="https://onmoon.co.kr/data/editor/2512/thumb-6be7981a180c0dbd5c6299d45aba48da_1765260956_3011_835x470.jpg" alt="아로마 테라피" loading="lazy" width="835" height="470"></div>
                        <div class="hp-massage-gallery__cap">아로마 테라피</div>
                    </div>
                    <div class="hp-massage-gallery__item">
                        <div class="hp-massage-gallery__img"><img src="https://onmoon.co.kr/data/editor/2512/thumb-6be7981a180c0dbd5c6299d45aba48da_1765260958_8943_835x470.jpg" alt="힐링 스웨디시" loading="lazy" width="835" height="470"></div>
                        <div class="hp-massage-gallery__cap">힐링 스웨디시</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section hp-massage-banner-wrap">
        <div class="boon-build__container">
            <div class="hp-massage-banner">
                <div class="hp-massage-banner__media">
                    <img src="https://enjoytokyo.co.kr/wp-content/uploads/2025/09/%ED%9B%84%EA%B8%B0_3.png" alt="" loading="lazy" width="800" height="420" referrerpolicy="no-referrer">
                </div>
                <div class="hp-massage-banner__body">
                    <span class="hp-massage-banner__badge">MASSAGE NO.1 GUIDE</span>
                    <h2 class="hp-h2 hp-h2--tight">전국 출장마사지<br><span class="hp-text-rose">지역별 바로가기</span></h2>
                    <p class="hp-prose">내 지역 정보를 한눈에 — 가격·코스·후기를 검색으로 확인해 보세요.</p>
                    <a href="<?php echo $lux_search_url; ?>" class="hp-btn hp-btn--deep">통합 검색 바로가기</a>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section hp-section--muted" id="hp-life">
        <div class="boon-build__container">
            <div class="hp-life-head">
                <div>
                    <h2 class="hp-h2 hp-h2--tight">지역별 출장 안내</h2>
                    <p class="hp-muted">업체 정보·후기는 게시판에서 확인해 보세요.</p>
                </div>
                <a href="<?php echo $massage_board_url; ?>" class="hp-link-arrow">출장마사지 게시판</a>
            </div>
            <div class="hp-life-grid">
                <div class="hp-life-card">
                    <div class="hp-life-card__title">
                        <span class="hp-life-ic hp-life-ic--blue">
                            <svg class="hp-icon hp-icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                        </span>
                        <h3 class="hp-h3">지역 검색</h3>
                    </div>
                    <p class="hp-prose">강남·수원·인천 등 원하시는 지역명으로 검색해 업체를 찾아보세요.</p>
                    <a href="<?php echo $lux_search_url; ?>" class="hp-text-deep hp-link-arrow">통합 검색으로 이동</a>
                </div>
                <div class="hp-life-card">
                    <div class="hp-life-card__title">
                        <span class="hp-life-ic hp-life-ic--green">
                            <svg class="hp-icon hp-icon--md" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                        </span>
                        <h3 class="hp-h3">커뮤니티</h3>
                    </div>
                    <div class="hp-life-tips">
                        <a href="<?php echo $massage_board_url; ?>" class="hp-tip"><span class="hp-tip__t">출장마사지 정보</span><span class="hp-tip__d">지역별 업체 보기</span></a>
                        <a href="<?php echo $hp_free_url; ?>" class="hp-tip"><span class="hp-tip__t">자유게시판</span><span class="hp-tip__d">소통·질문</span></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="hp-section hp-howto">
        <div class="boon-build__container">
            <div class="hp-howto__head">
                <span class="hp-lux-gold-bar" aria-hidden="true"></span>
                <h2 class="hp-h2 hp-h2--tight">이용 방법</h2>
            </div>
            <ol class="hp-howto__steps">
                <li class="hp-howto__step"><span class="hp-howto__num">1</span><div><strong class="hp-howto__st">예약하기</strong><p class="hp-howto__sd">상담전화·카톡으로 날짜·시간·코스를 예약해 주세요.</p></div></li>
                <li class="hp-howto__step"><span class="hp-howto__num">2</span><div><strong class="hp-howto__st">방문</strong><p class="hp-howto__sd">예약 시간에 맞춰 관리사가 빠르게 방문합니다.</p></div></li>
                <li class="hp-howto__step"><span class="hp-howto__num">3</span><div><strong class="hp-howto__st">상담</strong><p class="hp-howto__sd">컨디션과 요구사항을 확인하고 최적의 케어를 안내합니다.</p></div></li>
                <li class="hp-howto__step"><span class="hp-howto__num">4</span><div><strong class="hp-howto__st">마사지</strong><p class="hp-howto__sd">선택하신 코스로 정성껏 시술을 진행합니다.</p></div></li>
            </ol>
            <div class="hp-howto__note">
                <h3 class="hp-howto__note-t">이용 시 유의사항</h3>
                <ul class="hp-howto__note-ul">
                    <li>예약시간 10분 초과 시 예약이 자동 취소될 수 있습니다.</li>
                    <li>연락이 닿지 않을 경우 일정 조정이 필요할 수 있습니다.</li>
                    <li>카드결제(부가세 별도) 및 계좌이체 가능 여부는 상담 시 안내합니다.</li>
                    <li>일부 지역은 방문이 어렵거나 유류비가 추가될 수 있습니다.</li>
                    <li>비매너·퇴폐 문의 등에는 서비스가 제한될 수 있습니다.</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="hp-section hp-faq-wrap">
        <div class="boon-build__container hp-faq-inner">
            <h2 class="hp-h2 hp-text-center" data-i18n="faq.h2">자주 묻는 질문</h2>
            <p class="hp-muted hp-text-center hp-faq-lead" data-i18n="faq.lead">궁금하신 사항을 미리 확인해 보세요.</p>
            <div class="hp-faq">
                <?php foreach ($massage_faq as $fi => $faq) { ?>
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
                <h3 class="hp-cta-banner__title">지금 바로 편하게 상담해 보세요</h3>
                <p class="hp-cta-banner__sub">카카오톡 또는 전화로 코스·시간·지역을 문의해 주시면 친절히 안내해 드립니다.</p>
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
        <button type="button" class="boon-build__floating-button boon-build__floating-button--kakao" data-copy-text="공감마사지" data-i18n-title="float.kakao_copy" title="카카오톡 ID 복사">
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
    var root = document.querySelector('.boon-build--massage');
    var body = document.body;

    if (!root) return;

    body.classList.add('boon-build-home');

    var ggSearch = root.querySelector('#gg-home-search');
    var ggCards = Array.prototype.slice.call(root.querySelectorAll('.gg-card'));
    var ggButtons = Array.prototype.slice.call(root.querySelectorAll('.gg-region-filter__button'));
    var ggCount = root.querySelector('#gg-list-count');
    var ggTitle = root.querySelector('#gg-list-title');
    var ggEmpty = root.querySelector('#gg-empty');
    var ggActiveRegion = '전체지역';

    function ggApplyFilter() {
        var query = ggSearch ? ggSearch.value.trim().toLowerCase() : '';
        var visible = 0;

        ggCards.forEach(function(card) {
            var region = card.getAttribute('data-region') || '';
            var search = (card.getAttribute('data-search') || '').toLowerCase();
            var matchesRegion = ggActiveRegion === '전체지역' || region === ggActiveRegion;
            var matchesSearch = !query || search.indexOf(query) !== -1;
            var show = matchesRegion && matchesSearch;
            card.hidden = !show;
            if (show) visible += 1;
        });

        if (ggCount) ggCount.textContent = visible;
        if (ggTitle) ggTitle.textContent = ggActiveRegion === '전체지역' ? '추천 마사지 업체' : ggActiveRegion + ' 추천 마사지 업체';
        if (ggEmpty) ggEmpty.hidden = visible !== 0;
    }

    ggButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            ggActiveRegion = button.getAttribute('data-region') || '전체지역';
            ggButtons.forEach(function(item) {
                item.classList.toggle('is-active', item === button);
            });
            ggApplyFilter();
        });
    });

    if (ggSearch) {
        ggSearch.addEventListener('input', ggApplyFilter);
        var ggSearchButton = root.querySelector('.gg-search__button');
        if (ggSearchButton) {
            ggSearchButton.addEventListener('click', ggApplyFilter);
        }
    }

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

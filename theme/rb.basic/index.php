<?php
if (!defined('_INDEX_')) define('_INDEX_', true);
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (G5_IS_MOBILE) {
    include_once(G5_THEME_MOBILE_PATH.'/index.php');
    return;
}

if(G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_SHOP_PATH.'/index.php');
    return;
}

include_once(G5_THEME_PATH.'/head.php');
?>
    

    <?php 
    /**
     * 메인 레이아웃: DB(rb_config.co_layout)가 비어 있으면 boon-build(랜딩).
     * co_layout 이 'basic' 인 경우 flex_box 만 출력되어 모듈 미배치 시 본문이 빈 화면이 되므로
     * boon-build 로 전환해 theme/rb.basic/boon-build/home.php 를 불러옵니다.
     */
    $rb_main_layout_raw = isset($rb_core['layout']) ? trim((string) $rb_core['layout']) : '';
    $rb_main_layout = $rb_main_layout_raw !== '' ? $rb_main_layout_raw : 'boon-build';
    if (strcasecmp($rb_main_layout, 'basic') === 0) {
        $rb_main_layout = 'boon-build';
    }

    if (is_dir(G5_THEME_PATH . '/rb.layout/' . $rb_main_layout)) {
        // 레이아웃 인클루드
        include_once(G5_THEME_PATH . '/rb.layout/' . $rb_main_layout . '/index.php'); 
        //add_javascript('<script src="' . G5_THEME_URL . '/rb.js/rb.layout.js"></script>', 1);
    } else {
        echo "<div class='no_data' style='border:0px;'><span class='no_data_section_ul1 font-B color-000'>레이아웃 설정이 올바르지 않습니다.</span><br>환경설정 패널에서 먼저 레이아웃을 설정해주세요.</div>";
    }

    ?>
    


<?php
include_once(G5_THEME_PATH.'/tail.php');
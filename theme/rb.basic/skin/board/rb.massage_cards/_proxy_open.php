<?php
if (!defined('_GNUBOARD_')) exit;
$GLOBALS['_gonggam_mc_skin_url'] = $board_skin_url;
$GLOBALS['_gonggam_mc_skin_path'] = $board_skin_path;

/**
 * 글보기는 boon-build(PC) / boon-build_mo(모바일) 스킨 사용
 * (목록은 rb.massage_cards/list.skin.php 유지)
 */
if (G5_IS_MOBILE) {
    $mo_base = defined('G5_THEME_MOBILE_PATH') ? G5_THEME_MOBILE_PATH : G5_THEME_PATH.'/'.G5_MOBILE_DIR;
    $board_skin_path = $mo_base.'/skin/board/boon-build_mo';
    $board_skin_url  = G5_THEME_URL.'/'.G5_MOBILE_DIR.'/skin/board/boon-build_mo';
} else {
    $board_skin_path = G5_THEME_PATH.'/skin/board/boon-build';
    $board_skin_url  = G5_THEME_URL.'/skin/board/boon-build';
}

<?php
if (!defined('_GNUBOARD_')) exit;

/**
 * massage / massage2 / massage3 / massage4
 * - 목록: theme/rb.basic/skin/board/rb.massage_cards (카드형)
 * - 글보기·글쓰기 등: rb.massage_cards 가 프록시로 boon-build / boon-build_mo·rb.basic_bbs 를 로드
 *   (글보기 UI는 _proxy_open.php → boon-build, boon-build_mo)
 */
if (!isset($bo_table) || $bo_table === '' || !in_array($bo_table, array('massage', 'massage2', 'massage3', 'massage4'), true)) {
    return;
}
if (!defined('G5_THEME_PATH') || !defined('G5_THEME_URL')) {
    return;
}

$board_skin_path = G5_THEME_PATH.'/skin/board/rb.massage_cards';
$board_skin_url  = G5_THEME_URL.'/skin/board/rb.massage_cards';

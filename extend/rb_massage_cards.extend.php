<?php
if (!defined('_GNUBOARD_')) exit;

/**
 * massage / massage2 / massage3 / massage4 목록·글보기 등에 카드형 스킨 경로 적용
 * (DB의 bo_skin 설정과 무관하게 동일 UI를 쓰도록 함)
 */
if (!isset($bo_table) || $bo_table === '' || !in_array($bo_table, array('massage', 'massage2', 'massage3', 'massage4'), true)) {
    return;
}
if (!defined('G5_THEME_PATH') || !defined('G5_THEME_URL')) {
    return;
}

$board_skin_path = G5_THEME_PATH.'/skin/board/rb.massage_cards';
$board_skin_url  = G5_THEME_URL.'/skin/board/rb.massage_cards';

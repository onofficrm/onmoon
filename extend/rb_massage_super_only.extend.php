<?php
if (!defined('_GNUBOARD_')) exit;

/** @var string $bo_table */
/** @var string $is_admin */

$g5_massage_bo_tables = array('massage', 'massage2', 'massage3', 'massage4');

if (!isset($bo_table) || $bo_table === '' || !in_array($bo_table, $g5_massage_bo_tables, true)) {
    return;
}

$script = isset($_SERVER['SCRIPT_NAME']) ? basename($_SERVER['SCRIPT_NAME']) : '';

if (!isset($is_admin) || $is_admin !== 'super') {
    if (in_array($script, array('write.php', 'write_update.php', 'delete.php', 'delete_all.php', 'board_list_update.php', 'move.php'), true)) {
        alert('마사지 지역 게시판은 최고관리자만 글쓰기·수정·삭제 및 관리 처리를 할 수 있습니다.', G5_BBS_URL.'/board.php?bo_table='.urlencode($bo_table));
    }
}

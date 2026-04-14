<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$info_write_table = $g5['write_prefix'] . $bo_table;
$info_first = sql_fetch(" SELECT wr_id FROM `{$info_write_table}` WHERE wr_is_comment = 0 ORDER BY wr_num ASC, wr_reply ASC LIMIT 1 ");

if (!empty($info_first['wr_id'])) {
    $info_wr_id = (int) $info_first['wr_id'];
    $info_url = get_pretty_url($bo_table, $info_wr_id);
    if (!$info_url) {
        $info_url = G5_BBS_URL . '/board.php?bo_table=' . urlencode($bo_table) . '&wr_id=' . $info_wr_id;
    }
    goto_url($info_url);
    exit;
}

add_stylesheet('<link rel="stylesheet" href="' . $board_skin_url . '/style.css">', 0);
?>

<div id="bo_list" class="info-page-empty">
    <p class="info-page-empty__msg">등록된 내용이 없습니다.</p>
    <?php if (!empty($write_href)) { ?>
    <p class="info-page-empty__act"><a href="<?php echo $write_href ?>" class="btn_b02 btn">글 작성</a></p>
    <?php } ?>
</div>

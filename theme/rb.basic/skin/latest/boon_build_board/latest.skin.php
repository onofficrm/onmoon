<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$list_count = is_array($list) ? count($list) : 0;
$boon_build_titles = array(
    'notice' => '공지사항',
    'free' => '이용후기',
);
$boon_build_title = $bo_subject ? $bo_subject : (isset($boon_build_titles[$bo_table]) ? $boon_build_titles[$bo_table] : $bo_table);
?>

<section class="boon-build__board">
    <div class="boon-build__board-head">
        <div>
            <h3><?php echo $boon_build_title; ?></h3>
            <span class="boon-build__board-bar"></span>
        </div>
        <a href="<?php echo G5_BBS_URL; ?>/board.php?bo_table=<?php echo $bo_table; ?>" class="boon-build__board-more">View All +</a>
    </div>

    <?php if ($list_count > 0) { ?>
    <ul class="boon-build__board-list">
        <?php for ($i = 0; $i < $list_count; $i++) {
            $wr_href = get_pretty_url($bo_table, $list[$i]['wr_id']);
            $wr_date = '';

            if (!empty($list[$i]['wr_datetime'])) {
                $wr_date = substr($list[$i]['wr_datetime'], 0, 10);
            } else if (!empty($list[$i]['datetime'])) {
                $wr_date = preg_replace('/[^0-9\-]/', '', $list[$i]['datetime']);
            }
        ?>
        <li class="boon-build__board-item">
            <a href="<?php echo $wr_href; ?>" class="boon-build__board-link">
                <span class="boon-build__board-left">
                    <span class="boon-build__board-index"><?php echo sprintf('%02d', $i + 1); ?></span>
                    <span class="boon-build__board-subject"><?php echo $list[$i]['subject']; ?></span>
                </span>
                <span class="boon-build__board-date"><?php echo $wr_date; ?></span>
            </a>
        </li>
        <?php } ?>
    </ul>
    <?php } else { ?>
    <div class="boon-build__board-empty">
        <span class="boon-build__board-spinner"></span>
        <span>게시물이 없습니다.</span>
    </div>
    <?php } ?>
</section>

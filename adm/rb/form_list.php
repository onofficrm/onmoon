<?php
$sub_menu = '000740';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, "r");

//테이블이 있는지 검사한다.
if (!sql_query("DESCRIBE rb_form", false)) {
    sql_query("
        CREATE TABLE IF NOT EXISTS `rb_form` (
            `fr_id` varchar(20) NOT NULL DEFAULT '',
            `fr_html` tinyint(4) NOT NULL DEFAULT '0',
            `fr_level` tinyint(4) NOT NULL DEFAULT '1',
            `fr_level_opt` tinyint(4) NOT NULL DEFAULT '1',
            `fr_subject` varchar(255) NOT NULL DEFAULT '',
            `fr_content` longtext NOT NULL,
            `fr_hit` int(11) NOT NULL DEFAULT '0',
            `fr_include_head` varchar(255) NOT NULL,
            `fr_include_tail` varchar(255) NOT NULL,
            PRIMARY KEY (`fr_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ", true);
}

if (!sql_query("DESCRIBE rb_form_submit", false)) {
    sql_query("
        CREATE TABLE IF NOT EXISTS `rb_form_submit` (
            `fs_id` INT AUTO_INCREMENT PRIMARY KEY,
            `fr_id` VARCHAR(50) NOT NULL,
            `mb_id` VARCHAR(50) DEFAULT '',
            `fs_datetime` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `fs_data` LONGTEXT NOT NULL
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ", true);
}

$g5['title'] = '폼 관리';
require_once G5_ADMIN_PATH . '/admin.head.php';

$sql_common = " from rb_form ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) {
    $page = 1;
} // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "select * $sql_common order by fr_id limit $from_record, {$config['cf_page_rows']} ";
$result = sql_query($sql);
?>

<div class="local_ov01 local_ov">
    <?php if ($page > 1) { ?><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">처음으로</a><?php } ?>
    <span class="btn_ov01"><span class="ov_txt">전체 폼</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>
</div>

<div class="btn_fixed_top">
    <a href="./form.php" class="btn btn_01">폼 추가</a>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
        <caption><?php echo $g5['title']; ?> 목록</caption>
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">제목</th>
                <th scope="col">관리</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $row = sql_fetch_array($result); $i++) {
                $bg = 'bg' . ($i % 2);
            ?>
                <tr class="<?php echo $bg; ?>">
                    <td class="td_num" nowrap><?php echo $row['fr_id']; ?></td>
                    <td class="td_left" nowrap><?php echo htmlspecialchars2($row['fr_subject']); ?></td>
                    <td class="td_mng td_mng_l" nowrap>
                        <a href="./form.php?w=u&amp;fr_id=<?php echo $row['fr_id']; ?>" class="btn btn_03"><span class="sound_only"><?php echo htmlspecialchars2($row['fr_subject']); ?> </span>수정</a>
                        <a href="<?php echo G5_URL ?>/rb/form.php?fr_id=<?php echo $row['fr_id']; ?>" class="btn btn_02"><span class="sound_only"><?php echo htmlspecialchars2($row['fr_subject']); ?> </span> 보기</a>
                        <a href="./form_update.php?w=d&amp;fr_id=<?php echo $row['fr_id']; ?>" onclick="return delete_confirm(this);" class="btn btn_02"><span class="sound_only"><?php echo htmlspecialchars2($row['fr_subject']); ?> </span>삭제</a>
                    </td>
                </tr>
            <?php
            }
            if ($i == 0) {
                echo '<tr><td colspan="3" class="empty_table">자료가 한건도 없습니다.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<?php
require_once G5_ADMIN_PATH . '/admin.tail.php';

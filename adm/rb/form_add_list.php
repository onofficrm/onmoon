<?php
$sub_menu = '000741';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, "r");


$g5['title'] = '폼 접수현황';
require_once G5_ADMIN_PATH . '/admin.head.php';

$sql_common = " from rb_form_submit ";

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

$sql = "
    SELECT a.*, b.fr_subject
    FROM rb_form_submit a
    LEFT JOIN rb_form b ON a.fr_id = b.fr_id
    ORDER BY fs_id DESC
    LIMIT $from_record, {$config['cf_page_rows']}
";

$result = sql_query($sql);
?>

<div class="local_ov01 local_ov">
    <?php if ($page > 1) { ?><a href="<?php echo $_SERVER['SCRIPT_NAME']; ?>">처음으로</a><?php } ?>
    <span class="btn_ov01"><span class="ov_txt">전체 접수</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
        <caption><?php echo $g5['title']; ?> 목록</caption>
        <thead>
            <tr>
                <th scope="col">번호</th>
                <th scope="col">접수자</th>
                <th scope="col">폼 제목</th>
                <th scope="col">접수일시</th>
                <th scope="col">관리</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0; $row = sql_fetch_array($result); $i++) {
                $bg = 'bg' . ($i % 2);
                $mb_idx = get_member($row['mb_id']);
                if(isset($row['mb_id']) && $row['mb_id']) {
                $mb_nick = get_sideview($mb_idx['mb_id'], get_text($mb_idx['mb_nick']), $mb_idx['mb_email'], $mb_idx['mb_homepage']);
                } else { 
                    $mb_nick = '비회원';
                }
            ?>
                <tr class="<?php echo $bg; ?>">
                    <td class="td_num" nowrap><?php echo $row['fs_id']; ?></td>
                    <td class="td_name sv_use" nowrap>
                        <div><?php echo $mb_nick ?></div>
                    </td>
                    <td class="td_left" nowrap><a href="./form.php?w=u&amp;fr_id=<?php echo $row['fr_id']; ?>"><?php echo get_text($row['fr_subject']); ?></a></td>
                    <td class="td_datetime" nowrap><?php echo $row['fs_datetime']; ?></td>
                    <td class="td_datetime td_mng">
                        <a href="./form_add_view.php?w=u&amp;fs_id=<?php echo $row['fs_id']; ?>" class="btn btn_03">보기</a>
                        <a href="./form_add_update.php?w=d&amp;fs_id=<?php echo $row['fs_id']; ?>" onclick="return delete_confirm(this);" class="btn btn_02">삭제</a>
                    </td>
                </tr>
            <?php
            }
            if ($i == 0) {
                echo '<tr><td colspan="5" class="empty_table">자료가 한건도 없습니다.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<?php
require_once G5_ADMIN_PATH . '/admin.tail.php';

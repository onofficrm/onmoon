<?php
if (!defined('_GNUBOARD_')) exit;
include_once G5_LIB_PATH.'/thumbnail.lib.php';

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);

$bb_mc_board = isset($bo_table) && in_array($bo_table, array('massage', 'massage2', 'massage3', 'massage4'), true);
$bb_mc_can_edit = !$bb_mc_board || ($is_admin === 'super');
$bb_super = ($is_admin === 'super');
?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<article id="bo_v" class="bb-view-clean-mo<?php echo $bb_mc_board ? ' bb-view-massage' : ''; ?>" style="width:<?php echo $width; ?>">
    <h2 id="bo_v_title" class="sound_only"><?php echo get_text($view['wr_subject']); ?></h2>

    <section id="bo_v_atc" class="bb-view-clean-mo__body">
        <?php
        $v_img_count = count($view['file']);
        if ($v_img_count) {
            echo '<div id="bo_v_img">';
            foreach ($view['file'] as $view_file) {
                echo get_file_thumbnail($view_file);
            }
            echo '</div>';
        }
        ?>
        <div id="bo_v_con"><?php echo get_view_thumbnail($view['content']); ?></div>
        <?php if ($is_signature) { ?><div class="bb-view-clean-mo__sig"><?php echo $signature ?></div><?php } ?>
    </section>

    <?php
    $cnt_dl = 0;
    if ($view['file']['count']) {
        for ($i = 0; $i < count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
                $cnt_dl++;
            }
        }
    }
    ?>

    <?php if ($cnt_dl) { ?>
    <section id="bo_v_file" class="bb-view-clean-mo__section">
        <h2 class="sound_only">첨부파일</h2>
        <ul class="bb-view-clean-mo__filelist">
            <?php
            for ($i = 0; $i < count($view['file']); $i++) {
                if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
            ?>
            <li>
                <a href="<?php echo $view['file'][$i]['href']; ?>" class="view_file_download"><?php echo $view['file'][$i]['source'] ?></a>
                <span class="bb-view-clean-mo__filemeta"><?php echo $view['file'][$i]['size'] ?></span>
            </li>
            <?php
                }
            }
            ?>
        </ul>
    </section>
    <?php } ?>

    <?php if (isset($view['link']) && array_filter($view['link'])) { ?>
    <section id="bo_v_link" class="bb-view-clean-mo__section">
        <h2 class="sound_only">관련링크</h2>
        <ul class="bb-view-clean-mo__linklist">
            <?php
            for ($i = 1; $i <= count($view['link']); $i++) {
                if ($view['link'][$i]) {
                    $link = cut_str($view['link'][$i], 70);
            ?>
            <li>
                <a href="<?php echo $view['link_href'][$i] ?>" target="_blank" rel="noopener"><?php echo $link ?></a>
            </li>
            <?php
                }
            }
            ?>
        </ul>
    </section>
    <?php } ?>

    <?php if ($bb_super) { ?>
    <div class="bb-mo-view-adminbar" aria-label="관리 메뉴">
        <?php if ($list_href) { ?>
        <a href="<?php echo $list_href ?>" class="bb-mo-view-adminbar__btn">목록</a>
        <?php } ?>
        <?php if ($admin_href) { ?>
        <button type="button" class="bb-mo-view-adminbar__btn" onclick="window.open('<?php echo $admin_href ?>');">관리</button>
        <?php } ?>
        <?php if ($write_href && $bb_mc_can_edit) { ?>
        <a href="<?php echo $write_href ?>" class="bb-mo-view-adminbar__btn bb-mo-view-adminbar__btn--primary">글쓰기</a>
        <?php } ?>
        <?php if ($scrap_href) { ?>
        <a href="<?php echo $scrap_href; ?>" class="bb-mo-view-adminbar__btn" target="_blank" onclick="win_scrap(this.href); return false;">스크랩</a>
        <?php } ?>
        <?php if ($search_href) { ?>
        <a href="<?php echo $search_href ?>" class="bb-mo-view-adminbar__btn">검색</a>
        <?php } ?>
        <?php if ($update_href && $bb_mc_can_edit) { ?>
        <a href="<?php echo $update_href ?>" class="bb-mo-view-adminbar__btn">수정</a>
        <?php } ?>
        <?php if ($delete_href && $bb_mc_can_edit) { ?>
        <a href="<?php echo $delete_href ?>" class="bb-mo-view-adminbar__btn" onclick="del(this.href); return false;">삭제</a>
        <?php } ?>
        <?php if ($reply_href && $bb_mc_can_edit) { ?>
        <a href="<?php echo $reply_href ?>" class="bb-mo-view-adminbar__btn">답글</a>
        <?php } ?>
        <?php if ($copy_href && $bb_mc_can_edit) { ?>
        <a href="<?php echo $copy_href ?>" class="bb-mo-view-adminbar__btn" onclick="board_move(this.href); return false;">복사</a>
        <?php } ?>
        <?php if ($move_href && $bb_mc_can_edit) { ?>
        <a href="<?php echo $move_href ?>" class="bb-mo-view-adminbar__btn" onclick="board_move(this.href); return false;">이동</a>
        <?php } ?>
        <?php if ($board['bo_use_sns']) { ?>
        <div class="bb-mo-view-adminbar__sns"><?php include_once G5_SNS_PATH.'/view.sns.skin.php'; ?></div>
        <?php } ?>
    </div>
    <?php } ?>
</article>

<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $('a.view_file_download').click(function() {
        if (!g5_is_member) {
            alert('다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.');
            return false;
        }
        var msg = '파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']); ?>점)됩니다.\n\n그래도 다운로드 하시겠습니까?';
        if (confirm(msg)) {
            $(this).attr('href', $(this).attr('href') + '&js=on');
            return true;
        }
        return false;
    });
});
<?php } ?>

function board_move(href) {
    window.open(href, 'boardmove', 'left=50, top=50, width=500, height=550, scrollbars=1');
}

$(function() {
    $('a.view_image').click(function() {
        window.open(this.href, 'large_image', 'location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no');
        return false;
    });
    $('#bo_v_con').viewimageresize();
});
</script>

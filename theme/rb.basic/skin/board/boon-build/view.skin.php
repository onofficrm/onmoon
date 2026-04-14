<?php
if (!defined('_GNUBOARD_')) exit;
include_once G5_LIB_PATH.'/thumbnail.lib.php';

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);

$bb_mc_board = isset($bo_table) && in_array($bo_table, array('massage', 'massage2', 'massage3', 'massage4'), true);
$bb_mc_can_edit = !$bb_mc_board || ($is_admin === 'super');
$bb_super = ($is_admin === 'super');
?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<div class="rb_bbs_wrap bb-view-clean-wrap<?php echo $bb_mc_board ? ' bb-view-massage' : ''; ?>" id="scroll_container" style="width:<?php echo $width; ?>">
    <div class="bb-view-clean">

        <header class="bb-view-head">
            <h1 class="bb-view-title"><?php echo get_text($view['wr_subject']); ?></h1>
            <p class="bb-view-meta">
                조회 <?php echo number_format($view['wr_hit']); ?>
                <span class="bb-view-meta__sep">|</span>
                등록 <?php echo date('Y.m.d', strtotime($view['wr_datetime'])); ?>
            </p>
        </header>

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
        <section class="bb-view-section" aria-label="첨부파일">
            <h2 class="bb-view-section__tit">첨부파일</h2>
            <div class="bb-view-files">
                <?php
                for ($i = 0; $i < count($view['file']); $i++) {
                    if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
                ?>
                <div class="bb-view-files__row">
                    <a href="<?php echo $view['file'][$i]['href']; ?>" class="view_file_download"><?php echo $view['file'][$i]['source'] ?></a>
                    <span class="bb-view-files__meta"><?php echo $view['file'][$i]['size'] ?> · <?php echo number_format($view['file'][$i]['download']); ?>회</span>
                </div>
                <?php
                    }
                }
                ?>
            </div>
        </section>
        <?php } ?>

        <?php if (isset($view['link']) && array_filter($view['link'])) { ?>
        <section class="bb-view-section" aria-label="관련링크">
            <h2 class="bb-view-section__tit">관련링크</h2>
            <div class="bb-view-links">
                <?php
                for ($i = 1; $i <= count($view['link']); $i++) {
                    if ($view['link'][$i]) {
                        $link = cut_str($view['link'][$i], 70);
                ?>
                <div class="bb-view-links__row">
                    <a href="<?php echo $view['link_href'][$i] ?>" target="_blank" rel="noopener"><?php echo $link ?></a>
                    <span class="bb-view-links__meta"><?php echo (int) $view['link_hit'][$i]; ?>회</span>
                </div>
                <?php
                    }
                }
                ?>
            </div>
        </section>
        <?php } ?>

        <div class="bb-view-body" id="bo_v_atc">
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
        </div>

        <?php
        if (isset($board['bo_use_signature']) && $board['bo_use_signature']) {
            include_once G5_PATH.'/rb/rb.mod/signature/signature.skin.php';
        }
        ?>

        <?php if ($bb_super) { ?>
        <div class="bb-view-adminbar" aria-label="관리 메뉴">
            <div class="bb-view-adminbar__inner">
                <?php if ($list_href) { ?>
                <a href="<?php echo $list_href ?>" class="bb-view-btn bb-view-btn--ghost">목록</a>
                <?php } ?>
                <?php if ($admin_href) { ?>
                <button type="button" class="bb-view-btn bb-view-btn--ghost" onclick="window.open('<?php echo $admin_href ?>');">관리</button>
                <?php } ?>
                <?php if ($write_href && $bb_mc_can_edit) { ?>
                <a href="<?php echo $write_href ?>" class="bb-view-btn bb-view-btn--primary">글 등록</a>
                <?php } ?>
                <?php if ($scrap_href) { ?>
                <a href="<?php echo $scrap_href; ?>" class="bb-view-btn bb-view-btn--ghost" target="_blank" onclick="win_scrap(this.href); return false;">스크랩</a>
                <?php } ?>
                <?php if ($search_href) { ?>
                <a href="<?php echo $search_href ?>" class="bb-view-btn bb-view-btn--ghost">검색</a>
                <?php } ?>
                <?php if ($update_href && $bb_mc_can_edit) { ?>
                <a href="<?php echo $update_href ?>" class="bb-view-btn bb-view-btn--ghost">수정</a>
                <?php } ?>
                <?php if ($delete_href && $bb_mc_can_edit) { ?>
                <a href="<?php echo $delete_href ?>" class="bb-view-btn bb-view-btn--ghost" onclick="del(this.href); return false;">삭제</a>
                <?php } ?>
                <?php if ($copy_href && $bb_mc_can_edit) { ?>
                <a href="<?php echo $copy_href ?>" class="bb-view-btn bb-view-btn--ghost" onclick="board_move(this.href); return false;">복사</a>
                <?php } ?>
                <?php if ($move_href && $bb_mc_can_edit) { ?>
                <a href="<?php echo $move_href ?>" class="bb-view-btn bb-view-btn--ghost" onclick="board_move(this.href); return false;">이동</a>
                <?php } ?>
                <?php if ($reply_href && $bb_mc_can_edit) { ?>
                <a href="<?php echo $reply_href ?>" class="bb-view-btn bb-view-btn--ghost">답글</a>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

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

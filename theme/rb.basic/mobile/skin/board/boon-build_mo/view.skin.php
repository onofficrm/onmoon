<?php
if (!defined('_GNUBOARD_')) exit;
include_once G5_LIB_PATH.'/thumbnail.lib.php';

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
?>
<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<div class="bb-mo-view-toolbar">
    <?php if ($list_href) { ?>
    <a href="<?php echo $list_href ?>" class="bb-mo-view-toolbar__btn">목록</a>
    <?php } ?>
    <span class="bb-mo-view-toolbar__sp"></span>
    <?php if ($board['bo_use_sns'] || $scrap_href) { ?>
    <div class="bb-mo-share">
        <button type="button" class="bb-mo-view-toolbar__btn bb-mo-view-toolbar__btn--icon btn_share_opt is_view_btn" title="공유">공유</button>
        <div id="bo_v_share" class="is_view_btn bb-mo-share__panel">
            <?php if ($scrap_href) { ?>
            <a href="<?php echo $scrap_href; ?>" target="_blank" class="btn_scrap" onclick="win_scrap(this.href); return false;">스크랩</a>
            <?php } ?>
            <?php include_once G5_SNS_PATH.'/view.sns.skin.php'; ?>
        </div>
    </div>
    <?php } ?>
    <?php if ($write_href) { ?>
    <a href="<?php echo $write_href ?>" class="bb-mo-view-toolbar__btn">글쓰기</a>
    <?php } ?>
    <div class="bb-mo-more">
        <button type="button" class="bb-mo-view-toolbar__btn bb-mo-view-toolbar__btn--icon btn_more_opt is_view_btn" title="더보기">···</button>
        <?php ob_start(); ?>
        <ul class="more_opt is_view_btn bb-mo-more__list">
            <?php if ($reply_href) { ?><li><a href="<?php echo $reply_href ?>">답변</a></li><?php } ?>
            <?php if ($update_href) { ?><li><a href="<?php echo $update_href ?>">수정</a></li><?php } ?>
            <?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;">삭제</a></li><?php } ?>
            <?php if ($copy_href) { ?><li><a href="<?php echo $copy_href ?>" onclick="board_move(this.href); return false;">복사</a></li><?php } ?>
            <?php if ($move_href) { ?><li><a href="<?php echo $move_href ?>" onclick="board_move(this.href); return false;">이동</a></li><?php } ?>
            <?php if ($search_href) { ?><li><a href="<?php echo $search_href ?>">검색</a></li><?php } ?>
            <?php if ($list_href) { ?><li><a href="<?php echo $list_href ?>">목록</a></li><?php } ?>
        </ul>
        <?php $link_buttons = ob_get_contents();
        ob_end_flush(); ?>
    </div>
</div>
<script>
jQuery(function($) {
    $('.btn_more_opt.is_view_btn').on('click', function(e) {
        e.stopPropagation();
        $('.more_opt.is_view_btn').toggle();
    });
    $('.btn_share_opt').on('click', function(e) {
        e.stopPropagation();
        $('#bo_v_share').toggle();
    });
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.is_view_btn').length) {
            $('.more_opt.is_view_btn').hide();
            $('#bo_v_share').hide();
        }
    });
});
</script>

<article id="bo_v" class="bb-view-clean-mo" style="width:<?php echo $width; ?>">
    <header class="bb-view-clean-mo__head">
        <h2 id="bo_v_title" class="bb-view-clean-mo__title">
            <?php if ($category_name) { ?>
            <span class="bb-view-clean-mo__cate"><?php echo $view['ca_name']; ?></span>
            <?php } ?>
            <span class="bo_v_tit"><?php echo get_text($view['wr_subject']); ?></span>
        </h2>
        <div id="bo_v_info" class="bb-view-clean-mo__meta">
            <span>조회 <?php echo number_format($view['wr_hit']); ?></span>
            <span class="bb-view-clean-mo__meta-sep">·</span>
            <span><?php echo date('Y.m.d', strtotime($view['wr_datetime'])); ?></span>
        </div>
    </header>

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

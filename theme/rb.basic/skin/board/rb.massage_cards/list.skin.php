<?php
if (!defined('_GNUBOARD_')) exit;
include_once G5_LIB_PATH.'/thumbnail.lib.php';

if (!function_exists('gonggam_mc_phone')) {
    function gonggam_mc_phone(array $row)
    {
        foreach (array('wr_1', 'wr_2', 'wr_3', 'wr_4', 'wr_5', 'wr_6', 'wr_7', 'wr_8', 'wr_9', 'wr_10') as $k) {
            if (empty($row[$k])) {
                continue;
            }
            $v = trim((string) $row[$k]);
            if ($v === '') {
                continue;
            }
            if (preg_match('/^[\d\-\s]+$/u', $v) && preg_match('/\d{3,}/', $v)) {
                return preg_replace('/\s+/u', '', $v);
            }
        }
        $plain = isset($row['wr_content']) ? strip_tags($row['wr_content']) : '';
        $plain = html_entity_decode($plain, ENT_QUOTES, 'UTF-8');
        $plain = preg_replace('/\s+/u', ' ', $plain);
        if (preg_match('/(050\d{1,2}-\d{3,4}-\d{4}|010-\d{4}-\d{4}|01[16789]-\d{3,4}-\d{4})/u', $plain, $m)) {
            return $m[1];
        }

        return '';
    }

    function gonggam_mc_region(array $row)
    {
        if (!empty($row['ca_name'])) {
            return get_text($row['ca_name']);
        }
        $s = isset($row['wr_subject']) ? get_text($row['wr_subject']) : '';
        if (preg_match('/^(.+?)지역/u', $s, $m)) {
            return trim($m[1]);
        }
        if (preg_match('/^([^\s]+)/u', $s, $m)) {
            return trim($m[1]);
        }

        return '지역';
    }

    function gonggam_mc_tags(array $row)
    {
        $s = isset($row['wr_subject']) ? $row['wr_subject'] : '';
        $keywords = array('출장마사지', '출장안마', '스웨디시', '홈타이', '타이마사지', '아로마', '출장서비스');
        $out = array();
        foreach ($keywords as $kw) {
            if (mb_strpos($s, $kw, 0, 'UTF-8') !== false) {
                $out[] = $kw;
            }
        }
        if (!$out) {
            $out = array('출장마사지', '출장안마', '스웨디시');
        }

        return array_slice(array_unique($out), 0, 6);
    }

    function gonggam_mc_rating(array $row)
    {
        $g = isset($row['wr_good']) ? (int) $row['wr_good'] : 0;
        if ($g > 0) {
            return number_format(min(5, 4.5 + log(1 + $g) / 15), 1);
        }

        return '5.0';
    }
}

$gonggam_img_base = G5_THEME_URL.'/skin/board/rb.basic_bbs/img';
$gonggam_thumb_w = !empty($board['bo_gallery_width']) ? (int) $board['bo_gallery_width'] : 480;
$gonggam_thumb_h = !empty($board['bo_gallery_height']) ? (int) $board['bo_gallery_height'] : 300;

add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_URL.'/skin/board/rb.basic_bbs/style.css">', 0);
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 1);
?>

<div class="rb_bbs_wrap gonggam-massage-list" id="scroll_container" style="width:<?php echo $width; ?>">

    <form name="fboardlist" id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
    <input type="hidden" name="stx" value="<?php echo $stx ?>">
    <input type="hidden" name="spt" value="<?php echo $spt ?>">
    <input type="hidden" name="sst" value="<?php echo $sst ?>">
    <input type="hidden" name="sod" value="<?php echo $sod ?>">
    <input type="hidden" name="page" value="<?php echo $page ?>">
    <input type="hidden" name="sw" value="">

    <div class="btns_gr_wrap">
        <div class="sub" style="width:<?php echo $rb_core['sub_width'] ?>px;">
            <?php if (!$wr_id) { ?>
            <div class="btns_gr">
               <?php if ($admin_href) { ?>
               <button type="button" class="fl_btns" onclick="window.open('<?php echo $admin_href ?>');">
               <img src="<?php echo $gonggam_img_base ?>/ico_set.svg" alt="">
               <span class="tooltips">관리</span>
               </button>
               <?php } ?>
               <button type="button" class="fl_btns btn_bo_sch">
               <img src="<?php echo $gonggam_img_base ?>/ico_ser.svg" alt="">
               <span class="tooltips">검색</span>
               </button>
               <?php if ($rss_href) { ?>
               <button type="button" class="fl_btns" onclick="window.open('<?php echo $rss_href ?>');">
               <img src="<?php echo $gonggam_img_base ?>/ico_rss.svg" alt="">
               <span class="tooltips">RSS</span>
               </button>
               <?php } ?>
               <?php if ($write_href) { ?>
               <button type="button" class="fl_btns main_color_bg" onclick="location.href='<?php echo $write_href ?>';">
               <img src="<?php echo $gonggam_img_base ?>/ico_write.svg" alt="">
               <span class="tooltips">글 등록</span>
               </button>
               <?php } ?>
            </div>
            <?php } ?>
            <div class="cb"></div>
        </div>
    </div>

    <ul class="rb_bbs_top">
        <?php if ($board['bo_read_point'] || $board['bo_write_point'] || $board['bo_comment_point'] || $board['bo_download_point']) { ?>
        <li class="point_info_btns_wrap">
            <button type="button" class="point_info_btns" id="point_info_opens_btn">
            <i><svg width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 0C15.523 0 20 4.477 20 10C20 15.523 15.523 20 10 20C4.477 20 0 15.523 0 10C0 4.477 4.477 0 10 0ZM11.5 5H9C8.46957 5 7.96086 5.21071 7.58579 5.58579C7.21071 5.96086 7 6.46957 7 7V14C7 14.2652 7.10536 14.5196 7.29289 14.7071C7.48043 14.8946 7.73478 15 8 15C8.26522 15 8.51957 14.8946 8.70711 14.7071C8.89464 14.5196 9 14.2652 9 14V12H11.5C12.4283 12 13.3185 11.6313 13.9749 10.9749C14.6313 10.3185 15 9.42826 15 8.5C15 7.57174 14.6313 6.6815 13.9749 6.02513C13.3185 5.36875 12.4283 5 11.5 5ZM11.5 7C11.8978 7 12.2794 7.15804 12.5607 7.43934C12.842 7.72064 13 8.10218 13 8.5C13 8.89782 12.842 9.27936 12.5607 9.56066C12.2794 9.84196 11.8978 10 11.5 10H9V7H11.5Z" fill="#09244B"/></svg></i>
            <span class="pc">포인트정책</span></button>
            <div class="point_info_opens">
                <h6><?php echo $board['bo_subject'] ?> 포인트 정책</h6>
                <ul>
                    <?php if ($board['bo_read_point']) { ?>
                    <dl><dd>글읽기</dd><dd class="font-B"><?php echo number_format($board['bo_read_point']); ?>P</dd></dl>
                    <?php } ?>
                    <?php if ($board['bo_write_point']) { ?>
                    <dl><dd>글쓰기</dd><dd class="font-B"><?php echo number_format($board['bo_write_point']); ?>P</dd></dl>
                    <?php } ?>
                    <?php if ($board['bo_comment_point']) { ?>
                    <dl><dd>댓글</dd><dd class="font-B"><?php echo number_format($board['bo_comment_point']); ?>P</dd></dl>
                    <?php } ?>
                    <?php if ($board['bo_download_point']) { ?>
                    <dl><dd>다운로드</dd><dd class="font-B"><?php echo number_format($board['bo_download_point']); ?>P</dd></dl>
                    <?php } ?>
                </ul>
            </div>
            <script>
                $(document).ready(function() {
                    $(document).click(function(event) {
                        if (!$(event.target).closest('#point_info_opens_btn, .point_info_opens').length) {
                            if ($('.point_info_opens').is(':visible')) {
                                $('.point_info_opens').hide();
                                $('#point_info_opens_btn').removeClass('act');
                            }
                        }
                    });
                    $('#point_info_opens_btn').click(function(event) {
                        event.stopPropagation();
                        $('.point_info_opens').toggle();
                        $(this).toggleClass('act');
                    });
                });
            </script>
        </li>
        <?php } ?>
        <?php if ($is_checkbox) { ?>
        <li>
            <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
            <label for="chkall"></label>
        </li>
        <?php } ?>
        <li class="cnts">전체 <?php echo number_format($total_count) ?>건 / <?php echo $page ?> 페이지</li>
        <div class="cb"></div>
    </ul>

    <?php if ($is_category) { ?>
    <nav id="bo_cate" class="swiper-container swiper-container-category">
        <ul id="bo_cate_ul" class="swiper-wrapper swiper-wrapper-category"><?php echo $category_option ?></ul>
    </nav>
    <script>
        $(document).ready(function(){
            $("#bo_cate_ul li").addClass("swiper-slide swiper-slide-category");
            new Swiper('.swiper-container-category', {
                slidesPerView: 'auto',
                spaceBetween: 0,
                observer: true,
                observeParents: true,
                touchRatio: 1
            });
        });
    </script>
    <?php } ?>

    <div class="gonggam-card-grid">
        <?php
        for ($i = 0; $i < count($list); $i++) {
            $row = $list[$i];
            $thumb = get_list_thumbnail($board['bo_table'], $row['wr_id'], $gonggam_thumb_w, $gonggam_thumb_h, false, true);
            if (!empty($thumb['src'])) {
                if (strstr($row['wr_option'], 'secret')) {
                    $img_html = '<img src="'.G5_THEME_URL.'/rb.img/sec_image.png" alt="">';
                } else {
                    $img_html = '<img src="'.$thumb['src'].'" alt="'.get_text($row['wr_subject']).'">';
                }
            } else {
                $img_html = '<img src="'.G5_THEME_URL.'/rb.img/no_image.png" alt="">';
            }
            $wr_href = $row['href'];
            $region = gonggam_mc_region($row);
            $rating = gonggam_mc_rating($row);
            $tags = gonggam_mc_tags($row);
            $phone_raw = gonggam_mc_phone($row);
            $phone_display = $phone_raw ? $phone_raw : '문의(글 상세 확인)';
            $tel_href = $phone_raw ? 'tel:'.preg_replace('/\D/u', '', $phone_raw) : $wr_href;
            $title = get_text($row['wr_subject']);
        ?>
        <article class="gonggam-card">
            <div class="gonggam-card__media">
                <a href="<?php echo $wr_href ?>" class="gonggam-card__media-link"><?php echo run_replace('thumb_image_tag', $img_html, $thumb); ?></a>
                <div class="gonggam-card__badges">
                    <span class="gonggam-card__badge gonggam-card__badge--region"><?php echo get_text($region) ?></span>
                    <span class="gonggam-card__badge gonggam-card__badge--rating">
                        <span class="gonggam-card__star" aria-hidden="true">★</span><?php echo $rating ?>
                    </span>
                </div>
                <?php if ($is_checkbox) { ?>
                <label class="gonggam-card__chk">
                    <input type="checkbox" name="chk_wr_id[]" value="<?php echo $row['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
                </label>
                <?php } ?>
            </div>
            <div class="gonggam-card__body">
                <h3 class="gonggam-card__title"><a href="<?php echo $wr_href ?>"><?php echo $title ?></a></h3>
                <ul class="gonggam-card__tags">
                    <?php foreach ($tags as $t) { ?>
                    <li><?php echo get_text($t) ?></li>
                    <?php } ?>
                </ul>
                <a class="gonggam-card__more" href="<?php echo $wr_href ?>">상세 정보 보기 <span aria-hidden="true">→</span></a>
            </div>
            <div class="gonggam-card__footer">
                <div class="gonggam-card__booking">
                    <span class="gonggam-card__booking-label">BOOKING LINE</span>
                    <a class="gonggam-card__phone" href="<?php echo $tel_href ?>"><?php echo get_text($phone_display) ?></a>
                </div>
                <a class="gonggam-card__call" href="<?php echo $tel_href ?>" aria-label="전화 걸기">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z" fill="currentColor"/></svg>
                </a>
            </div>
        </article>
        <?php } ?>
    </div>

    <?php if (count($list) == 0) { ?>
    <div class="gonggam-card-empty">등록된 글이 없습니다.</div>
    <?php } ?>

    <ul class="btm_btns">
        <dd class="btm_btns_right">
            <?php if ($rss_href) { ?>
            <button type="button" name="btn_submit" class="fl_btns rss_pc" onclick="window.open('<?php echo $rss_href ?>');">RSS</button>
            <?php } ?>
            <?php if ($write_href) { ?>
            <button type="button" name="btn_submit" class="fl_btns main_color_bg" onclick="location.href='<?php echo $write_href ?>';">
                <img src="<?php echo $gonggam_img_base ?>/ico_write.svg" alt="">
                <span class="font-R">글 등록</span>
            </button>
            <?php } ?>
        </dd>
        <dd class="btm_btns_left">
        <?php if ($is_admin == 'super' || $is_auth) { ?>
            <?php if ($is_checkbox) { ?>
                <button type="submit" name="btn_submit" class="fl_btns" value="선택삭제" onclick="document.pressed=this.value"><span class="font-B">선택삭제</span></button>
                <button type="submit" name="btn_submit" class="fl_btns" value="선택복사" onclick="document.pressed=this.value"><span class="font-B">선택복사</span></button>
                <button type="submit" name="btn_submit" class="fl_btns" value="선택이동" onclick="document.pressed=this.value"><span class="font-B">선택이동</span></button>
            <?php } ?>
        <?php } ?>
        <button type="button" name="btn_submit" class="fl_btns btn_bo_sch"><span class="font-B">검색</span></button>
        </dd>
        <dd class="cb"></dd>
    </ul>

    <?php echo $write_pages; ?>

    </form>
</div>

<div class="bo_sch_wrap">
    <fieldset class="bo_sch">
        <h3>검색</h3>
        <legend>게시물 검색</legend>
        <form name="fsearch" method="get">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="sca" value="<?php echo $sca ?>">
        <input type="hidden" name="sop" value="and">
        <label for="sfl" class="sound_only">검색대상</label>
        <select name="sfl" id="sfl" class="select"><?php echo get_board_sfl_select_options($sfl); ?></select>
        <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label>
        <div class="sch_bar">
            <input type="text" name="stx" value="<?php echo stripslashes($stx); ?>" id="stx" required class="input" maxlength="20" placeholder="검색어를 입력해주세요">
            <button type="submit" value="검색" class="sch_btn" title="검색"><img src="<?php echo $gonggam_img_base ?>/ico_ser.svg" alt=""></button>
        </div>
        <button type="button" class="bo_sch_cls"><img src="<?php echo $gonggam_img_base ?>/icon_close.svg" alt="닫기"></button>
        </form>
    </fieldset>
    <div class="bo_sch_bg"></div>
</div>
<script>
$(".btn_bo_sch").on("click", function() { $(".bo_sch_wrap").toggle(); });
$('.bo_sch_bg, .bo_sch_cls').click(function(){ $('.bo_sch_wrap').hide(); });
</script>

<?php if ($is_checkbox) { ?>
<noscript><p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p></noscript>
<script>
function all_checked(sw) {
    var f = document.fboardlist;
    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]") f.elements[i].checked = sw;
    }
}
function fboardlist_submit(f) {
    var chk_count = 0;
    for (var i=0; i<f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked) chk_count++;
    }
    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }
    if(document.pressed == "선택복사") { select_copy("copy"); return; }
    if(document.pressed == "선택이동") { select_copy("move"); return; }
    if(document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
            return false;
        f.removeAttribute("target");
        f.action = g5_bbs_url+"/board_list_update.php";
    }
    return true;
}
function select_copy(sw) {
    var f = document.fboardlist;
    var str = (sw == 'copy') ? "복사" : "이동";
    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");
    f.sw.value = sw;
    f.target = "move";
    f.action = g5_bbs_url+"/move.php";
    f.submit();
}
jQuery(function($){
    $(".btn_more_opt.is_list_btn").on("click", function(e) {
        e.stopPropagation();
        $(".more_opt.is_list_btn").toggle();
    });
    $(document).on("click", function (e) {
        if(!$(e.target).closest('.is_list_btn').length) $(".more_opt.is_list_btn").hide();
    });
});
</script>
<?php } ?>

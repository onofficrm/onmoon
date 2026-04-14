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

$gonggam_thumb_w = !empty($board['bo_gallery_width']) ? (int) $board['bo_gallery_width'] : 480;
$gonggam_thumb_h = !empty($board['bo_gallery_height']) ? (int) $board['bo_gallery_height'] : 300;

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
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

    <?php if ($is_category) { ?>
    <nav id="bo_cate" class="swiper-container swiper-container-category gonggam-massage-cate">
        <ul id="bo_cate_ul" class="swiper-wrapper swiper-wrapper-category"><?php echo $category_option ?></ul>
    </nav>
    <script>
        jQuery(function($){
            $("#bo_cate_ul li").addClass("swiper-slide swiper-slide-category");
            if (typeof Swiper !== 'undefined') {
                new Swiper('.swiper-container-category', {
                    slidesPerView: 'auto',
                    spaceBetween: 0,
                    observer: true,
                    observeParents: true,
                    touchRatio: 1
                });
            }
        });
    </script>
    <?php } ?>

    <?php if ($is_admin === 'super' && $is_checkbox) { ?>
    <div class="gonggam-massage-superbar">
        <label class="gonggam-massage-superbar__chk">
            <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
            <span>전체 선택</span>
        </label>
        <div class="gonggam-massage-superbar__acts">
            <?php if ($write_href) { ?>
            <a href="<?php echo $write_href ?>" class="gonggam-massage-superbar__btn gonggam-massage-superbar__btn--primary">글 등록</a>
            <?php } ?>
            <button type="submit" name="btn_submit" class="gonggam-massage-superbar__btn" value="선택삭제" onclick="document.pressed=this.value">선택삭제</button>
            <button type="submit" name="btn_submit" class="gonggam-massage-superbar__btn" value="선택복사" onclick="document.pressed=this.value">선택복사</button>
            <button type="submit" name="btn_submit" class="gonggam-massage-superbar__btn" value="선택이동" onclick="document.pressed=this.value">선택이동</button>
        </div>
    </div>
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
                <?php if ($is_admin === 'super' && $is_checkbox) { ?>
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

    <div class="gonggam-massage-pages"><?php echo $write_pages; ?></div>

    </form>
</div>

<?php if ($is_admin === 'super' && $is_checkbox) { ?>
<noscript><p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p></noscript>
<script>
function all_checked(sw) {
    var f = document.fboardlist;
    for (var i = 0; i < f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]") f.elements[i].checked = sw;
    }
}
function fboardlist_submit(f) {
    var chk_count = 0;
    for (var i = 0; i < f.length; i++) {
        if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked) chk_count++;
    }
    if (!chk_count) {
        alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
        return false;
    }
    if (document.pressed == "선택복사") { select_copy("copy"); return false; }
    if (document.pressed == "선택이동") { select_copy("move"); return false; }
    if (document.pressed == "선택삭제") {
        if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다."))
            return false;
        f.removeAttribute("target");
        f.action = g5_bbs_url + "/board_list_update.php";
    }
    return true;
}
function select_copy(sw) {
    var f = document.fboardlist;
    var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");
    f.sw.value = sw;
    f.target = "move";
    f.action = g5_bbs_url + "/move.php";
    f.submit();
}
</script>
<?php } ?>

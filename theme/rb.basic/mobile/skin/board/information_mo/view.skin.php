<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once(G5_LIB_PATH.'/thumbnail.lib.php');

$hp_uri_dec = !empty($_SERVER['REQUEST_URI']) ? rawurldecode($_SERVER['REQUEST_URI']) : '';
$hp_visa_landing = !empty($board['bo_subject']) && @preg_match('/비자\s*정보/u', $board['bo_subject']);
if (!$hp_visa_landing && $hp_uri_dec !== '') {
    $hp_visa_landing = (bool) @preg_match('/비자정보/u', $hp_uri_dec);
}
$hp_tax_landing = !empty($board['bo_subject']) && @preg_match('/세무\s*정보/u', $board['bo_subject']);
if (!$hp_tax_landing && $hp_uri_dec !== '') {
    $hp_tax_landing = (bool) @preg_match('/세무정보/u', $hp_uri_dec);
}
$hp_biz_landing = !empty($board['bo_subject']) && @preg_match('/사업\s*관련\s*정보/u', $board['bo_subject']);
if (!$hp_biz_landing && $hp_uri_dec !== '') {
    $hp_biz_landing = (bool) @preg_match('/사업관련정보/u', $hp_uri_dec);
}
$hp_info_landing = $hp_visa_landing || $hp_tax_landing || $hp_biz_landing;
$hp_super_admin_view = (isset($is_admin) && $is_admin === 'super');

$boon_build_kakao_url = '';
if (isset($rb_builder) && is_array($rb_builder)) {
    $boon_build_kakao_url = !empty($rb_builder['bu_sns2']) ? $rb_builder['bu_sns2'] : (!empty($rb_builder['bu_sns1']) ? $rb_builder['bu_sns1'] : '');
}
$hp_kakao_url = $boon_build_kakao_url;
$hp_phone_href = '';
$hp_phone_label = '전화 문의하기';
if (!empty($config['cf_phone'])) {
    $hp_digits = preg_replace('/[^0-9+]/', '', $config['cf_phone']);
    if ($hp_digits !== '') {
        $hp_phone_href = 'tel:' . $hp_digits;
        $hp_phone_label = $config['cf_phone'];
    }
}

$hp_hide_editor_block = false;
if ($hp_info_landing) {
    $hp_tp = trim(preg_replace('/\s+/u', ' ', strip_tags($view['content'])));
    $hp_ts = trim(get_text($view['wr_subject']));
    $hp_has_img = (bool) preg_match('/<img\\b/i', $view['content']);
    if (($hp_tp === '' || $hp_tp === $hp_ts) && !$hp_has_img) {
        $hp_hide_editor_block = true;
    }
}

add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
if ($hp_info_landing && defined('G5_THEME_URL')) {
    add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_URL.'/skin/board/information_bbs/hp-visa.css">', 0);
}
if ($hp_info_landing) {
    add_stylesheet('<link rel="preconnect" href="https://fonts.googleapis.com">', 0);
    add_stylesheet('<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>', 0);
    add_stylesheet('<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">', 0);
}
?>

<script src="<?php echo G5_JS_URL; ?>/viewimageresize.js"></script>

<ul class="btn_top top btn_bo_user info-page-view__top<?php echo $hp_info_landing ? ' hp-visa-mo-toolbar' : ''; ?>">
    <?php if ($hp_super_admin_view && $write_href && !$hp_info_landing) { ?><li><a href="<?php echo $write_href ?>" class="btn_b03 btn" title="글쓰기"><i class="fa fa-pencil" aria-hidden="true"></i><span class="sound_only">글쓰기</span></a></li><?php } ?>
    <?php if ($hp_super_admin_view && ($update_href || $delete_href || $copy_href || $move_href || ($hp_info_landing && $write_href))) { ?>
	<li>
		<button type="button" class="btn_more_opt btn_b03 btn is_view_btn" title="옵션"><i class="fa fa-ellipsis-v" aria-hidden="true"></i><span class="sound_only">옵션</span></button>
    	<?php ob_start(); ?>
	    <ul class="more_opt is_view_btn">
            <?php if ($hp_info_landing && $write_href) { ?><li><a href="<?php echo $write_href ?>"><i class="fa fa-pencil" aria-hidden="true"></i> 글 등록</a></li><?php } ?>
			<?php if ($update_href) { ?><li><a href="<?php echo $update_href ?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 수정</a></li><?php } ?>
	    	<?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>" onclick="del(this.href); return false;"><i class="fa fa-trash-o" aria-hidden="true"></i> 삭제</a></li><?php } ?>
	    	<?php if ($copy_href) { ?><li><a href="<?php echo $copy_href ?>" onclick="board_move(this.href); return false;"><i class="fa fa-files-o" aria-hidden="true"></i> 복사</a></li><?php } ?>
	    	<?php if ($move_href) { ?><li><a href="<?php echo $move_href ?>" onclick="board_move(this.href); return false;"><i class="fa fa-arrows" aria-hidden="true"></i> 이동</a></li><?php } ?>
		</ul>
		<?php $link_buttons = ob_get_contents(); ob_end_flush(); ?>
	</li>
    <?php } ?>
</ul>
<script>
jQuery(function($){
    $(".btn_more_opt.is_view_btn").on("click", function(e) {
        e.stopPropagation();
        $(".more_opt.is_view_btn").toggle();
    });
    $(document).on("click", function (e) {
        if(!$(e.target).closest('.is_view_btn').length) {
            $(".more_opt.is_view_btn").hide();
        }
    });
});
</script>
<article id="bo_v" class="info-page-view<?php echo $hp_info_landing ? ' hp-info-landing' : ''; ?><?php echo $hp_visa_landing ? ' hp-info-landing--visa' : ''; ?><?php echo $hp_tax_landing ? ' hp-info-landing--tax' : ''; ?><?php echo $hp_biz_landing ? ' hp-info-landing--biz' : ''; ?><?php echo ($hp_info_landing && !empty($hp_hide_editor_block)) ? ' hp-info--no-editor' : ''; ?>" style="width:<?php echo $width; ?>">

    <?php if ($hp_visa_landing) { ?>
    <section class="hp-visa-hero hp-visa-hero--mo" aria-labelledby="hp-visa-hero-title-mo">
        <div class="hp-visa-hero__bg" aria-hidden="true"></div>
        <div class="hp-visa-hero__grid">
            <div class="hp-visa-hero__copy">
                <p class="hp-visa-badge"><span class="hp-visa-badge__dot" aria-hidden="true"></span> 필리핀 이민국 정식 등록 대행사 (CEBU)</p>
                <h1 id="hp-visa-hero-title-mo" class="hp-visa-hero__title"><?php echo get_text($view['wr_subject']); ?></h1>
                <p class="hp-visa-hero__subtitle">복잡한 필리핀 비자, <strong>한필</strong>이 가장 쉽고 정확하게 처리해 드립니다.</p>
                <p class="hp-visa-hero__desc">세부 현지 이민국과의 네트워크를 통해 빠르고 안전한 비자 업무를 약속드립니다.</p>
                <div class="hp-visa-hero__cta">
                    <?php if ($hp_kakao_url) { ?>
                    <a href="<?php echo htmlspecialchars($hp_kakao_url, ENT_QUOTES, 'UTF-8'); ?>" class="hp-visa-btn hp-visa-btn--kakao" target="_blank" rel="noopener noreferrer">카카오톡 상담하기</a>
                    <?php } ?>
                    <?php if ($hp_phone_href) { ?>
                    <a href="<?php echo htmlspecialchars($hp_phone_href, ENT_QUOTES, 'UTF-8'); ?>" class="hp-visa-btn hp-visa-btn--outline"><?php echo get_text($hp_phone_label); ?></a>
                    <?php } ?>
                </div>
                <p class="hp-visa-hero__trust"><span class="hp-visa-hero__trust-n">2,300+</span> 세부 교민들이 선택한 서비스</p>
            </div>
        </div>
    </section>
    <?php } elseif ($hp_tax_landing) { ?>
    <section class="hp-visa-hero hp-visa-hero--mo" aria-labelledby="hp-tax-hero-title-mo">
        <div class="hp-visa-hero__bg" aria-hidden="true"></div>
        <div class="hp-visa-hero__grid">
            <div class="hp-visa-hero__copy">
                <p class="hp-visa-badge"><span class="hp-visa-badge__dot" aria-hidden="true"></span> 필리핀 BIR 기준 전문 회계·세무 (CEBU)</p>
                <h1 id="hp-tax-hero-title-mo" class="hp-visa-hero__title"><?php echo get_text($view['wr_subject']); ?></h1>
                <p class="hp-visa-hero__subtitle">복잡한 필리핀 세무·회계, <strong>한필</strong>이 정확하고 투명하게 관리해 드립니다.</p>
                <p class="hp-visa-hero__desc">세부 현지 회계 기준과 BIR 규정에 맞춘 기장·신고·감사를 약속드립니다.</p>
                <div class="hp-visa-hero__cta">
                    <?php if ($hp_kakao_url) { ?>
                    <a href="<?php echo htmlspecialchars($hp_kakao_url, ENT_QUOTES, 'UTF-8'); ?>" class="hp-visa-btn hp-visa-btn--kakao" target="_blank" rel="noopener noreferrer">카카오톡 상담하기</a>
                    <?php } ?>
                    <?php if ($hp_phone_href) { ?>
                    <a href="<?php echo htmlspecialchars($hp_phone_href, ENT_QUOTES, 'UTF-8'); ?>" class="hp-visa-btn hp-visa-btn--outline"><?php echo get_text($hp_phone_label); ?></a>
                    <?php } ?>
                </div>
                <p class="hp-visa-hero__trust"><span class="hp-visa-hero__trust-n">2,300+</span> 세부 교민들이 선택한 서비스</p>
            </div>
        </div>
    </section>
    <?php } elseif ($hp_biz_landing) { ?>
    <section class="hp-visa-hero hp-visa-hero--mo" aria-labelledby="hp-biz-hero-title-mo">
        <div class="hp-visa-hero__bg" aria-hidden="true"></div>
        <div class="hp-visa-hero__grid">
            <div class="hp-visa-hero__copy">
                <p class="hp-visa-badge"><span class="hp-visa-badge__dot" aria-hidden="true"></span> 필리핀 비즈니스 통합 지원 센터 (CEBU)</p>
                <h1 id="hp-biz-hero-title-mo" class="hp-visa-hero__title"><?php echo get_text($view['wr_subject']); ?></h1>
                <p class="hp-visa-hero__subtitle">필리핀 법인 설립부터 인허가까지, <strong>한필</strong>이 가장 빠르고 안전하게 안내합니다.</p>
                <p class="hp-visa-hero__desc">현지 법률 및 행정 전문가 그룹이 성공적인 필리핀 비즈니스 안착을 지원합니다.</p>
                <div class="hp-visa-hero__cta">
                    <?php if ($hp_kakao_url) { ?>
                    <a href="<?php echo htmlspecialchars($hp_kakao_url, ENT_QUOTES, 'UTF-8'); ?>" class="hp-visa-btn hp-visa-btn--kakao" target="_blank" rel="noopener noreferrer">카카오톡 상담하기</a>
                    <?php } ?>
                    <?php if ($hp_phone_href) { ?>
                    <a href="<?php echo htmlspecialchars($hp_phone_href, ENT_QUOTES, 'UTF-8'); ?>" class="hp-visa-btn hp-visa-btn--outline"><?php echo get_text($hp_phone_label); ?></a>
                    <?php } ?>
                </div>
                <p class="hp-visa-hero__trust"><span class="hp-visa-hero__trust-n">2,300+</span> 세부 교민들이 선택한 서비스</p>
            </div>
        </div>
    </section>
    <?php } ?>

    <header<?php echo $hp_info_landing ? ' class="sound_only"' : ''; ?>>
        <h2 id="bo_v_title">
            <?php if ($category_name) { ?>
            <span class="bo_v_cate"><?php echo $view['ca_name']; ?></span>
            <?php } ?>
            <span class="bo_v_tit"><?php echo cut_str(get_text($view['wr_subject']), 70); ?></span>
        </h2>
    </header>

    <?php
    $cnt = 0;
    if ($view['file']['count']) {
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
                $cnt++;
        }
    }
	?>

    <?php if($cnt) { ?>
    <section id="bo_v_file" class="<?php echo $hp_info_landing ? 'hp-visa-mo-file' : ''; ?>">
        <h2>첨부파일</h2>
        <ul>
        <?php
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
         ?>
            <li>
                <a href="<?php echo $view['file'][$i]['href'];  ?>" class="view_file_download">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    <strong><?php echo $view['file'][$i]['source'] ?></strong>
                    <?php echo $view['file'][$i]['content'] ?> (<?php echo $view['file'][$i]['size'] ?>)
                </a>
                <span class="bo_v_file_cnt"><?php echo $view['file'][$i]['download'] ?>회 다운로드</span> |
                <span>DATE : <?php echo $view['file'][$i]['datetime'] ?></span>
            </li>
        <?php
            }
        }
         ?>
        </ul>
    </section>
    <?php } ?>

    <?php if(isset($view['link']) && array_filter($view['link'])) { ?>
    <section id="bo_v_link" class="<?php echo $hp_info_landing ? 'hp-visa-mo-link' : ''; ?>">
        <h2>관련링크</h2>
        <ul>
        <?php
        for ($i=1; $i<=count($view['link']); $i++) {
            if ($view['link'][$i]) {
                $link = cut_str($view['link'][$i], 70);
         ?>
            <li>
                <a href="<?php echo $view['link_href'][$i] ?>" target="_blank" rel="noopener noreferrer">
                    <i class="fa fa-link" aria-hidden="true"></i>
                    <strong><?php echo $link ?></strong>
                </a>
                <span class="bo_v_link_cnt"><?php echo $view['link_hit'][$i] ?>회 연결</span>
            </li>
        <?php
            }
        }
         ?>
        </ul>
    </section>
    <?php } ?>

    <?php
    $v_img_count = count($view['file']);
    $hp_mo_show_atc = !$hp_hide_editor_block || $v_img_count > 0 || $is_signature;
    ?>
    <?php if ($hp_mo_show_atc) { ?>
    <section id="bo_v_atc" class="<?php echo $hp_info_landing ? 'hp-visa-prose-wrap' : ''; ?>">
        <h2 id="bo_v_atc_title" class="sound_only">본문</h2>

        <?php
        if($v_img_count) {
            echo "<div id=\"bo_v_img\">\n";
            foreach($view['file'] as $view_file) {
                echo get_file_thumbnail($view_file);
            }
            echo "</div>\n";
		}
		?>

        <?php if (!$hp_hide_editor_block) { ?>
        <div id="bo_v_con" class="<?php echo $hp_info_landing ? 'hp-visa-prose' : ''; ?>"><?php echo get_view_thumbnail($view['content']); ?></div>
        <?php } ?>

        <?php if ($is_signature) { ?><p><?php echo $signature ?></p><?php } ?>
    </section>
    <?php } ?>

    <?php
    if (defined('G5_THEME_PATH')) {
        if ($hp_visa_landing) {
            $hp_visa_inc = G5_THEME_PATH.'/skin/board/information_bbs/visa_landing.inc.php';
            if (is_file($hp_visa_inc)) {
                include $hp_visa_inc;
            }
        } elseif ($hp_tax_landing) {
            $hp_tax_inc = G5_THEME_PATH.'/skin/board/information_bbs/tax_landing.inc.php';
            if (is_file($hp_tax_inc)) {
                include $hp_tax_inc;
            }
        } elseif ($hp_biz_landing) {
            $hp_biz_inc = G5_THEME_PATH.'/skin/board/information_bbs/business_landing.inc.php';
            if (is_file($hp_biz_inc)) {
                include $hp_biz_inc;
            }
        }
    }
    ?>

    <?php if ($hp_info_landing) { ?>
    <script>
    (function () {
        document.querySelectorAll('.hp-info-landing .hp-visa-faq__list .hp-visa-faq__q').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var item = btn.closest('.hp-visa-faq__item');
                var panel = document.getElementById(btn.getAttribute('aria-controls'));
                var isOpen = item && item.classList.contains('is-open');
                if (item) item.classList.toggle('is-open', !isOpen);
                btn.setAttribute('aria-expanded', isOpen ? 'false' : 'true');
                if (panel) {
                    if (isOpen) panel.setAttribute('hidden', '');
                    else panel.removeAttribute('hidden');
                }
            });
        });
    })();
    </script>
    <?php } ?>

</article>

<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $("a.view_file_download").click(function() {
        if(!g5_is_member) {
            alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
            return false;
        }

        var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

        if(confirm(msg)) {
            var href = $(this).attr("href")+"&js=on";
            $(this).attr("href", href);

            return true;
        } else {
            return false;
        }
    });
});
<?php } ?>

function board_move(href)
{
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });
    $("#bo_v_atc").viewimageresize();
});
</script>

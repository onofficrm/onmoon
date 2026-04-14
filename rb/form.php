<?php
include_once('./_common.php');

$fr_id = isset($_GET['fr_id']) ? preg_replace('/[^a-z0-9_]/i', '', $_GET['fr_id']) : 0;


// 레벨확인
if(!$is_admin) {

    if(isset($fr['fr_level']) && $fr['fr_level'] > 0) {
        
        if(isset($fr['fr_level_opt']) && $fr['fr_level_opt'] == 2) {
            if (isset($fr['fr_level']) && $fr['fr_level'] != $member['mb_level']) {
                alert('권한이 없습니다.', G5_URL);
            }
        } else { 
            if (isset($fr['fr_level']) && $fr['fr_level'] > $member['mb_level']) {
                alert('권한이 없습니다.', G5_URL);
            }
        }
        
    }
}



if (! (isset($fr['fr_id']) && $fr['fr_id']))
    alert('등록된 내용이 없습니다.');

$g5['title'] = $fr['fr_subject'];

if ($fr['fr_include_head'] && is_include_path_check($fr['fr_include_head']))
    @include_once($fr['fr_include_head']);
else
    include_once('../_head.php');

// KVE-2019-0828 취약점 내용
$fr['fr_tag_filter_use'] = 1;
$str = conv_content($fr['fr_content'], $fr['fr_html'], $fr['fr_tag_filter_use']);

// $src 를 $dst 로 변환
$src = $dst = array();
$src[] = "/{{쇼핑몰명}}|{{홈페이지제목}}/";
$dst[] = $config['cf_title'];
if(isset($default) && isset($default['de_admin_company_name'])){
    $src[] = "/{{회사명}}|{{상호}}/";
    $dst[] = isset($default['de_admin_company_name']) ? $default['de_admin_company_name'] : '';
    $src[] = "/{{대표자명}}/";
    $dst[] = isset($default['de_admin_company_owner']) ? $default['de_admin_company_owner'] : '';
    $src[] = "/{{사업자등록번호}}/";
    $dst[] = isset($default['de_admin_company_saupja_no']) ? $default['de_admin_company_saupja_no'] : '';
    $src[] = "/{{대표전화번호}}/";
    $dst[] = isset($default['de_admin_company_tel']) ? $default['de_admin_company_tel'] : '';
    $src[] = "/{{팩스번호}}/";
    $dst[] = isset($default['de_admin_company_fax']) ? $default['de_admin_company_fax'] : '';
    $src[] = "/{{통신판매업신고번호}}/";
    $dst[] = isset($default['de_admin_company_tongsin_no']) ? $default['de_admin_company_tongsin_no'] : '';
    $src[] = "/{{사업장우편번호}}/";
    $dst[] = isset($default['de_admin_company_zip']) ? $default['de_admin_company_zip'] : '';
    $src[] = "/{{사업장주소}}/";
    $dst[] = isset($default['de_admin_company_addr']) ? $default['de_admin_company_addr'] : '';
    $src[] = "/{{운영자명}}|{{관리자명}}/";
    $dst[] = isset($default['de_admin_name']) ? $default['de_admin_name'] : '';
    $src[] = "/{{운영자e-mail}}|{{관리자e-mail}}/i";
    $dst[] = isset($default['de_admin_email']) ? $default['de_admin_email'] : '';
    $src[] = "/{{정보관리책임자명}}/";
    $dst[] = isset($default['de_admin_info_name']) ? $default['de_admin_info_name'] : '';
    $src[] = "/{{정보관리책임자e-mail}}|{{정보책임자e-mail}}/i";
    $dst[] = isset($default['de_admin_info_email']) ? $default['de_admin_info_email'] : '';
}
$str = preg_replace($src, $dst, $str);

if ($is_admin)
    echo run_replace('form_admin_button_html', '<div class="ctt_admin"><a href="'.G5_ADMIN_URL.'/rb/form.php?w=u&amp;fr_id='.$fr_id.'" class="btn_admin btn"><span class="sound_only">수정</span><i class="fa fa-cog fa-spin fa-fw"></i></a></div>', $fr);
?>

<?php

    $himg = G5_DATA_PATH.'/form/'.$fr_id.'_h';
    if (file_exists($himg)) // 상단 이미지
        echo run_replace('form_head_image_html', '<div id="ctt_himg" class="ctt_img"><img src="'.G5_DATA_URL.'/form/'.$fr_id.'_h" alt=""></div><br>', $fr);

    include(G5_PATH.'/rb/rb.mod/form/form.skin.php');

    $timg = G5_DATA_PATH.'/form/'.$fr_id.'_t';
    if (file_exists($timg)) // 하단 이미지
        echo run_replace('form_tail_image_html', '<br><div id="ctt_timg" class="ctt_img"><img src="'.G5_DATA_URL.'/form/'.$fr_id.'_t" alt=""></div>', $fr);


if ($fr['fr_include_tail'] && is_include_path_check($fr['fr_include_tail']))
    @include_once($fr['fr_include_tail']);
else
    include_once('../_tail.php');
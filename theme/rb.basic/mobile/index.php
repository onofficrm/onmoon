<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if(G5_COMMUNITY_USE === false) {
    include_once(G5_THEME_MSHOP_PATH.'/index.php');
    return;
}

add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_URL.'/boon-build/home.css?ver='.filemtime(G5_THEME_PATH.'/boon-build/home.css').'" />', 0);

include_once(G5_THEME_MOBILE_PATH.'/head.php');
?>

<?php include_once(G5_THEME_PATH.'/boon-build/home.php'); ?>

<?php
include_once(G5_THEME_MOBILE_PATH.'/tail.php');
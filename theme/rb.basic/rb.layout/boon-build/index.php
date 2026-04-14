<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_stylesheet('<link rel="stylesheet" href="'.G5_THEME_URL.'/rb.layout/boon-build/style.css?ver='.filemtime(G5_THEME_PATH.'/rb.layout/boon-build/style.css').'" />', 0);

include_once(G5_THEME_PATH.'/boon-build/home.php');

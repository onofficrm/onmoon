<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

add_replace('admin_menu', 'add_admin_bbs_menu_form', 0, 1); // 관리자 메뉴를 추가함

function add_admin_bbs_menu_form($admin_menu){ // 메뉴추가
    
    $admin_menu['menu000'][] = array('000740', '폼 관리', G5_ADMIN_URL.'/rb/form_list.php', 'rb_config');
    $admin_menu['menu000'][] = array('000741', '폼 접수현황', G5_ADMIN_URL.'/rb/form_add_list.php', 'rb_config');
    
    $admin_menu['menu000'][] = array('000000', '　', G5_ADMIN_URL, 'rb_config');
    return $admin_menu;
}

// $fr_id 정의 여부 확인 후 기본값 설정 (PHP8 대응)
$fr_id = isset($fr_id) ? $fr_id : '';

// SQL 인젝션 방지 및 비어있지 않을 때만 쿼리 실행
$fr = array(); // 기본값 설정
if ($fr_id) {
    $fr_id = sql_real_escape_string($fr_id);
    $fr = sql_fetch("SELECT * FROM rb_form WHERE fr_id = '{$fr_id}'");
    if (!is_array($fr)) $fr = array(); // 결과가 배열이 아닐 경우 초기화
}
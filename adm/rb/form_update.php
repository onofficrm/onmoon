<?php
$sub_menu = '000740';
require_once './_common.php';

if ($w == "u" || $w == "d") {
    check_demo();
}

if ($w == 'd') {
    auth_check_menu($auth, $sub_menu, "d");
} else {
    auth_check_menu($auth, $sub_menu, "w");
}

check_admin_token();


// 폼 항목 저장용 서브 테이블 rb_form_field 자동 생성
$sqls = "SHOW TABLES LIKE 'rb_form_field'";
$rows = sql_fetch($sqls);

if (!$rows) {
    sql_query("
        CREATE TABLE `rb_form_field` (
            `ff_id` INT NOT NULL AUTO_INCREMENT,
            `fr_id` VARCHAR(20) NOT NULL,
            `ff_type` VARCHAR(20) NOT NULL,
            `ff_label` VARCHAR(255) NOT NULL,
            `ff_name` VARCHAR(100) NOT NULL,
            `ff_options` TEXT,
            `ff_placeholder` TEXT,
            `ff_required` TINYINT(1) DEFAULT 0,
            `ff_order` INT DEFAULT 0,
            PRIMARY KEY (`ff_id`),
            KEY (`fr_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");
}

if (!sql_query("SELECT ff_file_ext FROM rb_form_field LIMIT 1", false)) {
    sql_query("ALTER TABLE `rb_form_field` 
        ADD `ff_file_ext` VARCHAR(255) NOT NULL DEFAULT '' AFTER `ff_options`,
        ADD `ff_file_size` INT NOT NULL DEFAULT 0 AFTER `ff_file_ext`", true);
}



$fr_row = array('fr_id' => '', 'fr_include_head' => '', 'fr_include_tail' => '');

if ($w == "" || $w == "u") {
    if (isset($_REQUEST['fr_id']) && preg_match("/[^a-z0-9_]/i", $_REQUEST['fr_id'])) {
        alert("ID 는 영문자, 숫자, _ 만 가능합니다.");
    }

    $sql = " select * from rb_form where fr_id = '$fr_id' ";
    $fr_row = sql_fetch($sql);
}

$fr_id = isset($_REQUEST['fr_id']) ? preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['fr_id']) : '';
$fr_subject = isset($_POST['fr_subject']) ? strip_tags(clean_xss_attributes($_POST['fr_subject'])) : '';
$fr_level = isset($_POST['fr_level']) ? strip_tags(clean_xss_attributes($_POST['fr_level'])) : '1';
$fr_level_opt = isset($_POST['fr_level_opt']) ? strip_tags(clean_xss_attributes($_POST['fr_level_opt'])) : '1';
$fr_include_head = isset($_POST['fr_include_head']) ? preg_replace(array("#[\\\]+$#", "#(<\?php|<\?)#i"), "", substr($_POST['fr_include_head'], 0, 255)) : '';
$fr_include_tail = isset($_POST['fr_include_tail']) ? preg_replace(array("#[\\\]+$#", "#(<\?php|<\?)#i"), "", substr($_POST['fr_include_tail'], 0, 255)) : '';
$fr_tag_filter_use = isset($_POST['fr_tag_filter_use']) ? (int) $_POST['fr_tag_filter_use'] : 1;
$fr_himg_del = (isset($_POST['fr_himg_del']) && $_POST['fr_himg_del']) ? 1 : 0;
$fr_timg_del = (isset($_POST['fr_timg_del']) && $_POST['fr_timg_del']) ? 1 : 0;
$fr_html = isset($_POST['fr_html']) ? (int) $_POST['fr_html'] : 0;
$fr_content = isset($_POST['fr_content']) ? $_POST['fr_content'] : '';
$fr_mobile_content = isset($_POST['fr_mobile_content']) ? $_POST['fr_mobile_content'] : '';
$fr_skin = isset($_POST['fr_skin']) ? clean_xss_tags($_POST['fr_skin'], 1, 1) : '';
$fr_mobile_skin = isset($_POST['fr_mobile_skin']) ? clean_xss_tags($_POST['fr_mobile_skin'], 1, 1) : '';
$fr_file_ext = isset($_POST['fr_file_ext']) ? strip_tags(trim($_POST['fr_file_ext'])) : '';
$fr_file_size = isset($_POST['fr_file_size']) ? (int)$_POST['fr_file_size'] : 0;

$fr_privacy_use = isset($_POST['fr_privacy_use']) ? 1 : 0;
$fr_privacy_required = isset($_POST['fr_privacy_required']) ? 1 : 0;
$fr_privacy_text = isset($_POST['fr_privacy_text']) ? strip_tags($_POST['fr_privacy_text'], '<br><p><ul><li>') : '';

// 관리자가 자동등록방지를 사용해야 할 경우
if (((isset($fr_row['fr_include_head']) && $fr_row['fr_include_head'] !== $fr_include_head) || (isset($fr_row['fr_include_tail']) && $fr_row['fr_include_tail'] !== $fr_include_tail)) && function_exists('get_admin_captcha_by') && get_admin_captcha_by()) {
    include_once G5_CAPTCHA_PATH . '/captcha.lib.php';

    if (!chk_captcha()) {
        alert('자동등록방지 숫자가 틀렸습니다.');
    }
}

@mkdir(G5_DATA_PATH . "/form", G5_DIR_PERMISSION);
@chmod(G5_DATA_PATH . "/form", G5_DIR_PERMISSION);

if ($fr_himg_del) {
    @unlink(G5_DATA_PATH . "/form/{$fr_id}_h");
}
if ($fr_timg_del) {
    @unlink(G5_DATA_PATH . "/form/{$fr_id}_t");
}

$error_msg = '';

if ($fr_include_head) {
    $file_ext = pathinfo($fr_include_head, PATHINFO_EXTENSION);

    if (!$file_ext || !in_array($file_ext, array('php', 'htm', 'html')) || !preg_match('/^.*\.(php|htm|html)$/i', $fr_include_head)) {
        alert('상단 파일 경로의 확장자는 php, htm, html 만 허용합니다.');
    }
}

if ($fr_include_tail) {
    $file_ext = pathinfo($fr_include_tail, PATHINFO_EXTENSION);

    if (!$file_ext || !in_array($file_ext, array('php', 'htm', 'html')) || !preg_match('/^.*\.(php|htm|html)$/i', $fr_include_tail)) {
        alert('하단 파일 경로의 확장자는 php, htm, html 만 허용합니다.');
    }
}

if ($fr_include_head && !is_include_path_check($fr_include_head, 1)) {
    $fr_include_head = '';
    $error_msg = '/data/file/ 또는 /data/editor/ 포함된 문자를 상단 파일 경로에 포함시킬수 없습니다.';
}

if ($fr_include_tail && !is_include_path_check($fr_include_tail, 1)) {
    $fr_include_tail = '';
    $error_msg = '/data/file/ 또는 /data/editor/ 포함된 문자를 하단 파일 경로에 포함시킬수 없습니다.';
}

if (function_exists('filter_input_include_path')) {
    $fr_include_head = filter_input_include_path($fr_include_head);
    $fr_include_tail = filter_input_include_path($fr_include_tail);
}


$sql_common = " fr_include_head     = '$fr_include_head',
                fr_include_tail     = '$fr_include_tail',
                fr_html             = '$fr_html',
                fr_tag_filter_use   = '$fr_tag_filter_use',
                fr_subject          = '$fr_subject',
                fr_level            = '$fr_level',
                fr_level_opt        = '$fr_level_opt',
                fr_content          = '$fr_content',
                fr_mobile_content   = '$fr_mobile_content',
                fr_skin             = '$fr_skin',
                fr_mobile_skin      = '$fr_mobile_skin',
                fr_privacy_use = '$fr_privacy_use',
                fr_privacy_required = '$fr_privacy_required',
                fr_file_ext = '{$fr_file_ext}',
                fr_file_size = '{$fr_file_size}',
                fr_privacy_text = '$fr_privacy_text' ";

if ($w == "") {
    $row = $fr_row;
    if (isset($row['fr_id']) && $row['fr_id']) {
        alert("이미 같은 ID로 등록된 내용이 있습니다.");
    }

    $sql = " insert rb_form
                set fr_id = '$fr_id',
                    $sql_common ";
    sql_query($sql);
    run_event('admin_form_created', $fr_id);
    

    sql_query("DELETE FROM rb_form_field WHERE fr_id = '$fr_id'");

    if (isset($_POST['fields']) && is_array($_POST['fields'])) {
        foreach ($_POST['fields'] as $field) {
            $ff_type = preg_replace('/[^a-z]/', '', $field['type']);
            $ff_label = trim(strip_tags($field['label']));
            $ff_name = trim(preg_replace('/[^a-z0-9_]/i', '', $field['name']));
            $ff_options = isset($field['options']) ? strip_tags($field['options']) : '';
            $ff_placeholder = isset($field['placeholder']) ? strip_tags($field['placeholder']) : '';
            $ff_required = isset($field['required']) ? 1 : 0;

            if ($ff_type && $ff_label && $ff_name) {
                sql_query("INSERT INTO rb_form_field 
                    SET fr_id = '$fr_id',
                        ff_type = '$ff_type',
                        ff_label = '$ff_label',
                        ff_name = '$ff_name',
                        ff_options = '$ff_options',
                        ff_placeholder = '$ff_placeholder',
                        ff_required = '$ff_required'");
            }
        }
    }

} elseif ($w == "u") {
    $sql = " update rb_form
                set $sql_common
              where fr_id = '$fr_id' ";
    sql_query($sql);
    run_event('admin_form_updated', $fr_id);
    
    
    sql_query("DELETE FROM rb_form_field WHERE fr_id = '$fr_id'");

    if (isset($_POST['fields']) && is_array($_POST['fields'])) {
        foreach ($_POST['fields'] as $field) {
            $ff_type = preg_replace('/[^a-z]/', '', $field['type']);
            $ff_label = trim(strip_tags($field['label']));
            $ff_name = trim(preg_replace('/[^a-z0-9_]/i', '', $field['name']));
            $ff_options = isset($field['options']) ? strip_tags($field['options']) : '';
            $ff_placeholder = isset($field['placeholder']) ? strip_tags($field['placeholder']) : '';
            $ff_required = isset($field['required']) ? 1 : 0;

            if ($ff_type && $ff_label && $ff_name) {
                sql_query("INSERT INTO rb_form_field 
                    SET fr_id = '$fr_id',
                        ff_type = '$ff_type',
                        ff_label = '$ff_label',
                        ff_name = '$ff_name',
                        ff_options = '$ff_options',
                        ff_placeholder = '$ff_placeholder',
                        ff_required = '$ff_required'");
            }
        }
    }
    
} elseif ($w == "d") {
    @unlink(G5_DATA_PATH . "/form/{$fr_id}_h");
    @unlink(G5_DATA_PATH . "/form/{$fr_id}_t");

    $sql = " delete from rb_form where fr_id = '$fr_id' ";
    sql_query($sql);
    run_event('admin_form_deleted', $fr_id);
}






if (function_exists('get_admin_captcha_by')) {
    get_admin_captcha_by('remove');
}

g5_delete_cache_by_prefix('form-' . $fr_id . '-');

if ($w == "" || $w == "u") {
    if ($_FILES['fr_himg']['name']) {
        $dest_path = G5_DATA_PATH . "/form/" . $fr_id . "_h";
        @move_uploaded_file($_FILES['fr_himg']['tmp_name'], $dest_path);
        @chmod($dest_path, G5_FILE_PERMISSION);
    }
    if ($_FILES['fr_timg']['name']) {
        $dest_path = G5_DATA_PATH . "/form/" . $fr_id . "_t";
        @move_uploaded_file($_FILES['fr_timg']['tmp_name'], $dest_path);
        @chmod($dest_path, G5_FILE_PERMISSION);
    }

    if ($error_msg) {
        alert($error_msg, "./form.php?w=u&amp;fr_id=$fr_id");
    } else {
        goto_url("./form.php?w=u&amp;fr_id=$fr_id");
    }
} else {
    goto_url("./form_list.php");
}

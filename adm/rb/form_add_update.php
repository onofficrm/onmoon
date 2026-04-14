<?php
$sub_menu = '000741';
require_once './_common.php';

$fs_id = isset($_REQUEST['fs_id']) ? preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['fs_id']) : '';
check_demo();

if ($w == "d") {
    
    auth_check_menu($auth, $sub_menu, "d");

    // fr_id 조회
    $fs = sql_fetch("SELECT fr_id FROM rb_form_submit WHERE fs_id = '$fs_id'");
    $fr_id = '';

    if (isset($fs['fr_id'])) {
        $fr_id = $fs['fr_id'];
    }

    // fr_id 와 fs_id 모두 값이 있을 때만 삭제 실행
    if ($fr_id !== '' && $fs_id !== '') {
        $base_path = G5_DATA_PATH . '/form';
        $target_dir = $base_path . '/' . $fr_id . '/' . $fs_id;

        // 디렉토리 삭제 함수
        function rb_delete_dir($dir) {
            if (!is_dir($dir)) {
                return;
            }

            $files = scandir($dir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..') {
                    continue;
                }

                $path = $dir . '/' . $file;

                if (is_dir($path)) {
                    rb_delete_dir($path);
                } else {
                    @unlink($path);
                }
            }

            @rmdir($dir);
        }

        rb_delete_dir($target_dir);
    }

    // 접수 정보 삭제
    $sql = "DELETE FROM rb_form_submit WHERE fs_id = '$fs_id'";
    sql_query($sql);
    
}

check_admin_token();

goto_url("./form_add_list.php");
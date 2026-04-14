<?php
include_once('../../../../../../common.php');
if (!defined('_GNUBOARD_')) exit;

// JSON 응답
header('Content-Type: application/json; charset=utf-8');

// 권한 체크 (필요에 따라 조정)
if ($is_admin !== 'super' && !$is_auth) {
    echo json_encode(array('ok' => false, 'message' => '권한이 없습니다.'));
    exit;
}

$bo_table  = isset($_POST['bo_table']) ? preg_replace('/[^a-z0-9_]/i', '', $_POST['bo_table']) : '';
$chk_wr_id = isset($_POST['chk_wr_id']) && is_array($_POST['chk_wr_id']) ? $_POST['chk_wr_id'] : array();

// extra[wr_id][wr_1], extra[wr_id][wr_2] ...
$extra      = isset($_POST['extra']) && is_array($_POST['extra']) ? $_POST['extra'] : array();

// 개별 필드 (체크된 행에서만 넘어옴)
$wr_datetime    = isset($_POST['wr_datetime']) && is_array($_POST['wr_datetime']) ? $_POST['wr_datetime'] : array();
$wr_subject = isset($_POST['wr_subject']) && is_array($_POST['wr_subject']) ? $_POST['wr_subject'] : array();
$ca_name = isset($_POST['ca_name']) && is_array($_POST['ca_name']) ? $_POST['ca_name'] : array();

if ($bo_table === '' || !count($chk_wr_id)) {
    echo json_encode(array('ok' => false, 'message' => '저장할 데이터가 없습니다.'));
    exit;
}

$board = get_board_db($bo_table, true);
if (!$board || !$board['bo_table']) {
    echo json_encode(array('ok' => false, 'message' => '게시판 정보를 찾을 수 없습니다.'));
    exit;
}

$write_table = $g5['write_prefix'] . $bo_table;

foreach ($chk_wr_id as $wr_id) {
    $wr_id = (int)$wr_id;
    if (!$wr_id) continue;

    $set = array();

    // extra 필드 처리: extra[wr_id][wr_1], extra[wr_id][wr_2] ...
    if (isset($extra[$wr_id]) && is_array($extra[$wr_id])) {
        foreach ($extra[$wr_id] as $field => $value) {
            // 필드 이름 보안: wr_숫자 형태만 허용
            if (!preg_match('/^wr_[0-9]+$/', $field)) {
                continue;
            }

            $val = trim((string)$value);
            $val = strip_tags($val);

            $set[] = $field . " = '" . sql_real_escape_string($val) . "'";
        }
    }

    
    if (isset($wr_datetime[$wr_id])) {
        $datetime = trim((string)$wr_datetime[$wr_id]);
        $datetime = strip_tags($datetime);
        $set[] = "wr_datetime = '" . sql_real_escape_string($datetime) . "'";
    }


    if (isset($wr_subject[$wr_id])) {
        $subject = trim((string)$wr_subject[$wr_id]);
        $subject = strip_tags($subject);
        $set[] = "wr_subject = '" . sql_real_escape_string($subject) . "'";
        $set[] = "wr_seo_title = '" . sql_real_escape_string($subject) . "'";
    }
    
    if (isset($ca_name[$wr_id])) {
        $cate = trim((string)$ca_name[$wr_id]);
        $cate = strip_tags($cate);
        $set[] = "ca_name = '" . sql_real_escape_string($cate) . "'";
    }

    // 업데이트 할 항목이 없으면 스킵
    if (!count($set)) {
        continue;
    }

    $sql = " update {$write_table}
                set " . implode(', ', $set) . "
              where wr_id = '{$wr_id}' ";
    sql_query($sql);
}

echo json_encode(array('ok' => true));
exit;

<?php
include_once('../../../../../common.php');

$wr_id = isset($_POST['event_id']) ? (int)$_POST['event_id'] : 0; // wr_id는 숫자 (int) 강제 변환
$start_date = isset($_POST['start_date']) ? sql_real_escape_string(trim($_POST['start_date'])) : '';
$end_date = isset($_POST['end_date']) ? sql_real_escape_string(trim($_POST['end_date'])) : '';
$bo_table = isset($_POST['bo_table']) ? sql_real_escape_string(trim($_POST['bo_table'])) : '';
$write_table = $g5['write_prefix'].$bo_table;

// 요청 파라미터 확인
if (empty($wr_id) || empty($start_date) || empty($end_date) || empty($bo_table)) {
    echo json_encode(["Error" => "필수 데이터가 누락되었습니다."]);
    exit;
}

// 이벤트 업데이트 쿼리 실행
if ($is_admin) {
    $sql = "UPDATE {$write_table} 
               SET wr_1 = '{$start_date}', wr_2 = '{$end_date}' 
             WHERE wr_id = '{$wr_id}' AND wr_is_comment = '0'";
    $result = sql_query($sql);
} else {
    $sql = "UPDATE {$write_table} 
               SET wr_1 = '{$start_date}', wr_2 = '{$end_date}' 
             WHERE wr_id = '{$wr_id}' 
               AND mb_id = '".sql_real_escape_string($member['mb_id'])."' 
               AND wr_is_comment = '0'";
    $result = sql_query($sql);
}
?>
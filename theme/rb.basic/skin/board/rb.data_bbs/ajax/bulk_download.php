<?php
// bulk_download.php

include_once('../../../../../../common.php');

// 관리자 권한 체크
if (!$is_admin) {
    die('no auth');
}

// bo_table 받기
$bo_table = isset($_GET['bo_table']) ? preg_replace('/[^a-z0-9_]/i','',$_GET['bo_table']) : '';
if(!$bo_table) die('no bo_table');

// 게시판 정보 로드
$sql_board = " SELECT * FROM {$g5['board_table']} WHERE bo_table = '".sql_real_escape_string($bo_table)."' ";
$board = sql_fetch($sql_board);
if(!$board) die('no board');

$is_category = !empty($board['bo_use_category']);

// 엑셀 헤더 구성
$headers = array();
if ($is_category) $headers[] = '분류';

$headers[] = '제목';


// 여분필드 bo_1_subj ~ bo_10_subj
for ($i=1; $i<=10; $i++) {
    $label_key = 'bo_'.$i.'_subj';
    $label = isset($board[$label_key]) ? trim((string)$board[$label_key]) : '';
    if ($label !== '') {
        $headers[] = $label;
    }
}

// PHPExcel 로드
include_once(G5_LIB_PATH.'/PHPExcel.php');

// 새 워크북
$excel = new PHPExcel();
$sheet = $excel->getActiveSheet();

// 1행에 헤더 넣기 (A1, B1, C1 ...)
for ($c=0; $c<count($headers); $c++) {
    // setCellValueByColumnAndRow(컬럼인덱스0부터, 행번호1부터, 값)
    $sheet->setCellValueByColumnAndRow($c, 1, $headers[$c]);
}

// 2행은 빈 예시 한 줄 (사용자가 작성할 자리)
// 굳이 안 넣어도 되는데, 그냥 비워둔 칸 하나 만들어주고 싶으면 아래처럼
for ($c=0; $c<count($headers); $c++) {
    $sheet->setCellValueByColumnAndRow($c, 2, '');
}

// 여기서 스타일은 일부러 아무 것도 안 건드림
// 그래서 엑셀 기본 격자선 그대로 보임
// 배경색, 두꺼운 테두리 등 없음

// 다운로드 헤더 설정
$filename = $bo_table.'_sample.xlsx';

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="'.$filename.'"');
header('Cache-Control: max-age=0');

// 작성기
$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$objWriter->save('php://output');
exit;

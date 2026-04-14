<?php
// bulk_upload.php 최종

include_once('../../../../../../common.php');

// 관리자만 가능
if (!$is_admin) {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(array('status'=>'error','message'=>'권한이 없습니다.'), JSON_UNESCAPED_UNICODE);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

// bo_table
$bo_table = isset($_POST['bo_table']) ? preg_replace('/[^a-z0-9_]/i','',$_POST['bo_table']) : '';
if(!$bo_table) {
    echo json_encode(array('status'=>'error','message'=>'bo_table 누락'), JSON_UNESCAPED_UNICODE);
    exit;
}

$write_table = $g5['write_prefix'].$bo_table;

// 파일 확인
if(!isset($_FILES['bulk_file']) || !is_uploaded_file($_FILES['bulk_file']['tmp_name'])){
    echo json_encode(array('status'=>'error','message'=>'파일이 없습니다.'), JSON_UNESCAPED_UNICODE);
    exit;
}

// 확장자 체크 (xls / xlsx 둘 다 허용)
$orig_name = isset($_FILES['bulk_file']['name']) ? $_FILES['bulk_file']['name'] : '';
$ext = strtolower(pathinfo($orig_name, PATHINFO_EXTENSION));

if($ext !== 'xlsx' && $ext !== 'xls') {
    echo json_encode(array('status'=>'error','message'=>'xlsx 또는 xls 형식만 업로드 가능합니다.'), JSON_UNESCAPED_UNICODE);
    exit;
}

// PHPExcel 로드
include_once(G5_LIB_PATH.'/PHPExcel.php');

// 엑셀 읽어서 2차원 배열로 바꾸는 함수
function parse_excel_rows($tmpfile) {
    $out = array();

    // PHPExcel_IOFactory::load() 는 xls / xlsx 자동 감지
    $excel = PHPExcel_IOFactory::load($tmpfile);
    $sheet = $excel->getActiveSheet();

    $highestRow = $sheet->getHighestRow(); // ex) 20
    $highestCol = $sheet->getHighestColumn(); // ex) 'G'
    $highestColIndex = PHPExcel_Cell::columnIndexFromString($highestCol); // 'G' -> 7

    for ($row=1; $row<=$highestRow; $row++) {
        $line = array();
        for ($col=0; $col<$highestColIndex; $col++) {
            $cellVal = $sheet->getCellByColumnAndRow($col, $row)->getValue();
            if (is_null($cellVal)) {
                $cellVal = '';
            }
            $line[] = trim((string)$cellVal);
        }

        // 완전 빈 줄은 스킵
        $hasValue = false;
        foreach($line as $v){
            if($v !== ''){
                $hasValue = true;
                break;
            }
        }
        if($hasValue){
            $out[] = $line;
        }
    }

    return $out;
}

// 실제 데이터 추출
$rows = parse_excel_rows($_FILES['bulk_file']['tmp_name']);
if(!is_array($rows) || count($rows) < 1){
    echo json_encode(array('status'=>'error','message'=>'파일이 비어있습니다.'), JSON_UNESCAPED_UNICODE);
    exit;
}

// 헤더 = 첫 줄
$header = $rows[0];
if(!is_array($header) || count($header) < 1){
    echo json_encode(array('status'=>'error','message'=>'헤더가 없습니다.'), JSON_UNESCAPED_UNICODE);
    exit;
}

// 헤더 → DB 컬럼 매핑
// 업로드 허용 컬럼: 분류, 제목, 여분필드들만
$map = array();

// 기본
$map['제목'] = 'wr_subject';
$map['분류'] = 'ca_name';

// 여분필드: bo_1_subj ~ bo_10_subj 라벨 -> wr_1 ~ wr_10
for ($i=1; $i<=10; $i++) {
    $label_key = 'bo_'.$i.'_subj';
    $label_txt = isset($board[$label_key]) ? trim((string)$board[$label_key]) : '';
    if ($label_txt !== '') {
        $map[$label_txt] = 'wr_'.$i;
    }
}

// header 순서대로 어떤 DB필드에 들어갈지 준비
// 예: cols[0] = 'ca_name', cols[1] = 'wr_subject', cols[2] = 'wr_1' ...
$cols = array();
for ($c=0; $c<count($header); $c++) {
    $h = trim((string)$header[$c]);
    if (isset($map[$h])) {
        $cols[$c] = $map[$h];
    } else {
        $cols[$c] = ''; // 허용 안된 헤더는 무시
    }
}

// 실제 insert 루프
$inserted = 0;

for($r=1; $r<count($rows); $r++){
    $dataRow = $rows[$r];
    if(!is_array($dataRow)) continue;

    // wr 기본 세팅
    $wr = array(
        'wr_subject' => '',
        'wr_content' => '-', // 내용은 템플릿에서 안받음
        'wr_name'    => ($member['mb_id']
                           ? ($board['bo_use_name'] ? $member['mb_name'] : $member['mb_nick'])
                           : ''),
        'ca_name'    => '',
        'wr_option'  => 'html1',
        'mb_id'      => $member['mb_id'],
        'wr_email'   => $member['mb_email'],
        'wr_homepage'=> $member['mb_homepage'],
        'wr_link1'   => '',
        'wr_link2'   => '',
        'wr_password'=> ($member['mb_id'] ? '' : get_encrypt_string(G5_TIME_YMDHIS)),
        'wr_1'       => '',
        'wr_2'       => '',
        'wr_3'       => '',
        'wr_4'       => '',
        'wr_5'       => '',
        'wr_6'       => '',
        'wr_7'       => '',
        'wr_8'       => '',
        'wr_9'       => '',
        'wr_10'      => ''
    );

    // 한 행의 각 셀을 wr에 매핑
    for ($c=0; $c<count($dataRow); $c++) {
        if (!isset($cols[$c]) || !$cols[$c]) continue;
        $val = trim((string)$dataRow[$c]);
        $wr[$cols[$c]] = $val;
    }

    // 제목/내용 전부 비면 skip
    if ($wr['wr_subject'] === '' && $wr['wr_content'] === '') {
        continue;
    }
    if ($wr['wr_subject'] === '') {
        $wr['wr_subject'] = '(제목없음)';
    }

    // wr_num 계산
    $sql_num = "SELECT IFNULL(MIN(wr_num) - 1, -1) AS next_num FROM {$write_table} sq";
    $row_num = sql_fetch($sql_num);
    $wr_num = isset($row_num['next_num']) ? $row_num['next_num'] : -1;

    $now = G5_TIME_YMDHIS;
    $ip  = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '';

    // SEO 타이틀
    $seo_title_val = '';
    if (function_exists('generate_seo_title') && function_exists('exist_seo_title_recursive')) {
        $tmp_seo = generate_seo_title($wr['wr_subject']);
        $seo_title_val = exist_seo_title_recursive('bbs', $tmp_seo, $write_table, 0);
    }

    // insert
    $sql = "
        INSERT INTO {$write_table}
        SET wr_num = '".sql_real_escape_string($wr_num)."',
            wr_reply = '',
            wr_comment = 0,
            ca_name = '".sql_real_escape_string($wr['ca_name'])."',
            wr_option = '".sql_real_escape_string($wr['wr_option'])."',
            wr_subject = '".sql_real_escape_string($wr['wr_subject'])."',
            wr_content = '".sql_real_escape_string($wr['wr_content'])."',
            wr_seo_title = '".sql_real_escape_string($seo_title_val)."',
            wr_link1 = '".sql_real_escape_string($wr['wr_link1'])."',
            wr_link2 = '".sql_real_escape_string($wr['wr_link2'])."',
            wr_link1_hit = 0,
            wr_link2_hit = 0,
            wr_hit = 0,
            wr_good = 0,
            wr_nogood = 0,
            mb_id = '".sql_real_escape_string($wr['mb_id'])."',
            wr_password = '".sql_real_escape_string($wr['wr_password'])."',
            wr_name = '".sql_real_escape_string($wr['wr_name'])."',
            wr_email = '".sql_real_escape_string($wr['wr_email'])."',
            wr_homepage = '".sql_real_escape_string($wr['wr_homepage'])."',
            wr_datetime = '".$now."',
            wr_last = '".$now."',
            wr_ip = '".sql_real_escape_string($ip)."',
            wr_1 = '".sql_real_escape_string($wr['wr_1'])."',
            wr_2 = '".sql_real_escape_string($wr['wr_2'])."',
            wr_3 = '".sql_real_escape_string($wr['wr_3'])."',
            wr_4 = '".sql_real_escape_string($wr['wr_4'])."',
            wr_5 = '".sql_real_escape_string($wr['wr_5'])."',
            wr_6 = '".sql_real_escape_string($wr['wr_6'])."',
            wr_7 = '".sql_real_escape_string($wr['wr_7'])."',
            wr_8 = '".sql_real_escape_string($wr['wr_8'])."',
            wr_9 = '".sql_real_escape_string($wr['wr_9'])."',
            wr_10= '".sql_real_escape_string($wr['wr_10'])."'
    ";
    sql_query($sql);

    $new_wr_id = sql_insert_id();

    // wr_parent
    sql_query("UPDATE {$write_table} SET wr_parent = '{$new_wr_id}' WHERE wr_id = '{$new_wr_id}'");

    // 새글 테이블
    sql_query("
        INSERT INTO {$g5['board_new_table']} (bo_table, wr_id, wr_parent, bn_datetime, mb_id)
        VALUES ('{$bo_table}', '{$new_wr_id}', '{$new_wr_id}', '".$now."', '".sql_real_escape_string($wr['mb_id'])."')
    ");

    // 게시판 글수 증가
    sql_query("
        UPDATE {$g5['board_table']}
        SET bo_count_write = bo_count_write + 1
        WHERE bo_table = '{$bo_table}'
    ");

    // 포인트 적립
    /*
    insert_point(
        $member['mb_id'],
        $board['bo_write_point'],
        $board['bo_subject'].' '.$new_wr_id.' 일괄등록',
        $bo_table,
        $new_wr_id,
        '쓰기'
    );
    */

    $inserted++;
}

// 최종 응답
echo json_encode(array(
    'status' => 'ok',
    'inserted' => $inserted
), JSON_UNESCAPED_UNICODE);
exit;

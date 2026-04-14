<?php
include_once('../../../../../../common.php');

// 게시판 아이디
$bo_table = isset($_POST['bo_table']) ? $_POST['bo_table'] : '';

// 여분필드 1~10 전부 받기
$fields = array();
for ($i=1; $i<=10; $i++) {
    $fields[$i] = array(
        'subj' => isset($_POST['bo_'.$i.'_subj']) ? $_POST['bo_'.$i.'_subj'] : '',
        'val'  => isset($_POST['bo_'.$i])        ? $_POST['bo_'.$i]        : '',
    );
}

// 목록출력 설정 (번호/분류/제목/작성자/작성일/댓글/조회)
// 체크된 값이 "v1|v2|v4|..." 형태로 넘어옴
$bo_list_opt = isset($_POST['bo_list_opt']) ? $_POST['bo_list_opt'] : '';

// escape
$bo_table    = sql_escape_string($bo_table);
$bo_list_opt = sql_escape_string($bo_list_opt);

for ($i=1; $i<=10; $i++) {
    $fields[$i]['subj'] = sql_escape_string($fields[$i]['subj']);
    $fields[$i]['val']  = sql_escape_string($fields[$i]['val']);
}

// 관리자만 저장 가능
if ($is_admin) {

    if ($bo_table) {

        // bo_list_opt 컬럼이 없으면 추가 (varchar(255) NOT NULL DEFAULT '')
        $col_chk_sql = "SHOW COLUMNS FROM {$g5['board_table']} LIKE 'bo_list_opt'";
        $col_chk_res = sql_query($col_chk_sql);
        if (!sql_num_rows($col_chk_res)) {
            $alter_sql = "
                ALTER TABLE {$g5['board_table']}
                ADD bo_list_opt varchar(255) NOT NULL DEFAULT ''
            ";
            sql_query($alter_sql);
        }

        // UPDATE 실행
        // - bo_list_opt : 기본 컬럼 노출 옵션 "v1|v2|v3|..."
        // - bo_1 ~ bo_10 : "type;view|옵션들" 형식 (view 1/0)
        //   (bo_3도 이제 그냥 다른 여분필드랑 동일하게 취급)
        $sql = "
        UPDATE {$g5['board_table']} SET
            bo_1 = '{$fields[1]['val']}',
            bo_1_subj = '{$fields[1]['subj']}',
            bo_2 = '{$fields[2]['val']}',
            bo_2_subj = '{$fields[2]['subj']}',
            bo_3 = '{$fields[3]['val']}',
            bo_3_subj = '{$fields[3]['subj']}',
            bo_4 = '{$fields[4]['val']}',
            bo_4_subj = '{$fields[4]['subj']}',
            bo_5 = '{$fields[5]['val']}',
            bo_5_subj = '{$fields[5]['subj']}',
            bo_6 = '{$fields[6]['val']}',
            bo_6_subj = '{$fields[6]['subj']}',
            bo_7 = '{$fields[7]['val']}',
            bo_7_subj = '{$fields[7]['subj']}',
            bo_8 = '{$fields[8]['val']}',
            bo_8_subj = '{$fields[8]['subj']}',
            bo_9 = '{$fields[9]['val']}',
            bo_9_subj = '{$fields[9]['subj']}',
            bo_10 = '{$fields[10]['val']}',
            bo_10_subj = '{$fields[10]['subj']}',
            bo_list_opt = '{$bo_list_opt}'
        WHERE bo_table = '{$bo_table}'
        ";

        sql_query($sql);

        echo json_encode(array('status'=>'ok'));

    } else {

        echo json_encode(array('status'=>'no'));
    }
}

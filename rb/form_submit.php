<?php
include_once('../common.php');

// 1. 필수 정보 확보
$fr_id = isset($_POST['fr_id']) ? preg_replace('/[^a-z0-9_]/i', '', $_POST['fr_id']) : '';
if (!$fr_id) alert('올바른 방법으로 이용해주세요.');

// 2. 설정값 조회
$fr = sql_fetch("SELECT * FROM rb_form WHERE fr_id = '$fr_id'");
$allowed_exts = array_map('trim', explode('|', strtolower($fr['fr_file_ext'])));
$max_file_size_mb = isset($fr['fr_file_size']) && $fr['fr_file_size'] > 0 ? (int)$fr['fr_file_size'] : 2;
$max_file_size_bytes = $max_file_size_mb * 1024 * 1024;

// 3. 필드 목록 조회
$fields = array();
$sql = "SELECT ff_name, ff_type, ff_label FROM rb_form_field WHERE fr_id = '$fr_id'";
$result = sql_query($sql);
while ($row = sql_fetch_array($result)) {
    $fields[] = array(
        'name' => $row['ff_name'],
        'label' => $row['ff_label'],
        'type' => $row['ff_type']
    );
}

// 4. 임시 insert로 fs_id 확보
$sql = "INSERT INTO rb_form_submit (fr_id, mb_id, fs_data, fs_datetime)
        VALUES ('$fr_id', '{$member['mb_id']}', '', '".G5_TIME_YMDHIS."')";
sql_query($sql);
$fs_id = sql_insert_id();

// 5. 유저 입력값 수집 및 파일 업로드
$data = array();
$upload_base = G5_DATA_PATH . "/form/{$fr_id}/{$fs_id}";
@mkdir($upload_base, G5_DIR_PERMISSION, true);
@chmod($upload_base, G5_DIR_PERMISSION);

foreach ($fields as $field) {
    $name = $field['name'];
    $label = $field['label'];
    $type = $field['type'];

    switch ($type) {
        case 'datetodate':
            $val1 = isset($_POST[$name . '_1']) ? trim($_POST[$name . '_1']) : '';
            $val2 = isset($_POST[$name . '_2']) ? trim($_POST[$name . '_2']) : '';
            $data[$label] = ($val1 && $val2) ? $val1 . ' ~ ' . $val2 : '';
            break;

        case 'datetime':
            $date = isset($_POST[$name . '_1']) ? trim($_POST[$name . '_1']) : '';
            $time = isset($_POST[$name . '_2']) ? trim($_POST[$name . '_2']) : '';
            $data[$label] = ($date || $time) ? $date . ' ' . $time : '';
            break;

        case 'number':
            $val = isset($_POST[$name]) ? preg_replace('/[^0-9]/', '', $_POST[$name]) : '';
            $data[$label] = $val ? number_format($val) : '';
            break;

        case 'address':
            $collected = array();
            for ($i = 1; $i <= 4; $i++) {
                if (isset($_POST[$name . "_{$i}"]) && $_POST[$name . "_{$i}"] !== '') {
                    $collected[] = trim($_POST[$name . "_{$i}"]);
                }
            }
            $data[$label] = count($collected) ? implode(' ', $collected) : '';
            break;

        case 'file':
            if (!isset($_FILES[$name]) || $_FILES[$name]['error'] !== UPLOAD_ERR_OK) {
                $data[$label] = ['name' => $name, 'file' => ''];
                break;
            }

            $file = $_FILES[$name];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $size = $file['size'];

            if (!in_array($ext, $allowed_exts)) {
                alert("허용되지 않은 확장자입니다. ({$ext})\\n업로드 가능 확장자: " . implode(', ', $allowed_exts));
            }

            if ($size > $max_file_size_bytes) {
                alert("업로드 용량 초과입니다. 최대 {$max_file_size_mb}MB까지 업로드할 수 있습니다.");
            }

            $unique_name = md5($file['name'] . microtime()) . '.' . $ext;
            $target_dir = $upload_base . "/{$name}";
            @mkdir($target_dir, G5_DIR_PERMISSION, true);
            @chmod($target_dir, G5_DIR_PERMISSION);

            $target_path = $target_dir . '/' . $unique_name;
            if (!move_uploaded_file($file['tmp_name'], $target_path)) {
                alert("파일 업로드에 실패했습니다.");
            }

            @chmod($target_path, G5_FILE_PERMISSION);

            // ✅ 라벨과 네임이 달라도 라벨 기준으로 저장
            $data[$label] = [
                'name' => $name,          // ff_name
                'label' => $label,        // ff_label
                'file' => $unique_name
            ];
            break;

        default:
            if (isset($_POST[$name])) {
                $val = $_POST[$name];
                $data[$label] = is_array($val) ? implode(', ', array_map('trim', $val)) : trim($val);
            } else {
                $data[$label] = '';
            }
            break;
    }
}

// 6. 개인정보 동의
$data['개인정보 수집 및 이용 동의'] = (isset($_POST['agree_personal']) && $_POST['agree_personal'] == '1') ? '동의함' : '미동의';

// 7. 최종 데이터 저장
$final_json = json_encode($data, JSON_UNESCAPED_UNICODE);
sql_query("UPDATE rb_form_submit SET fs_data = '".sql_real_escape_string($final_json)."' WHERE fs_id = '$fs_id'");

//관리자에게 쪽지발송
$subject = $fr['fr_subject'] ? get_text($fr['fr_subject']) : '';
memo_auto_send($subject . '에 신규 접수건이 있습니다.', '', $config['cf_admin'], "system-msg");

alert('정상적으로 접수되었습니다.');
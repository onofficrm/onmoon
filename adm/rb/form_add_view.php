<?php
$sub_menu = '000741';
require_once './_common.php';

auth_check_menu($auth, $sub_menu, "w");

$fs_id = isset($_REQUEST['fs_id']) ? preg_replace('/[^0-9]/', '', $_REQUEST['fs_id']) : '';

$g5['title'] = '폼 접수현황';

$sql = " SELECT * FROM rb_form_submit WHERE fs_id = '$fs_id' ";
$fs = sql_fetch($sql);

if (!$fs['fs_id']) {
    alert('등록된 자료가 없습니다.');
}

require_once G5_ADMIN_PATH . '/admin.head.php';

// 회원 정보 불러오기
$mb_nick = '비회원';
if ($fs['mb_id']) {
    $mb = get_member($fs['mb_id']);
    $mb_nick = get_sideview($mb['mb_id'], get_text($mb['mb_nick']), $mb['mb_email'], $mb['mb_homepage']);
}

// 폼 제목 가져오기
$form = sql_fetch("SELECT fr_subject FROM rb_form WHERE fr_id = '{$fs['fr_id']}'");
$form_subject = $form['fr_subject'] ?? '';

// 접수 내용 파싱
$submit_data = json_decode($fs['fs_data'], true); // 배열로

$pretty_output = '';

// 필드 라벨을 ff_id 순으로 가져옴
$field_order = array();
$sql = "SELECT ff_name, ff_label FROM rb_form_field WHERE fr_id = '{$fs['fr_id']}' ORDER BY ff_id ASC";
$res = sql_query($sql);
while ($row = sql_fetch_array($res)) {
    $field_order[] = array(
        'label' => get_text($row['ff_label']),
        'name'  => $row['ff_name']
    );
}

// 순서대로 출력
if (is_array($submit_data)) {
    $printed_labels = [];

    foreach ($field_order as $f) {
        $label = $f['label'];
        $ff_name = $f['name'];

        // ✅ 중복 출력 방지
        if (in_array($label, $printed_labels)) continue;
        $printed_labels[] = $label;

        if (!isset($submit_data[$label])) {
            // 값이 없더라도 라벨은 출력
            $pretty_output .= "<strong>{$label}</strong> : <br>\n";
            continue;
        }

        $val = $submit_data[$label];
        $download_link = '';
        $value = '';

        // ✅ 파일 항목
        if (is_array($val) && isset($val['file'])) {
            $filename = $val['file'];

            if ($filename) {
                $encoded_file = urlencode($filename);
                $file_path = G5_DATA_URL . "/form/{$fs['fr_id']}/{$fs['fs_id']}/{$ff_name}/{$encoded_file}";
                $download_link = "<a href='{$file_path}' download><b>[다운로드]</b></a>";
                $value = get_text($filename);
            } else {
                $value = ''; // 파일 없을 때는 내용 비우기
            }

        } else if (is_array($val)) {
            $value = implode(', ', array_map('get_text', $val));
        } else {
            $value = get_text($val);
        }

        $pretty_output .= "<strong>{$label}</strong> : {$value} {$download_link}<br>\n";
    }

    // 개인정보 동의 항목
    if (isset($submit_data['개인정보 수집 및 이용 동의'])) {
        $agree = get_text($submit_data['개인정보 수집 및 이용 동의']);
        $pretty_output .= "<strong>개인정보 수집 및 이용 동의</strong> : {$agree}<br>\n";
    }
}
?>



  <div class="tbl_frm01 tbl_wrap">
    <table>
        <caption><?php echo $g5['title']; ?> 목록</caption>
        <colgroup>
            <col class="grid_4">
            <col>
        </colgroup>
        <tbody>
            <tr>
                <th scope="row">접수자</th>
                <td><?php echo $mb_nick; ?></td>
            </tr>
            <tr>
                <th scope="row">접수일시</th>
                <td><?php echo $fs['fs_datetime']; ?></td>
            </tr>
            <tr>
                <th scope="row">폼 제목</th>
                <td><a href="./form.php?w=u&amp;fr_id=<?php echo $fs['fr_id']; ?>"><?php echo get_text($form_subject); ?></a></td>
            </tr>
            <tr>
                <th scope="row">접수내용</th>
                <td><?php echo $pretty_output ?: '내용 없음'; ?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="btn_fixed_top">
    <a href="./form_add_list.php" class="btn btn_02">목록</a>
</div>

<?php 
require_once G5_ADMIN_PATH . '/admin.tail.php';
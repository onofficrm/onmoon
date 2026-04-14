<?php
include_once('./_common.php');

// 관리자만 접근 허용
if (!$is_admin) {
    die('Access denied');
}

$fr_id = isset($_GET['fr_id']) ? preg_replace('/[^a-z0-9_]/i', '', $_GET['fr_id']) : '';

$fields = [];
$sql = "SELECT ff_type, ff_label, ff_name, ff_options, ff_placeholder, ff_required FROM rb_form_field 
        WHERE fr_id = '{$fr_id}' ORDER BY ff_order ASC, ff_id ASC";
$result = sql_query($sql);

while ($row = sql_fetch_array($result)) {
    $fields[] = [
        'type' => $row['ff_type'],
        'label' => $row['ff_label'],
        'name' => $row['ff_name'],
        'options' => $row['ff_options'],
        'placeholder' => $row['ff_placeholder'],
        'required' => (int)$row['ff_required']
    ];
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($fields, JSON_UNESCAPED_UNICODE);
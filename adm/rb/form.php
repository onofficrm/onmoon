<?php
$sub_menu = '000740';
require_once './_common.php';
require_once G5_EDITOR_LIB;

auth_check_menu($auth, $sub_menu, "w");

$fr_id = isset($_REQUEST['fr_id']) ? preg_replace('/[^a-z0-9_]/i', '', $_REQUEST['fr_id']) : '';

// 상단, 하단 파일경로 필드 추가
if (!sql_query(" select fr_include_head from rb_form limit 1 ", false)) {
    $sql = " ALTER TABLE `rb_form`  ADD `fr_include_head` VARCHAR( 255 ) NOT NULL , DD `fr_include_tail` VARCHAR( 255 ) NOT NULL ";
    sql_query($sql, false);
}

// html purifier 사용여부 필드
if (!sql_query(" select fr_tag_filter_use from rb_form limit 1 ", false)) {
    sql_query(
        " ALTER TABLE `rb_form`
                    ADD `fr_tag_filter_use` tinyint(4) NOT NULL DEFAULT '0' AFTER `fr_content` ",
        true
    );
    sql_query(" update rb_form set fr_tag_filter_use = '1' ");
}

// 모바일 내용 추가
if (!sql_query(" select fr_mobile_content from rb_form limit 1", false)) {
    sql_query(
        " ALTER TABLE `rb_form`
                    ADD `fr_mobile_content` longtext NOT NULL AFTER `fr_content` ",
        true
    );
}

// 스킨 설정 추가
if (!sql_query(" select fr_skin from rb_form limit 1 ", false)) {
    sql_query(
        " ALTER TABLE `rb_form`
                    ADD `fr_skin` varchar(255) NOT NULL DEFAULT '',
                    ADD `fr_mobile_skin` varchar(255) NOT NULL DEFAULT '' AFTER `fr_skin` ",
        true
    );
    sql_query(" update rb_form set fr_skin = 'basic', fr_mobile_skin = 'basic' ");
}

// 파일 추가
if (!sql_query(" select fr_file_ext from rb_form limit 1 ", false)) {
    sql_query(
        " ALTER TABLE `rb_form`
                    ADD `fr_file_ext` VARCHAR(255) NOT NULL DEFAULT '',
                    ADD `fr_file_size` INT(11) NOT NULL DEFAULT 0 AFTER `fr_file_ext` ",
        true
    );
}

// 기타 추가
if (!sql_query(" select fr_privacy_use from rb_form limit 1 ", false)) {
    sql_query(
        " ALTER TABLE `rb_form`
                    ADD `fr_privacy_use` TINYINT(1) NOT NULL DEFAULT 0 ",
        true
    );
}

if (!sql_query(" select fr_privacy_required from rb_form limit 1 ", false)) {
    sql_query(
        " ALTER TABLE `rb_form`
                    ADD `fr_privacy_required` TINYINT(1) NOT NULL DEFAULT 1 ",
        true
    );
}

if (!sql_query(" select fr_privacy_text from rb_form limit 1 ", false)) {
    sql_query(
        " ALTER TABLE `rb_form`
                    ADD `fr_privacy_text` LONGTEXT NOT NULL ",
        true
    );
}




$html_title = "폼";
$g5['title'] = $html_title . ' 관리';
$readonly = '';

if ($w == "u") {
    $html_title .= " 수정";
    $readonly = " readonly";

    $sql = " select * from rb_form where fr_id = '$fr_id' ";
    $fr = sql_fetch($sql);
    if (!$fr['fr_id']) {
        alert('등록된 자료가 없습니다.');
    }

    if (function_exists('check_case_exist_title')) check_case_exist_title($fr, G5_CONTENT_DIR, false);

} else {
    $html_title .= ' 입력';
    $fr = array(
        'fr_id' => '',
        'fr_subject' => '',
        'fr_level' => '1',
        'fr_level_opt' => '1',
        'fr_content' => '',
        'fr_mobile_content' => '',
        'fr_include_head' => '',
        'fr_include_tail' => '',
        'fr_tag_filter_use' => 1,
        'fr_html' => 2,
        'fr_skin' => 'basic',
        'fr_mobile_skin' => 'basic',
        'fr_privacy_use' => 0,
        'fr_privacy_required' => 1,
        'fr_privacy_text' => '',
        'fr_file_ext' => '',
        'fr_file_size' => 0
    );
}





require_once G5_ADMIN_PATH . '/admin.head.php';
?>




<form name="frmcontentform" action="./form_update.php" onsubmit="return frmcontentform_check(this);" method="post" enctype="MULTIPART/FORM-DATA">
    <input type="hidden" name="w" value="<?php echo $w; ?>">
    <input type="hidden" name="fr_html" value="1">
    <input type="hidden" name="token" value="">

    <div class="tbl_frm01 tbl_wrap">
        <table>
            <caption><?php echo $g5['title']; ?> 목록</caption>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>
                <tr>
                    <th scope="row"><label for="fr_id">ID</label></th>
                    <td>
                        <?php echo help('20자 이내의 영문자, 숫자, _ 만 가능합니다.'); ?>
                        <input type="text" value="<?php echo $fr['fr_id']; ?>" name="fr_id" id="fr_id" required <?php echo $readonly; ?> class="required <?php echo $readonly; ?> frm_input" size="20" maxlength="20">
                        <?php if ($w == 'u') { ?><a href="<?php echo G5_URL ?>/rb/form.php?fr_id=<?php echo $fr_id; ?>" target="_blank" class="btn_frmline">보기</a><?php } ?>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="fr_level">접근 권한</label></th>
                    <td>
                        <?php echo help('권한 1은 비회원, 2 이상 회원입니다. 권한은 10 이 가장 높습니다.') ?>
                        <?php echo get_member_level_select('fr_level', 1, $member['mb_level'], $fr['fr_level']) ?>
                        
                        <select id="fr_level_opt" name="fr_level_opt">
                        <option value="1" <?php if (isset($fr['fr_level_opt']) && $fr['fr_level_opt'] == "1") { ?>selected<?php } ?>>레벨 부터 접근가능</option>
                        <option value="2" <?php if (isset($fr['fr_level_opt']) && $fr['fr_level_opt'] == "2") { ?>selected<?php } ?>>레벨만 접근가능</option>
                        </select>
                    </td>
                </tr>
                
                
                
                <tr>
                    <th scope="row"><label for="fr_subject">제목</label></th>
                    <td><input type="text" name="fr_subject" value="<?php echo htmlspecialchars2($fr['fr_subject']); ?>" id="fr_subject" required class="frm_input required" size="90"></td>
                </tr>
                <tr>
                    <th scope="row">상단내용</th>
                    <td><?php echo editor_html('fr_content', get_text(html_purifier($fr['fr_content']), 0)); ?></td>
                </tr>
                
                
                <tr>
                    <th scope="row">폼 항목 설정</th>
                    <td>
                        <?php echo help("name 속성은 중복되지 않는 고유한 값으로 설정해주세요. (영문 또는 영문+숫자)<br>셀렉트, 라디오, 체크박스에 여러개 선택 항목을 사용하는 경우 콤마(,)로 구분해주세요."); ?>
                        <div class="tbl_head01 tbl_wrap">
                        <table id="form_fields_wrap" class="frm_tbl">
                            <thead>
                                <tr>
                                    <th>타입</th>
                                    <th>제목</th>
                                    <th>속성(name)</th>
                                    <th>선택항목</th>
                                    <th>입력예시(placeholder)</th>
                                    <th>필수여부</th>
                                    <th>관리</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- 항목들 여기에 생성됨 -->
                            </tbody>
                        </table>
                        </div>

                        <p style="margin-top: 10px;">
                            <button type="button" class="btn_add_field btn_frmline">항목추가</button>
                        </p>

                        <script>
                            const fieldTypes = {
                                'text': '텍스트',
                                'textarea': '텍스트(줄바꿈)',
                                'select': '셀렉트',
                                'radio': '라디오(단일선택)',
                                'checkbox': '체크박스(복수선택)',
                                'number': '숫자',
                                'email': '이메일',
                                'tel': '전화번호',
                                'date': '날짜(캘린더)',
                                'datetodate': '기간(캘린더)',
                                'time': '시간',
                                'datetime': '날짜/시간',
                                'address': '주소(주소검색)',
                                'file': '파일(첨부)'
                            };

                            let fieldIndex = 0;

                            function createFieldRow(index, fieldData = {}) {
                                const html = `
                                <tr class="field_row_table" data-index="${index}">
                                    <td>
                                        <select name="fields[${index}][type]" required>
                                            ${Object.entries(fieldTypes).map(([val, label]) =>
                                                `<option value="${val}" ${fieldData.type === val ? 'selected' : ''}>${label}</option>`
                                            ).join('')}
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" name="fields[${index}][label]" class="frm_input" value="${fieldData.label || ''}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="fields[${index}][name]" class="frm_input" value="${fieldData.name || ''}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="fields[${index}][options]" class="frm_input" value="${fieldData.options || ''}">
                                    </td>
                                    <td>
                                        <input type="text" name="fields[${index}][placeholder]" class="frm_input" value="${fieldData.placeholder || ''}">
                                    </td>
                                    <td>
                                        <label><input type="checkbox" name="fields[${index}][required]" value="1" ${fieldData.required ? 'checked' : ''}> 필수</label>
                                    </td>
                                    <td class="td_mng td_mng_s">
                                        <button type="button" class="btn_remove_field btn_frmline">삭제</button>
                                    </td>
                                </tr>
                            
                                `;

                                $('#form_fields_wrap tbody').append(html);
                            }

                            $(document).on('click', '.btn_add_field', function () {
                                createFieldRow(fieldIndex++);
                            });

                            $(document).on('click', '.btn_remove_field', function () {
                                $(this).closest('tr').remove();
                            });

                            <?php if ($w == 'u'): ?>
                            $.ajax({
                                url: './form_fields.php',
                                type: 'GET',
                                data: { fr_id: "<?php echo $fr_id; ?>" },
                                dataType: 'json',
                                success: function (data) {
                                    data.forEach(field => {
                                        createFieldRow(fieldIndex++, field);
                                    });
                                }
                            });
                            <?php endif; ?>
                        </script>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">개인정보 수집 및<br>이용 동의</th>
                    <td>
                        <?php echo help("사용 설정시 사용자 폼에 출력 됩니다.<br>필수 체크시에 이용동의를 반드시 체크해야만 접수가 가능합니다."); ?>
                        <textarea name="fr_privacy_text" rows="6" style="width:100%;"><?php echo isset($fr['fr_privacy_text']) ? htmlspecialchars2($fr['fr_privacy_text']) : ''; ?></textarea>
                        <label>
                            <input type="checkbox" name="fr_privacy_use" value="1" <?php echo isset($fr['fr_privacy_use']) && $fr['fr_privacy_use'] ? 'checked' : ''; ?>>
                            사용
                        </label>
                        &nbsp;&nbsp;
                        <label>
                            <input type="checkbox" name="fr_privacy_required" value="1" <?php echo isset($fr['fr_privacy_required']) && $fr['fr_privacy_required'] ? 'checked' : ''; ?>>
                            필수
                        </label>
                    </td>
                </tr>
                
                <?php
                $default_ext = 'jpg|jpeg|png|gif|pdf|zip';
                $default_size = 2;
                $file_ext_val  = isset($fr['fr_file_ext'])  && $fr['fr_file_ext']  ? $fr['fr_file_ext']  : $default_ext;
                $file_size_val = isset($fr['fr_file_size']) && $fr['fr_file_size'] ? $fr['fr_file_size'] : $default_size;
                ?>
                <tr>
                    <th scope="row">파일업로드 확장자 설정</th>
                    <td>
                        <?php echo help("이 폼에서 업로드 가능한 파일의 확장자를 설정합니다.<br>해당 설정은 모든 파일 항목에 공통 적용됩니다."); ?>
                        <input type="text" name="fr_file_ext" value="<?php echo htmlspecialchars2($file_ext_val); ?>" class="frm_input" size="70" placeholder="허용 확장자 (예: jpg|png|pdf)">
                    </td>
                </tr>
                
                <tr>
                    <th scope="row">파일업로드 용량제한 설정</th>
                    <td>
                        <?php echo help("이 폼에서 업로드 가능한 파일의 최대용량을 설정합니다.<br>해당 설정은 모든 파일 항목에 공통 적용됩니다."); ?>
                        <input type="number" name="fr_file_size" value="<?php echo (int)$file_size_val; ?>" class="frm_input" size="20" placeholder="업로드 제한용량 (M)"> M<br>
                        <span style="color:#888;">서버 허용 용량: <?php echo ini_get('upload_max_filesize'); ?> / <?php echo ini_get('post_max_size'); ?></span>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="fr_himg">상단이미지</label></th>
                    <td>
                        <input type="file" name="fr_himg" id="fr_himg">
                        <?php
                        $himg = G5_DATA_PATH . '/form/' . $fr['fr_id'] . '_h';
                        $himg_str = '';
                        if (file_exists($himg)) {
                            $size = @getimagesize($himg);
                            if ($size[0] && $size[0] > 750) {
                                $width = 750;
                            } else {
                                $width = $size[0];
                            }

                            echo '<input type="checkbox" name="fr_himg_del" value="1" id="fr_himg_del"> <label for="fr_himg_del">삭제</label>';
                            $himg_str = '<img src="' . G5_DATA_URL . '/form/' . $fr['fr_id'] . '_h" width="' . $width . '" alt="">';
                        }
                        if ($himg_str) {
                            echo '<div class="banner_or_img">';
                            echo $himg_str;
                            echo '</div>';
                        }
                        ?>
                    </td>
                </tr>
                
                <tr>
                    <th scope="row"><label for="fr_timg">하단이미지</label></th>
                    <td>
                        <input type="file" name="fr_timg" id="fr_timg">
                        <?php
                        $timg = G5_DATA_PATH . '/form/' . $fr['fr_id'] . '_t';
                        $timg_str = '';
                        if (file_exists($timg)) {
                            $size = @getimagesize($timg);
                            if ($size[0] && $size[0] > 750) {
                                $width = 750;
                            } else {
                                $width = $size[0];
                            }

                            echo '<input type="checkbox" name="fr_timg_del" value="1" id="fr_timg_del"> <label for="fr_timg_del">삭제</label>';
                            $timg_str = '<img src="' . G5_DATA_URL . '/form/' . $fr['fr_id'] . '_t" width="' . $width . '" alt="">';
                        }
                        if ($timg_str) {
                            echo '<div class="banner_or_img">';
                            echo $timg_str;
                            echo '</div>';
                        }
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="btn_fixed_top">
        <a href="./form_list.php" class="btn btn_02">목록</a>
        <input type="submit" value="확인" class="btn btn_submit" accesskey="s">
    </div>

</form>

<?php
// [KVE-2018-2089] 취약점 으로 인해 파일 경로 수정시에만 자동등록방지 코드 사용
?>
<script>


    function frm_check_file() {
        var fr_include_head = "<?php echo $fr['fr_include_head']; ?>";
        var fr_include_tail = "<?php echo $fr['fr_include_tail']; ?>";
        var head = jQuery.trim(jQuery("#fr_include_head").val());
        var tail = jQuery.trim(jQuery("#fr_include_tail").val());

        if (fr_include_head !== head || fr_include_tail !== tail) {
            return false;
        }

        return true;
    }

    jQuery(function($) {
        if (window.self !== window.top) { // frame 또는 iframe을 사용할 경우 체크
            $("#fr_include_head, #fr_include_tail").on("change paste keyup", function(e) {
                frm_check_file();
            });
        }
    });

    function frmcontentform_check(f) {
        errmsg = "";
        errfld = "";

        <?php echo get_editor_js('fr_content'); ?>

        check_field(f.fr_id, "ID를 입력하세요.");
        check_field(f.fr_subject, "제목을 입력하세요.");

        if (errmsg != "") {
            alert(errmsg);
            errfld.focus();
            return false;
        }

        return true;
    }
</script>

<?php
require_once G5_ADMIN_PATH . '/admin.tail.php';

<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
add_javascript(G5_POSTCODE_JS, 0);    //다음 주소 js
?>

<link rel="stylesheet" href="<?php echo G5_URL ?>/rb/rb.mod/form/style.css?ver=<?php echo G5_SERVER_TIME ?>">

<?php
if (isset($str) && $str) {
    echo '<div class="rb_form_wrap_top">'.$str.'</div>';
}

$sql = "SELECT * FROM rb_form_field WHERE fr_id = '$fr_id' ORDER BY ff_order ASC, ff_id ASC";
$result = sql_query($sql);
?>

<form name="user_form" id="user_form" action="<?php echo G5_URL ?>/rb/form_submit.php" enctype="multipart/form-data" method="post" onsubmit="return check_agree();">
<div class="rb_form_wrap">

    <?php if (isset($fr['fr_privacy_use']) && $fr['fr_privacy_use']): ?>
    <div class="rb_form_item">
        <textarea class="form_item_textarea" readonly><?php echo $fr['fr_privacy_text']; ?></textarea>
        <input type="checkbox" name="agree_personal" value="1" id="agree_personal" data-required="<?php echo $fr['fr_privacy_required'] ? '1' : '0'; ?>">
        <label for="agree_personal">개인정보 수집 및 이용에 동의합니다.</label>
    </div><br>
    <?php endif; ?>

    <input type="hidden" name="fr_id" value="<?php echo $fr_id; ?>">
    <div class="rb_form_wrap_inner">
    <?php
    $field_idx = 1;
    while ($field = sql_fetch_array($result)) {
        $type = $field['ff_type'];
        $label = htmlspecialchars($field['ff_label']);
        $name = htmlspecialchars($field['ff_name']);
        $options = explode(',', $field['ff_options']);
        $placeholder = htmlspecialchars($field['ff_placeholder']);
        $is_required = $field['ff_required'] ? '1' : '0';
        $is_required_class = $field['ff_required'] ? 'requireds' : '';

        echo "<div class='rb_form_item'>";
        echo "<ul class='rb_form_item_tit'><span class=''>{$label}</span></ul>";
        echo "<ul class='rb_form_item_data'>";
        /*
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
        */
        switch ($type) {
            case 'text':
                echo "<input type='text' id='fr_{$name}_{$field_idx}' class='required_field {$is_required_class} w50' name='{$name}' data-required='{$is_required}' placeholder='{$placeholder}'>";
                break;

            case 'textarea':
                echo "<textarea id='fr_{$name}_{$field_idx}' name='{$name}' class='required_field {$is_required_class}' data-required='{$is_required}' placeholder='{$placeholder}'></textarea>";
                break;

            case 'select':
                if($placeholder) {
                    echo "<span class='help_text'>{$placeholder}</span>";
                }
                echo "<select id='fr_{$name}_{$field_idx}' class='required_field select w40' name='{$name}' data-required='{$is_required}'>";
                foreach ($options as $opt) {
                    $opt = trim($opt);
                    echo "<option value='{$opt}'>{$opt}</option>";
                }
                echo "</select>";
                break;

            case 'radio':
                if($placeholder) {
                    echo "<span class='help_text'>{$placeholder}</span>";
                }
                echo "<dl>";
                foreach ($options as $i => $opt) {
                    $opt = trim($opt);
                    $id = "fr_{$name}_{$field_idx}_{$i}";
                    echo "<dd><input type='radio' name='{$name}' id='{$id}' value='{$opt}' data-required='{$is_required}' class='required_field'>";
                    echo "<label for='{$id}'>{$opt}</label></dd>";
                }
                echo "</dl>";
                break;

            case 'checkbox':
                if($placeholder) {
                    echo "<span class='help_text'>{$placeholder}</span>";
                }
                echo "<dl>";
                foreach ($options as $i => $opt) {
                    $opt = trim($opt);
                    $id = "fr_{$name}_{$field_idx}_{$i}";
                    echo "<dd><input type='checkbox' name='{$name}[]' id='{$id}' value='{$opt}' class='required_field' data-required='{$is_required}'>";
                    echo "<label for='{$id}'>{$opt}</label></dd>";
                }
                echo "</dl>";
                break;
                
            case 'number':
                echo "<input type='number' id='fr_{$name}_{$field_idx}' class='required_field {$is_required_class} w30' name='{$name}' data-required='{$is_required}' placeholder='{$placeholder}'>";
                break;
                
            case 'email':
                echo "<input type='email' id='fr_{$name}_{$field_idx}' class='required_field {$is_required_class} w60' name='{$name}' data-required='{$is_required}' placeholder='{$placeholder}'>";
                break;
                
            case 'tel':
                echo "<input type='tel' id='fr_{$name}_{$field_idx}' class='required_field {$is_required_class} w30' name='{$name}' data-required='{$is_required}' placeholder='{$placeholder}'>";
                break;
                
            case 'date':
                if($placeholder) {
                    echo "<span class='help_text'>{$placeholder}</span>";
                }
                echo "<input type='text' id='fr_{$name}_{$field_idx}' class='required_field {$is_required_class} datepicker datepicker_inp' name='{$name}' data-required='{$is_required}' autocomplete='off'>";
                break;
                
            case 'datetodate':
                if($placeholder) {
                    echo "<span class='help_text'>{$placeholder}</span>";
                }
                echo "<input type='text' id='fr_{$name}_{$field_idx}_1' class='required_field {$is_required_class} datepicker datepicker_inp datepicker_start' name='{$name}_1' data-required='{$is_required}' autocomplete='off'> ~ ";
                echo "<input type='text' id='fr_{$name}_{$field_idx}_2' class='required_field {$is_required_class} datepicker datepicker_inp datepicker_end' name='{$name}_2' data-required='{$is_required}' autocomplete='off'>";
                break;
                
             case 'time':
                if($placeholder) {
                    echo "<span class='help_text'>{$placeholder}</span>";
                }
                echo "<input type='time' id='fr_{$name}_{$field_idx}' class='required_field {$is_required_class} times_inp' name='{$name}' data-required='{$is_required}'>";
                break;
                
            case 'datetime':
                if($placeholder) {
                    echo "<span class='help_text'>{$placeholder}</span>";
                }
                echo "<input type='date' id='fr_{$name}_{$field_idx}_1' class='required_field {$is_required_class} dates_inp' name='{$name}_1' data-required='{$is_required}'> ";
                echo "<input type='time' id='fr_{$name}_{$field_idx}_2' class='required_field {$is_required_class} times_inp' name='{$name}_2' data-required='{$is_required}'>";
                break;
                
            case 'address':
                if($placeholder) {
                    echo "<span class='help_text'>{$placeholder}</span>";
                }
                echo '<div>';
                echo '<input type="text" id="fr_'.$name.'_'.$field_idx.'_1" name="'.$name.'_1" class="input twopart_input w20" maxlength="6" placeholder="우편번호">';
                echo '<button type="button" class="btn_frmline btn_win_zip" onclick="win_zip(\'user_form\', \''.$name.'_1\', \''.$name.'_2\', \''.$name.'_3\', \''.$name.'_4\', \''.$name.'_5\');">주소검색</button>';
                echo '</div>';
                echo '<div>';
                echo "<input type='text' id='fr_{$name}_{$field_idx}_2' class='required_field {$is_required_class} w70 mt-5' name='{$name}_2' data-required='{$is_required}' placeholder='기본 주소'>";
                echo "<input type='text' id='fr_{$name}_{$field_idx}_3' class='required_field w70 mt-5' name='{$name}_3' placeholder='나머지 주소'>";
                echo "<input type='text' id='fr_{$name}_{$field_idx}_4' class='required_field w70 mt-5' name='{$name}_4' placeholder='참고항목' readonly>";
                echo "<input type='hidden' id='fr_{$name}_{$field_idx}_5' class='required_field w70 mt-5' name='{$name}_5'>";
                echo '</div>';
                break;
                
            case 'file':
                if($placeholder) {
                    echo "<span class='help_text'>{$placeholder}</span>";
                }
                echo "<input type='file' id='fr_{$name}_{$field_idx}' class='required_field {$is_required_class} files_inp' name='{$name}' data-required='{$is_required}'>";
                break;
        }

        echo "</ul>";
        echo "<div class='cb'></div>";
        echo "</div>";
        $field_idx++;
    }
    ?>
    

            </li>
    </div>

    <div class="win_btn">
        <button type="submit" class="btn btn_b02 reply_btn">접수하기</button>
    </div>
</div>
</form>

            <script>
                $(function() {
                    $('.datepicker_start').datepicker({
                        minDate: 0
                    });
                    $('.datepicker_end').datepicker({
                        minDate: 0,
                        beforeShow: function(input, inst) {
                            var startDate = $('.datepicker_start').datepicker('getDate');
                            if (startDate) {
                                $(this).datepicker('option', 'minDate', startDate);
                            }
                        }
                    });

                    // 시작일이 변경되면 종료일의 최소 날짜를 업데이트
                    $('.datepicker_start').on('change', function() {
                        var startDate = $(this).datepicker('getDate');
                        if (startDate) {
                            $('.datepicker_end').datepicker('option', 'minDate', startDate);
                        }
                    });
                });
            </script>

<script>
function check_agree() {
    const agreeCheck = document.getElementById('agree_personal');
    if (agreeCheck && agreeCheck.dataset.required === '1' && !agreeCheck.checked) {
        alert('개인정보 수집 및 이용에 동의해 주세요.');
        return false;
    }

    const requiredFields = document.querySelectorAll('.required_field[data-required="1"]');
    const checkedGroups = new Set();

    for (let el of requiredFields) {
        const type = el.type;
        const name = el.name;
        const wrapper = el.closest('.rb_form_item');

        let labelText = '해당 항목';
        if (wrapper) {
            const span = wrapper.querySelector('.rb_form_item_tit span');
            if (span && span.innerText.trim()) {
                labelText = span.innerText.trim();
            }
        }

        if ((type === 'radio' || type === 'checkbox') && !checkedGroups.has(name)) {
            const checked = document.querySelectorAll(`[name="${name}"]:checked`).length;
            if (!checked) {
                alert(`${labelText} 항목은 필수선택 입니다.`);
                return false;
            }
            checkedGroups.add(name);
        } else if (type !== 'radio' && type !== 'checkbox') {
            if (!el.value.trim()) {
                alert(`${labelText} 항목은 필수입력 입니다.`);
                el.focus();
                return false;
            }
        }
    }

    return true;
}

</script>

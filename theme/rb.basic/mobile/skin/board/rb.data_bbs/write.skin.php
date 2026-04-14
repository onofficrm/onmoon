<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css?ver='.G5_SERVER_TIME.'">', 0);
?>

<div class="rb_bbs_wrap rb_bbs_write_wrap">

    <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off" style="width:<?php echo $width; ?>">
        <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>">
        <input type="hidden" name="w" value="<?php echo $w ?>">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
        <input type="hidden" name="sca" value="<?php echo $sca ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="spt" value="<?php echo $spt ?>">
        <input type="hidden" name="sst" value="<?php echo $sst ?>">
        <input type="hidden" name="sod" value="<?php echo $sod ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">

        <!-- 카테고리 { -->
        <?php if ($is_category) { ?>
        <div class="rb_inp_wrap">
            <ul>
                <select name="ca_name" id="ca_name" required class="select ca_name">
                    <option value="">분류를 선택하세요</option>
                    <?php echo $category_option ?>
                </select>
            </ul>
        </div>
        <?php } ?>
        <!-- } -->

        <!-- 제목 { -->
        <div class="rb_inp_wrap">
            <div id="autosave_wrapper" class="write_div">
                <ul class="autosave_wrapper_ul1" <?php if (!$is_member) { ?>style="padding-right:0px;" <?php } ?>>
                    <input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="input required full_input" maxlength="255" placeholder="제목을 입력하세요.">
                </ul>
                <?php if ($is_member) { // 임시 저장된 글 기능 ?>
                <ul class="autosave_wrapper_ul2">
                    <script src="<?php echo G5_JS_URL; ?>/autosave.js"></script>
                    <?php if($editor_content_js) echo $editor_content_js; ?>
                    <button type="button" id="btn_autosave" class="btn_frmline">임시저장 <span id="autosave_count" class="font-B"><?php echo $autosave_count; ?></span></button>
                    <div id="autosave_pop">
                        <strong>임시 저장된 글 목록</strong>
                        <?php if($autosave_count > 0) { ?>
                        <ul></ul>
                        <?php } else { ?>
                        <div class="autosave_guide">저장된 데이터가 없습니다.</div>
                        <?php } ?>
                        <div class="autosave_btn_wrap">
                            <button type="button" class="autosave_close autosave_save font-B" onclick="autosave()">저장</button>
                            <button type="button" class="autosave_close font-B">닫기</button>
                        </div>
                    </div>
                </ul>
                <?php } ?>
            </div>
        </div>
        <!-- } -->


        <?php
        $fields = [];
        for ($i=1; $i<=10; $i++) {
            $subj = trim((string)($board["bo_{$i}_subj"] ?? ''));
            $spec = trim((string)($board["bo_{$i}"] ?? ''));
            if ($subj === '' || $spec === '') continue;

            // 기본값
            $type     = 'text';   // text / radio / checkbox / select ...
            $opts_arr = [];       // 옵션 배열 (radio, checkbox, select일 때)
            // view 플래그(목록출력 여부)는 글쓰기 폼에서는 필요 없으므로 굳이 안 써도 됨.

            // 새 포맷 예시: "radio;1|항목1,항목2,항목3"
            //               "select;0|옵션A,옵션B"
            //               "text;1"
            if (strpos($spec, ';') !== false) {
                list($typePart, $restPart) = explode(';', $spec, 2);
                $type = strtolower(trim($typePart)); // radio / checkbox / select / text 등

                if (strpos($restPart, '|') !== false) {
                    list($viewFlagPart, $optsPart) = explode('|', $restPart, 2);
                    // $viewFlagPart 는 "1" 또는 "0" (목록출력 여부) - 여기선 안 씀
                    $opts_arr = array_values(array_filter(array_map('trim', explode(',', $optsPart))));
                } else {
                    // "text;1" 처럼 옵션 문자열이 아예 없는 경우
                    $opts_arr = [];
                }

            } else {
                // 구버전 예시: "radio|항목1,항목2,항목3"
                //             "text"
                if (strpos($spec, '|') !== false) {
                    list($typePart, $optsPart) = explode('|', $spec, 2);
                    $type = strtolower(trim($typePart));
                    $opts_arr = array_values(array_filter(array_map('trim', explode(',', $optsPart))));
                } else {
                    // 그냥 "text" 이런 케이스
                    $type = strtolower(trim($spec));
                    $opts_arr = [];
                }
            }

            $name   = "wr_{$i}";
            $cur    = ($w === 'u') ? (string)($write[$name] ?? '') : '';
            $curSet = $cur !== '' ? array_map('trim', explode(',', $cur)) : [];

            $fields[] = [
                'name'   => $name,      // ex) wr_1
                'subj'   => $subj,      // 라벨에 찍을 타이틀
                'type'   => $type,      // text / radio / checkbox / select
                'opts'   => $opts_arr,  // ['항목1','항목2',...]
                'cur'    => $cur,       // 현재 저장된 값(수정시)
                'curSet' => $curSet,    // 체크박스용 다중값 배열
            ];
        }
        ?>

        <?php if (!empty($fields)) { ?>
        <div class="rb-extra-grid">
           
            <?php foreach ($fields as $f):
                $name = $f['name'];
                $subj = htmlspecialchars($f['subj'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                $type = strtolower($f['type']);
                $opts = $f['opts'];
                $cur  = $f['cur'];
                $curSet = $f['curSet'];
              ?>

            <div class="rb-label font-B color-999"><?php echo $subj; ?></div>


            <div class="rb-input">
                <?php if ($type === 'checkbox'): ?>

                <input type="hidden" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo htmlspecialchars($cur, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
                <div class="rb-stack">
                    <?php foreach ($opts as $j=>$opt):
                        $val = htmlspecialchars($opt, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                        $id  = $name.'_cb_'.$j;
                        $checked = in_array($opt, $curSet, true) ? ' checked' : '';
                      ?>
                    <span class="rb-choice">
                        <input type="checkbox" id="<?php echo $id; ?>" class="rb-extra-cb" data-target="<?php echo $name; ?>" value="<?php echo $val; ?>" <?php echo $checked; ?>>
                        <label for="<?php echo $id; ?>"><?php echo $val; ?></label>
                    </span>
                    <?php endforeach; ?>
                </div>

                <?php elseif ($type === 'radio'): ?>
                <div class="rb-stack">
                    <?php foreach ($opts as $j=>$opt):
                        $val = htmlspecialchars($opt, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                        $id  = $name.'_rd_'.$j;
                        $checked = ($cur === $opt) ? ' checked' : '';
                      ?>
                    <span class="rb-choice">
                        <input type="radio" id="<?php echo $id; ?>" name="<?php echo $name; ?>" value="<?php echo $val; ?>" <?php echo $checked; ?>>
                        <label for="<?php echo $id; ?>"><?php echo $val; ?></label>
                    </span>
                    <?php endforeach; ?>
                </div>

                <?php elseif ($type === 'select'): ?>
                <select name="<?php echo $name; ?>" class="select">
                    <option value="">선택하세요</option>
                    <?php foreach ($opts as $opt):
                        $val = htmlspecialchars($opt, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                        $sel = ($cur === $opt) ? ' selected' : '';
                      ?>
                    <option value="<?php echo $val; ?>" <?php echo $sel; ?>><?php echo $val; ?></option>
                    <?php endforeach; ?>
                </select>

                <?php else: /* text */ ?>
                <input type="text" name="<?php echo $name; ?>" value="<?php echo htmlspecialchars($cur, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <script>
            (function() {
                function syncHidden(target) {
                    var vals = [];
                    document.querySelectorAll('.rb-extra-cb[data-target="' + target + '"]:checked')
                        .forEach(function(cb) {
                            vals.push(cb.value);
                        });
                    var hid = document.getElementById(target);
                    if (hid) hid.value = vals.join(',');
                }
                document.addEventListener('change', function(e) {
                    var t = e.target;
                    if (t && t.classList && t.classList.contains('rb-extra-cb')) {
                        var target = t.getAttribute('data-target');
                        if (target) syncHidden(target);
                    }
                });
                document.querySelectorAll('.rb-extra-cb').forEach(function(cb) {
                    var target = cb.getAttribute('data-target');
                    if (target) syncHidden(target);
                });
                var f = document.getElementById('fwrite') || document.forms['fwrite'];
                if (f) {
                    var orig = f.onsubmit;
                    f.onsubmit = function(ev) {
                        document.querySelectorAll('.rb-extra-cb').forEach(function(cb) {
                            var target = cb.getAttribute('data-target');
                            if (target) syncHidden(target);
                        });
                        return (typeof orig === 'function') ? (orig.call(f, ev) !== false) : true;
                    };
                }
            })();
        </script>
        <?php } ?>


        <?php
        $option = '';
        $option_hidden = '';
        if ($is_notice || $is_html || $is_secret || $is_mail) { 
            $option = '';
            if ($is_notice) {
                $option .= PHP_EOL.'<input type="checkbox" id="notice" name="notice"  class="selec_chk" value="1" '.$notice_checked.'>'.PHP_EOL.'<label for="notice"><span></span>공지</label>　';
            }
            if ($is_html) {
                if ($is_dhtml_editor) {
                    $option_hidden .= '<input type="hidden" value="html1" name="html">';
                } else {
                    $option .= PHP_EOL.'<input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" class="selec_chk" value="'.$html_value.'" '.$html_checked.'>'.PHP_EOL.'<label for="html"><span></span>html</label>　';
                }
            }
            if ($is_secret) {
                if ($is_admin || $is_secret==1) {
                    $option .= PHP_EOL.'<input type="checkbox" id="secret" name="secret"  class="selec_chk" value="secret" '.$secret_checked.'>'.PHP_EOL.'<label for="secret"><span></span>비밀글</label>　';
                } else {
                    $option_hidden .= '<input type="hidden" name="secret" value="secret">';
                }
            }
            if ($is_mail) {
                $option .= PHP_EOL.'<input type="checkbox" id="mail" name="mail"  class="selec_chk" value="mail" '.$recv_email_checked.'>'.PHP_EOL.'<label for="mail"><span></span>답변메일받기</label>　';
            }
        }
        echo $option_hidden;
    ?>


        <?php if ($option) { ?>
        <div class="rb_inp_wrap">
            <ul>
                <div class="write_div">
                    <span class="sound_only">옵션</span>
                    <ul class="bo_v_option">
                        <?php echo $option ?>
                    </ul>
                </div>
            </ul>
        </div>
        <?php } ?>



        <!-- 내용 { -->
        <div class="rb_inp_wrap">
            <ul>
                <div class="wr_content <?php echo $is_dhtml_editor ? $config['cf_editor'] : ''; ?>">
                    <?php if($board['bo_write_min'] || $board['bo_write_max']) { ?>
                    <!-- 최소/최대 글자 수 사용 시 -->
                    <p id="char_count_desc" class="help_text">이 게시판은 최소 <strong><?php echo $board['bo_write_min']; ?></strong>글자 이상, 최대 <strong><?php echo $board['bo_write_max']; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
                    <?php } ?>
                    <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>

                    <?php if($board['bo_write_min'] || $board['bo_write_max']) { ?>
                    <?php if(!$is_dhtml_editor) { ?>
                    <div id="char_count_wrap"><span id="char_count"></span>글자</div>
                    <?php } ?>
                    <?php } ?>

                </div>

                <?php if(!$is_dhtml_editor) { ?>
                <style>
                    .wr_content>textarea {
                        overflow: hidden;
                    }
                </style>
                <script>
                    //에디터가 아닌경우 textarea의 높이 자동설정
                    $(document).ready(function() {
                        $('.wr_content > textarea').on('input', function() {
                            this.style.height = 'auto'; /* 높이를 자동으로 설정합니다. */
                            this.style.height = (this.scrollHeight) + 'px'; /* 스크롤 높이를 textarea에 적용합니다. */
                            this.style.minHeight = '300px';
                        });
                    });
                </script>
                <?php } ?>
            </ul>
        </div>
        <!-- } -->


        <!-- 비회원 { -->
        <?php if ($is_name) { ?>
        <div class="rb_inp_wrap">
            <ul class="guest_inp_wrap">

                <lebel class="help_text">작성자 정보를 입력해주세요. 비밀번호는 게시글 수정 시 사용됩니다.</lebel>
                <li>

                    <input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="input_tiny required" placeholder="성함">


                    <?php if ($is_password) { ?>
                    <input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="input_tiny <?php echo $password_required ?>" placeholder="비밀번호">
                    <?php } ?>
                </li>


                <li>
                    <?php if ($is_email) { ?>
                    <input type="text" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="input_tiny email " placeholder="이메일">
                    <?php } ?>
                </li>

            </ul>
        </div>
        <!-- } -->
        <?php } ?>

        <?php if(isset($is_link) && $is_link) { ?>
        <!-- 링크 { -->
        <div class="rb_inp_wrap rb_inp_wrap_gap">
            <label class="help_text">링크 주소를 입력할 수 있어요.</label>
            <?php for ($i=1; $is_link && $i<=G5_LINK_COUNT; $i++) { ?>
            <ul class="rb_inp_wrap_link">
                <i><img src="<?php echo $board_skin_url ?>/img/ico_link.svg"></i>
                <input type="text" name="wr_link<?php echo $i ?>" value="<?php if($w=="u"){ echo $write['wr_link'.$i]; } ?>" id="wr_link<?php echo $i ?>" class="input full_input">
            </ul>
            <?php } ?>

            </ul>
        </div>
        <!-- } -->
        <?php } ?>


        <?php if(isset($board['bo_upload_count']) && $board['bo_upload_count'] > 0) { ?>
        <?php
                        $wr_file = isset($wr_file) ? $wr_file : [];
                        $wf_cnt = count((array)$wr_file) + 1;
                        ?>
        <?php if (isset($is_file) && $is_file && $wf_cnt > 0): ?>

        <!-- 파일 { -->
        <div class="rb_inp_wrap rb_inp_wrap_gap">
            <label class="help_text">
                최대 <?php echo $board['bo_upload_count']; ?>개 / 이미지 및 일반 파일을 첨부할 수 있어요.<br>
                업로드된 이미지 파일을 클릭하면 [대표] 이미지를 변경할 수 있으며, 클릭+드래그 하는 경우 순서를 변경할 수 있어요.<br>
                [대표] 로 설정된 이미지는 순서에 상관없이 저장시 맨앞으로 이동되요.
            </label>

            <div class="">


                <?php
                          $new_files = [];
                          if (isset($w) && $w == 'u') {
                            // 파일이 존재하는지 확인
                            if (isset($file) && is_array($file)) {
                              foreach ($file as $k => $v) {
                                // 등록된 파일에는 삭제시 필요한 bf_file 필드 추가
                                if (empty($v['file'])) {
                                  continue;
                                }
                                $new_files[] = $v;
                              }
                            }
                          } else {
                            $new_files = [];
                          }
                          ?>
                <input type="file" name="bf_file[]" style="display:none;" />


                <div class="divmb-10">
                    <input type="hidden" id="ajax_files" name="ajax_files" value="" />
                    <div style="position:relative;">
                        <input type="file" id="pic" name="pic" onchange="upload_start()" multiple="multiple" class="au_input" />
                        <div class="au_btn_search_file font-b">파일을 여기에 끌어놓으세요.</div>
                    </div>


                    <div class="swiper-container swiper-wfile" style="overflow: inherit; padding-bottom:15px; font-size:11px;">
                        <div class="swiper-wrapper" id="file_list">
                            <?php foreach($new_files as $v): ?>
                            <div class="swiper-slide swiper-slide_lists" data-bf-file="<?php echo $v['file']; ?>" data-bf-source="<?php echo htmlspecialchars($v['source'], ENT_QUOTES); ?>" data-is-image="<?php echo $v['view'] ? '1' : '0'; ?>">
                                <div class="au_file_list">
                                    <div class="au_file_list_img_wrap js-rep-toggle">
                                        <?php if($v['view']) { ?>
                                        <img src="<?php echo $v['href']; ?>" alt="">
                                        <?php } else { 
                                                $pinfo = pathinfo($v['source']); ?>
                                        <div class="w_pd">
                                            <a href="javascript:void(0);" class="w_etc w_<?php echo $pinfo['extension']?>"><?php echo $pinfo['extension']?></a>
                                        </div>
                                        <?php } ?>
                                        <!-- 대표 뱃지: 기본은 숨김, 대표이면 보여줌 -->
                                        <span class="au_rep_badge font-r">대표</span>
                                    </div>

                                    <div class="au_btn_del font-r" onclick="delete_file('<?php echo $v['file']?>', this)">삭제</div>
                                    <div class="cut" style="margin-top:5px;"><a href="<?php echo $v['href']?>" download><?php echo $v['source'] ?></a></div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <script>
                        var swiper_file = new Swiper('.swiper-wfile', {
                            slidesPerColumnFill: 'row',
                            slidesPerView: 10, // 가로갯수
                            slidesPerColumn: 99, // 세로갯수
                            spaceBetween: 7, // 간격
                            touchRatio: 0, // 드래그 가능여부(1, 0)

                            breakpoints: { // 반응형 처리
                                1024: {
                                    slidesPerView: 10, // 가로갯수
                                    slidesPerColumn: 99, // 세로갯수
                                    touchRatio: 0,
                                },
                                10: {
                                    slidesPerView: 3.8, // 가로갯수
                                    slidesPerColumn: 1, // 세로갯수
                                    touchRatio: 1,
                                }
                            }
                        });
                    </script>

                    <div class="au_progress">
                        <div id="son" class="font-R au_bars"></div>
                    </div>
                </div>

                <script type="text/javascript">
                    var ajax_files = {
                        'files': <?php echo empty($new_files) ? '[]' : json_encode($new_files)?>,
                        'del': []
                    };
                    var xhr = new XMLHttpRequest();

                    function buildFilesFromDOMOrder() {
                        var list = [];
                        $('#file_list .swiper-slide_lists').each(function() {
                            var bf = $(this).data('bf-file') || '';
                            var src = $(this).data('bf-source') || '';

                            // 기존 ajax_files.files에서 해당 항목( bf_file==bf || file==bf ) 찾아 상세필드 보존
                            var found = null;
                            if (Array.isArray(ajax_files.files)) {
                                for (var i = 0; i < ajax_files.files.length; i++) {
                                    var it = ajax_files.files[i] || {};
                                    if (it.bf_file === bf || it.file === bf) {
                                        found = it;
                                        break;
                                    }
                                }
                            }
                            // 못 찾으면 최소 필드라도 채움
                            if (!found) found = {
                                bf_file: bf,
                                bf_source: src
                            };
                            // 정규화: 서버가 bf_*를 우선 보게
                            if (!found.bf_file && bf) found.bf_file = bf;
                            if (!found.bf_source && src) found.bf_source = src;

                            list.push(found);
                        });
                        ajax_files.files = list;
                        $("#ajax_files").val(JSON.stringify(ajax_files));
                    }

                    function upload_start() {
                        var cnts = $("#file_list .swiper-slide_lists").length;
                        var maxUploadCount = <?php echo $board['bo_upload_count']; ?>;
                        var picFileList = $("#pic").get(0).files;

                        if (cnts + picFileList.length > maxUploadCount) {
                            alert("첨부파일은 " + maxUploadCount + "개 이하만 업로드 가능합니다.");
                            return false;
                        }

                        var formData = new FormData();
                        formData.append("act_type", "upload");
                        formData.append("write_table", "<?php echo $write_table ?>");
                        formData.append("bo_table", "<?php echo $bo_table ?>");
                        formData.append("wr_id", "<?php echo $wr_id ?>");
                        for (var i = 0; i < picFileList.length; i++) {
                            formData.append("file[]", picFileList[i]);
                        }

                        var xhr = new XMLHttpRequest();
                        xhr.upload.addEventListener("progress", onprogress, false);
                        xhr.addEventListener("error", upload_failed, false);
                        xhr.addEventListener("load", upload_success, false);
                        xhr.open("POST", "<?php echo G5_URL ?>/rb/rb.lib/ajax.upload.php");
                        xhr.send(formData);
                    }

                    function onprogress(evt) {
                        var loaded = evt.loaded;
                        var tot = evt.total;
                        var per = Math.floor(100 * loaded / tot);
                        $("#son").parent().css("display", "block");
                        //$("#son").html(per + "%");
                        $("#son").css("width", per + "%");
                        if (per > 99) {
                            $("#son").parent().css("display", "none");
                        }
                    }

                    function upload_failed(evt) {
                        alert("업로드에 실패하였습니다.");
                    }

                    function escapeHtml(s) {
                        if (s == null) return '';
                        return String(s).replace(/[&<>"']/g, function(m) {
                            return ({
                                '&': '&amp;',
                                '<': '&lt;',
                                '>': '&gt;',
                                '"': '&quot;',
                                "'": '&#39;'
                            } [m]);
                        });
                    }

                    function upload_success(evt) {
                        var res = JSON.parse(evt.target.response);
                        if (res.res == 'true') {
                            for (var i = 0; i < res.list.length; i++) {
                                var item = res.list[i];
                                var file = item.bf_file;
                                var src = item.bf_source;

                                // 이미지 여부: view 안에 <img 가 있으면 이미지라고 판단
                                var isImg = (item.view && /<img\b/i.test(item.view)) ? 1 : 0;

                                var str = '<div class="swiper-slide swiper-slide_lists"' +
                                    ' data-bf-file="' + file + '"' +
                                    ' data-bf-source="' + escapeHtml(src) + '"' +
                                    ' data-is-image="' + isImg + '">';
                                str += '<div class="au_file_list">';
                                str += '<div class="au_file_list_img_wrap js-rep-toggle">';
                                if (isImg) {
                                    str += (item.view || '');
                                } else {
                                    // 비이미지 확장자 배지
                                    var ext = (src.split('.').pop() || '').toLowerCase();
                                    str += '<div class="w_pd"><a href="javascript:void(0);" class="w_etc w_' + ext + '">' + ext + '</a></div>';
                                }
                                str += '<span class="au_rep_badge font-r">대표</span>';
                                str += '</div>';
                                str += '<div class="au_btn_del font-r" onclick="delete_file(\'' + file + '\', this)">삭제</div>';
                                str += '<div class="cut" style="margin-top:5px;">' + escapeHtml(src) + '</div>';
                                str += '</div>';
                                str += '</div>';

                                $("#file_list").append(str);
                                ajax_files.files.push(item);
                            }
                            swiper_file.update();
                            ensureRepresentativeExists();
                            $("#ajax_files").val(JSON.stringify(ajax_files));
                        } else {
                            alert(res.msg);
                        }
                    }

                    function delete_file(file, obj) {
                        var formData = new FormData();
                        formData.append("act_type", "delete");
                        formData.append("write_table", "<?php echo $write_table ?>");
                        formData.append("bo_table", "<?php echo $bo_table ?>");
                        formData.append("wr_id", "<?php echo $wr_id ?>");
                        formData.append("bf_file", file);
                        xhr.open("POST", "<?php echo G5_URL ?>/rb/rb.lib/ajax.upload.php");
                        xhr.send(formData);

                        // UI/배열 반영
                        var $slide = $(obj).closest('.swiper-slide');
                        var deletedFile = $slide.data('bf-file');
                        $slide.remove();

                        // ajax_files.del 기록 (이미 있음)
                        ajax_files.del.push(file);

                        // ajax_files.files에서도 제거
                        if (Array.isArray(ajax_files.files)) {
                            ajax_files.files = ajax_files.files.filter(function(f) {
                                return f.bf_file !== deletedFile;
                            });
                        }

                        // 대표 보정 + 히든 갱신
                        handleRepresentativeAfterDelete();
                        $("#ajax_files").val(JSON.stringify(ajax_files));
                    }


                    // 전역 대표 파일 식별자
                    var repFile = null;

                    // 초기 진입 시 기존 파일들에서 대표가 없으면 첫 번째를 대표로 지정
                    $(document).ready(function() {
                        ensureRepresentativeExists();
                        bindRepresentativeHandlers();

                        // 폼 전송 시: DOM과 ajax_files 순서를 대표가 앞으로 오도록 정렬
                        $('#fwrite, form[name="fwrite"]').on('submit', function() {
                            reorderByRepresentative();
                            buildFilesFromDOMOrder();
                            // 히든 갱신
                            $("#ajax_files").val(JSON.stringify(ajax_files));
                        });
                    });



                    // 대표 뱃지/클릭 바인딩
                    function bindRepresentativeHandlers() {
                        // 이미지 클릭으로 대표 지정
                        $('#file_list').on('click', '.js-rep-toggle', function() {
                            var $slide = $(this).closest('.swiper-slide_lists');
                            setRepresentativeBySlide($slide);
                        });
                    }

                    // 대표가 하나도 없으면 첫번째를 대표로
                    function ensureRepresentativeExists() {
                        var $slidesAll = $('#file_list .swiper-slide_lists');
                        var $imgSlides = $slidesAll.filter(function() {
                            return String($(this).data('is-image')) === '1';
                        });

                        if (!$imgSlides.length) {
                            repFile = null;
                            return;
                        }

                        // 현 대표가 유효한 이미지인지 확인
                        if (repFile) {
                            var ok = $imgSlides.filter(function() {
                                return $(this).data('bf-file') == repFile;
                            }).length > 0;
                            if (ok) {
                                refreshRepUI();
                                return;
                            }
                        }
                        // 첫 이미지 슬라이드를 대표로
                        setRepresentativeBySlide($imgSlides.first());
                    }

                    // 슬라이드 기준으로 대표 지정
                    function setRepresentativeBySlide($slide) {
                        if (!$slide || !$slide.length) return;

                        // 이미지가 아니면 대표 지정 불가
                        var isImg = String($slide.data('is-image')) === '1';
                        if (!isImg) {
                            alert('이미지 파일만 대표로 지정할 수 있어요.');
                            return;
                        }

                        // 대표 파일 갱신
                        repFile = $slide.data('bf-file') || null;

                        // 전체 초기화
                        var $slides = $('#file_list .swiper-slide_lists');
                        $slides.removeClass('is-rep');
                        $slides.find('.au_rep_badge').remove();

                        // 클릭한 슬라이드만 대표 적용
                        $slide.addClass('is-rep');
                        $slide.find('.au_file_list_img_wrap').append('<span class="au_rep_badge font-r">대표</span>');
                    }

                    function refreshRepUI() {
                        var $slides = $('#file_list .swiper-slide_lists');

                        // 전체 초기화
                        $slides.removeClass('is-rep');
                        $slides.find('.au_rep_badge').remove();

                        if (!repFile) return;

                        // 대표 적용
                        var $rep = $slides.filter(function() {
                            return $(this).data('bf-file') == repFile;
                        }).first();

                        if ($rep.length) {
                            $rep.addClass('is-rep');
                            var $wrap = $rep.find('.au_file_list_img_wrap');
                            $wrap.append('<span class="au_rep_badge font-r">대표</span>');
                            // 혹시 다른 CSS가 display를 막으면 강제로 표시
                            $wrap.find('.au_rep_badge').last().css('display', 'inline-block');
                        }
                    }

                    // 대표가 삭제되면 다음 첫번째로 자동 대표 지정
                    function handleRepresentativeAfterDelete() {
                        var $slides = $('#file_list .swiper-slide_lists');
                        if (!$slides.length) {
                            repFile = null;
                            return;
                        }
                        if (!repFile) {
                            setRepresentativeBySlide($slides.first());
                            return;
                        }
                        // repFile이 남아있는지 확인
                        var stillExists = $slides.filter(function() {
                            return $(this).data('bf-file') == repFile;
                        }).length > 0;

                        if (!stillExists) {
                            setRepresentativeBySlide($slides.first());
                        } else {
                            refreshRepUI();
                        }
                    }

                    // 제출 전: 대표 슬라이드를 맨 앞으로 이동 + ajax_files.files 배열도 대표 먼저로 재정렬
                    function reorderByRepresentative() {
                        if (!repFile) return;

                        // 1) DOM 재배치
                        var $list = $('#file_list');
                        var $rep = $list.find('.swiper-slide_lists').filter(function() {
                            return $(this).data('bf-file') == repFile;
                        }).first();
                        if ($rep.length) $rep.prependTo($list);

                        // 2) 배열 재정렬 (bf_file || file 둘 다 케어)
                        if (Array.isArray(ajax_files.files) && ajax_files.files.length) {
                            var idx = -1;
                            for (var i = 0; i < ajax_files.files.length; i++) {
                                var it = ajax_files.files[i] || {};
                                if (it.bf_file === repFile || it.file === repFile) {
                                    idx = i;
                                    break;
                                }
                            }
                            if (idx > 0) {
                                var repItem = ajax_files.files.splice(idx, 1)[0];
                                ajax_files.files.unshift(repItem);
                            } else if (idx === -1) {
                                // 배열에서 못 찾았으면 최소 정보로 앞에 추가 (안전장치)
                                ajax_files.files.unshift({
                                    bf_file: repFile
                                });
                            }
                        }
                    }
                </script>

                <script>
                    // === 스와이퍼를 건드리지 않는 파일 정렬: 핸들 기반 DnD ===
                    (function() {
                        var listEl = document.getElementById('file_list');
                        if (!listEl) return;

                        // 1) 모든 슬라이드에 핸들 주입 (중복 방지)
                        function ensureHandles() {
                            listEl.querySelectorAll('.swiper-slide_lists').forEach(function(slide) {
                                if (!slide.querySelector('.rb-dnd-handle')) {
                                    var h = document.createElement('div');
                                    h.className = 'rb-dnd-handle';
                                    h.innerHTML = '≡';
                                    // 이미지/버튼 위라도 핸들이 최상단서만 이벤트 받게
                                    slide.appendChild(h);
                                }
                            });
                        }
                        ensureHandles();
                        // 업로드/삭제 후에도 새 슬라이드 생기면 다시 붙일 수 있도록 관찰
                        var mo = new MutationObserver(ensureHandles);
                        mo.observe(listEl, {
                            childList: true,
                            subtree: false
                        });

                        // 공통 유틸
                        function slides() {
                          return Array.prototype.slice.call(
                            listEl.querySelectorAll('.swiper-slide_lists')
                          ).filter(function(el){
                            return !el.classList.contains('dnd-placeholder');
                          });
                        }

                        function getPoint(e) {
                            return {
                                x: e.clientX,
                                y: e.clientY
                            };
                        }

                        function indexByX(clientX) {
                            var arr = slides();
                            if (!arr.length) return -1;
                            var idx = 0,
                                min = Infinity;
                            for (var i = 0; i < arr.length; i++) {
                                var r = arr[i].getBoundingClientRect();
                                var cx = r.left + r.width / 2;
                                var d = Math.abs(clientX - cx);
                                if (d < min) {
                                    min = d;
                                    idx = i;
                                }
                            }
                            return idx;
                        }

                        var dragging = false;
                        var dragEl = null,
                            ghostEl = null,
                            placeholder = null;
                        var startPt = {
                                x: 0,
                                y: 0
                            },
                            startRect = null;
                        var suppressClick = false;

                        // 2) 핸들에서만 DnD 시작
                        listEl.addEventListener('pointerdown', function(e) {
                            var handle = e.target.closest('.rb-dnd-handle');
                            if (!handle) return; // 핸들이 아니면 스와이퍼/기존 클릭 그대로 통과

                            var slide = handle.closest('.swiper-slide_lists');
                            if (!slide) return;

                            // 스와이퍼 이벤트와 충돌 막기 위해 이 흐름만 멈춤 (스와이퍼 설정은 건드리지 않음)
                            e.stopPropagation();
                            e.preventDefault();

                            // 초기화
                            var pt = getPoint(e);
                            startPt = pt;
                            startRect = slide.getBoundingClientRect();

                            // placeholder
                            placeholder = document.createElement('div');
                            placeholder.className = 'swiper-slide swiper-slide_lists dnd-placeholder';
                            placeholder.style.width = startRect.width + 'px';
                            placeholder.style.height = '90px';
                            listEl.insertBefore(placeholder, slide);

                            // 원본은 자리 유지 + 숨김(튀어보이는 문제 제거)
                            dragEl = slide;
                            dragEl.classList.add('dragging');

                            // 고스트: 이미지가 있으면 썸네일, 없으면 라벨
                            ghostEl = document.createElement('div');
                            ghostEl.id = 'rb-dnd-ghost';
                            // 원본과 동일한 크기로 보여주면 자연스러움
                            ghostEl.style.width = startRect.width + 'px';
                            ghostEl.style.height = '90px';

                            // 슬라이드가 이미지인지 확인
                            var isImg = String(dragEl.dataset.isImage || dragEl.getAttribute('data-is-image')) === '1';
                            if (isImg) {
                                // 썸네일 <img>를 찾아서 복제
                                var img = dragEl.querySelector('.au_file_list_img_wrap img');
                                if (img) {
                                    var clone = img.cloneNode(true);
                                    // 스타일: 꽉 채우기
                                    clone.classList.add('ghost-img');
                                    // lazy 속성이 있으면 즉시 렌더 위해 제거
                                    clone.removeAttribute('loading');
                                    ghostEl.appendChild(clone);
                                } else {
                                    // 혹시 view가 <img> 외 래퍼일 수 있으니, 배경이미지 대안
                                    var bg = dragEl.querySelector('.au_file_list_img_wrap img, .au_file_list_img_wrap [style*="background-image"]');
                                    if (bg) {
                                        ghostEl.style.background = window.getComputedStyle(bg).background || '#fff';
                                        ghostEl.style.backgroundSize = 'cover';
                                        ghostEl.style.backgroundPosition = 'center';
                                    } else {
                                        // 마지막 폴백: 라벨
                                        var fallback = document.createElement('div');
                                        fallback.className = 'ghost-label';
                                        fallback.textContent = '이미지';
                                        ghostEl.appendChild(fallback);
                                    }
                                }
                            } else {
                                // 비이미지인 경우: 파일명 라벨 유지
                                var label = dragEl.getAttribute('data-bf-source') || (dragEl.querySelector('.cut')?.textContent) || '파일';
                                var div = document.createElement('div');
                                div.className = 'ghost-label';
                                div.textContent = (label.length > 28 ? label.slice(0, 25) + '…' : label);
                                ghostEl.appendChild(div);
                            }

                            document.body.appendChild(ghostEl);
                            ghostEl.style.left = startRect.left + 'px';
                            ghostEl.style.top = startRect.top + 'px';

                            // 드래그 모드 진입
                            dragging = true;
                            listEl.classList.add('reorder-active');
                            document.body.classList.add('dnd-noselect');

                            // move/up 핸들링
                            var moved = false;

                            function onMove(ev) {
                                if (!dragging) return;
                                ev.stopPropagation();
                                ev.preventDefault();
                                    
                                var p = getPoint(ev);
                                var dx = p.x - startPt.x;
                                var dy = p.y - startPt.y;
                                ghostEl.style.transform = 'translate3d(' + dx + 'px,' + dy + 'px,0)';
                                if (!moved && (Math.abs(dx) > 2 || Math.abs(dy) > 2)) moved = true;
                                if (!moved) return;

                                // placeholder 위치 갱신
                                var idx = indexByX(p.x);
                                var arr = slides().filter(function(el) {
                                    return el !== dragEl;
                                });

                                if (arr.length) {
                                    // 마지막 카드의 오른쪽을 충분히 넘겼다면 → 맨 끝으로 보냄
                                    var last = arr[arr.length - 1];
                                    var lr = last.getBoundingClientRect();
                                    if (p.x >= lr.right - 4) {
                                        if (placeholder.parentNode !== listEl || placeholder.nextSibling !== null) {
                                            listEl.appendChild(placeholder); // ★ 핵심: 진짜 끝으로
                                        }
                                    } else {
                                        // 중앙선 기준으로 ref 잡아서 '앞'에 끼우기 (기존 동작)
                                        if (idx < 0) idx = 0;
                                        if (idx >= arr.length) idx = arr.length - 1;
                                        var ref = arr[idx];
                                        if (ref && ref !== placeholder && ref.parentNode === listEl) {
                                            listEl.insertBefore(placeholder, ref);
                                        }
                                    }
                                }


                                // 스와이퍼 컨테이너 가장자리에서만 약간씩 스크롤 (설정 변경 X)
                                var sc = (listEl.closest('.swiper-container') || listEl);
                                var box = sc.getBoundingClientRect(),
                                    edge = 26,
                                    speed = 10;
                                if (p.x < box.left + edge) sc.scrollLeft -= speed;
                                if (p.x > box.right - edge) sc.scrollLeft += speed;
                            }

                            function onUp(ev) {
                                // 정리
                                if (ghostEl && ghostEl.parentNode) ghostEl.parentNode.removeChild(ghostEl);
                                ghostEl = null;

                                if (placeholder && placeholder.parentNode === listEl) {
                                   if (moved) {
                                      // 실제로 움직였을 때만 재배치
                                      listEl.insertBefore(dragEl, placeholder);
                                    }
                                    // placeholder 정리(있을 때만)
                                    if (placeholder.parentNode) {
                                      placeholder.parentNode.removeChild(placeholder);
                                    }
                                    moved = false; // 상태 초기화
                                }
                                placeholder = null;

                                dragEl.classList.remove('dragging');
                                dragEl = null;

                                listEl.classList.remove('reorder-active');
                                document.body.classList.remove('dnd-noselect');
                                dragging = false;

                                // 드래그 후 남는 클릭 억제(대표 이미지 클릭 보호)
                                if (moved) {
                                    suppressClick = true;
                                    setTimeout(function() {
                                        suppressClick = false;
                                    }, 120);
                                }

                                // 순서 반영
                                if (typeof buildFilesFromDOMOrder === 'function') buildFilesFromDOMOrder();
                                if (typeof refreshRepUI === 'function') refreshRepUI();
                                if (typeof ajax_files !== 'undefined') $("#ajax_files").val(JSON.stringify(ajax_files));

                                // 이벤트 해제
                                window.removeEventListener('pointermove', onMove, {
                                    passive: false
                                });
                                window.removeEventListener('pointerup', onUp, {
                                    passive: true
                                });
                                window.removeEventListener('pointercancel', onUp, {
                                    passive: true
                                });
                            }

                            window.addEventListener('pointermove', onMove, {
                                passive: false
                            });
                            window.addEventListener('pointerup', onUp, {
                                passive: true
                            });
                            window.addEventListener('pointercancel', onUp, {
                                passive: true
                            });
                        }, {
                            passive: false
                        });

                        // 드래그 직후 생기는 클릭만 막아 기존 대표지정 클릭은 그대로 유지
                        listEl.addEventListener('click', function(e) {
                            if (suppressClick) {
                                e.stopPropagation();
                                e.preventDefault();
                            }
                        }, true);
                    })();
                </script>



            </div>
        </div>
        <?php endif; ?>
        <?php } ?>
        <!-- } -->


        <?php if ($is_use_captcha) { //자동등록방지  ?>
        <div class="rb_inp_wrap">
            <ul>
                <?php echo $captcha_html ?>
            </ul>
        </div>
        <?php } ?>


        <div class="rb_inp_wrap_confirm">
            <a href="<?php echo get_pretty_url($bo_table); ?>" class="btn_cancel btn font-B">취소</a>
            <button type="submit" id="btn_submit" accesskey="s" class="btn_submit btn font-B">작성완료</button>
        </div>

    </form>
</div>






<script>
    <?php if($board['bo_write_min'] || $board['bo_write_max']) { ?>
    // 글자수 제한
    var char_min = parseInt(<?php echo $board['bo_write_min']; ?>); // 최소
    var char_max = parseInt(<?php echo $board['bo_write_max']; ?>); // 최대
    check_byte("wr_content", "char_count");

    $(function() {
        $("#wr_content").on("keyup", function() {
            check_byte("wr_content", "char_count");
        });
    });

    <?php } ?>

    function html_auto_br(obj) {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        } else
            obj.value = "";
    }

    function fwrite_submit(f) {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url + "/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.wr_subject.value,
                "content": f.wr_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('" + subject + "')가 포함되어있습니다");
            f.wr_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('" + content + "')가 포함되어있습니다");
            if (typeof(ed_wr_content) != "undefined")
                ed_wr_content.returnFalse();
            else
                f.wr_content.focus();
            return false;
        }

        if (document.getElementById("char_count")) {
            if (char_min > 0 || char_max > 0) {
                var cnt = parseInt(check_byte("wr_content", "char_count"));
                if (char_min > 0 && char_min > cnt) {
                    alert("내용은 " + char_min + "글자 이상 쓰셔야 합니다.");
                    return false;
                } else if (char_max > 0 && char_max < cnt) {
                    alert("내용은 " + char_max + "글자 이하로 쓰셔야 합니다.");
                    return false;
                }
            }
        }

        <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
</script>
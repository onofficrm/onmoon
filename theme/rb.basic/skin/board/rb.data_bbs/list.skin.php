<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css?ver='.G5_SERVER_TIME.'">', 0);


        // --- bo_list_opt 파싱해서 어떤 기본 컬럼을 노출할지 결정 ---
        $opt_raw = trim((string)($board['bo_list_opt'] ?? ''));
        $opt_arr = array_filter(array_map('trim', explode('|', $opt_raw)));
        $bo_list_flags = array(
            'v1' => in_array('v1', $opt_arr), // 번호
            'v2' => in_array('v2', $opt_arr), // 분류
            'v3' => in_array('v3', $opt_arr), // 제목
            'v4' => in_array('v4', $opt_arr), // 작성자
            'v5' => in_array('v5', $opt_arr), // 작성일
            'v6' => in_array('v6', $opt_arr), // 댓글
            'v7' => in_array('v7', $opt_arr), // 조회
            'v8' => in_array('v8', $opt_arr), // 이미지
        );

        // --- 여분필드(bo_1 ~ bo_10) 정리해서 화면에 뿌릴 컬럼 목록 만들기 ---
        // 저장형식: "type;viewFlag|opt1,opt2,..." 또는 구버전 "type|opt1,opt2"
        $extra_cols = array();        // 실제 테이블 컬럼으로 뿌릴 목록 (목록출력 on 인 것만)
        $filterable_fields = array(); // 검색필터로 쓸 수 있는 것만(select/radio/checkbox)

        for ($n = 1; $n <= 10; $n++) {
            $subj = isset($board['bo_'.$n.'_subj']) ? trim($board['bo_'.$n.'_subj']) : '';
            $raw  = isset($board['bo_'.$n])        ? trim($board['bo_'.$n])        : '';

            if ($subj === '' && $raw === '') continue;

            $type      = 'text';
            $viewFlag  = '1';   // 기본값: 노출
            $opts_arr  = array();

            if (strpos($raw, ';') !== false) {
                // 신포맷 "type;viewFlag|옵션들"
                list($typePart, $restPart) = explode(';', $raw, 2);
                $type = strtolower(trim($typePart));

                if (strpos($restPart, '|') !== false) {
                    list($viewFlagPart, $optsPart) = explode('|', $restPart, 2);
                    $viewFlag = trim($viewFlagPart) !== '' ? trim($viewFlagPart) : '1';
                    $opts_arr = array_map('trim', explode(',', $optsPart));
                } else {
                    // "text;1" 처럼 옵션 없는 경우
                    $viewFlag = trim($restPart) !== '' ? trim($restPart) : '1';
                }
            } else {
                // 구버전 "radio|항목1,항목2.항목3" 이런 패턴도 대응
                $parts = explode('|', $raw, 2); // [0]=type, [1]=옵션들(있을수도/없을수도)
                $type  = strtolower(trim($parts[0]));
                $viewFlag = '1';
                if (isset($parts[1]) && $parts[1] !== '') {
                    $opts_arr = array_map('trim', explode(',', $parts[1]));
                }
            }

            // 목록출력 체크 안 된 필드는 리스트 컬럼에서 빼야 하니까 여기서 거른다.
            if ($viewFlag !== '1') {
                // viewFlag가 0 이면 테이블 헤더/바디에도 안 나옴
                continue;
            }

            // extra_cols 쌓기: 나중에 테이블 thead / tbody 루프에서 사용
            $extra_cols[] = array(
                'n'     => $n,          // 몇 번 여분필드인지 (1~10)
                'subj'  => $subj,       // 컬럼 타이틀
                'type'  => $type,       // text / select / radio / checkbox
                'opts'  => $opts_arr,   // 선택지 배열
            );

            // 필터용 후보: select / checkbox / radio 이면서 옵션이 있는 경우만
            if (($type === 'select' || $type === 'checkbox' || $type === 'radio') && count($opts_arr) > 0) {
                $filterable_fields[] = array(
                    'n'    => $n,
                    'subj' => $subj,
                    'type' => $type,
                    'opts' => $opts_arr
                );
            }
        }

        // JS에서 쓰게 전역으로 내려준다.
        ?>
<script>
    window.EXTRA_FILTERS = <?php echo json_encode($filterable_fields, JSON_UNESCAPED_UNICODE); ?>;
    window.EXTRA_KEYS = <?php
            // 이건 hydrateRow() / snapshot()에서 data-* 로 처리할 키 목록
            $tmp_keys = array_map(function($c){ return 'wr_'.$c['n']; }, $extra_cols);
            echo json_encode($tmp_keys, JSON_UNESCAPED_UNICODE);
        ?>;
</script>


<style>
    /* 기본컬럼 숨김용 */
    .col-hide {
        display: none;
    }
</style>



<div class="rb_bbs_wrap" id="scroll_container" style="width:<?php echo $width; ?>">


    <div class="rb_datagrid_bbs">


        <div class="controls controls_1">
            <div class="control">
                <label>등록일</label>
                <div class="quick-row">
                    <input id="dateFrom" type="text" class="date-input datepicker_start datepicker_inp datepicker" readonly autocomplete="off">
                    <span>~</span>
                    <input id="dateTo" type="text" class="date-input datepicker_end datepicker_inp datepicker" readonly autocomplete="off">

                    <!-- 빠른 범위 버튼 (한 줄) -->
                    <div id="quickRanges" class="quick-buttons">
                        <button type="button" class="btn ghost" data-range="today">오늘</button>
                        <button type="button" class="btn ghost" data-range="thisWeek">이번주</button>
                        <button type="button" class="btn ghost" data-range="lastWeek">지난주</button>
                        <button type="button" class="btn ghost" data-range="thisMonth">당월</button>
                        <button type="button" class="btn ghost" data-range="lastMonth">전월</button>
                        <button type="button" class="btn ghost" data-range="all">전체</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="controls controls_2">

            <?php
            // qField 기본값을 뭘로 줄지 결정
            // 우선순위: 제목(v3) → 번호(v1) → (분류 v2) → 작성자(v4) → 없으면 첫 extra_cols → fallback 'title'
            $default_qField = 'title';
            if (!empty($bo_list_flags['v3'])) {
                $default_qField = 'title';
            } else if (!empty($bo_list_flags['v1'])) {
                $default_qField = 'id';
            } else if (!empty($bo_list_flags['v2']) && $is_category) {
                $default_qField = 'category';
            } else if (!empty($bo_list_flags['v4'])) {
                $default_qField = 'author';
            } else if (!empty($extra_cols)) {
                // 여분필드가 있다면 그 중 첫 번째를 기본으로
                $default_qField = 'wr_'.$extra_cols[0]['n'];
            }
            ?>

            <div class="control">
                <label for="q">검색</label>

                <select id="qField" class="select input_tiny" style="width:100px !important;">
                    <?php if (!empty($bo_list_flags['v3'])) { // 제목 ?>
                    <option value="title" <?php echo $default_qField==='title'?'selected':''; ?>>제목</option>
                    <?php } ?>

                    <?php if (!empty($bo_list_flags['v1'])) { // 번호 ?>
                    <option value="id" <?php echo $default_qField==='id'?'selected':''; ?>>번호</option>
                    <?php } ?>

                    <?php if ($is_category && !empty($bo_list_flags['v2'])) { // 분류 ?>
                    <option value="category" <?php echo $default_qField==='category'?'selected':''; ?>>분류</option>
                    <?php } ?>

                    <?php if (!empty($bo_list_flags['v4'])) { // 작성자 ?>
                    <option value="author" <?php echo $default_qField==='author'?'selected':''; ?>>작성자</option>
                    <?php } ?>

                    <?php
                    // 여분필드 옵션
                    // extra_cols: 목록출력(view=1)인 여분필드들
                    foreach ($extra_cols as $col) {
                        $key   = 'wr_'.$col['n']; // ex) wr_1
                        $label = $col['subj'] !== '' ? $col['subj'] : $key;
                    ?>
                    <option value="<?php echo $key; ?>" <?php echo $default_qField===$key?'selected':''; ?>>
                        <?php echo htmlspecialchars($label, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
                    </option>
                    <?php } ?>
                </select>

                <input id="q" type="search" placeholder="검색어를 입력하세요.">
            </div>

            <?php if ($is_category && !empty($bo_list_flags['v2'])) { ?>
            <div class="control">
                <label>분류</label>
                <div id="categoryBox" class="chipbox"></div>
            </div>
            <?php } ?>

        </div>

        <div class="controls controls_2 controls_dynamic"></div>




        <div class="toolbar">

            <div class="toolbar_inner">
                <button class="btn" id="clearFilters">초기화</button>
                <?php if($is_admin) { ?>
                <button class="btn" id="bulkOpenBtn">일괄등록</button>
                <button class="btn ghost" id="exportCsv">CSV</button>
                <button class="btn ghost" id="exportExcel">엑셀</button>
                <button class="btn ghost" id="exportPDF">PDF</button>
                <?php } ?>
            </div>

            <div class="rb-set-data">
                <div class="muted" id="resultInfo">0건</div>

                <?php if($board['bo_read_point'] || $board['bo_write_point'] || $board['bo_comment_point'] || $board['bo_download_point']) { ?>
                <div class="point_info_btns_wrap">
                    <button type="button" class="point_info_btns" id="point_info_opens_btn">
                        <i><svg width="14" height="14" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10 0C15.523 0 20 4.477 20 10C20 15.523 15.523 20 10 20C4.477 20 0 15.523 0 10C0 4.477 4.477 0 10 0ZM11.5 5H9C8.46957 5 7.96086 5.21071 7.58579 5.58579C7.21071 5.96086 7 6.46957 7 7V14C7 14.2652 7.10536 14.5196 7.29289 14.7071C7.48043 14.8946 7.73478 15 8 15C8.26522 15 8.51957 14.8946 8.70711 14.7071C8.89464 14.5196 9 14.2652 9 14V12H11.5C12.4283 12 13.3185 11.6313 13.9749 10.9749C14.6313 10.3185 15 9.42826 15 8.5C15 7.57174 14.6313 6.6815 13.9749 6.02513C13.3185 5.36875 12.4283 5 11.5 5ZM11.5 7C11.8978 7 12.2794 7.15804 12.5607 7.43934C12.842 7.72064 13 8.10218 13 8.5C13 8.89782 12.842 9.27936 12.5607 9.56066C12.2794 9.84196 11.8978 10 11.5 10H9V7H11.5Z" fill="#09244B" />
                            </svg></i>
                        <span class="pc">포인트정책</span></button>

                    <div class="point_info_opens">
                        <h6><?php echo $board['bo_subject'] ?> 포인트 정책</h6>
                        <ul>
                            <?php if($board['bo_read_point']) { ?>
                            <dl>
                                <dd>글읽기</dd>
                                <dd class="font-B"><?php echo number_format($board['bo_read_point']); ?>P</dd>
                            </dl>
                            <?php } ?>
                            <?php if($board['bo_write_point']) { ?>
                            <dl>
                                <dd>글쓰기</dd>
                                <dd class="font-B"><?php echo number_format($board['bo_write_point']); ?>P</dd>
                            </dl>
                            <?php } ?>
                            <?php if($board['bo_comment_point']) { ?>
                            <dl>
                                <dd>댓글</dd>
                                <dd class="font-B"><?php echo number_format($board['bo_comment_point']); ?>P</dd>
                            </dl>
                            <?php } ?>
                            <?php if($board['bo_download_point']) { ?>
                            <dl>
                                <dd>다운로드</dd>
                                <dd class="font-B"><?php echo number_format($board['bo_download_point']); ?>P</dd>
                            </dl>
                            <?php } ?>
                        </ul>
                    </div>

                    <script>
                        $(document).ready(function() {
                            $(document).click(function(event) {
                                if (!$(event.target).closest('#point_info_opens_btn, .point_info_opens').length) {
                                    if ($('.point_info_opens').is(':visible')) {
                                        $('.point_info_opens').hide();
                                        $('.rb_bbs_set_opens').hide();
                                        $('#point_info_opens_btn').removeClass('act');
                                        $('#rb_bbs_set_btn').removeClass('act');
                                    }
                                }
                            });

                            $('#point_info_opens_btn').click(function(event) {
                                event.stopPropagation();
                                $('.point_info_opens').toggle();
                                $('.rb_bbs_set_opens').hide();
                                $('#rb_bbs_set_btn').removeClass('act');
                                $(this).toggleClass('act');
                            });
                        });
                    </script>


                </div>
                <?php } ?>

                <?php if($is_admin) { ?>
                <div class="point_info_btns_wrap">
                    <button type="button" class="point_info_btns rb_bbs_set_btn" id="rb_bbs_set_btn">
                        <i><svg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24'>
                                <g fill='none' fill-rule='evenodd'>
                                    <path d='M24 0v24H0V0zM12.593 23.258l-.011.002-.071.035-.02.004-.014-.004-.071-.035c-.01-.004-.019-.001-.024.005l-.004.01-.017.428.005.02.01.013.104.074.015.004.012-.004.104-.074.012-.016.004-.017-.017-.427c-.002-.01-.009-.017-.017-.018m.265-.113-.013.002-.185.093-.01.01-.003.011.018.43.005.012.008.007.201.093c.012.004.023 0 .029-.008l.004-.014-.034-.614c-.003-.012-.01-.02-.02-.022m-.715.002a.023.023 0 0 0-.027.006l-.006.014-.034.614c0 .012.007.02.017.024l.015-.002.201-.093.01-.008.004-.011.017-.43-.003-.012-.01-.01z' />
                                    <path fill='#09244BFF' d='M10.586 2.1a2 2 0 0 1 2.7-.116l.128.117L15.314 4H18a2 2 0 0 1 1.994 1.85L20 6v2.686l1.9 1.9a2 2 0 0 1 .116 2.701l-.117.127-1.9 1.9V18a2 2 0 0 1-1.85 1.995L18 20h-2.685l-1.9 1.9a2 2 0 0 1-2.701.116l-.127-.116-1.9-1.9H6a2 2 0 0 1-1.995-1.85L4 18v-2.686l-1.9-1.9a2 2 0 0 1-.116-2.701l.116-.127 1.9-1.9V6a2 2 0 0 1 1.85-1.994L6 4h2.686zM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6' />
                                </g>
                            </svg></i>
                        <span class="pc">설정</span></button>

                    <div class="rb_bbs_set_opens">

                        <div class="adm_bbs_set_wrap">

                            <div>
                                <h6 class="">목록출력 설정</h6>
                                <dl class="rb_bbs_set_select_wrap">

                                    <div class="">
                                        <input type="checkbox" name="bo_list_opt" id="bo_list_opt_v1" value="v1">
                                        <label for="bo_list_opt_v1">번호</label>　
                                    </div>

                                    <?php if ($is_category) { ?>
                                    <div class="">
                                        <input type="checkbox" name="bo_list_opt" id="bo_list_opt_v2" value="v2">
                                        <label for="bo_list_opt_v2">분류</label>　
                                    </div>
                                    <?php } ?>

                                    <div class="">
                                        <input type="checkbox" name="bo_list_opt" id="bo_list_opt_v3" value="v3">
                                        <label for="bo_list_opt_v3">제목</label>　
                                    </div>

                                    <div class="">
                                        <input type="checkbox" name="bo_list_opt" id="bo_list_opt_v4" value="v4">
                                        <label for="bo_list_opt_v4">작성자</label>　
                                    </div>

                                    <div class="">
                                        <input type="checkbox" name="bo_list_opt" id="bo_list_opt_v5" value="v5">
                                        <label for="bo_list_opt_v5">작성일</label>　
                                    </div>

                                    <div class="">
                                        <input type="checkbox" name="bo_list_opt" id="bo_list_opt_v6" value="v6">
                                        <label for="bo_list_opt_v6">댓글</label>　
                                    </div>

                                    <div class="">
                                        <input type="checkbox" name="bo_list_opt" id="bo_list_opt_v7" value="v7">
                                        <label for="bo_list_opt_v7">조회</label>　
                                    </div>

                                    <div class="">
                                        <input type="checkbox" name="bo_list_opt" id="bo_list_opt_v8" value="v8">
                                        <label for="bo_list_opt_v8">이미지</label>　
                                    </div>

                                </dl>
                            </div>

                            <script>
                                (function hydrateBoListOpt() {
                                    // PHP에서 현재 게시판 설정값을 그대로 내려줌
                                    var raw = <?php echo json_encode($board['bo_list_opt'] ?? ''); ?>;

                                    if (!raw) {
                                        // 저장된 값이 없으면 기본은 전부 보이도록 전체 체크
                                        jQuery('#bo_list_opt_v1,#bo_list_opt_v2,#bo_list_opt_v3,#bo_list_opt_v4,#bo_list_opt_v5,#bo_list_opt_v6,#bo_list_opt_v7,#bo_list_opt_v8')
                                            .prop('checked', true);
                                        return;
                                    }

                                    var map = {};
                                    raw.split('|').forEach(function(v) {
                                        v = jQuery.trim(v);
                                        if (v) map[v] = true;
                                    });

                                    if (map['v1']) jQuery('#bo_list_opt_v1').prop('checked', true);
                                    if (map['v2']) jQuery('#bo_list_opt_v2').prop('checked', true);
                                    if (map['v3']) jQuery('#bo_list_opt_v3').prop('checked', true);
                                    if (map['v4']) jQuery('#bo_list_opt_v4').prop('checked', true);
                                    if (map['v5']) jQuery('#bo_list_opt_v5').prop('checked', true);
                                    if (map['v6']) jQuery('#bo_list_opt_v6').prop('checked', true);
                                    if (map['v7']) jQuery('#bo_list_opt_v7').prop('checked', true);
                                    if (map['v8']) jQuery('#bo_list_opt_v8').prop('checked', true);
                                })();
                            </script>



                            <div id="bo1_block" style="display:none">
                                <h6 class="mt-10">여분필드1 설정</h6>
                                <dl class="rb_bbs_set_select_wrap">
                                    <dd class="po_rel">
                                        <label>타이틀</label>
                                        <input type="text" class="iuput tiny_input w100" name="bo_1_subj" id="bo_1_subj" value="<?php echo $board['bo_1_subj'] ?>">
                                    </dd>
                                    <dd class="po_rel">
                                        <label>속성</label>
                                        <select name="bo_1_type" id="bo_1_type" class="select input_tiny w100">
                                            <option value="text">text</option>
                                            <option value="checkbox">checkbox</option>
                                            <option value="radio">radio</option>
                                            <option value="select">select</option>
                                        </select>
                                    </dd>
                                    <dd class="po_rel w100">
                                        <label>항목</label>
                                        <input type="text" class="iuput tiny_input w100" name="bo_1_opt" id="bo_1_opt" placeholder="선택항목(콤마구분)">
                                    </dd>
                                    <div class="">
                                        <input type="checkbox" name="bo_1_view" id="bo_1_view">
                                        <label for="bo_1_view">목록출력</label>　
                                    </div>
                                    <input type="hidden" name="bo_1" id="bo_1" value="<?php echo $board['bo_1'] ?>">
                                </dl>
                            </div>

                            <div id="extraHelp" class="help w100">추가된 필드가 없습니다</div>
                            <div id="extraFields"></div>

                            <ul class="w100">
                                필드를 추가하는 경우 목록, 쓰기, 보기에 자동 출력 됩니다.<br>
                                선택항목이 있는 필드는 필터에 자동으로 추가 됩니다.
                            </ul>

                            <dd class="po_rel opt_set_btn_wrap w100">
                                <button type="button" class="adm_set_btn adm_set_btn2" id="add_field_btn">필드 추가</button>
                                <button type="button" class="adm_set_btn adm_set_btn2" id="remove_field_btn">필드 삭제</button>
                            </dd>

                            <dl class="rb_bbs_set_select_wrap">
                                <dd class="adm_set_btn_wrap w100"><button type="button" class="adm_set_btn" id="adm_set" onclick="adm_settings();">저장하기</button></dd>
                            </dl>

                            <?php
                              // 서버에 저장된 2~10번 값 주입
                              $rb_extra_fields = [];
                              for ($i = 2; $i <= 10; $i++) {
                                  $subj = isset($board['bo_'.$i.'_subj']) ? trim($board['bo_'.$i.'_subj']) : '';
                                  $val  = isset($board['bo_'.$i])       ? trim($board['bo_'.$i])       : '';
                                  if ($subj !== '' || $val !== '') {
                                      $rb_extra_fields[] = ['idx'=>$i, 'subj'=>$subj, 'val'=>$val];
                                  }
                              }
                            ?>
                            <script>
                                // 예: [{idx:2, subj:"제목2", val:"select|사과,수박"}]
                                var PRESET_FIELDS = <?php echo json_encode($rb_extra_fields, JSON_UNESCAPED_UNICODE); ?>;
                            </script>

                            <!-- 동적 세트(2~10) 생성기 -->
                            <script>
                                function toggleOptInputForSet(root) {
                                    var t = root.querySelector('.bo-type');
                                    var op = root.querySelector('.bo-opt');
                                    if (!t || !op) return;
                                    var needs = (t.value === 'select' || t.value === 'checkbox' || t.value === 'radio');
                                    op.disabled = !needs;
                                    op.placeholder = needs ? '선택항목(콤마구분)' : '(일반입력)';
                                }

                                function makeFieldSet(idx) {
                                    var wrap = document.createElement('div');
                                    wrap.className = 'extra-field-set';
                                    wrap.style.margin = '8px 0 14px';
                                    wrap.innerHTML =
                                        '<h6 class="mt-15">여분필드' + idx + ' 설정</h6>' +
                                        '<dl class="rb_bbs_set_select_wrap" data-index="' + idx + '">' +

                                        '<dd class="po_rel"><label>타이틀</label>' +
                                        '<input type="text" class="iuput tiny_input w100 bo-subj" ' +
                                        'name="bo_' + idx + '_subj" id="bo_' + idx + '_subj" placeholder="필드명"></dd>' +

                                        '<dd class="po_rel"><label>속성</label>' +
                                        '<select name="bo_' + idx + '_type" id="bo_' + idx + '_type" ' +
                                        'class="select input_tiny w100 bo-type">' +
                                        '<option value="text">text</option>' +
                                        '<option value="checkbox">checkbox</option>' +
                                        '<option value="radio">radio</option>' +
                                        '<option value="select">select</option>' +
                                        '</select></dd>' +

                                        '<dd class="po_rel w100"><label>항목</label>' +
                                        '<input type="text" class="iuput tiny_input w100 bo-opt" ' +
                                        'name="bo_' + idx + '_opt" id="bo_' + idx + '_opt" placeholder="선택항목(콤마구분)"></dd>' +

                                        '<div class="">' +
                                        '<input type="checkbox" class="bo-view" name="bo_' + idx + '_view" id="bo_' + idx + '_view" checked>' +
                                        '<label for="bo_' + idx + '_view">목록출력</label>　' +
                                        '</div>' +

                                        '<input type="hidden" name="bo_' + idx + '" id="bo_' + idx + '" class="bo-hidden">' +
                                        '</dl>';
                                    toggleOptInputForSet(wrap); // 그대로 유지
                                    return wrap;
                                }
                            </script>

                            <!-- 저장 -->
                            <script>
                                function adm_settings() {
                                    var data = {};
                                    var bo_table = "<?php echo $bo_table ?>";

                                    // 1) bo_list_opt 수집
                                    // 체크된 v1|v2|... 문자열 만들기
                                    var listOptArr = [];
                                    jQuery('input[name="bo_list_opt"]:checked').each(function() {
                                        listOptArr.push(jQuery(this).val());
                                    });
                                    data['bo_list_opt'] = listOptArr.join('|');

                                    // 2) 필드 개수 가져오기 (너 기존 코드에서 showCount(n) 호출할 때 window.__extraCount 관리하던 거 그대로 씀)
                                    var currentCount = (window.__extraCount || 0);

                                    // 3) 여분필드1(bo_1)
                                    var bo_1_visible = currentCount >= 1;
                                    var bo_1_subj = jQuery.trim(jQuery('#bo_1_subj').val() || '');

                                    if (bo_1_visible) {
                                        if (!bo_1_subj) {
                                            alert('1번 필드의 타이틀은 필수입니다.');
                                            jQuery('#bo_1_subj').focus();
                                            return;
                                        }

                                        var bo_1_type = jQuery('#bo_1_type').val();
                                        var bo_1_opt = jQuery.trim(jQuery('#bo_1_opt').val() || '');
                                        var bo_1_viewFlag = jQuery('#bo_1_view').is(':checked') ? '1' : '0';

                                        var needs1 = (bo_1_type === 'select' || bo_1_type === 'checkbox' || bo_1_type === 'radio');

                                        // 기본 "type;view"
                                        var composed1 = bo_1_type + ';' + bo_1_viewFlag;

                                        // 옵션 필요한 타입이면 "type;view|옵션들"
                                        if (needs1) {
                                            if (!bo_1_opt) {
                                                alert('1번 필드의 선택항목(콤마구분)을 입력해 주세요.');
                                                jQuery('#bo_1_opt').focus();
                                                return;
                                            }
                                            composed1 = bo_1_type + ';' + bo_1_viewFlag + '|' + bo_1_opt;
                                        }

                                        // hidden에도 넣어주고
                                        jQuery('#bo_1').val(composed1);

                                        // ajax payload
                                        data['bo_1_subj'] = bo_1_subj;
                                        data['bo_1'] = composed1;
                                    } else {
                                        jQuery('#bo_1').val('');
                                        data['bo_1_subj'] = '';
                                        data['bo_1'] = '';
                                    }

                                    // 4) 동적 필드 bo_2 ~ bo_10
                                    var invalidMsg = '';
                                    jQuery('#extraFields .rb_bbs_set_select_wrap[data-index]').each(function() {
                                        var dl = this;
                                        var idx = parseInt(dl.getAttribute('data-index'), 10) || 0;

                                        // 지금 화면에서 살아있는(보이고 있는) 필드만 처리
                                        var isActive = idx >= 2 && idx <= currentCount && jQuery(dl).closest('.extra-field-set').is(':visible');
                                        if (!isActive) return;

                                        var subjEl = dl.querySelector('.bo-subj');
                                        var typeEl = dl.querySelector('.bo-type');
                                        var optEl = dl.querySelector('.bo-opt');
                                        var viewEl = dl.querySelector('.bo-view');
                                        var hidEl = dl.querySelector('.bo-hidden');

                                        var subj = (subjEl && subjEl.value ? subjEl.value.trim() : '');
                                        var type = (typeEl && typeEl.value ? typeEl.value : 'text');
                                        var opt = (optEl && optEl.value ? optEl.value.trim() : '');
                                        var viewChecked = (viewEl && jQuery(viewEl).is(':checked')) ? '1' : '0';

                                        if (!subj && !invalidMsg) {
                                            invalidMsg = (idx + '번 필드의 타이틀은 필수입니다.');
                                        }

                                        var needs = (type === 'select' || type === 'checkbox' || type === 'radio');

                                        // "type;view"
                                        var composed = type + ';' + viewChecked;

                                        // 옵션 필요한 타입일 경우 "type;view|옵션들"
                                        if (needs) {
                                            if (!opt && !invalidMsg) {
                                                invalidMsg = (idx + '번 필드의 선택항목(콤마구분)을 입력해 주세요.');
                                            }
                                            composed = type + ';' + viewChecked + '|' + opt;
                                        }

                                        if (hidEl) hidEl.value = composed;

                                        data['bo_' + idx + '_subj'] = subj;
                                        data['bo_' + idx] = composed;
                                    });

                                    if (invalidMsg) {
                                        alert(invalidMsg);
                                        return;
                                    }

                                    // 5) 마지막으로 bo_table
                                    data['bo_table'] = bo_table;

                                    // 6) ajax 저장 호출
                                    jQuery.ajax({
                                        url: '<?php echo $board_skin_url ?>/ajax/ajax.update.php',
                                        type: 'post',
                                        dataType: 'json',
                                        data: data,
                                        success: function(res) {
                                            if (res.status === 'ok') {
                                                alert('설정이 저장되었습니다.');
                                            } else {
                                                alert('설정 저장 실패');
                                            }
                                        },
                                        error: function() {
                                            alert('서버 오류');
                                        }
                                    });
                                }
                            </script>

                            <!-- 단순 제어(보이기/추가/삭제/초기화) -->
                            <script>
                                jQuery(function($) {
                                    var MAX = 10; // 1~10
                                    var $help = $('#extraHelp');
                                    var $host = $('#extraFields');

                                    // bo_1 값 파싱(초기 표시와 select/옵션 세팅)
                                    (function hydrateBo1() {
                                        var raw = ($('#bo_1').val() || '').trim(); // 예: "select;1|사과,수박" 또는 "text;0"

                                        if (!raw) {
                                            // 값 없으면 기본
                                            $('#bo_1_type').val('text');
                                            $('#bo_1_opt').val('').prop('disabled', true).attr('placeholder', '(일반입력)');
                                            $('#bo_1_view').prop('checked', true); // 기본 on
                                            return;
                                        }

                                        var type = 'text';
                                        var viewFlag = '1';
                                        var opts = '';

                                        if (raw.indexOf(';') >= 0) {
                                            // "type;view|옵션들"
                                            var semiParts = raw.split(';'); // ["select","1|사과,수박"] or ["text","0"]
                                            type = (semiParts[0] || 'text').toLowerCase();
                                            var rest = semiParts.slice(1).join(';'); // "1|사과,수박" or "0"

                                            if (rest.indexOf('|') >= 0) {
                                                var pipeParts = rest.split('|'); // ["1","사과,수박"]
                                                viewFlag = pipeParts[0] || '1';
                                                opts = pipeParts.slice(1).join('|'); // "사과,수박"
                                            } else {
                                                viewFlag = rest || '1';
                                            }
                                        } else {
                                            // 구버전 "select|사과,수박" or "text"
                                            var oldParts = raw.split('|');
                                            type = (oldParts[0] || 'text').toLowerCase();
                                            opts = oldParts.slice(1).join('|');
                                            viewFlag = '1';
                                        }

                                        $('#bo_1_type').val(type);
                                        if (type !== 'text' && opts) {
                                            $('#bo_1_opt').val(opts);
                                        }
                                        $('#bo_1_view').prop('checked', viewFlag === '1');

                                        var needs = (type === 'select' || type === 'checkbox' || type === 'radio');
                                        $('#bo_1_opt')
                                            .prop('disabled', !needs)
                                            .attr('placeholder', needs ? '선택항목(콤마구분)' : '(일반입력)');
                                    })();

                                    // 옵션 입력 활성/비활성
                                    function toggleOptBo1() {
                                        var t = ($('#bo_1_type').val() || '').toLowerCase();
                                        var needs = (t === 'select' || t === 'checkbox' || t === 'radio');
                                        $('#bo_1_opt')
                                            .prop('disabled', !needs)
                                            .attr('placeholder', needs ? '선택항목(콤마구분)' : '(일반입력)');
                                    }
                                    $('#bo_1_type').on('change', toggleOptBo1);
                                    toggleOptBo1();

                                    // 표시해야 할 마지막 인덱스 계산(서버 값 기준)
                                    function computeInitialCount() {
                                        var count = 0;
                                        if ($.trim($('#bo_1_subj').val() || '') !== '' || $.trim($('#bo_1').val() || '') !== '') count = 1;
                                        if (Array.isArray(window.PRESET_FIELDS)) {
                                            PRESET_FIELDS.forEach(function(it) {
                                                var idx = parseInt(it.idx, 10);
                                                if (!idx || idx < 2 || idx > 10) return;
                                                var hasAny = ($.trim(it.subj || '') !== '' || $.trim(it.val || '') !== '');
                                                if (hasAny && idx > count) count = idx;
                                            });
                                        }
                                        return Math.min(Math.max(count, 0), MAX);
                                    }

                                    // n개까지 세트 생성(2..n)
                                    function ensureSets(n) {
                                        var $host = $('#extraFields');

                                        for (var i = 2; i <= n; i++) {

                                            // DOM 없으면 생성
                                            if ($host.find('.rb_bbs_set_select_wrap[data-index="' + i + '"]').length === 0) {
                                                $host.append(window.makeFieldSet(i));
                                            }

                                            // 프리셋 주입
                                            var preset = (Array.isArray(PRESET_FIELDS) ?
                                                PRESET_FIELDS.find(function(p) {
                                                    return +p.idx === i;
                                                }) :
                                                null);

                                            if (preset) {
                                                $('#bo_' + i + '_subj').val(preset.subj || '');

                                                var raw = String(preset.val || '').trim(); // "type;view|옵션들" or 구버전
                                                var type = 'text';
                                                var viewFlag = '1';
                                                var opts = '';

                                                if (raw.indexOf(';') >= 0) {
                                                    // 새 포맷 "type;view|옵션들"
                                                    var semiParts = raw.split(';');
                                                    type = (semiParts[0] || 'text').toLowerCase();
                                                    var rest = semiParts.slice(1).join(';');
                                                    if (rest.indexOf('|') >= 0) {
                                                        var pipeParts = rest.split('|');
                                                        viewFlag = pipeParts[0] || '1';
                                                        opts = pipeParts.slice(1).join('|');
                                                    } else {
                                                        viewFlag = rest || '1';
                                                    }
                                                } else {
                                                    // 구버전 "select|사과,수박"
                                                    var oldParts = raw.split('|');
                                                    type = (oldParts[0] || 'text').toLowerCase();
                                                    opts = oldParts.slice(1).join('|');
                                                    viewFlag = '1';
                                                }

                                                $('#bo_' + i + '_type').val(type);
                                                if (type !== 'text' && opts) {
                                                    $('#bo_' + i + '_opt').val(opts);
                                                }
                                                $('#bo_' + i + '_view').prop('checked', viewFlag === '1');

                                                var needs = (type === 'select' || type === 'checkbox' || type === 'radio');
                                                $('#bo_' + i + '_opt')
                                                    .prop('disabled', !needs)
                                                    .attr('placeholder', needs ? '선택항목(콤마구분)' : '(일반입력)');
                                            } else {
                                                // 신규 필드 기본
                                                $('#bo_' + i + '_type').val('text');
                                                $('#bo_' + i + '_opt')
                                                    .val('')
                                                    .prop('disabled', true)
                                                    .attr('placeholder', '(일반입력)');
                                                $('#bo_' + i + '_view').prop('checked', true);
                                            }
                                        }
                                    }

                                    // n 표시: 0=도움말만, 1=bo1만, 2~10=bo1+세트
                                    function showCount(n) {
                                        window.__extraCount = n; // 저장에서 참조
                                        if (n <= 0) {
                                            $('#bo1_block').hide();
                                            $host.find('.extra-field-set').hide();
                                            $help.show();
                                            return;
                                        }
                                        $('#bo1_block').show();
                                        $help.hide();
                                        ensureSets(n);
                                        for (var i = 2; i <= MAX; i++) {
                                            var $blk = $host.find('.rb_bbs_set_select_wrap[data-index="' + i + '"]').closest('.extra-field-set');
                                            if ($blk.length) $blk.toggle(i <= n);
                                        }
                                    }

                                    // 초기 표시
                                    var count = computeInitialCount();
                                    showCount(count);

                                    // 추가
                                    $('#add_field_btn').off('click').on('click', function() {
                                        if (count >= MAX) return;
                                        count += 1;
                                        showCount(count);
                                        (count === 1 ? $('#bo_1_subj') : $('#bo_' + count + '_subj')).focus();
                                    });

                                    // 삭제(끝에서부터)
                                    $('#remove_field_btn').off('click').on('click', function() {
                                        if (count <= 0) return;

                                        if (count === 1) {
                                            // bo_1 정리
                                            $('#bo_1_subj').val('');
                                            $('#bo_1').val('');
                                            $('#bo_1_type').val('text');
                                            $('#bo_1_opt').val('')
                                                .prop('disabled', true)
                                                .attr('placeholder', '(일반입력)');
                                            $('#bo_1_view').prop('checked', false);
                                        } else {
                                            // 마지막 세트 정리
                                            $('#bo_' + count + '_subj').val('');
                                            $('#bo_' + count).val('');
                                            var $dl = $host.find('.rb_bbs_set_select_wrap[data-index="' + count + '"]');
                                            if ($dl.length) {
                                                $dl.find('.bo-type').val('text');
                                                $dl.find('.bo-opt').val('')
                                                    .prop('disabled', true)
                                                    .attr('placeholder', '(일반입력)');
                                                $dl.find('.bo-view').prop('checked', false);
                                            }
                                        }

                                        count -= 1;
                                        showCount(count);
                                    });


                                    // 동적 세트: 타입 변경 시 옵션 활성화/비활성
                                    $(document).on('change', '.bo-type', function() {
                                        var $dl = $(this).closest('.rb_bbs_set_select_wrap');
                                        var tVal = ($(this).val() || '').toLowerCase();
                                        var needs = (tVal === 'select' || tVal === 'checkbox' || tVal === 'radio');
                                        $dl.find('.bo-opt').prop('disabled', !needs)
                                            .attr('placeholder', needs ? '선택항목(콤마구분)' : '(일반입력)');
                                    });
                                });
                            </script>

                        </div>

                    </div>


                    <script>
                        $(document).ready(function() {
                            $(document).click(function(event) {
                                if (!$(event.target).closest('#rb_bbs_set_btn, .rb_bbs_set_opens').length) {
                                    if ($('.rb_bbs_set_opens').is(':visible')) {
                                        $('.rb_bbs_set_opens').hide();
                                        $('.point_info_opens').hide();
                                        $('#rb_bbs_set_btn').removeClass('act');
                                        $('#point_info_opens_btn').removeClass('act');
                                    }
                                }
                            });

                            $('#rb_bbs_set_btn').click(function(event) {
                                event.stopPropagation();
                                $('.rb_bbs_set_opens').toggle();
                                $('.point_info_opens').hide();
                                $('#point_info_opens_btn').removeClass('act');
                                $(this).toggleClass('act');
                            });
                        });
                    </script>

                </div>
                <?php } ?>

            </div>

        </div>
    </div>

    <form name="fboardlist" id="fboardlist" action="<?php echo G5_BBS_URL; ?>/board_list_update.php" onsubmit="return fboardlist_submit(this);" method="post">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="spt" value="<?php echo $spt ?>">
        <input type="hidden" name="sst" value="<?php echo $sst ?>">
        <input type="hidden" name="sod" value="<?php echo $sod ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="sw" value="">

        <div class="btns_gr_wrap">


            <div class="sub" style="width:<?php echo $rb_core['sub_width'] ?>px;">

                <?php if(!$wr_id) { //목록보기를 했을 경우 노출되는 부분 방지?>

                <div class="btns_gr">
                    <?php if ($admin_href) { ?>
                    <button type="button" class="fl_btns" onclick="window.open('<?php echo $admin_href ?>');">
                        <img src="<?php echo $board_skin_url ?>/img/ico_set.svg">
                        <span class="tooltips">관리</span>
                    </button>
                    <?php } ?>

                    <?php if ($rss_href) { ?>
                    <button type="button" class="fl_btns" onclick="window.open('<?php echo $rss_href ?>');">
                        <img src="<?php echo $board_skin_url ?>/img/ico_rss.svg">
                        <span class="tooltips">RSS</span>
                    </button>
                    <?php } ?>


                    <?php if ($write_href) { ?>
                    <button type="button" class="fl_btns main_color_bg" onclick="location.href='<?php echo $write_href ?>';">
                        <img src="<?php echo $board_skin_url ?>/img/ico_write.svg">
                        <span class="tooltips">글 등록</span>
                    </button>
                    <?php } ?>

                </div>
                <?php } ?>

                <div class="cb"></div>
            </div>
        </div>



        <ul class="rb_bbs_list">

            <div class="rb-board-container">
                <div class="rb-board-content">

                    <div class="rb_datagrid_bbs">

                        <div class="card">
                            <div class="table-wrap">
                                <table id="grid">
                                    <thead>
                                        <tr>
                                            <?php if ($is_checkbox) { ?>
                                            <th>
                                                <input type="checkbox" id="chkall" onclick="if (this.checked) all_checked(true); else all_checked(false);">
                                                <label for="chkall"></label>
                                            </th>
                                            <?php } ?>

                                            <!-- 번호 v1 -->
                                            <th class="<?php echo !$bo_list_flags['v1'] ? 'col-hide' : '' ?>">
                                                <span class="th-sort" data-key="id" data-dir="">
                                                    번호
                                                    <span class="arrows">
                                                        <span class="up"><img src="<?php echo $board_skin_url ?>/img/up.svg"></span>
                                                        <span class="down"><img src="<?php echo $board_skin_url ?>/img/down.svg"></span>
                                                    </span>
                                                </span>
                                            </th>

                                            <!-- 분류 v2 -->
                                            <?php if ($is_category) { ?>
                                            <th class="<?php echo !$bo_list_flags['v2'] ? 'col-hide' : '' ?>">
                                                <span class="th-sort" data-key="category" data-dir="">
                                                    분류
                                                    <span class="arrows">
                                                        <span class="up"><img src="<?php echo $board_skin_url ?>/img/up.svg"></span>
                                                        <span class="down"><img src="<?php echo $board_skin_url ?>/img/down.svg"></span>
                                                    </span>
                                                </span>
                                            </th>
                                            <?php } ?>



                                            <!-- 제목 v3 -->
                                            <th class="<?php echo !$bo_list_flags['v3'] ? 'col-hide' : '' ?>">
                                                <span class="th-sort" data-key="title" data-dir="">
                                                    제목
                                                    <span class="arrows">
                                                        <span class="up"><img src="<?php echo $board_skin_url ?>/img/up.svg"></span>
                                                        <span class="down"><img src="<?php echo $board_skin_url ?>/img/down.svg"></span>
                                                    </span>
                                                </span>
                                            </th>

                                            <!-- 이미지 v8 -->
                                            <th class="<?php echo !$bo_list_flags['v8'] ? 'col-hide' : '' ?>">
                                                <span class="th-sort" data-key="image" data-dir="">
                                                    이미지
                                                    <span class="arrows">
                                                        <span class="up"><img src="<?php echo $board_skin_url ?>/img/up.svg"></span>
                                                        <span class="down"><img src="<?php echo $board_skin_url ?>/img/down.svg"></span>
                                                    </span>
                                                </span>
                                            </th>

                                            <!-- 여분필드 컬럼들 -->
                                            <?php foreach ($extra_cols as $col): 
                                                $k = 'wr_'.$col['n']; // ex) wr_1
                                            ?>
                                            <th>
                                                <span class="th-sort" data-key="<?php echo $k; ?>" data-dir="">
                                                    <?php echo htmlspecialchars($col['subj'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>
                                                    <span class="arrows">
                                                        <span class="up"><img src="<?php echo $board_skin_url ?>/img/up.svg"></span>
                                                        <span class="down"><img src="<?php echo $board_skin_url ?>/img/down.svg"></span>
                                                    </span>
                                                </span>
                                            </th>
                                            <?php endforeach; ?>

                                            <!-- 작성자 v4 -->
                                            <th class="<?php echo !$bo_list_flags['v4'] ? 'col-hide' : '' ?>">
                                                <span class="th-sort" data-key="author" data-dir="">
                                                    작성자
                                                    <span class="arrows">
                                                        <span class="up"><img src="<?php echo $board_skin_url ?>/img/up.svg"></span>
                                                        <span class="down"><img src="<?php echo $board_skin_url ?>/img/down.svg"></span>
                                                    </span>
                                                </span>
                                            </th>

                                            <!-- 작성일 v5 -->
                                            <th class="<?php echo !$bo_list_flags['v5'] ? 'col-hide' : '' ?>">
                                                <span class="th-sort" data-key="date" data-dir="">
                                                    작성일
                                                    <span class="arrows">
                                                        <span class="up"><img src="<?php echo $board_skin_url ?>/img/up.svg"></span>
                                                        <span class="down"><img src="<?php echo $board_skin_url ?>/img/down.svg"></span>
                                                    </span>
                                                </span>
                                            </th>

                                            <!-- 댓글 v6 -->
                                            <th class="<?php echo !$bo_list_flags['v6'] ? 'col-hide' : '' ?>">
                                                <span class="th-sort" data-key="comment" data-dir="">
                                                    댓글
                                                    <span class="arrows">
                                                        <span class="up"><img src="<?php echo $board_skin_url ?>/img/up.svg"></span>
                                                        <span class="down"><img src="<?php echo $board_skin_url ?>/img/down.svg"></span>
                                                    </span>
                                                </span>
                                            </th>

                                            <!-- 조회 v7 -->
                                            <th class="<?php echo !$bo_list_flags['v7'] ? 'col-hide' : '' ?>">
                                                <span class="th-sort" data-key="views" data-dir="">
                                                    조회
                                                    <span class="arrows">
                                                        <span class="up"><img src="<?php echo $board_skin_url ?>/img/up.svg"></span>
                                                        <span class="down"><img src="<?php echo $board_skin_url ?>/img/down.svg"></span>
                                                    </span>
                                                </span>
                                            </th>

                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php for ($i=0; $i<count($list); $i++) {
    
                                        $thumb = get_list_thumbnail($board['bo_table'], $list[$i]['wr_id'], $board['bo_gallery_width'], $board['bo_gallery_height'], false, true);
            
                                        if($thumb['src']) {
                                            if (strstr($list[$i]['wr_option'], 'secret')) {
                                                $img_content = '<img src="'.G5_THEME_URL.'/rb.img/sec_image.png" alt="'.$thumb['alt'].'" >';
                                            } else {
                                                $img_content = '<img src="'.$thumb['src'].'" alt="'.$thumb['alt'].'" >';
                                            }
                                        } else { 
                                            $img_content = '';
                                        }
    
                                        $category_option_list = get_category_option($bo_table, $list[$i]['ca_name']);

                                        $sec_icon = '';
                                        if (strstr($list[$i]['wr_option'], 'secret')) {
                                            $sec_txt  = '<span style="opacity:0.6">작성자 및 관리자 외 열람할 수 없습니다.';
                                            $sec_icon = '<img src="'.$board_skin_url.'/img/ico_sec.svg"> ';
                                        }
                                    ?>
                                        <tr <?php if ($list[$i]['icon_new']) echo "class='new_tr_bg'"; ?>>
                                            <?php if ($is_checkbox) { ?>
                                            <td>
                                                <input type="checkbox" name="chk_wr_id[]" value="<?php echo $list[$i]['wr_id'] ?>" id="chk_wr_id_<?php echo $i ?>">
                                                <label for="chk_wr_id_<?php echo $i ?>"></label>
                                            </td>
                                            <?php } ?>

                                            <!-- 번호 -->
                                            <td class="<?php echo !$bo_list_flags['v1'] ? 'col-hide' : '' ?>">
                                                <?php
                                                if($list[$i]['is_notice']) {
                                                    echo '<svg class="ico-notice" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" fill="currentColor" aria-label="notice" role="img"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.09 3.355h3.523c.969 0 1.371 1.24.588 1.81l-2.852 2.073 1.09 3.355c.3.921-.755 1.688-1.54 1.118L10 12.347l-2.85 2.291c-.784.57-1.84-.197-1.54-1.118l1.09-3.355L3.847 8.092c-.783-.57-.38-1.81.588-1.81h3.523l1.09-3.355z"/></svg>';
                                                } else if (strstr($list[$i]['wr_option'], 'secret')) {
                                                    echo $sec_icon;
                                                } else {
                                                    echo number_format($list[$i]['num']);
                                                }
                                                ?>
                                            </td>

                                            <?php if ($is_category) { ?>
                                            <td class="<?php echo !$bo_list_flags['v2'] ? 'col-hide' : '' ?>">
                                                <span class="rb-list-text">
                                                    <?php echo $list[$i]['ca_name'] ?>
                                                </span>

                                                <select name="ca_name[<?php echo (int)$list[$i]['wr_id']; ?>]" class="select input_tiny2 rb-list-input" style="display:none;">
                                                    <?php echo $category_option_list ?>
                                                </select>

                                            </td>
                                            <?php } ?>

                                            <!-- 제목 -->
                                            <td class="<?php echo !$bo_list_flags['v3'] ? 'col-hide' : '' ?>">
                                                <span class="rb-list-text">
                                                    <a href="<?php echo $list[$i]['href'] ?>" class="font-B"><?php echo $list[$i]['subject'] ?></a>
                                                </span>
                                                <input type="text" name="wr_subject[<?php echo (int)$list[$i]['wr_id']; ?>]" value="<?php echo $list[$i]['subject']; ?>" class="rb-list-input" style="display:none; width:100%">
                                            </td>

                                            <!-- 이미지 -->
                                            <td class="<?php echo !$bo_list_flags['v8'] ? 'col-hide' : '' ?>">
                                                <a href="<?php echo $list[$i]['href'] ?>" class="font-B">
                                                    <?php echo run_replace('thumb_image_tag', $img_content, $thumb); ?>
                                                </a>
                                            </td>



                                            <!-- 여분필드들 -->
                                            <?php foreach ($extra_cols as $col):
                                                $k = 'wr_'.$col['n']; // ex) wr_1
                                                $wr_id = (int)$list[$i]['wr_id'];
                                                $val = isset($list[$i][$k]) ? $list[$i][$k] : '';
                                            ?>
                                            <td>
                                                <span class="rb-list-text">
                                                    <?php echo $val; ?>
                                                </span>

                                                <!-- wr_id + 필드명을 같이 보내기 -->
                                                <input type="text"
                                                       name="extra[<?php echo $wr_id; ?>][<?php echo $k; ?>]"
                                                       value="<?php echo $val; ?>"
                                                       class="rb-list-input"
                                                       style="display:none; width:100%">
                                            </td>
                                            <?php endforeach; ?>

                                            <!-- 작성자 -->
                                            <td class="<?php echo !$bo_list_flags['v4'] ? 'col-hide' : '' ?>">
                                                <?php echo $list[$i]['wr_name'] ?>
                                            </td>

                                            <!-- 작성일 -->
                                            <td class="<?php echo !$bo_list_flags['v5'] ? 'col-hide' : '' ?>">
                                                <span class="rb-list-text">
                                                    <?php echo $list[$i]['datetime']; ?>
                                                </span>
                                                <input type="text" name="wr_datetime[<?php echo (int)$list[$i]['wr_id']; ?>]" value="<?php echo $list[$i]['datetime']; ?>" class="rb-list-input" style="display:none; width:100%">
                                            </td>

                                            <!-- 댓글 -->
                                            <td class="<?php echo !$bo_list_flags['v6'] ? 'col-hide' : '' ?>">
                                                <?php echo number_format($list[$i]['wr_comment']); ?>
                                            </td>

                                            <!-- 조회 -->
                                            <td class="<?php echo !$bo_list_flags['v7'] ? 'col-hide' : '' ?>">
                                                <?php echo number_format($list[$i]['wr_hit']); ?>
                                            </td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>

                                <?php if (count($list) == 0) { echo "<div class=\"no_data\" style=\"text-align:center\">데이터가 없습니다.</div>"; } ?>

                            </div>
                        </div>


                    </div>


                    <div class="grid-footer">
                        <div class="pagination" id="pagination"></div>
                        <div class="page-size">
                            <select id="rowsPerPage" class="select">
                                <option value="10" selected>10개</option>
                                <option value="20">20개</option>
                                <option value="30">30개</option>
                                <option value="50">50개</option>
                                <option value="100">100개</option>
                                <option value="all">전체</option>
                            </select>
                        </div>

                    </div>

                </div>
            </div>

        </ul>

        <ul class="btm_btns">

            <dd class="btm_btns_right">

                <?php if ($rss_href) { ?>
                <button type="button" name="btn_submit" class="fl_btns rss_pc" onclick="window.open('<?php echo $rss_href ?>');">
                    RSS
                </button>
                <?php } ?>

                <?php if ($write_href) { ?>
                <button type="button" name="btn_submit" class="fl_btns main_color_bg" onclick="location.href='<?php echo $write_href ?>';">
                    <img src="<?php echo $board_skin_url ?>/img/ico_write.svg">
                    <span class="font-R">글 등록</span>
                </button>
                <?php } ?>

            </dd>

            <dd class="btm_btns_left">
                <?php if ($is_admin == 'super' || $is_auth) { ?>
                <?php if ($is_checkbox) { ?>
                <button type="submit" name="btn_submit" class="fl_btns" value="선택삭제" onclick="document.pressed=this.value">
                    <span class="font-B">선택삭제</span>
                </button>

                <button type="submit" name="btn_submit" class="fl_btns" value="선택복사" onclick="document.pressed=this.value">
                    <span class="font-B">선택복사</span>
                </button>

                <button type="submit" name="btn_submit" class="fl_btns" value="선택이동" onclick="document.pressed=this.value">
                    <span class="font-B">선택이동</span>
                </button>
                
                <button type="button" id="rbSelectedSaveBtn" class="fl_btns">
                    <span class="font-B">선택저장</span>
                </button>
                <?php } ?>
                <?php } ?>

            </dd>
            <dd class="cb"></dd>

        </ul>

    </form>

</div>


<script>
    var IS_ADMIN = <?php echo json_encode(($is_admin=='super' || $is_auth)); ?>;
    var EXTRA_KEYS = <?php echo json_encode(array_map(function($c){ return 'wr_'.$c['n']; }, $extra_cols)); ?>;
</script>

<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js"></script>


<script>
    (function() {

        var $rangeBox = document.getElementById('quickRanges');
        if (!$rangeBox) return;

        function pad(n) {
            return (n < 10 ? '0' : '') + n;
        }

        function fmt(d) {
            return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate());
        }

        // ISO week 기준: 월요일 시작
        function weekRange(offsetWeeks) { // 0=이번주, -1=지난주
            var today = new Date();
            var day = today.getDay(); // 0=일,1=월,...
            var diffToMonday = (day === 0 ? -6 : 1 - day); // 월요일까지 이동
            var monday = new Date(today);
            monday.setDate(today.getDate() + diffToMonday + (offsetWeeks * 7));
            var sunday = new Date(monday);
            sunday.setDate(monday.getDate() + 6);
            return {
                from: monday,
                to: sunday
            };
        }

        function monthRange(offsetMonths) { // 0=당월, -1=전월
            var base = new Date();
            base.setDate(1); // 1일 기준
            base.setMonth(base.getMonth() + offsetMonths);
            var first = new Date(base);
            var last = new Date(base.getFullYear(), base.getMonth() + 1, 0);
            return {
                from: first,
                to: last
            };
        }

        function setRange(fromDate, toDate) {
            var f = fmt(fromDate),
                t = fmt(toDate);
            var $from = document.getElementById('dateFrom');
            var $to = document.getElementById('dateTo');
            if ($from) $from.value = f;
            if ($to) $to.value = t;
            state.dateFrom = f;
            state.page = 1;
            state.dateTo = t;
            render();
        }

        $rangeBox.addEventListener('click', function(e) {
            var btn = e.target.closest('button[data-range]');
            if (!btn) return;
            var key = btn.getAttribute('data-range');
            if (key === 'today') {
                var d = new Date();
                setRange(d, d);
            } else if (key === 'thisWeek') {
                var r = weekRange(0);
                setRange(r.from, r.to);
            } else if (key === 'lastWeek') {
                var r = weekRange(-1);
                setRange(r.from, r.to);
            } else if (key === 'thisMonth') {
                var r = monthRange(0);
                setRange(r.from, r.to);
            } else if (key === 'lastMonth') {
                var r = monthRange(-1);
                setRange(r.from, r.to);
            } else if (key === 'all') {
                // 전체: 날짜 필터 해제
                var $from = document.getElementById('dateFrom');
                var $to = document.getElementById('dateTo');
                if ($from) $from.value = '';
                if ($to) $to.value = '';

                state.dateFrom = '';
                state.dateTo = '';
                state.page = 1;
                render();
            }
        });



        try {
            var grid = document.getElementById('grid');
            if (!grid) return;
            var tbody = grid.querySelector('tbody');
            var resultInfo = document.getElementById('resultInfo');

            // --- 헤더 구성 파악
            var hasSel = !!grid.querySelector('thead input#chkall');
            var hasCat = !!grid.querySelector('thead .th-sort[data-key="category"]');

            // --- 동적 컬럼: 헤더 인덱스 자동 맵
            var headerIndex = {};
            if (grid.tHead && grid.tHead.rows[0]) {
                Array.prototype.forEach.call(grid.tHead.rows[0].cells, function(th, idx) {
                    var el = th.querySelector('.th-sort');
                    if (el) {
                        var key = el.getAttribute('data-key');
                        if (key) headerIndex[key] = idx;
                    }
                });
            }

            window.headerIndex = headerIndex;

            function colIndexFor(key) {
                var base = hasSel ? 1 : 0;
                switch (key) {
                    case 'id':
                        return base + 0;
                    case 'category':
                        return hasCat ? base + 1 : -1;
                    case 'image':
                        return hasCat ? base + 2 : base + 1;
                    case 'title':
                        return hasCat ? base + 3 : base + 2;
                    case 'author':
                        return hasCat ? base + 4 : base + 3;
                    case 'date':
                        return hasCat ? base + 5 : base + 4;
                    case 'comment':
                        return hasCat ? base + 6 : base + 5;
                    case 'views':
                        return hasCat ? base + 7 : base + 6;
                    default:
                        return -1;
                }
            }

            // --- 유틸
            function txt(el) {
                return (el && el.textContent || '').trim();
            }

            function digits(s) {
                s = String(s || '').replace(/[^\d-]/g, '');
                return s ? +s : 0;
            }

            function toISO(s) {
                var m = String(s || '').match(/\b(20\d{2}|19\d{2})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])\b/);
                return m ? m[0] : '';
            }

            function uniq(a) {
                var m = {},
                    o = [];
                for (var i = 0; i < a.length; i++) {
                    if (a[i] && !m[a[i]]) {
                        m[a[i]] = 1;
                        o.push(a[i]);
                    }
                }
                return o;
            }

            function debounce(fn, ms) {
                var t;
                return function() {
                    clearTimeout(t);
                    var a = arguments;
                    t = setTimeout(function() {
                        fn.apply(null, a);
                    }, ms);
                };
            }

            function csvCell(v) {
                v = String(v == null ? '' : v);
                return '"' + v.replace(/"/g, '""') + '"';
            }

            function savePageSizePref(val) {
                try {
                    localStorage.setItem('rowsPerPage', val);
                } catch (e) {
                    // localStorage 안 될 때 쿠키 fallback (1년 유지)
                    document.cookie = 'rowsPerPage=' + encodeURIComponent(val) + '; path=/; max-age=' + (60 * 60 * 24 * 365);
                }
            }

            function loadPageSizePref() {
                var v = null;
                try {
                    v = localStorage.getItem('rowsPerPage');
                } catch (e) {
                    // localStorage 막혀있을 수 있으므로 쿠키에서 읽기
                    var m = document.cookie.match(/(?:^|;\s*)rowsPerPage=([^;]+)/);
                    if (m) v = decodeURIComponent(m[1]);
                }
                return v;
            }

            var idx = {
                id: headerIndex['id'],
                category: headerIndex['category'],
                image: headerIndex['image'],
                title: headerIndex['title'],
                author: headerIndex['author'],
                date: headerIndex['date'],
                comment: headerIndex['comment'],
                views: headerIndex['views']
            };

            function hydrateRow(tr) {
                if (!tr) return;

                var ds = tr.dataset,
                    tds = tr.children;

                function cell(i) {
                    return (i >= 0 && tds[i]) ? tds[i] : null;
                }

                // --- 기존 로직 유지 ---
                if (!ds.id) ds.id = String(digits(txt(cell(idx.id))));
                
                // 분류값은 td 전체가 아니라 span.rb-list-text 기준으로만 읽기
                if (!ds.category && idx.category >= 0) {
                    var ccell = cell(idx.category);
                    if (ccell) {
                        var span = ccell.querySelector('.rb-list-text');
                        ds.category = span ? txt(span) : txt(ccell);
                    }
                }

                if (!ds.title) {
                    var c = cell(idx.title),
                        a = c ? c.querySelector('a') : null;
                    ds.title = a ? txt(a) : txt(c);
                }

                if (!ds.author) ds.author = txt(cell(idx.author));

                // 날짜: 기존 로직 유지 + 백업 보정
                if (!ds.date) ds.date = toISO(txt(cell(idx.date)));

                // 혹시라도 toISO가 실패해서 빈 값이면 직접 셀에서 YYYY-MM-DD 추출
                if (!ds.date) {
                    var dateIdx = (typeof headerIndex !== 'undefined' && headerIndex && headerIndex['date'] != null) ?
                        headerIndex['date'] :
                        (typeof colIndexFor === 'function' ? colIndexFor('date') : -1);
                    var txtDate = (dateIdx >= 0 && tds[dateIdx]) ? tds[dateIdx].textContent.trim() : '';
                    var m = txtDate.match(/(\d{4})[-/.](\d{2})[-/.](\d{2})/);
                    if (m) ds.date = [m[1], m[2], m[3]].join('-');
                }

                if (!ds.comment) ds.comment = String(digits(txt(cell(idx.comment))));
                if (!ds.views) ds.views = String(digits(txt(cell(idx.views))));

                if (!ds.image && idx.image >= 0) {
                    var tdImg = cell(idx.image);
                    if (tdImg && tdImg.querySelector('img')) {
                        ds.image = '1';
                    } else {
                        ds.image = '0';
                    }
                }

                // --- 동적 컬럼 dataset 채우기 (텍스트만) ---
                if (Array.isArray(window.EXTRA_KEYS)) {
                    EXTRA_KEYS.forEach(function(key) {
                        if (!ds[key]) {
                            var ci = headerIndex[key];
                            if (ci >= 0 && tds[ci]) ds[key] = txt(tds[ci]);
                        }
                    });
                }
            }
            Array.prototype.forEach.call(tbody.querySelectorAll('tr'), hydrateRow);

            // --- 스냅샷
            function snapshot() {
                var rows = [];

                Array.prototype.forEach.call(tbody.querySelectorAll('tr'), function(tr) {
                    if (!tr.children.length) return;

                    // ★ 추가: 먼저 dataset 채우기 (image 포함해서)
                    hydrateRow(tr);

                    var ds = tr.dataset;
                    var o = {
                        tr: tr,
                        id: +(ds.id || 0),
                        category: ds.category || '',
                        image: ds.image || '', // ds.image 는 hydrateRow()에서 '1' 또는 '0' 으로 채워짐
                        title: ds.title || '',
                        author: ds.author || '',
                        date: ds.date || '',
                        comment: +(ds.comment || 0),
                        views: +(ds.views || 0)
                    };

                    // 여분필드 wr_1 ~ wr_n 값도 복사
                    if (Array.isArray(window.EXTRA_KEYS)) {
                        EXTRA_KEYS.forEach(function(key) {
                            if (ds[key] !== undefined) {
                                o[key] = ds[key];
                            }
                        });
                    }

                    rows.push(o);
                });

                return rows;
            }

            var data = snapshot();

            // --- rowsPerPage 초기값 결정 (저장된 값 불러오기) ★수정
            (function initPageSizeFromPref() {
                var raw = loadPageSizePref(); // '10' / '20' / 'all' 등
                var initial;
                if (raw === 'all') {
                    initial = Infinity;
                } else if (raw) {
                    var n = parseInt(raw, 10);
                    if (!n || n < 1) n = 10;
                    initial = n;
                } else {
                    // 저장된 거 없으면 기본 10
                    initial = 10;
                }
                window.__INITIAL_PAGE_SIZE__ = initial;
            })();

            // --- 상태 ★수정
            var state = {
                q: '',
                qField: 'title', // 기본 검색필드 (제목)
                categories: new Set(),
                dateFrom: '',
                dateTo: '',
                sort: [{
                    key: 'date',
                    dir: 'desc'
                }], // 기본 정렬: 날짜 ↓
                pageSize: window.__INITIAL_PAGE_SIZE__, // 저장된 pageSize로 시작
                page: 1
            };

            window.state = state;

            // --- 분류 칩 만들기 + 활성화 표시
            var CATS = uniq(data.map(function(r) {
                return r.category;
            })).sort();
            var categoryBox = document.getElementById('categoryBox');
            if (categoryBox) {
                categoryBox.innerHTML = '';

                if (CATS.length === 0) {
                    // 값이 없을 때 메시지 출력
                    const msg = document.createElement('div');
                    msg.className = 'empty-msg';
                    msg.innerHTML = '<span class="color-999">데이터가 없습니다.</span>';
                    categoryBox.appendChild(msg);
                } else {
                    // 값이 있을 때만 칩 생성
                    CATS.forEach(function(c) {
                        var lab = document.createElement('label');
                        lab.className = 'chip';
                        var id = 'cat-' + c.replace(/\s+/g, '_');
                        lab.innerHTML =
                            '<input type="checkbox" id="' + id + '" value="' + c + '"><span>' + c + '</span>';
                        categoryBox.appendChild(lab);
                    });

                    // 체크 상태 변경 시 필터 반영
                    categoryBox.addEventListener('change', function(e) {
                        var t = e.target;
                        if (t && t.type === 'checkbox') {
                            if (t.checked) state.categories.add(t.value);
                            else state.categories.delete(t.value);
                            state.page = 1;
                            render();
                        }
                    });
                }
            }

            // --- 필터/정렬
            function withinDateRange(iso, from, to) {
                if (!from && !to) return true;
                if (!iso) return false;
                var t = new Date(iso).getTime();
                if (from && t < new Date(from).getTime()) return false;
                if (to && t > new Date(to).getTime()) return false;
                return true;
            }

            function applyFilters(rows) {
                var q = (state.q || '').toLowerCase().trim();
                var f = state.qField || 'title'; // title / id / category / author / wr_1 / wr_2 ...

                return rows.filter(function(r) {
                    // 1) 텍스트 검색
                    if (q) {
                        var searchVal = '';

                        if (f === 'id') {
                            searchVal = String(r.id || '');
                        } else if (f === 'category') {
                            searchVal = String(r.category || '');
                        } else if (f === 'title') {
                            searchVal = String(r.title || '');
                        } else if (f === 'author') {
                            searchVal = String(r.author || '');
                        } else {
                            // 여분필드 (wr_1, wr_2, ...)
                            searchVal = String(r[f] || '');
                        }

                        if (searchVal.toLowerCase().indexOf(q) === -1) {
                            return false;
                        }
                    }

                    // 2) 카테고리 필터
                    if (state.categories.size > 0 && !state.categories.has(r.category)) return false;

                    // 3) 날짜 범위
                    if (!withinDateRange(r.date, state.dateFrom, state.dateTo)) return false;

                    // 4) 동적 필드 필터(.controls_dynamic에서 설정된 STATE.extra)
                    if (window.STATE && STATE.extra) {
                        for (var k in STATE.extra) {
                            if (!STATE.extra.hasOwnProperty(k)) continue;
                            var val = STATE.extra[k];
                            if (!val || (Array.isArray(val) && val.length === 0)) continue;

                            var cell = String(r[k] || '');

                            // checkbox(다중) → OR 매칭
                            // select/radio(단일) → 포함 매칭
                            if (Array.isArray(val)) {
                                var ok = false;
                                for (var i = 0; i < val.length; i++) {
                                    if (cell.indexOf(val[i]) !== -1) {
                                        ok = true;
                                        break;
                                    }
                                }
                                if (!ok) return false;
                            } else {
                                if (cell.indexOf(val) === -1) return false;
                            }
                        }
                    }

                    return true;
                });
            }

            function applySort(rows) {
                var chain = state.sort.slice();
                return rows.slice().sort(function(a, b) {
                    for (var i = 0; i < chain.length; i++) {
                        var s = chain[i],
                            k = s.key,
                            va = a[k],
                            vb = b[k];
                        if (k === 'date') {
                            va = a.date ? new Date(a.date).getTime() : 0;
                            vb = b.date ? new Date(b.date).getTime() : 0;
                        } else if (typeof va === 'number' && typeof vb === 'number') {} else if (!isNaN(va) && !isNaN(vb)) {
                            va = +va;
                            vb = +vb;
                        } else {
                            va = String(va || '').toLowerCase();
                            vb = String(vb || '').toLowerCase();
                        }
                        if (va < vb) return s.dir === 'asc' ? -1 : 1;
                        if (va > vb) return s.dir === 'asc' ? 1 : -1;
                    }
                    return 0;
                });
            }

            function currentView() {
                return applySort(applyFilters(data));
            }

            // --- 렌더(기존 tr 재배치)
            function render() {
                var view = currentView();
                var total = view.length;

                // 전체 보기 여부
                var isAll = !isFinite(state.pageSize);

                // 총 페이지
                var totalPages = isAll ? 1 : Math.max(1, Math.ceil(total / state.pageSize));
                if (state.page > totalPages) state.page = totalPages;
                if (state.page < 1) state.page = 1;

                // 현재 페이지 범위 계산
                var startIdx, endIdx, pageSlice;
                if (isAll) {
                    startIdx = 0;
                    endIdx = total;
                    pageSlice = view; // 전체 그대로 사용 (slice 생략)
                } else {
                    startIdx = (state.page - 1) * state.pageSize;
                    endIdx = Math.min(total, startIdx + state.pageSize);
                    pageSlice = view.slice(startIdx, endIdx);
                }

                // tbody 재배치
                var frag = document.createDocumentFragment();
                for (var i = 0; i < pageSlice.length; i++) frag.appendChild(pageSlice[i].tr);
                tbody.innerHTML = '';
                tbody.appendChild(frag);

                // 정렬표시 갱신
                var ths = grid.querySelectorAll('thead .th-sort');
                for (var j = 0; j < ths.length; j++) {
                    var key = ths[j].getAttribute('data-key'),
                        found = null;
                    for (var k = 0; k < state.sort.length; k++)
                        if (state.sort[k].key === key) {
                            found = state.sort[k];
                            break;
                        }
                    ths[j].setAttribute('data-dir', found ? found.dir : '');
                }

                // 결과 개수 + 범위
                if (resultInfo) {
                    var rangeText = total ? (' (' + (startIdx + 1) + '–' + endIdx + ' / ' + total.toLocaleString() + ')') : '';
                    //resultInfo.textContent = total.toLocaleString() + '건' + rangeText;
                    resultInfo.textContent = total.toLocaleString() + '건';
                }

                // 페이지네이션
                renderPagination(totalPages);
            }

            window.render = render;



            var $rowsPerPage = document.getElementById('rowsPerPage');
            if ($rowsPerPage) {

                // 초기값 동기화 ★수정
                $rowsPerPage.value = (state.pageSize === Infinity ? 'all' : String(state.pageSize));

                $rowsPerPage.addEventListener('change', function() {
                    var raw = this.value;

                    if (raw === 'all') {
                        state.pageSize = Infinity; // 전체 보기
                    } else {
                        var v = parseInt(raw, 10);
                        if (!v || v < 1) v = 10;
                        state.pageSize = v;
                    }

                    // 선택값 저장 (localStorage → 쿠키 fallback) ★추가
                    savePageSizePref(raw);

                    state.page = 1; // 첫 페이지로 이동
                    render(); // 즉시 반영
                });
            }

            // 페이지네이션 렌더 & 이벤트
            var $pagination = document.getElementById('pagination');

            function renderPagination(totalPages) {
                if (!$pagination) return;
                $pagination.innerHTML = '';

                function addBtn(label, page, disabled, aria) {
                    var b = document.createElement('button');
                    b.type = 'button';
                    b.className = 'page-btn';
                    b.textContent = label;
                    if (aria) b.setAttribute('aria-label', aria);
                    if (disabled) {
                        b.disabled = true;
                    } else {
                        b.addEventListener('click', function() {
                            state.page = page;
                            render();
                        });
                    }
                    $pagination.appendChild(b);
                }

                // 좌측 이전
                addBtn('«', Math.max(1, state.page - 1), state.page === 1, '이전 페이지');

                // 1~5 윈도우
                var start = Math.max(1, state.page - 2);
                var end = Math.min(totalPages, start + 4);
                start = Math.max(1, end - 4);

                for (var p = start; p <= end; p++) {
                    (function(pp) {
                        var btn = document.createElement('button');
                        btn.type = 'button';
                        btn.className = 'page-num' + (pp === state.page ? ' active' : '');
                        btn.textContent = String(pp);
                        if (pp === state.page) {
                            btn.disabled = true;
                        } else {
                            btn.addEventListener('click', function() {
                                state.page = pp;
                                render();
                            });
                        }
                        $pagination.appendChild(btn);
                    })(p);
                }

                // 우측 다음
                addBtn('»', Math.min(totalPages, state.page + 1), state.page === totalPages, '다음 페이지');
            }

            // 테이블 클론 안의 <img>들을 dataURL(base64)로 고정해서
            // 엑셀(.xls) / PDF(html2canvas)에서도 그대로 보이게 만든다.
            function fixExportImages(clone, originalTable) {
                // 원본 grid의 img들과 clone의 img들을 같은 index로 매칭
                var origImgs = originalTable.querySelectorAll('img');
                var cloneImgs = clone.querySelectorAll('img');

                for (var i = 0; i < cloneImgs.length; i++) {
                    (function(idx) {
                        var ci = cloneImgs[idx]; // clone 쪽 img
                        var oi = origImgs[idx] || ci; // 원본 img (naturalWidth 확보용)

                        // 캔버스에 그려서 dataURL 만들기
                        var canvas = document.createElement('canvas');
                        var w = oi.naturalWidth || oi.width || 0;
                        var h = oi.naturalHeight || oi.height || 0;

                        // 이미지 정보가 없으면 alt 텍스트로 대체
                        if (!w || !h) {
                            var altSpan = document.createElement('span');
                            altSpan.textContent = oi.alt || '';
                            ci.parentNode.replaceChild(altSpan, ci);
                            return;
                        }

                        canvas.width = w;
                        canvas.height = h;

                        var ctx = canvas.getContext('2d');
                        try {
                            ctx.drawImage(oi, 0, 0, w, h);
                            var dataURL = canvas.toDataURL('image/png');

                            // clone 쪽 img를 base64로 교체
                            ci.removeAttribute('srcset');
                            ci.src = dataURL;

                            // 너무 커서 셀 깨지는 것 방지
                            ci.style.maxWidth = '80px';
                            ci.style.height = 'auto';
                        } catch (e) {
                            // crossOrigin 등으로 drawImage 실패하면 alt만 출력
                            var fallback = document.createElement('span');
                            fallback.textContent = oi.alt || '';
                            ci.parentNode.replaceChild(fallback, ci);
                        }
                    })(i);
                }
            }

            function removeImageColumnForExcel(cloneTable) {
                if (!cloneTable || !cloneTable.tHead || !cloneTable.tBodies.length) return;

                var theadRow = cloneTable.tHead.rows[0];
                if (!theadRow) return;

                // 1) 우선 헤더에서 "이미지" 라는 텍스트인 셀 찾아본다.
                var imgColIndex = -1;
                for (var c = 0; c < theadRow.cells.length; c++) {
                    var txt = theadRow.cells[c].textContent.trim();
                    if (txt === '이미지') {
                        imgColIndex = c;
                        break;
                    }
                }

                // 2) 혹시 못 찾았으면 본문에서 <img> 있는 컬럼을 찾아본다.
                if (imgColIndex === -1) {
                    var bodyRows = cloneTable.tBodies[0].rows;
                    outer:
                        for (var r = 0; r < bodyRows.length; r++) {
                            var cells = bodyRows[r].cells;
                            for (var c2 = 0; c2 < cells.length; c2++) {
                                if (cells[c2].querySelector && cells[c2].querySelector('img')) {
                                    imgColIndex = c2;
                                    break outer;
                                }
                            }
                        }
                }

                // 이미지 칼럼이 없으면 그대로 리턴
                if (imgColIndex === -1) return;

                // 3) thead에서 해당 셀 삭제
                if (theadRow.cells[imgColIndex]) {
                    theadRow.deleteCell(imgColIndex);
                }

                // 4) tbody 모든 행에서 그 인덱스 삭제
                var bodies = cloneTable.tBodies;
                for (var b = 0; b < bodies.length; b++) {
                    var rows = bodies[b].rows;
                    for (var r2 = 0; r2 < rows.length; r2++) {
                        if (rows[r2].cells[imgColIndex]) {
                            rows[r2].deleteCell(imgColIndex);
                        }
                    }
                }
            }

            function removeHiddenColumnsForExport(cloneTable){
              if (!cloneTable || !cloneTable.tHead || !cloneTable.tBodies.length) return;

              var theadRow = cloneTable.tHead.rows[0];
              if (!theadRow) return;

              // // 뒤에서부터 지워야 인덱스가 안 틀어짐
              for (var i = theadRow.cells.length - 1; i >= 0; i--) {
                var th = theadRow.cells[i];
                var cs = th && th.style ? th.style : {};

                var hiddenByClass = th.classList && th.classList.contains('col-hide');
                var hiddenByAttr  = th.hasAttribute && (th.hasAttribute('hidden'));
                var hiddenByStyle = (cs.display === 'none' || cs.visibility === 'hidden');

                if (hiddenByClass || hiddenByAttr || hiddenByStyle) {
                  // thead
                  if (theadRow.cells[i]) theadRow.deleteCell(i);
                  // tbody들
                  for (var b = 0; b < cloneTable.tBodies.length; b++) {
                    var rows = cloneTable.tBodies[b].rows;
                    for (var r = 0; r < rows.length; r++) {
                      if (rows[r].cells[i]) rows[r].deleteCell(i);
                    }
                  }
                }
              }
            }


            // --- 테이블 복제(관리자면 첫 열 제거, 다운로드용)
            function cloneTableForExport() {
                var clone = grid.cloneNode(true);

                // 링크 처리
                Array.prototype.slice.call(clone.querySelectorAll('a')).forEach(function(a) {
                    var hasImg = a.querySelector('img') !== null;

                    if (hasImg) {
                        // 이미지 링크는 링크만 벗기고 이미지 남김
                        var wrapper = document.createElement('span');
                        wrapper.innerHTML = a.innerHTML;
                        a.parentNode.replaceChild(wrapper, a);
                    } else {
                        // 텍스트 링크는 그냥 텍스트만
                        var span = document.createElement('span');
                        span.textContent = a.textContent;
                        a.parentNode.replaceChild(span, a);
                    }
                });

                // 체크박스, 버튼, 정렬화살표 등 불필요한 요소 제거
                Array.prototype.slice.call(clone.querySelectorAll('input, button, .arrows')).forEach(function(el) {
                    el.remove();
                });

                // thead 안의 정렬용 .th-sort -> 순수 텍스트만 남기기
                Array.prototype.slice.call(clone.querySelectorAll('thead .th-sort')).forEach(function(th) {
                    var span = document.createElement('span');
                    span.appendChild(
                        document.createTextNode(
                            th.textContent.replace(/\s+/g, ' ').trim()
                        )
                    );
                    th.parentNode.replaceChild(span, th);
                });

                // 관리자라면 첫 번째 선택열 지우기 (hasSel이 true일 때)
                if (window.IS_ADMIN && hasSel) {
                    var theadRow = clone.tHead && clone.tHead.rows[0];
                    if (theadRow && theadRow.cells.length) {
                        theadRow.deleteCell(0);
                    }
                    var rows = clone.tBodies[0] ? clone.tBodies[0].rows : [];
                    for (var r = 0; r < rows.length; r++) {
                        if (rows[r].cells.length) {
                            rows[r].deleteCell(0);
                        }
                    }
                }
                
                removeHiddenColumnsForExport(clone);

                return clone;
            }
            // --- CSV
            function exportCSV() {
                var clone = cloneTableForExport();
                var header = [],
                    htr = (clone.tHead && clone.tHead.rows[0]) ? clone.tHead.rows[0] : null;
                if (htr) {
                    for (var i = 0; i < htr.cells.length; i++) header.push(txt(htr.cells[i]));
                }
                var lines = [];
                if (header.length) lines.push(header.map(csvCell).join(','));
                var rows = clone.tBodies[0] ? clone.tBodies[0].rows : [];
                for (var r = 0; r < rows.length; r++) {
                    var cells = rows[r].cells,
                        row = [];
                    for (var c = 0; c < cells.length; c++) row.push(csvCell(txt(cells[c])));
                    lines.push(row.join(','));
                }
                var blob = new Blob(["\ufeff" + lines.join('\n')], {
                    type: 'text/csv;charset=utf-8;'
                });
                triggerDownload(blob, '<?php echo $bo_table ?>-' + new Date().toISOString().slice(0, 10) + '.csv');
            }

            // --- Excel(.xls) (외부 라이브러리 없음)
            function exportExcel() {
                // 1. 현재 grid 복제
                var clone = cloneTableForExport();

                // 2. 엑셀 버전에서는 이미지 컬럼 통째로 제거
                removeImageColumnForExcel(clone);

                // 3. 엑셀용 HTML 문서 구성
                var doc = document.implementation.createHTMLDocument('');
                var meta = doc.createElement('meta');
                meta.setAttribute('charset', 'utf-8');
                doc.head.appendChild(meta);

                // 최소 스타일 (테이블 라인만 / 가독용)
                var style = doc.createElement('style');
                style.textContent =
                    'table{border-collapse:separate;border-spacing:0}' +
                    'thead th{background:#f2f2f2;text-align:center}' +
                    'th,td{border:0;border-right:0.5pt solid #bbb;border-bottom:0.5pt solid #bbb;padding:6px 8px;font-size:12px;vertical-align:middle}' +
                    'thead th{border-top:0.5pt solid #bbb}' +
                    'th:first-child,td:first-child{border-left:0.5pt solid #bbb}' +
                    'tr:last-child td{border-bottom:0.5pt solid #bbb}' +
                    'th:last-child,td:last-child{border-right:0.5pt solid #bbb}';
                doc.head.appendChild(style);

                // table old attributes 정리
                clone.removeAttribute('border');
                clone.removeAttribute('cellspacing');
                clone.removeAttribute('cellpadding');
                clone.style.borderCollapse = 'separate';
                clone.style.borderSpacing = '0';

                doc.body.appendChild(clone);

                var html = '<!doctype html>' + doc.documentElement.outerHTML;

                var blob = new Blob([html], {
                    type: 'application/vnd.ms-excel;charset=utf-8;'
                });

                triggerDownload(
                    blob,
                    '<?php echo $bo_table ?>-' + new Date().toISOString().slice(0, 10) + '.xls'
                );
            }

            // --- PDF (html2canvas + jsPDF) : 바로 다운로드
            async function exportPDF() {
                // 1. 테이블 복제
                var clone = cloneTableForExport();

                // 2. 이미지 dataURL 고정 (PDF 캡처 시 외부이미지 / 권한이미지 안 깨지게)
                fixExportImages(clone, grid);

                // 3. 오프스크린 렌더용 래퍼 만들기
                var wrap = document.createElement('div');
                var SCOPE_ID = '_pdf_scope_' + Date.now();
                wrap.id = SCOPE_ID;
                wrap.style.cssText = 'position:fixed;left:-99999px;top:0;background:#fff;padding:24px';
                document.body.appendChild(wrap);

                // 4. PDF 전용 테이블 스타일 (원래 코드 유지 + img 사이즈 추가)
                var css = document.createElement('style');
                css.textContent =
                    '#' + SCOPE_ID + ' *{box-sizing:border-box}' +
                    '#' + SCOPE_ID + ' h1{font:700 18px/1.4 Arial,Malgun Gothic,sans-serif;margin:0 0 12px}' +
                    // 얇은 격자 보더 스타일 (원래 있던 내용 유지) :contentReference[oaicite:8]{index=8}
                    '#' + SCOPE_ID + ' table{width:100%;border-collapse:separate;border-spacing:0}' +
                    '#' + SCOPE_ID + ' th,#' + SCOPE_ID + ' td{border:0;border-right:0.5px solid #bbb;border-bottom:0.5px solid #bbb;padding:6px 8px;font-size:12px;vertical-align:middle}' +
                    '#' + SCOPE_ID + ' thead th{background:#f2f2f2;text-align:center;border-top:0.5px solid #bbb}' +
                    '#' + SCOPE_ID + ' th:first-child,#' + SCOPE_ID + ' td:first-child{border-left:0.5px solid #bbb}' +
                    '#' + SCOPE_ID + ' tr:last-child td{border-bottom:0.5px solid #bbb}' +
                    '#' + SCOPE_ID + ' th:last-child,#' + SCOPE_ID + ' td:last-child{border-right:0.5px solid #bbb}' +
                    '#' + SCOPE_ID + ' img{max-width:80px;height:auto;display:block}';
                wrap.appendChild(css);

                // 5. 제목 + 테이블 append (원래 로직 유지) :contentReference[oaicite:9]{index=9}
                var h1 = document.createElement('h1');
                h1.textContent = '<?php echo $board['bo_subject'] ?>';
                wrap.appendChild(h1);

                clone.style.borderCollapse = 'separate';
                clone.style.borderSpacing = '0';
                wrap.appendChild(clone);

                // 6. html2canvas로 bitmap 뽑기
                var canvas = await html2canvas(wrap, {
                    scale: 2,
                    useCORS: true
                });

                // 7. jsPDF로 A4에 맞춰서 넣기 (원래 로직 그대로)
                var img = canvas.toDataURL('image/png');
                var pdf = new jspdf.jsPDF('p', 'pt', 'a4');

                var pageW = pdf.internal.pageSize.getWidth();
                var pageH = pdf.internal.pageSize.getHeight();
                var margin = 30;
                var imgW = pageW - margin * 2;
                var ratio = imgW / canvas.width;
                var imgH = canvas.height * ratio;

                // 한 페이지에 다 들어가면 한 장만, 아니면 나눠서 여러 장 그리는 기존 패턴 이어서 사용
                if (imgH <= pageH - margin * 2) {
                    pdf.addImage(img, 'PNG', margin, margin, imgW, imgH);
                } else {
                    // 여러 페이지 분할
                    var y = 0;
                    while (y < imgH) {
                        pdf.addImage(img, 'PNG', margin, margin - y, imgW, imgH);
                        y += (pageH - margin * 2);
                        if (y < imgH) pdf.addPage();
                    }
                }

                pdf.save('<?php echo $bo_table ?>-' + new Date().toISOString().slice(0, 10) + '.pdf');

                // 8. cleanup
                document.body.removeChild(wrap);
            }

            function triggerDownload(blob, filename) {
                var a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = filename;
                document.body.appendChild(a);
                a.click();
                setTimeout(function() {
                    URL.revokeObjectURL(a.href);
                    a.remove();
                }, 400);
            }

            // --- 이벤트
            var $q = document.getElementById('q');
            var $qField = document.getElementById('qField');

            // 검색어 입력
            if ($q) $q.addEventListener('input', debounce(function(e) {
                state.q = e.target.value;
                state.page = 1;
                render();
            }, 120));

            // 검색 대상 필드(제목/번호/...) 변경
            if ($qField) $qField.addEventListener('change', function(e) {
                state.qField = e.target.value || 'title';
                state.page = 1;
                render();
            });

            var $from = document.getElementById('dateFrom');
            var $to = document.getElementById('dateTo');

            function bindDateInput(el, key) {
                if (!el) return;

                // 캘린더/수동 입력을 yyyy-mm-dd 로 변환
                function toISODate(v) {
                    v = String(v || '').trim();
                    if (!v) return '';
                    // 1) 구분자 통일
                    v = v.replace(/[./]/g, '-');
                    // 2) yyyy-mm-dd 추출 (앞쪽 10글자 방어)
                    var m = v.slice(0, 10).match(/^(\d{4})-(\d{1,2})-(\d{1,2})$/);
                    if (!m) return '';
                    var y = m[1],
                        mo = ('0' + m[2]).slice(-2),
                        d = ('0' + m[3]).slice(-2);
                    return y + '-' + mo + '-' + d;
                }

                function apply(raw) {
                    var v = toISODate(raw);
                    state[key] = v || '';

                    // 시작/종료 모두 값이 있을 때 유효성 체크
                    if (state.dateFrom && state.dateTo) {
                        var from = new Date(state.dateFrom).getTime();
                        var to = new Date(state.dateTo).getTime();
                        if (to < from) {
                            alert('종료일은 시작일과 동일하거나 이후로 설정해주세요');
                            if (key === 'dateTo') {
                                state.dateTo = state.dateFrom;
                                if ($to) $to.value = state.dateFrom;
                            } else if (key === 'dateFrom') {
                                state.dateFrom = state.dateTo;
                                if ($from) $from.value = state.dateTo;
                            }
                        }
                    }
                    state.page = 1;
                    render();
                }

                el.addEventListener('input', function(e) {
                    apply(e.target.value);
                });
                el.addEventListener('change', function(e) {
                    apply(e.target.value);
                });
            }

            bindDateInput($from, 'dateFrom');
            bindDateInput($to, 'dateTo');

            // jQuery UI datepicker 선택 시 기존 핸들러(input/change) 강제 실행
            (function() {
                var $els = window.jQuery ? jQuery('#dateFrom, #dateTo') : null;
                if (!$els || !$els.length) return;

                // 아직 datepicker 안 붙어있으면 붙여주고, 이미 붙어 있으면 옵션만 갱신
                $els.each(function() {
                    var $t = jQuery(this);

                    // 네이티브 date 타입이면 이벤트가 안 올라올 수 있으니 안전하게 text로 전환
                    try {
                        if (this.type === 'date') this.type = 'text';
                    } catch (e) {}

                    if (!$t.data('datepicker')) {
                        $t.datepicker(); // setDefaults 유지, 여기선 초기화만
                    }

                    // 핵심: onSelect에서 value 세팅 + input/change 이벤트 발생
                    $t.datepicker('option', 'onSelect', function(dt) {
                        this.value = dt;
                        this.dispatchEvent(new Event('input', {
                            bubbles: true
                        }));
                        this.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    });
                });
            })();


            Array.prototype.forEach.call(grid.querySelectorAll('thead .th-sort'), function(th) {
                th.addEventListener('click', function() {
                    var key = th.getAttribute('data-key');
                    if (!key) return;

                    // 지금 이 컬럼이 이미 정렬 중인지 확인
                    var isSameCol = (state.sort && state.sort[0] && state.sort[0].key === key);
                    var curDir = isSameCol ? state.sort[0].dir : '';

                    var nextDir;
                    if (!curDir) {
                        // 이 컬럼을 처음 누른 경우
                        if (key === 'image') {
                            // 이미지 컬럼은 처음부터 desc (이미지 있는 글 위로)
                            nextDir = 'desc';
                        } else {
                            // 나머지는 기존처럼 asc 먼저
                            nextDir = 'asc';
                        }
                    } else {
                        // 이미 한 번 정렬 중인 컬럼이면 asc <-> desc 토글
                        nextDir = (curDir === 'asc') ? 'desc' : 'asc';
                    }

                    state.sort = [{
                        key: key,
                        dir: nextDir
                    }];

                    // 정렬 누르면 항상 첫 페이지
                    state.page = 1;
                    render();
                });
            });

            // 초기화
            var $clear = document.getElementById('clearFilters');
            if ($clear) $clear.addEventListener('click', function() {

                // 1) 검색어 / 기간 초기화 ★수정
                state.q = '';
                state.dateFrom = '';
                state.dateTo = '';

                if ($q) $q.value = '';
                if ($from) $from.value = '';
                if ($to) $to.value = '';

                // 검색필드(qField)도 기본 'title'(제목)로 복귀 ★추가
                state.qField = 'title';
                if ($qField) $qField.value = 'title';

                // 2) 분류(카테고리) 선택 전부 해제 ★동일
                state.categories.clear();
                if (categoryBox) {
                    var cbs = categoryBox.querySelectorAll('input[type=checkbox]');
                    for (var i = 0; i < cbs.length; i++) {
                        cbs[i].checked = false;
                    }
                }

                // 3) 동적 필터(.controls_dynamic)도 리셋 ★동일
                var dyn = document.querySelector('.controls_dynamic');
                if (dyn) {
                    // 상태 초기화
                    if (window.STATE && STATE.extra) STATE.extra = {};

                    // select 전부 첫 옵션("전체")로
                    var sels = dyn.querySelectorAll('select');
                    for (var s = 0; s < sels.length; s++) {
                        sels[s].selectedIndex = 0;
                    }

                    // checkbox/radio 전부 해제
                    var inputs = dyn.querySelectorAll('input[type="checkbox"], input[type="radio"]');
                    for (var k = 0; k < inputs.length; k++) {
                        inputs[k].checked = false;
                    }

                    // chip UI 선택 표시 제거
                    var chips = dyn.querySelectorAll('.chip.selected');
                    for (var j = 0; j < chips.length; j++) {
                        chips[j].classList.remove('selected');
                    }
                }

                // 4) 정렬도 기본으로 되돌림 (날짜 desc) ★추가
                state.sort = [{
                    key: 'date',
                    dir: 'desc'
                }];

                // 5) 페이지 1로 이동 ★동일
                state.page = 1;

                // 만약 "초기화하면 rowsPerPage도 10개로 돌려"를 원하면 아래 주석 해제:
                // state.pageSize = 10;
                // if (document.getElementById('rowsPerPage')) {
                //     document.getElementById('rowsPerPage').value = '10';
                //     savePageSizePref('10');
                // }

                // 6) 다시 렌더
                render();
            });


            // 내보내기
            var $csv = document.getElementById('exportCsv');
            if ($csv) $csv.addEventListener('click', exportCSV);
            var $xls = document.getElementById('exportExcel');
            if ($xls) $xls.addEventListener('click', exportExcel);
            var $pdf = document.getElementById('exportPDF');
            if ($pdf) $pdf.addEventListener('click', function() {
                exportPDF();
            });

            // 부팅
            render();

        } catch (err) {
            console.error('[GridBoard] fatal:', err);
            if (window && window.alert) alert('스크립트 오류: ' + err.message);
        }
    })();


    (function() {

        window.buildSnapshot = window.buildSnapshot || function() {
            var tbody = document.querySelector('#grid tbody');
            var rows = [];
            if (!tbody) {
                window.SNAPSHOT = [];
                return [];
            }

            tbody.querySelectorAll('tr').forEach(function(tr) {
                if (!tr.children.length) return;
                var ds = tr.dataset;
                var row = {
                    tr: tr,
                    id: +(ds.id || 0),
                    category: ds.category || '',
                    image: ds.image || '',
                    title: ds.title || '',
                    author: ds.author || '',
                    date: ds.date || '',
                    comment: +(ds.comment || 0),
                    views: +(ds.views || 0)
                };
                if (Array.isArray(window.EXTRA_FILTERS)) {
                    window.EXTRA_FILTERS.forEach(function(f) {
                        var key = 'wr_' + f.n;
                        var v = ds[key];
                        if (v == null) {
                            var ci = (window.headerIndex || {})[key];
                            if (ci != null && tr.children[ci]) v = tr.children[ci].textContent.trim();
                        }
                        row[key] = v || '';
                    });
                }
                rows.push(row);
            });

            window.SNAPSHOT = rows;
            return rows;
        };



        const wrap = document.querySelector('.controls_dynamic');
        if (!wrap || !Array.isArray(EXTRA_FILTERS)) return;

        window.STATE = window.STATE || {};
        STATE.extra = STATE.extra || {};


        EXTRA_FILTERS.forEach(f => {

            if (f.type === 'text') return;

            // 필터 컨테이너
            const box = document.createElement('div');
            box.className = 'control';
            const label = document.createElement('label');
            label.textContent = f.subj;
            box.appendChild(label);

            // select
            if (f.type === 'select' && f.opts.length) {
                const sel = document.createElement('select');
                sel.className = 'select'; // ← 추가된 부분
                sel.innerHTML =
                    '<option value="">전체</option>' +
                    f.opts.map(v => `<option value="${v}">${v}</option>`).join('');

                sel.addEventListener('change', () => {
                    STATE.extra['wr_' + f.n] = sel.value;
                    state.page = 1;
                    if (window.state) window.state.page = 1;
                    window.render && window.render();
                });

                box.appendChild(sel);
            }

            // checkbox
            else if (f.type === 'checkbox' && f.opts.length) {
                const grp = document.createElement('div');
                grp.className = 'chipbox';

                f.opts.forEach((v, i) => {
                    const id = `cb_${f.n}_${i}`;
                    const lab = document.createElement('label');
                    lab.className = 'chip'; // ← 핵심: 칩 스타일
                    lab.innerHTML =
                        `<input type="checkbox" id="${id}" value="${v}"><span>${v}</span>`;
                    grp.appendChild(lab);
                });

                grp.addEventListener('change', () => {
                    const vals = Array.from(grp.querySelectorAll('input:checked')).map(i => i.value);
                    STATE.extra['wr_' + f.n] = vals;
                    state.page = 1;
                    if (window.state) window.state.page = 1;
                    window.render && window.render();
                });

                box.appendChild(grp);
            }

            // radio
            else if (f.type === 'radio' && f.opts.length) {
                const grp = document.createElement('div');
                grp.className = 'chipbox chipbox-radio';

                const allId = `rd_${f.n}_all`;
                const allSpan = document.createElement('span');
                allSpan.className = 'rb-choice';
                allSpan.innerHTML = `<input type="radio" name="rd_${f.n}" id="${allId}" value="" checked><label for="${allId}">전체</label>`;
                grp.appendChild(allSpan);

                f.opts.forEach(v => {
                    const id = `rd_${f.n}_${v}`;
                    const span = document.createElement('span');
                    span.className = 'rb-choice';
                    span.innerHTML = `<input type="radio" name="rd_${f.n}" id="${id}" value="${v}"><label for="${id}">${v}</label>`;
                    grp.appendChild(span);
                });
                grp.addEventListener('change', () => {
                    const sel = grp.querySelector('input:checked');
                    STATE.extra['wr_' + f.n] = sel ? sel.value : '';
                    state.page = 1;
                    if (window.state) window.state.page = 1;
                    window.render && window.render();
                });
                box.appendChild(grp);
            }

            wrap.appendChild(box);
        });


    })();
</script>

<div id="bulkOverlay" style="
    display:none;
    position:fixed;left:0;top:0;right:0;bottom:0;
    background:rgba(0,0,0,0.4);z-index:9999;
    align-items:center;justify-content:center;">
    <div id="bulkBox" style="
        background:#fff;
        border-radius:20px;
        box-shadow:0 20px 40px rgba(0,0,0,0.2);
        max-width:90%;
        width:400px;
        padding:24px;
        box-sizing:border-box;
        position:relative;">

        <button type="button" id="bulkCloseBtn">✕</button>

        <h3 class="font-B">일괄등록</h3>

        <div class="bulksubword">
            샘플 양식을 다운로드 하셔서 작성 > 업로드 해주세요.<br>
            샘플 양식은 현재 컬럼을 기준으로 생성되요. 제목을 포함한 추가된 필드에 값을 저장할 수 있어요. (이미지는 직접 업로드)<br>
            체크박스, 라디오, 셀렉트 등의 선택항목이 있는 필드는 선택항목과 일치한 값을 넣어주셔야 해요.
        </div>

        <form id="bulkUploadForm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>">
            <input type="file" name="bulk_file" id="bulk_file" accept=".xlsx,.xls">
            <button type="button" id="bulkUploadBtn" class="btn_admin main_rb_bg">
                업로드
            </button>
        </form>

        <div>
            <button type="button" id="bulkDownloadBtn" class="btn_admin">
                샘플양식 다운로드
            </button>
        </div>

        <div id="bulkMsg" style="margin-top:7px;font-size:13px;color:#666;line-height:1.4;word-break:keep-all;"></div>
    </div>
</div>

<script>
    var BULK_BO_TABLE = "<?php echo $bo_table; ?>";

    var BULK_HEADERS = (function() {
        var arr = [];

        // 1) 분류
        arr.push('분류');

        // 2) 제목
        arr.push('제목');

        // 3) 여분필드 라벨들 (bo_1_subj ~ bo_10_subj)
        <?php
    for ($i=1; $i<=10; $i++) {
        $label = isset($board['bo_'.$i.'_subj']) ? trim((string)$board['bo_'.$i.'_subj']) : '';
        if ($label !== '') {
            // JS에 push
            // JSON 인코딩해서 안전하게 출력
            echo "arr.push(".json_encode($label, JSON_UNESCAPED_UNICODE).");\n";
        }
    }
    ?>

        return arr;
    })();

    var bulkOverlay = document.getElementById('bulkOverlay');
    var bulkOpenBtn = document.getElementById('bulkOpenBtn');
    var bulkCloseBtn = document.getElementById('bulkCloseBtn');
    var bulkDownloadBtn = document.getElementById('bulkDownloadBtn');
    var bulkUploadBtn = document.getElementById('bulkUploadBtn');
    var bulkFile = document.getElementById('bulk_file');
    var bulkMsg = document.getElementById('bulkMsg');
    var bulkUploadForm = document.getElementById('bulkUploadForm');

    function openBulkModal() {
        if (!bulkOverlay) return;
        bulkMsg.textContent = '';
        bulkOverlay.style.display = 'flex'; // 여기서 flex로 전환
    }

    function closeBulkModal() {
        if (!bulkOverlay) return;
        bulkOverlay.style.display = 'none';
    }

    if (bulkOpenBtn) {
        bulkOpenBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openBulkModal();
        });
    }

    if (bulkCloseBtn) {
        bulkCloseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            closeBulkModal();
        });
    }

    // 오버레이 바깥쪽 클릭하면 닫기 (흰 박스 내부 클릭은 유지)
    if (bulkOverlay) {
        bulkOverlay.addEventListener('click', function(e) {
            // bulkOverlay 배경 영역만 클릭했을 때만 닫기
            if (e.target === bulkOverlay) {
                closeBulkModal();
            }
        });
    }

    // 샘플양식 다운로드
    if (bulkDownloadBtn) {
        bulkDownloadBtn.addEventListener('click', function(e) {
            e.preventDefault();

            // 전역에서 받은 bo_table
            var boTable = (typeof BULK_BO_TABLE !== 'undefined') ? BULK_BO_TABLE : '';

            if (!boTable) {
                if (bulkMsg) {
                    bulkMsg.style.color = '#c00';
                    bulkMsg.textContent = 'bo_table 값이 없습니다.';
                }
                return;
            }

            // 실제 다운로드 호출
            var url = "<?php echo $board_skin_url; ?>/ajax/bulk_download.php?bo_table=" + encodeURIComponent(boTable);
            window.location.href = url;

            if (bulkMsg) {
                bulkMsg.style.color = '#000';
                bulkMsg.textContent = '샘플양식 다운로드를 시작합니다.';
            }
        });
    }

    // 업로드 버튼 클릭 -> ajax 업로드
    if (bulkUploadBtn) {
        bulkUploadBtn.addEventListener('click', function() {
            // 파일 선택 체크
            if (!bulkFile.files.length) {
                if (bulkMsg) {
                    bulkMsg.style.color = '#c00';
                    bulkMsg.textContent = '업로드할 .xls 파일을 선택해주세요.';
                }
                return;
            }

            var fileName = bulkFile.files[0].name;
            if (!/\.xlsx?$/i.test(fileName)) {
                if (bulkMsg) {
                    bulkMsg.style.color = '#c00';
                    bulkMsg.textContent = 'xlsx 또는 xls 형식만 업로드 가능합니다.';
                }
                return;
            }

            var formData = new FormData(bulkUploadForm);

            fetch('<?php echo $board_skin_url; ?>/ajax/bulk_upload.php', {
                    method: 'POST',
                    body: formData
                })
                .then(function(res) {
                    return res.json();
                })
                .then(function(json) {
                    if (json.status === 'ok') {
                        if (bulkMsg) {
                            bulkMsg.style.color = '#008000';
                            bulkMsg.textContent = '등록 성공 : ' + json.inserted + '건 추가되었습니다. 새로고침 해주세요.';
                        }
                    } else {
                        if (bulkMsg) {
                            bulkMsg.style.color = '#c00';
                            bulkMsg.textContent = '등록 실패 : ' + (json.message || '알 수 없는 오류');
                        }
                    }
                })
                .catch(function(err) {
                    if (bulkMsg) {
                        bulkMsg.style.color = '#c00';
                        bulkMsg.textContent = '통신 오류가 발생했습니다.';
                    }
                });
        });
    }
</script>



<?php if($is_checkbox) { ?>
<noscript>
    <p>자바스크립트를 사용하지 않는 경우<br>별도의 확인 절차 없이 바로 선택삭제 처리하므로 주의하시기 바랍니다.</p>
</noscript>
<?php } ?>

<?php if ($is_checkbox) { ?>
<script>
// // 체크박스 연동 인라인 수정 + AJAX 저장
(function() {
    var form = document.getElementById('fboardlist');
    if (!form) return;

    var btnSave = document.getElementById('rbSelectedSaveBtn');
    if (!btnSave) return;

    var checks = form.querySelectorAll('input[name="chk_wr_id[]"]');

    // // 부모 tr 찾기 (IE 호환)
    function findParentTr(el) {
        while (el && el.tagName && el.tagName.toLowerCase() !== 'tr') {
            el = el.parentNode;
        }
        if (!el || !el.tagName) return null;
        return el.tagName.toLowerCase() === 'tr' ? el : null;
    }

    // // 해당 행의 span/input 보이기/숨기기
    function updateRowDisplay(tr, on) {
        if (!tr) return;

        // // 이 행 안의 모든 표시용 텍스트
        var spans  = tr.querySelectorAll('.rb-list-text');
        // // 이 행 안의 모든 입력창
        var inputs = tr.querySelectorAll('.rb-list-input');

        var i;

        for (i = 0; i < spans.length; i++) {
            spans[i].style.display = on ? 'none' : '';
        }
        for (i = 0; i < inputs.length; i++) {
            inputs[i].style.display = on ? '' : 'none';
        }
    }



    // // 각 줄 체크박스 change 이벤트
    for (var i = 0; i < checks.length; i++) {
        (function(chk) {
            chk.addEventListener('change', function() {
                var tr = findParentTr(chk);
                updateRowDisplay(tr, chk.checked);
            });
        })(checks[i]);
    }


    // // [선택저장] 클릭 시 AJAX 저장
    btnSave.addEventListener('click', function(e) {
        e.preventDefault();

        if (btnSave.disabled) return;

        var hasChecked = false;
        var i;

        for (i = 0; i < checks.length; i++) {
            if (checks[i].checked) {
                hasChecked = true;
                break;
            }
        }

        if (!hasChecked) {
            alert('선택저장할 게시물을 하나 이상 선택하세요.');
            return;
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '<?php echo $board_skin_url; ?>/ajax/ajax.list_save.php', true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState !== 4) return;

            btnSave.disabled = false;

            if (xhr.status === 200) {
                var res;

                try {
                    res = JSON.parse(xhr.responseText);
                } catch (err) {
                    alert('서버 응답 처리 중 오류가 발생했습니다.');
                    return;
                }

                if (res && res.ok) {
                    alert('선택한 게시물이 저장되었습니다.');
                    // // 필요하면 주석 풀어서 새로고침
                    // location.reload();
                } else {
                    alert(res && res.message ? res.message : '저장 중 오류가 발생했습니다.');
                }
            } else {
                alert('통신 오류가 발생했습니다.');
            }
        };

        btnSave.disabled = true;

        var fd = new FormData(form);
        xhr.send(fd);
    });
})();
</script>

<script>
    function all_checked(sw) {
        var f = document.fboardlist;

        for (var i = 0; i < f.length; i++) {
            if (f.elements[i].name == "chk_wr_id[]")
                f.elements[i].checked = sw;
        }
    }

    function fboardlist_submit(f) {
        var chk_count = 0;

        for (var i = 0; i < f.length; i++) {
            if (f.elements[i].name == "chk_wr_id[]" && f.elements[i].checked)
                chk_count++;
        }

        if (!chk_count) {
            alert(document.pressed + "할 게시물을 하나 이상 선택하세요.");
            return false;
        }

        if (document.pressed == "선택복사") {
            select_copy("copy");
            return;
        }

        if (document.pressed == "선택이동") {
            select_copy("move");
            return;
        }

        if (document.pressed == "선택삭제") {
            if (!confirm("선택한 게시물을 정말 삭제하시겠습니까?\n\n한번 삭제한 자료는 복구할 수 없습니다\n\n답변글이 있는 게시글을 선택하신 경우\n답변글도 선택하셔야 게시글이 삭제됩니다."))
                return false;

            f.removeAttribute("target");
            f.action = g5_bbs_url + "/board_list_update.php";
        }

        return true;
    }

    // 선택한 게시물 복사 및 이동
    function select_copy(sw) {
        var f = document.fboardlist;

        if (sw == 'copy')
            str = "복사";
        else
            str = "이동";

        var sub_win = window.open("", "move", "left=50, top=50, width=500, height=550, scrollbars=1");

        f.sw.value = sw;
        f.target = "move";
        f.action = g5_bbs_url + "/move.php";
        f.submit();
    }

    // 게시판 리스트 관리자 옵션
    jQuery(function($) {
        $(".btn_more_opt.is_list_btn").on("click", function(e) {
            e.stopPropagation();
            $(".more_opt.is_list_btn").toggle();
        });
        $(document).on("click", function(e) {
            if (!$(e.target).closest('.is_list_btn').length) {
                $(".more_opt.is_list_btn").hide();
            }
        });
    });
</script>
<?php } ?>
<!-- } 게시판 목록 끝 -->
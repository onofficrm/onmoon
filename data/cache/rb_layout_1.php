<?php
    ob_start();

    $rb_module_table = 'rb_module';
    $GLOBALS['rb_module_table'] = $rb_module_table;
    $is_admin = '';

    // -- 회원 레벨 가시성 헬퍼 (관리자 제외)
    if (!function_exists('rb__level_visible')) {
        function rb__level_visible($mb_level, $rule, $level) {
            $mb_level = (int)$mb_level;
            $rule     = (int)$rule;
            $level    = (int)$level;
            if (!$rule || !$level) return true; // 설정 없으면 항상 출력

            switch ($rule) {
                case 1: return $mb_level === $level;
                case 2: return $mb_level !== $level;
                case 3: return $mb_level >=  $level;
                case 4: return $mb_level <   $level;
                case 5: return $mb_level >=  $level;
                case 6: return $mb_level <   $level;
                default: return true;
            }
        }
    }
    ?>
<?php
$row_mod = array (
  'md_id' => '1',
  'md_layout' => '1',
  'md_layout_name' => 'basic',
  'md_theme' => 'rb.basic',
  'md_title' => '공감마사지',
  'md_type' => 'banner',
  'md_bo_table' => '',
  'md_sca' => '',
  'md_widget' => '',
  'md_poll' => '',
  'md_poll_id' => '',
  'md_banner' => '배너1',
  'md_banner_id' => '',
  'md_banner_skin' => 'rb.mod/banner/skin/rb.banner',
  'md_module' => '0',
  'md_skin' => '',
  'md_cnt' => '0',
  'md_auto_time' => '0',
  'md_gap' => '0',
  'md_gap_mo' => '0',
  'md_col' => '0',
  'md_row' => '0',
  'md_col_mo' => '0',
  'md_row_mo' => '0',
  'md_width' => '100',
  'md_height' => 'auto',
  'md_subject_is' => '0',
  'md_thumb_is' => '0',
  'md_nick_is' => '0',
  'md_date_is' => '0',
  'md_content_is' => '0',
  'md_icon_is' => '0',
  'md_comment_is' => '0',
  'md_ca_is' => '0',
  'md_swiper_is' => '1',
  'md_auto_is' => '0',
  'md_order' => '',
  'md_datetime' => '2025-12-09 12:11:15',
  'md_ip' => '175.176.71.7',
  'md_order_id' => '1',
  'md_banner_bg' => '#FFFFFF',
  'md_border' => '',
  'md_radius' => '11',
  'md_padding' => '0',
  'md_padding_mo' => '',
  'md_margin_top_mo' => '',
  'md_margin_top_pc' => '',
  'md_margin_btm_pc' => '',
  'md_margin_btm_mo' => '',
  'md_size' => '%',
  'md_show' => '',
  'md_level' => '',
  'md_level_is' => '',
  'md_tab_list' => '',
  'md_tab_skin' => '',
  'md_item_tab_list' => '',
  'md_item_tab_skin' => '',
  'md_title_color' => '#25282B',
  'md_title_size' => '20',
  'md_title_font' => '',
  'md_title_hide' => '1',
  'md_order_latest' => '',
  'md_order_banner' => 'bn_order, bn_id desc',
  'md_notice' => '0',
  'md_soldout_hidden' => '0',
  'md_soldout_asc' => '0',
  'md_arrow_type' => '0',
  'md_wide_is' => '0',
  'md_sec_key' => '',
  'md_sec_uid' => '',
  'md_border_width' => '0',
  'md_border_color' => '#DDDDDD',
  'md_box_shadow' => '',
  'md_box_shadow_w' => '0',
  'md_box_shadow_c' => '#25282b16',
  'md_padding_lr_pc' => '',
  'md_padding_lr_mo' => '',
  'md_padding_tb_pc' => '',
  'md_padding_tb_mo' => '',
  'md_1' => '',
  'md_2' => '',
  'md_3' => '',
  'md_4' => '',
  'md_5' => '',
  'md_6' => '',
  'md_7' => '',
  'md_8' => '',
  'md_9' => '',
  'md_10' => '',
);
?>
<?php $__rb_mb_level = isset($GLOBALS['member']['mb_level']) ? (int)$GLOBALS['member']['mb_level'] : 1;
            if (!$is_admin && !rb__level_visible($__rb_mb_level, 0, 0)) { } else { ?>
            <div 
              class="rb_layout_box " 
              style="width:100%;" 
              data-order-id="1" 
              data-id="1" 
              data-layout="1" 
              data-sec-key=""
              data-sec-uid=""
              data-title="공감마사지" 
              data-shop="0"
            >

                <ul class="content_box rb_module_1    " 
                    
                    style="                    margin-top:0px; 
                      margin-bottom:0px;
                    ">
                    
                    

                    
                    
                    
                                            <div class="bbs_main_wrap_tit" style="display:none">
                            <ul class="bbs_main_wrap_tit_l">
                                <a href="javascript:void(0);">
                                    <h2 class="" style="color:#25282B; font-size:20px; ">공감마사지</h2>
                                </a>
                            </ul>
                            <ul class="bbs_main_wrap_tit_r"></ul>
                            <div class="cb"></div>
                        </div>
                        <div class="rb-module-wrap module_banner_wrap md_arrow_0" 
                           style="
                           height:auto;                           border-color:#DDDDDD;                            border-radius:11px;                                                       background-color:#FFFFFF;                            
                           ">
                            <?php echo rb_banners("배너1", "", "rb.mod/banner/skin/rb.banner", "bn_order, bn_id desc"); ?>                        </div>
                    
                                        
                    
                                    
                
                                    
                    
                    

                                        
                                    </ul>
                
                
                <div class="flex_box_inner flex_box" data-layout="1-1"></div>
            </div>

            
            <?php } ?>
<?php
$row_mod = array (
  'md_id' => '5',
  'md_layout' => '1',
  'md_layout_name' => 'basic',
  'md_theme' => 'rb.basic',
  'md_title' => '서울지역',
  'md_type' => 'banner',
  'md_bo_table' => '',
  'md_sca' => '',
  'md_widget' => '',
  'md_poll' => '',
  'md_poll_id' => '',
  'md_banner' => '서울지역 배너',
  'md_banner_id' => '',
  'md_banner_skin' => 'rb.mod/banner/skin/rb.banner',
  'md_module' => '0',
  'md_skin' => '',
  'md_cnt' => '0',
  'md_auto_time' => '3000',
  'md_gap' => '20',
  'md_gap_mo' => '10',
  'md_col' => '5',
  'md_row' => '1',
  'md_col_mo' => '0',
  'md_row_mo' => '0',
  'md_width' => '100',
  'md_height' => 'auto',
  'md_subject_is' => '0',
  'md_thumb_is' => '0',
  'md_nick_is' => '0',
  'md_date_is' => '0',
  'md_content_is' => '0',
  'md_icon_is' => '0',
  'md_comment_is' => '0',
  'md_ca_is' => '0',
  'md_swiper_is' => '1',
  'md_auto_is' => '1',
  'md_order' => '',
  'md_datetime' => '2025-12-09 14:21:17',
  'md_ip' => '175.176.71.7',
  'md_order_id' => '2',
  'md_banner_bg' => '#FFFFFF',
  'md_border' => '',
  'md_radius' => '11',
  'md_padding' => '0',
  'md_padding_mo' => '',
  'md_margin_top_mo' => '',
  'md_margin_top_pc' => '',
  'md_margin_btm_pc' => '',
  'md_margin_btm_mo' => '',
  'md_size' => '%',
  'md_show' => '',
  'md_level' => '',
  'md_level_is' => '',
  'md_tab_list' => '',
  'md_tab_skin' => '',
  'md_item_tab_list' => '',
  'md_item_tab_skin' => '',
  'md_title_color' => '#25282B',
  'md_title_size' => '20',
  'md_title_font' => 'font-B',
  'md_title_hide' => '0',
  'md_order_latest' => '',
  'md_order_banner' => 'bn_order, bn_id desc',
  'md_notice' => '0',
  'md_soldout_hidden' => '0',
  'md_soldout_asc' => '0',
  'md_arrow_type' => '0',
  'md_wide_is' => '0',
  'md_sec_key' => '',
  'md_sec_uid' => '',
  'md_border_width' => '0',
  'md_border_color' => '#DDDDDD',
  'md_box_shadow' => '',
  'md_box_shadow_w' => '0',
  'md_box_shadow_c' => '#25282b16',
  'md_padding_lr_pc' => '',
  'md_padding_lr_mo' => '',
  'md_padding_tb_pc' => '',
  'md_padding_tb_mo' => '',
  'md_1' => '',
  'md_2' => '',
  'md_3' => '',
  'md_4' => '',
  'md_5' => '',
  'md_6' => '',
  'md_7' => '',
  'md_8' => '',
  'md_9' => '',
  'md_10' => '',
);
?>
<?php $__rb_mb_level = isset($GLOBALS['member']['mb_level']) ? (int)$GLOBALS['member']['mb_level'] : 1;
            if (!$is_admin && !rb__level_visible($__rb_mb_level, 0, 0)) { } else { ?>
            <div 
              class="rb_layout_box " 
              style="width:100%;" 
              data-order-id="2" 
              data-id="5" 
              data-layout="1" 
              data-sec-key=""
              data-sec-uid=""
              data-title="서울지역" 
              data-shop="0"
            >

                <ul class="content_box rb_module_5    " 
                    
                    style="                    margin-top:0px; 
                      margin-bottom:0px;
                    ">
                    
                    

                    
                    
                    
                                            <div class="bbs_main_wrap_tit" style="display:block">
                            <ul class="bbs_main_wrap_tit_l">
                                <a href="javascript:void(0);">
                                    <h2 class="font-B" style="color:#25282B; font-size:20px; ">서울지역</h2>
                                </a>
                            </ul>
                            <ul class="bbs_main_wrap_tit_r"></ul>
                            <div class="cb"></div>
                        </div>
                        <div class="rb-module-wrap module_banner_wrap md_arrow_0" 
                           style="
                           height:auto;                           border-color:#DDDDDD;                            border-radius:11px;                                                       background-color:#FFFFFF;                            
                           ">
                            <?php echo rb_banners("서울지역 배너", "", "rb.mod/banner/skin/rb.banner", "bn_order, bn_id desc"); ?>                        </div>
                    
                                        
                    
                                    
                
                                    
                    
                    

                                        
                                    </ul>
                
                
                <div class="flex_box_inner flex_box" data-layout="1-5"></div>
            </div>

            
            <?php } ?>
<?php
$row_mod = array (
  'md_id' => '6',
  'md_layout' => '1',
  'md_layout_name' => 'basic',
  'md_theme' => 'rb.basic',
  'md_title' => '경기지역',
  'md_type' => 'banner',
  'md_bo_table' => '',
  'md_sca' => '',
  'md_widget' => '',
  'md_poll' => '',
  'md_poll_id' => '',
  'md_banner' => '경기지역 배너',
  'md_banner_id' => '',
  'md_banner_skin' => 'rb.mod/banner/skin/rb.banner',
  'md_module' => '0',
  'md_skin' => '',
  'md_cnt' => '0',
  'md_auto_time' => '3000',
  'md_gap' => '20',
  'md_gap_mo' => '10',
  'md_col' => '5',
  'md_row' => '1',
  'md_col_mo' => '0',
  'md_row_mo' => '0',
  'md_width' => '100',
  'md_height' => 'auto',
  'md_subject_is' => '0',
  'md_thumb_is' => '0',
  'md_nick_is' => '0',
  'md_date_is' => '0',
  'md_content_is' => '0',
  'md_icon_is' => '0',
  'md_comment_is' => '0',
  'md_ca_is' => '0',
  'md_swiper_is' => '1',
  'md_auto_is' => '1',
  'md_order' => '',
  'md_datetime' => '2025-12-09 15:20:03',
  'md_ip' => '175.176.71.7',
  'md_order_id' => '3',
  'md_banner_bg' => '#FFFFFF',
  'md_border' => '',
  'md_radius' => '11',
  'md_padding' => '0',
  'md_padding_mo' => '',
  'md_margin_top_mo' => '',
  'md_margin_top_pc' => '',
  'md_margin_btm_pc' => '',
  'md_margin_btm_mo' => '',
  'md_size' => '%',
  'md_show' => '',
  'md_level' => '',
  'md_level_is' => '',
  'md_tab_list' => '',
  'md_tab_skin' => '',
  'md_item_tab_list' => '',
  'md_item_tab_skin' => '',
  'md_title_color' => '#25282B',
  'md_title_size' => '20',
  'md_title_font' => 'font-B',
  'md_title_hide' => '0',
  'md_order_latest' => '',
  'md_order_banner' => 'bn_order, bn_id desc',
  'md_notice' => '0',
  'md_soldout_hidden' => '0',
  'md_soldout_asc' => '0',
  'md_arrow_type' => '0',
  'md_wide_is' => '0',
  'md_sec_key' => '',
  'md_sec_uid' => '',
  'md_border_width' => '0',
  'md_border_color' => '#DDDDDD',
  'md_box_shadow' => '',
  'md_box_shadow_w' => '0',
  'md_box_shadow_c' => '#25282b16',
  'md_padding_lr_pc' => '',
  'md_padding_lr_mo' => '',
  'md_padding_tb_pc' => '',
  'md_padding_tb_mo' => '',
  'md_1' => '',
  'md_2' => '',
  'md_3' => '',
  'md_4' => '',
  'md_5' => '',
  'md_6' => '',
  'md_7' => '',
  'md_8' => '',
  'md_9' => '',
  'md_10' => '',
);
?>
<?php $__rb_mb_level = isset($GLOBALS['member']['mb_level']) ? (int)$GLOBALS['member']['mb_level'] : 1;
            if (!$is_admin && !rb__level_visible($__rb_mb_level, 0, 0)) { } else { ?>
            <div 
              class="rb_layout_box " 
              style="width:100%;" 
              data-order-id="3" 
              data-id="6" 
              data-layout="1" 
              data-sec-key=""
              data-sec-uid=""
              data-title="경기지역" 
              data-shop="0"
            >

                <ul class="content_box rb_module_6    " 
                    
                    style="                    margin-top:0px; 
                      margin-bottom:0px;
                    ">
                    
                    

                    
                    
                    
                                            <div class="bbs_main_wrap_tit" style="display:block">
                            <ul class="bbs_main_wrap_tit_l">
                                <a href="javascript:void(0);">
                                    <h2 class="font-B" style="color:#25282B; font-size:20px; ">경기지역</h2>
                                </a>
                            </ul>
                            <ul class="bbs_main_wrap_tit_r"></ul>
                            <div class="cb"></div>
                        </div>
                        <div class="rb-module-wrap module_banner_wrap md_arrow_0" 
                           style="
                           height:auto;                           border-color:#DDDDDD;                            border-radius:11px;                                                       background-color:#FFFFFF;                            
                           ">
                            <?php echo rb_banners("경기지역 배너", "", "rb.mod/banner/skin/rb.banner", "bn_order, bn_id desc"); ?>                        </div>
                    
                                        
                    
                                    
                
                                    
                    
                    

                                        
                                    </ul>
                
                
                <div class="flex_box_inner flex_box" data-layout="1-6"></div>
            </div>

            
            <?php } ?>
<?php
$row_mod = array (
  'md_id' => '7',
  'md_layout' => '1',
  'md_layout_name' => 'basic',
  'md_theme' => 'rb.basic',
  'md_title' => '인천지역',
  'md_type' => 'banner',
  'md_bo_table' => '',
  'md_sca' => '',
  'md_widget' => '',
  'md_poll' => '',
  'md_poll_id' => '',
  'md_banner' => '인천지역 배너',
  'md_banner_id' => '',
  'md_banner_skin' => 'rb.mod/banner/skin/rb.banner',
  'md_module' => '0',
  'md_skin' => '',
  'md_cnt' => '0',
  'md_auto_time' => '3000',
  'md_gap' => '20',
  'md_gap_mo' => '10',
  'md_col' => '5',
  'md_row' => '1',
  'md_col_mo' => '0',
  'md_row_mo' => '0',
  'md_width' => '100',
  'md_height' => 'auto',
  'md_subject_is' => '0',
  'md_thumb_is' => '0',
  'md_nick_is' => '0',
  'md_date_is' => '0',
  'md_content_is' => '0',
  'md_icon_is' => '0',
  'md_comment_is' => '0',
  'md_ca_is' => '0',
  'md_swiper_is' => '1',
  'md_auto_is' => '1',
  'md_order' => '',
  'md_datetime' => '2025-12-09 14:20:48',
  'md_ip' => '175.176.71.7',
  'md_order_id' => '4',
  'md_banner_bg' => '#FFFFFF',
  'md_border' => '',
  'md_radius' => '11',
  'md_padding' => '0',
  'md_padding_mo' => '',
  'md_margin_top_mo' => '',
  'md_margin_top_pc' => '',
  'md_margin_btm_pc' => '',
  'md_margin_btm_mo' => '',
  'md_size' => '%',
  'md_show' => '',
  'md_level' => '',
  'md_level_is' => '',
  'md_tab_list' => '',
  'md_tab_skin' => '',
  'md_item_tab_list' => '',
  'md_item_tab_skin' => '',
  'md_title_color' => '#25282B',
  'md_title_size' => '20',
  'md_title_font' => '',
  'md_title_hide' => '0',
  'md_order_latest' => '',
  'md_order_banner' => 'bn_order, bn_id desc',
  'md_notice' => '0',
  'md_soldout_hidden' => '0',
  'md_soldout_asc' => '0',
  'md_arrow_type' => '0',
  'md_wide_is' => '0',
  'md_sec_key' => '',
  'md_sec_uid' => '',
  'md_border_width' => '0',
  'md_border_color' => '#DDDDDD',
  'md_box_shadow' => '',
  'md_box_shadow_w' => '0',
  'md_box_shadow_c' => '#25282b16',
  'md_padding_lr_pc' => '',
  'md_padding_lr_mo' => '',
  'md_padding_tb_pc' => '',
  'md_padding_tb_mo' => '',
  'md_1' => '',
  'md_2' => '',
  'md_3' => '',
  'md_4' => '',
  'md_5' => '',
  'md_6' => '',
  'md_7' => '',
  'md_8' => '',
  'md_9' => '',
  'md_10' => '',
);
?>
<?php $__rb_mb_level = isset($GLOBALS['member']['mb_level']) ? (int)$GLOBALS['member']['mb_level'] : 1;
            if (!$is_admin && !rb__level_visible($__rb_mb_level, 0, 0)) { } else { ?>
            <div 
              class="rb_layout_box " 
              style="width:100%;" 
              data-order-id="4" 
              data-id="7" 
              data-layout="1" 
              data-sec-key=""
              data-sec-uid=""
              data-title="인천지역" 
              data-shop="0"
            >

                <ul class="content_box rb_module_7    " 
                    
                    style="                    margin-top:0px; 
                      margin-bottom:0px;
                    ">
                    
                    

                    
                    
                    
                                            <div class="bbs_main_wrap_tit" style="display:block">
                            <ul class="bbs_main_wrap_tit_l">
                                <a href="javascript:void(0);">
                                    <h2 class="" style="color:#25282B; font-size:20px; ">인천지역</h2>
                                </a>
                            </ul>
                            <ul class="bbs_main_wrap_tit_r"></ul>
                            <div class="cb"></div>
                        </div>
                        <div class="rb-module-wrap module_banner_wrap md_arrow_0" 
                           style="
                           height:auto;                           border-color:#DDDDDD;                            border-radius:11px;                                                       background-color:#FFFFFF;                            
                           ">
                            <?php echo rb_banners("인천지역 배너", "", "rb.mod/banner/skin/rb.banner", "bn_order, bn_id desc"); ?>                        </div>
                    
                                        
                    
                                    
                
                                    
                    
                    

                                        
                                    </ul>
                
                
                <div class="flex_box_inner flex_box" data-layout="1-7"></div>
            </div>

            
            <?php } ?>
<?php
return ob_get_clean();
?>
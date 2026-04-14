<?php
if (!defined('_GNUBOARD_')) exit;
/** 한필 비자 랜딩 — Remix 시안 정적 섹션 (프로세스 / FAQ / CTA) */
$hp_kakao = isset($hp_kakao_url) ? $hp_kakao_url : '';
$hp_tel = isset($hp_phone_href) ? $hp_phone_href : '';
$hp_tel_label = isset($hp_phone_label) ? $hp_phone_label : '전화 문의하기';
?>
<section class="hp-visa-process" id="process" aria-labelledby="hp-visa-process-h">
    <div class="hp-visa-inner">
        <header class="hp-visa-process__head">
            <h2 id="hp-visa-process-h" class="hp-visa-h2">한필 비자 대행 프로세스</h2>
            <p class="hp-visa-lead hp-visa-lead--on-dark">가장 투명하고 체계적인 절차로 진행됩니다.</p>
        </header>
        <ol class="hp-visa-steps">
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">01</span>
                <h3 class="hp-visa-step__t">상담 및 서류 검토</h3>
                <p class="hp-visa-step__d">카톡·전화 상담을 통해 비자 종류 결정 및 필요 서류 안내</p>
            </li>
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">02</span>
                <h3 class="hp-visa-step__t">서류 접수 및 픽업</h3>
                <p class="hp-visa-step__d">여권 및 서류를 한필 사무실로 전달 (픽업 서비스 가능)</p>
            </li>
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">03</span>
                <h3 class="hp-visa-step__t">이민국 접수 및 처리</h3>
                <p class="hp-visa-step__d">전담 직원이 이민국에 방문하여 신속하게 업무 처리</p>
            </li>
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">04</span>
                <h3 class="hp-visa-step__t">완료 안내 및 배송</h3>
                <p class="hp-visa-step__d">업무 완료 즉시 안내 드리고 안전하게 여권 전달</p>
            </li>
        </ol>
    </div>
</section>

<section class="hp-visa-faq" id="faq" aria-labelledby="hp-visa-faq-h">
    <div class="hp-visa-inner hp-visa-inner--narrow">
        <header class="hp-visa-faq__head">
            <h2 id="hp-visa-faq-h" class="hp-visa-h2 hp-visa-h2--dark">자주 묻는 질문</h2>
            <p class="hp-visa-lead">비자 업무와 관련하여 가장 많이 궁금해하시는 내용입니다.</p>
        </header>
        <div class="hp-visa-faq__list">
            <?php
            $hp_faqs = array(
                array(
                    'q' => '관광비자 연장은 최대 얼마까지 가능한가요?',
                    'a' => '일반적으로 필리핀 관광비자는 최대 36개월(3년)까지 연장이 가능합니다. 다만 체류 목적과 이민국 정책에 따라 변동될 수 있으므로 장기 체류 시 전문가와 상담이 필요합니다.',
                ),
                array(
                    'q' => '여권을 맡기는 게 불안한데 안전한가요?',
                    'a' => '한필은 필리핀 이민국 정식 등록 업체로, 모든 여권은 전용 금고에 보관되며 이동 시 전담 직원이 직접 관리합니다.',
                ),
                array(
                    'q' => '급행 서비스는 얼마나 걸리나요?',
                    'a' => '관광비자 연장 급행의 경우, 오전 접수 시 당일 오후 또는 익일 오전 중 완료되는 경우가 많습니다. (이민국 시스템 상황에 따라 상이할 수 있음)',
                ),
                array(
                    'q' => '은퇴비자(SRRV) 신청 시 가족도 함께 받을 수 있나요?',
                    'a' => '주 신청자 외에 배우자와 만 21세 미만의 미혼 자녀도 동반 비자 신청이 가능합니다. 가족 수에 따라 예치금 금액이 달라질 수 있습니다.',
                ),
            );
            foreach ($hp_faqs as $fi => $faq) {
                $fid = 'hp-faq-' . $fi;
            ?>
            <div class="hp-visa-faq__item">
                <button type="button" class="hp-visa-faq__q" aria-expanded="false" aria-controls="<?php echo $fid; ?>" id="hp-faq-btn-<?php echo $fi; ?>">
                    <span class="hp-visa-faq__q-mark">Q</span>
                    <span class="hp-visa-faq__q-text"><?php echo get_text($faq['q']); ?></span>
                    <span class="hp-visa-faq__icon" aria-hidden="true"></span>
                </button>
                <div class="hp-visa-faq__a" id="<?php echo $fid; ?>" role="region" aria-labelledby="hp-faq-btn-<?php echo $fi; ?>" hidden>
                    <span class="hp-visa-faq__a-mark">A</span>
                    <p><?php echo get_text($faq['a']); ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="hp-visa-cta" aria-labelledby="hp-visa-cta-h">
    <div class="hp-visa-cta__bg" aria-hidden="true"></div>
    <div class="hp-visa-inner hp-visa-cta__inner">
        <h2 id="hp-visa-cta-h" class="hp-visa-cta__title">세부 생활의 시작,<br>비자 걱정은 한필에 맡기세요.</h2>
        <p class="hp-visa-cta__lead">지금 바로 카카오톡으로 문의하시면 비자 전문가가 1:1로 친절하게 상담해 드립니다.</p>
        <div class="hp-visa-cta__btns">
            <?php if ($hp_kakao) { ?>
            <a href="<?php echo htmlspecialchars($hp_kakao, ENT_QUOTES, 'UTF-8'); ?>" class="hp-visa-btn hp-visa-btn--kakao" target="_blank" rel="noopener noreferrer">카카오톡 실시간 상담</a>
            <?php } ?>
            <?php if ($hp_tel) { ?>
            <a href="<?php echo htmlspecialchars($hp_tel, ENT_QUOTES, 'UTF-8'); ?>" class="hp-visa-btn hp-visa-btn--ghost"><?php echo get_text($hp_tel_label); ?></a>
            <?php } ?>
        </div>
    </div>
</section>

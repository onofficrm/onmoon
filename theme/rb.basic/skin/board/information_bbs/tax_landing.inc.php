<?php
if (!defined('_GNUBOARD_')) exit;
/** 한필 세무/회계 랜딩 — Remix 시안 정적 섹션 */
$hp_kakao = isset($hp_kakao_url) ? $hp_kakao_url : '';
$hp_tel = isset($hp_phone_href) ? $hp_phone_href : '';
$hp_tel_label = isset($hp_phone_label) ? $hp_phone_label : '전화 문의하기';
?>
<section class="hp-visa-process" id="process" aria-labelledby="hp-tax-process-h">
    <div class="hp-visa-inner">
        <header class="hp-visa-process__head">
            <h2 id="hp-tax-process-h" class="hp-visa-h2">한필 세무 관리 프로세스</h2>
            <p class="hp-visa-lead hp-visa-lead--on-dark">체계적인 단계별 관리로 세무 리스크를 완벽하게 통제합니다.</p>
        </header>
        <ol class="hp-visa-steps">
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">01</span>
                <h3 class="hp-visa-step__t">세무 진단 및 상담</h3>
                <p class="hp-visa-step__d">현 세무 상태 파악 및 최적의 신고 플랜 수립</p>
            </li>
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">02</span>
                <h3 class="hp-visa-step__t">장부 기장 및 정리</h3>
                <p class="hp-visa-step__d">매일 발생하는 거래 내역의 체계적 기록 및 증빙 관리</p>
            </li>
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">03</span>
                <h3 class="hp-visa-step__t">정기 세무 신고</h3>
                <p class="hp-visa-step__d">BIR 기준에 맞춘 월·분기별 정확한 세금 계산 및 납부</p>
            </li>
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">04</span>
                <h3 class="hp-visa-step__t">연간 감사 및 보고</h3>
                <p class="hp-visa-step__d">AFS 작성 및 외부 감사를 통한 연간 결산 완료</p>
            </li>
        </ol>
    </div>
</section>

<section class="hp-visa-faq" id="faq" aria-labelledby="hp-tax-faq-h">
    <div class="hp-visa-inner hp-visa-inner--narrow">
        <header class="hp-visa-faq__head">
            <h2 id="hp-tax-faq-h" class="hp-visa-h2 hp-visa-h2--dark">자주 묻는 질문</h2>
            <p class="hp-visa-lead">세무·회계 업무와 관련하여 가장 많이 궁금해하시는 내용입니다.</p>
        </header>
        <div class="hp-visa-faq__list">
            <?php
            $hp_faqs = array(
                array(
                    'q' => '필리핀 세무 신고를 누락하면 어떤 불이익이 있나요?',
                    'a' => 'BIR은 신고 누락이나 지연에 대해 가산세(Surcharge, Interest, Compromise Penalty)를 부과할 수 있습니다. 한필은 철저한 일정 관리로 이러한 리스크를 사전에 방지합니다.',
                ),
                array(
                    'q' => '북키퍼 서비스와 세무 신고 대행의 차이는 무엇인가요?',
                    'a' => '북키퍼는 일상적인 거래 기록과 장부 정리를 담당하고, 세무 신고 대행은 이를 바탕으로 국세청에 정식으로 세금을 계산·보고하는 업무입니다. 한필은 두 서비스를 통합하여 제공합니다.',
                ),
                array(
                    'q' => '개인사업자도 세무 감사를 받아야 하나요?',
                    'a' => '매출 규모가 일정 수준을 초과하는 경우 공인회계사(CPA) 서명이 담긴 재무제표가 필요할 수 있습니다. 업종과 매출액에 따른 기준은 상담 시 안내해 드립니다.',
                ),
                array(
                    'q' => '세무 조사가 나왔을 때 어떻게 대응해야 하나요?',
                    'a' => 'BIR로부터 LOA(Letter of Authority)를 받으셨다면 즉시 전문가와 상의하시는 것이 좋습니다. 한필은 세무 조사 전 과정에서 서류 소명 및 협의를 지원합니다.',
                ),
            );
            foreach ($hp_faqs as $fi => $faq) {
                $fid = 'hp-tax-faq-' . $fi;
            ?>
            <div class="hp-visa-faq__item">
                <button type="button" class="hp-visa-faq__q" aria-expanded="false" aria-controls="<?php echo $fid; ?>" id="hp-tax-faq-btn-<?php echo $fi; ?>">
                    <span class="hp-visa-faq__q-mark">Q</span>
                    <span class="hp-visa-faq__q-text"><?php echo get_text($faq['q']); ?></span>
                    <span class="hp-visa-faq__icon" aria-hidden="true"></span>
                </button>
                <div class="hp-visa-faq__a" id="<?php echo $fid; ?>" role="region" aria-labelledby="hp-tax-faq-btn-<?php echo $fi; ?>" hidden>
                    <span class="hp-visa-faq__a-mark">A</span>
                    <p><?php echo get_text($faq['a']); ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="hp-visa-cta" aria-labelledby="hp-tax-cta-h">
    <div class="hp-visa-cta__bg" aria-hidden="true"></div>
    <div class="hp-visa-inner hp-visa-cta__inner">
        <h2 id="hp-tax-cta-h" class="hp-visa-cta__title">복잡한 필리핀 세무,<br>한필이 가장 명쾌한 해답을 드립니다.</h2>
        <p class="hp-visa-cta__lead">지금 바로 카카오톡으로 문의하시면 세무 전문가가 1:1로 친절하게 상담해 드립니다.</p>
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

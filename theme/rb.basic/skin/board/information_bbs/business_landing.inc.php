<?php
if (!defined('_GNUBOARD_')) exit;
/** 한필 비즈니스·법인 설립 랜딩 — Remix 시안 정적 섹션 */
$hp_kakao = isset($hp_kakao_url) ? $hp_kakao_url : '';
$hp_tel = isset($hp_phone_href) ? $hp_phone_href : '';
$hp_tel_label = isset($hp_phone_label) ? $hp_phone_label : '전화 문의하기';

$hp_biz_services = array(
    array(
        'id' => 'sec-dti',
        'title' => '법인 설립 대행 (SEC/DTI)',
        'subtitle' => 'Corporation & Business Registration',
        'image' => 'https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=800',
        'description' => '필리핀에서 사업을 시작하기 위한 가장 첫 단계입니다. 복잡한 정관 작성부터 주주 구성 컨설팅까지 원스톱으로 지원합니다.',
        'pricing' => "신규 설립: 약 45,000페소부터\n(자본금 및 법인 형태에 따라 상이)",
        'details' => array(
            'SEC(증권거래위원회) 법인 등록 및 정관 승인',
            'DTI(통상산업부) 개인사업자 상호 등록',
            '외국인 지분 제한(Anti-Dummy Law) 법적 검토',
            '최소 자본금 설정 및 납입 증명 가이드',
            '법인 인장(Dry Seal) 및 주식 대장 제작',
        ),
    ),
    array(
        'id' => 'permits',
        'title' => '사업자 등록 & Permit 갱신',
        'subtitle' => "Mayor's Permit & BIR Registration",
        'image' => 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?auto=format&fit=crop&q=80&w=800',
        'description' => "법인 설립 후 실제 영업을 위해 필요한 각종 인허가 절차입니다. 매년 초 진행되는 갱신 업무도 누락 없이 관리해 드립니다.",
        'pricing' => "신규 등록: 약 20,000페소 / 갱신: 실비 정산",
        'details' => array(
            'Barangay Clearance (마을 단위 허가)',
            "Mayor's Permit (시청 영업 허가증) 발급 및 갱신",
            'BIR (국세청) 사업자 등록 및 영수증 인쇄 승인',
            '소방 점검(BFP) 및 위생 허가(Sanitary) 대행',
            '영업 중단 시 폐업 절차(Retirement) 지원',
        ),
    ),
    array(
        'id' => 'notary',
        'title' => '공증 및 서류 작성 대행',
        'subtitle' => 'Notarization & Documentation',
        'image' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?auto=format&fit=crop&q=80&w=800',
        'description' => '필리핀 비즈니스에서 발생하는 모든 법적 서류의 공증과 작성을 지원합니다. 이사회 결의서 등 필수 서류를 완벽하게 준비합니다.',
        'pricing' => '건당 1,500페소부터 (서류 종류별 상이)',
        'details' => array(
            'Board Resolution (이사회 결의서) 작성',
            "Secretary's Certificate (법인 비서 증명서) 발급",
            '임대차 계약서(Lease Contract) 검토 및 공증',
            '각종 위임장(SPA) 및 진술서(Affidavit) 작성',
            '필리핀 외교부(DFA) 아포스티유 인증 대행',
        ),
    ),
    array(
        'id' => 'dole',
        'title' => '노동법 컨설팅 (DOLE)',
        'subtitle' => 'Labor Law & HR Compliance',
        'image' => 'https://images.unsplash.com/photo-1521791136364-798a7bc0d262?auto=format&fit=crop&q=80&w=800',
        'description' => '필리핀의 까다로운 노동법으로부터 사업주를 보호합니다. 합법적인 고용 계약과 해고 절차, 분쟁 해결을 지원합니다.',
        'pricing' => '월간 자문: 별도 문의 / 계약서 작성: 5,000페소부터',
        'details' => array(
            'DOLE(노동고용부) 기준 고용 계약서 작성',
            '사규(Employee Handbook) 제작 및 등록',
            '징계 절차(Due Process) 가이드 및 서류 지원',
            '퇴직금 계산 및 해고 관련 법적 리스크 관리',
            '노동청 분쟁(NLRC) 대응 및 화해 조정 지원',
        ),
    ),
);
?>
<section class="hp-biz-services" id="services" aria-labelledby="hp-biz-services-h">
    <div class="hp-visa-inner">
        <header class="hp-biz-services__head">
            <h2 id="hp-biz-services-h" class="hp-visa-h2 hp-visa-h2--dark">한필 비즈니스 지원 서비스</h2>
            <p class="hp-visa-lead">법인 설립부터 인허가, 노동법 컨설팅까지 비즈니스의 모든 과정을 함께합니다.</p>
        </header>
        <?php foreach ($hp_biz_services as $bi => $svc) { ?>
        <article class="hp-biz-service<?php echo ($bi % 2 === 1) ? ' hp-biz-service--rev' : ''; ?>" id="<?php echo $svc['id']; ?>">
            <div class="hp-biz-service__media">
                <div class="hp-biz-service__frame">
                    <img class="hp-biz-service__img" src="<?php echo htmlspecialchars($svc['image'], ENT_QUOTES, 'UTF-8'); ?>" alt="" loading="lazy" decoding="async" width="800" height="600">
                    <div class="hp-biz-service__cap">
                        <p class="hp-biz-service__cap-sub"><?php echo get_text($svc['subtitle']); ?></p>
                        <h3 class="hp-biz-service__cap-title"><?php echo get_text($svc['title']); ?></h3>
                    </div>
                </div>
            </div>
            <div class="hp-biz-service__body">
                <h3 class="hp-biz-service__title"><?php echo get_text($svc['title']); ?></h3>
                <p class="hp-biz-service__desc"><?php echo get_text($svc['description']); ?></p>
                <div class="hp-biz-pricing">
                    <p class="hp-biz-pricing__label">서비스 비용 가이드</p>
                    <p class="hp-biz-pricing__val"><?php echo nl2br(get_text($svc['pricing'])); ?></p>
                    <p class="hp-biz-pricing__note">정부 공과금 실비 별도 (업종별 상이)</p>
                </div>
                <ul class="hp-biz-detail-list">
                    <?php foreach ($svc['details'] as $line) { ?>
                    <li class="hp-biz-detail-list__i"><?php echo get_text($line); ?></li>
                    <?php } ?>
                </ul>
                <?php if ($hp_kakao) { ?>
                <a href="<?php echo htmlspecialchars($hp_kakao, ENT_QUOTES, 'UTF-8'); ?>" class="hp-visa-btn hp-visa-btn--primary hp-biz-service__cta" target="_blank" rel="noopener noreferrer">이 서비스 상담하기</a>
                <?php } elseif ($hp_tel) { ?>
                <a href="<?php echo htmlspecialchars($hp_tel, ENT_QUOTES, 'UTF-8'); ?>" class="hp-visa-btn hp-visa-btn--primary hp-biz-service__cta"><?php echo get_text($hp_tel_label); ?></a>
                <?php } ?>
            </div>
        </article>
        <?php } ?>
    </div>
</section>

<section class="hp-visa-process" id="process" aria-labelledby="hp-biz-process-h">
    <div class="hp-visa-inner">
        <header class="hp-visa-process__head">
            <h2 id="hp-biz-process-h" class="hp-visa-h2">한필 비즈니스 지원 프로세스</h2>
            <p class="hp-visa-lead hp-visa-lead--on-dark">체계적인 단계별 지원으로 리스크를 최소화합니다.</p>
        </header>
        <ol class="hp-visa-steps">
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">01</span>
                <h3 class="hp-visa-step__t">비즈니스 컨설팅</h3>
                <p class="hp-visa-step__d">업종에 따른 법인 형태 결정 및 자본금, 주주 구성 상담</p>
            </li>
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">02</span>
                <h3 class="hp-visa-step__t">서류 준비 및 접수</h3>
                <p class="hp-visa-step__d">정관 작성 및 SEC/DTI 접수, 공증 절차 진행</p>
            </li>
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">03</span>
                <h3 class="hp-visa-step__t">인허가 및 등록</h3>
                <p class="hp-visa-step__d">시청 Permit 발급 및 BIR 세무 등록 완료</p>
            </li>
            <li class="hp-visa-step">
                <span class="hp-visa-step__n">04</span>
                <h3 class="hp-visa-step__t">운영 지원 및 관리</h3>
                <p class="hp-visa-step__d">노동법 준수 가이드 및 매년 갱신 업무 대행</p>
            </li>
        </ol>
    </div>
</section>

<section class="hp-visa-faq" id="faq" aria-labelledby="hp-biz-faq-h">
    <div class="hp-visa-inner hp-visa-inner--narrow">
        <header class="hp-visa-faq__head">
            <h2 id="hp-biz-faq-h" class="hp-visa-h2 hp-visa-h2--dark">자주 묻는 질문</h2>
            <p class="hp-visa-lead">비즈니스·법인 설립과 관련하여 가장 많이 궁금해하시는 내용입니다.</p>
        </header>
        <div class="hp-visa-faq__list">
            <?php
            $hp_faqs = array(
                array(
                    'q' => '법인 설립까지 기간이 얼마나 소요되나요?',
                    'a' => "일반적으로 SEC 등록부터 Mayor's Permit 발급까지 약 1.5개월에서 2개월 정도 소요됩니다. 업종이나 지역(LGU)에 따라 차이가 있을 수 있으므로 사전 상담이 필수입니다.",
                ),
                array(
                    'q' => '외국인 지분 100% 법인 설립이 가능한가요?',
                    'a' => '네, 소매업이나 특정 업종을 제외하고 일정 자본금 요건($200,000 이상 등)을 충족하면 외국인 100% 지분 법인 설립이 가능합니다. 업종별 규제(Negative List)를 확인해 드립니다.',
                ),
                array(
                    'q' => '매년 갱신해야 하는 서류는 무엇인가요?',
                    'a' => "매년 1월에 Mayor's Permit(영업 허가)을 갱신해야 하며, BIR(국세청) 등록 갱신 및 각종 검사(Fire, Sanitary)도 매년 진행되어야 합니다.",
                ),
                array(
                    'q' => '사무실 임대 없이 법인 설립이 가능한가요?',
                    'a' => '법인 설립 시 실제 사업장 주소지가 반드시 필요합니다. 가상 오피스(Virtual Office) 사용 가능 여부는 업종과 지역 관청의 판단에 따라 달라질 수 있습니다.',
                ),
            );
            foreach ($hp_faqs as $fi => $faq) {
                $fid = 'hp-biz-faq-' . $fi;
            ?>
            <div class="hp-visa-faq__item">
                <button type="button" class="hp-visa-faq__q" aria-expanded="false" aria-controls="<?php echo $fid; ?>" id="hp-biz-faq-btn-<?php echo $fi; ?>">
                    <span class="hp-visa-faq__q-mark">Q</span>
                    <span class="hp-visa-faq__q-text"><?php echo get_text($faq['q']); ?></span>
                    <span class="hp-visa-faq__icon" aria-hidden="true"></span>
                </button>
                <div class="hp-visa-faq__a" id="<?php echo $fid; ?>" role="region" aria-labelledby="hp-biz-faq-btn-<?php echo $fi; ?>" hidden>
                    <span class="hp-visa-faq__a-mark">A</span>
                    <p><?php echo get_text($faq['a']); ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</section>

<section class="hp-visa-cta hp-visa-cta--biz" aria-labelledby="hp-biz-cta-h">
    <div class="hp-visa-cta__bg" aria-hidden="true"></div>
    <div class="hp-visa-inner hp-visa-cta__inner">
        <h2 id="hp-biz-cta-h" class="hp-visa-cta__title">성공적인 필리핀 비즈니스,<br>한필이 든든한 파트너가 됩니다.</h2>
        <p class="hp-visa-cta__lead">지금 바로 카카오톡으로 문의하시면 비즈니스 전문가가 1:1로 친절하게 상담해 드립니다.</p>
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

<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가
?>

<!-- 
    공감마사지 최종 디자인 및 인터랙션 수정 버전 (v1.3)
    - 해결사항: 배너 클릭 시 상세페이지 이동 / 전화걸기 버튼 분리
    - 디자인: 화이트 배경 + 베이지(#F9F7F4) 카드 + 에디토리얼 레이아웃
-->

<!-- 라이브러리 강제 로드 -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:italic,wght@700&family=Noto+Sans+KR:wght@300;400;700;900&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('gonggamApp', () => ({
            activeCat: 'all',
            searchTerm: '',
            categories: [
                { id: 'all', name: '전체지역' },
                { id: 'seoul', name: '서울 특별시' },
                { id: 'incheon', name: '인천 광역시' },
                { id: 'gyeonggi', name: '경기도' },
                { id: 'other', name: '그외 지역' }
            ],
            rawData: [
                { phone: '0503-6982-1011', area: '강남', cat: 'seoul' },
                { phone: '0503-6982-1038', area: '여의도', cat: 'seoul' },
                { phone: '0503-6982-1071', area: '이태원', cat: 'seoul' },
                { phone: '0503-6982-1072', area: '홍대', cat: 'seoul' },
                { phone: '0503-6982-1074', area: '서초', cat: 'seoul' },
                { phone: '0503-6982-1075', area: '논현', cat: 'seoul' },
                { phone: '0503-6982-1078', area: '영등포', cat: 'seoul' },
                { phone: '0503-6982-1080', area: '잠실', cat: 'seoul' },
                { phone: '0503-6982-1035', area: '인천', cat: 'incheon' },
                { phone: '0503-6982-1036', area: '부평', cat: 'incheon' },
                { phone: '0503-6982-1029', area: '파주', cat: 'gyeonggi' },
                { phone: '0503-6982-1034', area: '부천', cat: 'gyeonggi' },
                { phone: '0503-6982-1042', area: '일산', cat: 'gyeonggi' },
                { phone: '0503-6982-1043', area: '김포', cat: 'gyeonggi' },
                { phone: '0503-6982-1047', area: '안산', cat: 'gyeonggi' },
                { phone: '0503-6982-1049', area: '수원', cat: 'gyeonggi' },
                { phone: '0503-6982-1050', area: '용인', cat: 'gyeonggi' },
                { phone: '0503-6982-1021', area: '공주', cat: 'other' }
            ],
            massageImages: [
                "https://images.unsplash.com/photo-1544161515-4ab6ce6db874?auto=format&fit=crop&q=80&w=800",
                "https://images.unsplash.com/photo-1600334089648-b0d9d3028eb2?auto=format&fit=crop&q=80&w=800",
                "https://images.unsplash.com/photo-1519823551278-64ac92734fb1?auto=format&fit=crop&q=80&w=800",
                "https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&q=80&w=800",
                "https://images.unsplash.com/photo-1570172619644-dfd03ed5d881?auto=format&fit=crop&q=80&w=800"
            ],
            get banners() {
                return this.rawData.map((item, idx) => ({
                    id: idx,
                    cat: item.cat,
                    area: item.area,
                    title: item.area + ' 출장마사지 스웨디시',
                    phone: item.phone,
                    rating: (4.8 + Math.random() * 0.2).toFixed(1),
                    img: this.massageImages[idx % this.massageImages.length]
                }));
            },
            get filteredBanners() {
                const list = this.banners.filter(b => {
                    const matchesCat = this.activeCat === 'all' || b.cat === this.activeCat;
                    const matchesSearch = b.area.toLowerCase().includes(this.searchTerm.toLowerCase());
                    return matchesCat && matchesSearch;
                });
                this.$nextTick(() => { if(window.lucide) lucide.createIcons(); });
                return list;
            },
            handleCall(phone) {
                window.location.href = 'tel:' + phone.replace(/-/g, '');
            },
            // 상세페이지 이동 함수 (그누보드 게시판 경로에 맞게 수정 가능)
            goToDetail(id) {
                // 예: window.location.href = '/bbs/board.php?bo_table=massage&wr_id=' + id;
                console.log('상세페이지로 이동:', id);
                // 현재는 예시로 알림창을 띄우거나 이동 로직을 주석처리합니다.
                // window.location.href = '#'; 
            },
            init() {
                setTimeout(() => { if(window.lucide) lucide.createIcons(); }, 100);
            }
        }));
    });
</script>
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

<style>
    :root {
        --gg-beige: #F9F7F4;
        --gg-amber: #F59E0B;
        --gg-header-height: 60px; 
    }
    .gonggam-wrap { font-family: 'Noto Sans KR', sans-serif; background-color: #ffffff; }
    .font-serif-italic { font-family: 'Playfair Display', serif; font-style: italic; }
    .bg-beige { background-color: var(--gg-beige) !important; }
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    .banner-card { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .banner-card:hover { transform: translateY(-8px); box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08); }
    .sticky-filter { top: var(--gg-header-height) !important; z-index: 30 !important; }
    [x-cloak] { display: none !important; }
</style>

<div x-data="gonggamApp" x-cloak class="gonggam-wrap text-neutral-900 overflow-hidden">
    
    <!-- 1. Hero Section -->
    <section class="relative py-24 px-6 bg-white border-b border-neutral-100">
        <div class="container mx-auto max-w-6xl relative z-10">
            <div class="flex flex-col items-center text-center">
                <div class="flex items-center gap-3 mb-8">
                    <span class="w-12 h-[1px] bg-amber-500"></span>
                    <span class="text-amber-600 text-xs font-black tracking-[0.4em] uppercase">Premium Mobile Care</span>
                    <span class="w-12 h-[1px] bg-amber-500"></span>
                </div>
                <h1 class="text-5xl md:text-7xl font-black mb-8 tracking-tighter leading-tight text-neutral-900 uppercase">
                    진심으로 <span class="font-serif-italic text-amber-500">Gonggam</span>하는<br />
                    완벽한 휴식의 순간
                </h1>
                <p class="text-neutral-500 text-base md:text-xl max-w-2xl mx-auto font-light leading-relaxed mb-12">
                    공감마사지는 단순한 케어를 넘어 고객님의 컨디션을 최상으로 끌어올립니다.<br />
                    <strong>출장마사지 · 스웨디시 · 출장안마</strong> 각 분야 전문가가 찾아갑니다.
                </p>
                
                <!-- Search -->
                <div class="relative w-full max-w-xl shadow-2xl shadow-neutral-200/50">
                    <i data-lucide="search" class="absolute left-6 top-1/2 -translate-y-1/2 w-5 h-5 text-neutral-300"></i>
                    <input 
                        type="text" 
                        x-model="searchTerm"
                        placeholder="찾으시는 지역명을 입력하세요 (강남, 김포, 인천...)"
                        class="w-full bg-white border border-neutral-200 rounded-3xl py-6 pl-16 pr-8 text-base focus:outline-none focus:ring-2 focus:ring-amber-500/20 transition-all shadow-inner"
                    />
                </div>
            </div>
        </div>
        <div class="absolute top-0 right-0 w-1/3 h-full bg-beige opacity-50 -skew-x-12 translate-x-1/2"></div>
    </section>

    <!-- 2. Keyword Classification -->
    <section class="py-16 container mx-auto px-6 bg-white">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="flex flex-col items-center p-10 bg-beige rounded-[3rem] border border-neutral-100 group hover:border-amber-400 transition-all text-center shadow-sm">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6 text-amber-600 shadow-sm group-hover:bg-amber-gold group-hover:text-white transition-all">
                    <i data-lucide="map-pin" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-black text-neutral-800 mb-3 italic">출장마사지 전문</h3>
                <p class="text-[13px] text-neutral-400 font-medium leading-relaxed">숙련된 관리사가 직접 방문하여<br/>지친 몸의 피로를 즉각 해소</p>
            </div>
            <div class="flex flex-col items-center p-10 bg-beige rounded-[3rem] border border-neutral-100 group hover:border-amber-400 transition-all text-center shadow-sm">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6 text-amber-600 shadow-sm group-hover:bg-amber-gold group-hover:text-white transition-all">
                    <i data-lucide="droplets" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-black text-neutral-800 mb-3 italic">스웨디시 케어</h3>
                <p class="text-[13px] text-neutral-400 font-medium leading-relaxed">최고급 오일을 사용한 부드러운<br/>감성 핸들링과 정서적 안정</p>
            </div>
            <div class="flex flex-col items-center p-10 bg-beige rounded-[3rem] border border-neutral-100 group hover:border-amber-400 transition-all text-center shadow-sm">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center mb-6 text-amber-600 shadow-sm group-hover:bg-amber-gold group-hover:text-white transition-all">
                    <i data-lucide="user-check" class="w-8 h-8"></i>
                </div>
                <h3 class="text-xl font-black text-neutral-800 mb-3 italic">출장안마 서비스</h3>
                <p class="text-[13px] text-neutral-400 font-medium leading-relaxed">24시간 신원 보증 관리사 파견<br/>안전하고 프라이빗한 홈 케어</p>
            </div>
        </div>
    </section>

    <!-- 3. Region Tabs -->
    <section class="sticky sticky-filter bg-white/95 backdrop-blur-lg border-y border-neutral-100">
        <div class="container mx-auto px-6 py-5">
            <div class="flex items-center space-x-4 overflow-x-auto pb-1 no-scrollbar">
                <div class="flex items-center gap-2 text-amber-600 border-r border-neutral-200 pr-6 flex-shrink-0">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    <span class="text-xs font-black uppercase tracking-widest italic">Region Selection</span>
                </div>
                <div class="flex space-x-3">
                    <template x-for="cat in categories" :key="cat.id">
                        <button
                            @click="activeCat = cat.id"
                            :class="activeCat === cat.id ? 'bg-neutral-900 text-white shadow-xl scale-105' : 'bg-neutral-50 text-neutral-400 hover:bg-neutral-100'"
                            class="px-8 py-3 rounded-2xl text-xs font-black transition-all duration-300 whitespace-nowrap tracking-wider"
                            x-text="cat.name"
                        ></button>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- 4. Banner Grid -->
    <section class="py-24 container mx-auto px-6 bg-white">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-10 gap-y-16">
            <template x-for="banner in filteredBanners" :key="banner.id">
                <!-- Banner Card: Clicking the whole card goes to DETAIL PAGE -->
                <div 
                    @click="goToDetail(banner.id)"
                    class="banner-card group flex flex-col cursor-pointer bg-beige rounded-[3.5rem] border border-neutral-100 overflow-hidden active:scale-[0.98]"
                >
                    <!-- Image Area -->
                    <div class="relative aspect-[4/3] overflow-hidden bg-neutral-200">
                        <img :src="banner.img" :alt="banner.title" class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-110" />
                        <div class="absolute inset-0 bg-gradient-to-t from-beige via-transparent to-transparent opacity-60"></div>
                        <div class="absolute top-6 left-6 flex items-center gap-2">
                            <span class="px-4 py-1.5 bg-amber-500 text-white rounded-full text-[10px] font-black tracking-widest uppercase shadow-lg" x-text="banner.area"></span>
                            <div class="bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full flex items-center gap-1 shadow-sm border border-neutral-100">
                                <i data-lucide="star" class="w-3.5 h-3.5 text-amber-500 fill-current"></i>
                                <span class="text-[11px] font-black text-neutral-900" x-text="banner.rating"></span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Text Area -->
                    <div class="p-10 flex flex-col flex-1">
                        <h3 class="text-2xl font-black text-neutral-900 mb-4 tracking-tighter leading-tight group-hover:text-amber-600 transition-colors uppercase italic" x-text="banner.title"></h3>
                        <div class="flex flex-wrap gap-2 mb-6 text-[9px] font-black text-neutral-400">
                            <span class="border border-neutral-200 px-2 py-1 rounded-lg uppercase">출장마사지</span>
                            <span class="border border-neutral-200 px-2 py-1 rounded-lg uppercase">스웨디시</span>
                            <span class="border border-neutral-200 px-2 py-1 rounded-lg uppercase">출장안마</span>
                        </div>
                        
                        <!-- NEW Detail Link Button -->
                        <div class="flex items-center gap-1 text-[11px] font-black text-amber-600 mb-8 group/link">
                            상세 정보 보기 <i data-lucide="arrow-right" class="w-3 h-3 group-hover:translate-x-1 transition-transform"></i>
                        </div>

                        <!-- Reservation Call Area: Clicking HERE only triggers CALL -->
                        <div 
                            @click.stop="handleCall(banner.phone)"
                            class="flex items-center justify-between pt-8 border-t border-neutral-200 mt-auto group/call hover:border-amber-400 transition-colors"
                        >
                            <div class="flex flex-col">
                                <span class="text-[10px] text-neutral-400 font-bold uppercase tracking-widest mb-1 italic">Booking Line</span>
                                <span class="text-2xl font-black text-neutral-900 tracking-tighter group-hover/call:text-amber-600 transition-all duration-300" x-text="banner.phone"></span>
                            </div>
                            <div class="w-14 h-14 bg-neutral-900 text-white rounded-[1.2rem] flex items-center justify-center group-hover/call:bg-amber-500 group-hover:rotate-12 transition-all shadow-xl">
                                <i data-lucide="phone-call" class="w-6 h-6"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
        
        <template x-if="filteredBanners.length === 0">
            <div class="py-40 text-center flex flex-col items-center opacity-40">
                <i data-lucide="map-pin-off" class="w-16 h-16 text-neutral-200 mb-6"></i>
                <p class="text-sm font-bold text-neutral-400 uppercase tracking-[0.5em] italic">No Services Found In This Area</p>
            </div>
        </template>
    </section>

    <!-- 5. Floating Action (Global Call) -->
    <div class="fixed bottom-12 right-10 z-50">
        <button 
            @click="handleCall('0503-6982-1080')"
            class="w-16 h-16 bg-amber-500 text-white rounded-[1.5rem] shadow-[0_20px_50px_rgba(245,158,11,0.5)] flex items-center justify-center hover:scale-110 active:scale-95 transition-all animate-bounce"
        >
            <i data-lucide="phone" class="w-7 h-7"></i>
        </button>
    </div>
</div>
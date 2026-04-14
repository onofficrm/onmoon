import React from 'react';
import { motion } from 'motion/react';
import { Clock, Check, Star, Zap, Heart, Sparkles, ChevronRight } from 'lucide-react';
import { Link } from 'react-router-dom';

const courses = [
  {
    title: '타이 코스',
    icon: <Zap className="w-6 h-6 text-blue-500" />,
    description: '전통 방식의 스트레칭과 압을 이용한 전신 케어',
    prices: [
      { time: '60분', price: '7만' },
      { time: '90분', price: '8만' },
      { time: '120분', price: '10만' },
    ],
    color: 'blue',
  },
  {
    title: '아로마 코스',
    icon: <Heart className="w-6 h-6 text-pink-500" />,
    description: '부드러운 오일 테라피로 심신의 안정을 돕는 케어',
    prices: [
      { time: '60분', price: '8만' },
      { time: '90분', price: '9만' },
      { time: '120분', price: '11만' },
    ],
    color: 'pink',
  },
  {
    title: '감성 힐링 코스',
    icon: <Sparkles className="w-6 h-6 text-amber-500" />,
    description: '섬세한 터치와 감각적인 릴렉싱 전문 케어',
    tag: '인기코스',
    prices: [
      { time: '60분', price: '9만' },
      { time: '90분', price: '11만' },
      { time: '120분', price: '13만' },
    ],
    color: 'amber',
    popular: true,
    recommended: true,
  },
  {
    title: '스페셜 코스',
    icon: <Star className="w-6 h-6 text-primary" />,
    description: '타이 + 감성힐링 + 풋 케어가 결합된 프리미엄 패키지',
    tag: '추천코스',
    prices: [
      { time: '60분', price: '10만' },
      { time: '90분', price: '12만' },
      { time: '120분', price: '14만' },
      { time: '150분', price: '16만' },
    ],
    color: 'primary',
    recommended: true,
  },
];

export const PriceSection = () => {
  return (
    <section className="py-24 bg-luxury-bg relative overflow-hidden">
      {/* Decorative background elements */}
      <div className="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full pointer-events-none">
        <div className="absolute top-1/4 left-0 w-96 h-96 bg-primary/5 rounded-full blur-[100px]" />
        <div className="absolute bottom-1/4 right-0 w-96 h-96 bg-primary/10 rounded-full blur-[100px]" />
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div className="text-center mb-16">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
          >
            <span className="inline-block px-4 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-bold uppercase tracking-widest mb-4">
              Service Menu & Pricing
            </span>
            <h2 className="text-4xl md:text-5xl font-extrabold text-luxury-ink tracking-tight mb-6">
              프리미엄 <span className="text-[#E6B8A2]">최고의 힐링 코스</span> 안내
            </h2>
            <p className="text-luxury-text max-w-2xl mx-auto text-lg leading-relaxed">
              엄격하게 선별된 테라피스트들이 제공하는 고품격 서비스를<br className="hidden md:block" />
              합리적인 가격으로 당신의 공간에서 경험해보세요.
            </p>
          </motion.div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          {courses.map((course, idx) => (
            <motion.div
              key={course.title}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: idx * 0.1 }}
              className={`relative group h-full`}
            >
              <div className={`h-full bg-luxury-card rounded-[2.5rem] border border-luxury-border shadow-xl shadow-luxury-ink/5 transition-all duration-500 hover:shadow-2xl hover:shadow-primary/10 hover:-translate-y-2 flex flex-col overflow-hidden ${course.recommended ? 'ring-2 ring-primary' : ''}`}>
                
                {course.tag && (
                  <div className={`absolute -top-4 left-1/2 -translate-x-1/2 px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter shadow-lg z-20 bg-primary text-white`}>
                    {course.tag}
                  </div>
                )}

                <div className="p-8 pb-0 flex-grow flex flex-col">
                  <div className="mt-4" />

                  <h3 className="text-xl font-black text-luxury-ink mb-3 tracking-tighter flex items-center justify-center gap-2 whitespace-nowrap">
                    <Sparkles className="w-4 h-4 text-amber-500 fill-amber-500/20 shrink-0" />
                    <span className="shrink-0">{course.title}</span>
                    <Sparkles className="w-4 h-4 text-amber-500 fill-amber-500/20 shrink-0" />
                  </h3>
                  <p className="text-sm text-luxury-text mb-8 leading-relaxed min-h-[40px]">
                    {course.description}
                  </p>

                  <div className="space-y-4 flex-grow">
                    {course.prices.map((p) => (
                      <div key={p.time} className="flex items-center justify-between group/item">
                        <div className="flex items-center gap-3">
                          <div className="w-1.5 h-1.5 rounded-full bg-stone-300 group-hover/item:bg-primary transition-colors" />
                          <span className="text-sm font-bold text-luxury-text group-hover/item:text-luxury-ink transition-colors">{p.time}</span>
                        </div>
                        <div className="flex items-center gap-1">
                          <span className="text-xl font-black text-luxury-ink group-hover/item:text-primary transition-colors">{p.price}</span>
                          <span className="text-[10px] font-bold text-stone-400 uppercase tracking-tighter">KRW</span>
                        </div>
                      </div>
                    ))}
                  </div>
                  <div className="mb-8" />
                </div>

                <div className="bg-gradient-to-br from-[#C9A84C] via-[#E68A8A] to-[#B35D5D] p-6 border-t border-white/10">
                  <Link 
                    to="/search"
                    className="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/90 text-[#B35D5D] text-[11px] font-black uppercase tracking-widest hover:bg-white hover:text-[#C9A84C] transition-all duration-300 shadow-lg backdrop-blur-sm"
                  >
                    <span>지역별 상담문의</span>
                    <ChevronRight className="w-3 h-3" />
                  </Link>
                </div>
              </div>
            </motion.div>
          ))}
        </div>

        <motion.div
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          transition={{ duration: 1, delay: 0.5 }}
          className="mt-20 p-8 bg-primary/5 rounded-3xl border border-primary/10 flex flex-col md:flex-row items-center justify-between gap-8"
        >
          <div className="flex items-center gap-6">
            <div className="w-16 h-16 bg-luxury-card rounded-2xl shadow-lg flex items-center justify-center border border-primary/5">
              <Check className="w-8 h-8 text-primary" />
            </div>
            <div>
              <h4 className="text-xl font-bold text-luxury-ink mb-1">모든 코스 공통 사항</h4>
              <p className="text-sm text-luxury-text">유류비 및 출장비가 모두 포함된 최종 결제 금액입니다.</p>
            </div>
          </div>
          <div className="flex flex-wrap gap-4 justify-center">
            {['카드결제 가능', '현금영수증 발행', '정찰제 운영', '노쇼 방지 시스템'].map((item) => (
              <div key={item} className="px-4 py-2 bg-luxury-card rounded-xl border border-primary/10 text-xs font-bold text-luxury-ink shadow-sm hover:border-primary/30 transition-colors">
                {item}
              </div>
            ))}
          </div>
        </motion.div>
      </div>
    </section>
  );
};

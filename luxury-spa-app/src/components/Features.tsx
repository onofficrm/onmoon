import React from 'react';
import { motion } from 'motion/react';
import { Check } from 'lucide-react';

const features = [
  {
    title: '최고의 관리사',
    description: '전원 20대 실력파 미녀 관리사가 직접 방문합니다.',
  },
  {
    title: '24시간 서비스',
    description: '365일 언제든지 예약 가능하며 30분 이내 방문합니다.',
  },
  {
    title: '다양한 결제방법',
    description: '현금, 카드결제, 계좌이체 모두 가능합니다.',
  },
  {
    title: '맞춤형 케어',
    description: '고객의 컨디션에 맞춘 최적의 마사지를 제공합니다.',
  },
  {
    title: '전문 교육 이수',
    description: '모든 관리사는 전문적인 교육을 이수한 전문가입니다.',
  },
  {
    title: '편안한 출장 서비스',
    description: '집, 호텔, 오피스 등 고객님이 계신 곳 어디든 찾아가는 프리미엄 서비스',
  },
];

export const Features = () => {
  return (
    <section className="py-20 bg-luxury-bg">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div 
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="bg-luxury-card rounded-[2.5rem] p-8 md:p-16 shadow-2xl shadow-black/50 border border-luxury-border/30"
        >
          <div className="mb-12">
            <div className="flex items-center gap-3">
              <div className="w-1 h-7 bg-gold rounded-full" />
              <h2 className="text-2xl md:text-3xl font-bold text-white">
                저희 서비스만의 특별함
              </h2>
            </div>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-10">
            {features.map((feature, idx) => (
              <motion.div 
                key={feature.title}
                initial={{ opacity: 0, x: idx % 2 === 0 ? -20 : 20 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.5, delay: idx * 0.1 }}
                className="flex items-start gap-5 group"
              >
                <div className="flex-shrink-0 w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                  <Check className="w-6 h-6 text-primary" strokeWidth={3} />
                </div>
                <div>
                  <h3 className="text-lg font-bold text-primary mb-1">
                    {feature.title}
                  </h3>
                  <p className="text-luxury-text text-sm md:text-base leading-relaxed">
                    {feature.description}
                  </p>
                </div>
              </motion.div>
            ))}
          </div>
        </motion.div>
      </div>
    </section>
  );
};

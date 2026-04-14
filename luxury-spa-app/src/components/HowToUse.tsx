import React from 'react';
import { motion } from 'motion/react';
import { Phone, Navigation, MessageSquare, Heart, Check } from 'lucide-react';

const steps = [
  {
    id: 1,
    title: '예약하기',
    description: '상담전화를 통해 원하시는 날짜, 시간, 코스를 예약해 주세요.',
    icon: Phone,
  },
  {
    id: 2,
    title: '방문',
    description: '예약 시간에 맞춰 전문 관리사가 30분 이내 방문합니다.',
    icon: Navigation,
  },
  {
    id: 3,
    title: '상담',
    description: '고객님의 컨디션과 요구사항을 확인하고 최적의 마사지를 상담해 드립니다.',
    icon: MessageSquare,
  },
  {
    id: 4,
    title: '마사지',
    description: '선택하신 코스에 따라 전문 관리사의 정성어린 마사지를 받으세요.',
    icon: Heart,
  },
];

const precautions = [
  '예약시간 10분 초과 시 예약이 자동 취소될 수 있습니다.',
  '폰이 꺼져있을 시 랜덤휴무 또는 마감입니다.',
  '카드결제(부가세 별도) 및 계좌이체 가능합니다.',
  '일부 지역은 방문이 불가능하거나 유류비가 추가될 수 있습니다.',
  '과음, 비매너, 퇴폐문의 등의 경우 서비스가 제한될 수 있습니다.',
];

export const HowToUse = () => {
  return (
    <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
      <div className="mb-12">
        <div className="flex items-center gap-3">
          <div className="w-1 h-8 bg-gold rounded-full" />
          <h2 className="text-3xl font-bold text-white">이용 방법</h2>
        </div>
      </div>

      <div className="space-y-6 mb-12">
        {steps.map((step, idx) => (
          <motion.div
            key={step.id}
            initial={{ opacity: 0, x: -20 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.5, delay: idx * 0.1 }}
            className="flex items-center bg-luxury-card p-6 rounded-2xl shadow-xl shadow-black/20 border border-luxury-border/30 group hover:border-gold/50 transition-all"
          >
            <div className="flex-none w-12 h-12 bg-gold rounded-full flex items-center justify-center text-luxury-bg font-bold text-xl shadow-lg mr-6">
              {step.id}
            </div>
            <div className="flex-grow">
              <h3 className="text-xl font-bold text-luxury-ink mb-1">{step.title}</h3>
              <p className="text-luxury-text font-medium">{step.description}</p>
            </div>
            <div className="flex-none ml-4 opacity-30 group-hover:opacity-100 transition-opacity">
              <step.icon className="w-8 h-8 text-gold" />
            </div>
          </motion.div>
        ))}
      </div>

      <motion.div
        initial={{ opacity: 0, y: 20 }}
        whileInView={{ opacity: 1, y: 0 }}
        viewport={{ once: true }}
        transition={{ duration: 0.5 }}
        className="bg-luxury-card rounded-3xl p-8 border border-luxury-border/30 shadow-2xl shadow-black/20"
      >
        <h3 className="text-xl font-bold text-luxury-ink mb-6">이용 시 유의사항</h3>
        <ul className="space-y-4">
          {precautions.map((item, idx) => (
            <li key={idx} className="flex items-start gap-3 text-luxury-text font-medium">
              <Check className="w-5 h-5 text-gold flex-none mt-0.5" />
              <span>{item}</span>
            </li>
          ))}
        </ul>
      </motion.div>
    </section>
  );
};

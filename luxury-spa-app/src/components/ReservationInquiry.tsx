import React from 'react';
import { motion } from 'motion/react';
import { Phone } from 'lucide-react';

export const ReservationInquiry = () => {
  return (
    <section className="py-20 bg-luxury-bg">
      <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="text-center mb-12">
          <h2 className="text-3xl font-bold text-luxury-ink mb-8">예약 문의</h2>
          
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            className="bg-white rounded-[2.5rem] p-12 md:p-20 shadow-2xl shadow-black/5 border border-luxury-border/20 relative overflow-hidden"
          >
            <div className="relative z-10">
              <p className="text-xl md:text-2xl font-medium text-luxury-text mb-12 leading-relaxed">
                예약비 없는 <span className="text-[#E6B8A2] font-bold">100% 후불제</span> 서비스로 인근지역 출장 마사지 통해 홈타이 관리를 받아보세요<br />
                모텔, 호텔, 오피스텔, 자택 어디든 <span className="text-[#E6B8A2] font-bold">30분 이내 방문</span>하여 고품격 힐링을 선사합니다
              </p>

              <div className="flex flex-col items-center">
                {/* Phone Banner Graphic Style */}
                <div className="relative inline-block mb-12">
                  <div className="bg-[#5B21B6] text-white px-6 py-2 rounded-lg font-bold text-sm absolute -top-6 -left-4 transform -rotate-12 shadow-lg">
                    예약
                  </div>
                  <div className="flex flex-col items-center bg-white border-4 border-[#5B21B6] rounded-3xl px-8 py-6 shadow-xl">
                    <span className="text-[#5B21B6] font-black text-xl mb-2 tracking-tighter">예약문의 출장마사지</span>
                    <a 
                      href="tel:0503-6982-1081" 
                      className="text-4xl md:text-6xl font-black text-[#7C3AED] tracking-tighter hover:scale-105 transition-transform"
                    >
                      0503-6982-1081
                    </a>
                  </div>
                  <div className="absolute -top-4 -right-4">
                    <div className="flex gap-1">
                      {[...Array(3)].map((_, i) => (
                        <div key={i} className="w-2 h-2 bg-[#5B21B6] rounded-full animate-pulse" style={{ animationDelay: `${i * 0.2}s` }} />
                      ))}
                    </div>
                  </div>
                </div>

                <p className="text-luxury-text/60 text-sm italic">
                  * 서비스 품질향상을 위해 통화내용은 녹음될 수 있습니다.
                </p>
              </div>
            </div>

            {/* Decorative background for the card */}
            <div className="absolute top-0 right-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2" />
            <div className="absolute bottom-0 left-0 w-64 h-64 bg-primary/5 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2" />
          </motion.div>
        </div>
      </div>
    </section>
  );
};

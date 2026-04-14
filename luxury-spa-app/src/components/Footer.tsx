import React from 'react';
import { Sparkles } from 'lucide-react';

export const Footer = () => {
  return (
    <footer className="bg-luxury-card border-t border-luxury-border pt-16 pb-12">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col items-center text-center gap-8 mb-12">
          <div className="space-y-4">
            <p className="text-luxury-text text-lg md:text-xl leading-relaxed font-medium">
              예약비 없는 <span className="text-[#E6B8A2] font-bold">100% 후불제</span> 서비스로 인근지역 출장 마사지 통해 홈타이 관리를 받아보세요<br />
              모텔, 호텔, 오피스텔, 자택 어디든 <span className="text-[#E6B8A2] font-bold">30분 이내 방문</span>하여 고품격 힐링을 선사합니다
            </p>
            <p className="text-luxury-text/70 text-sm md:text-base">
              언제나 고객님의 편안한 휴식을 위해 최선을 다하겠습니다.
            </p>
          </div>
        </div>
        
        <div className="border-t border-luxury-border/30 pt-8 text-center">
          <p className="text-sm text-luxury-text/60">
            © 2026. All rights reserved.
          </p>
        </div>
      </div>
    </footer>
  );
};

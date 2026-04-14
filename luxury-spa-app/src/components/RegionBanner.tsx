import React from 'react';
import { motion } from 'motion/react';
import { Home } from 'lucide-react';
import { Link } from 'react-router-dom';

export const RegionBanner = () => {
  return (
    <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
      <motion.div
        initial={{ opacity: 0, scale: 0.95 }}
        whileInView={{ opacity: 1, scale: 1 }}
        whileHover={{ 
          scale: 1.01,
          y: -4,
          boxShadow: "0 25px 50px -12px rgba(0, 0, 0, 0.5)"
        }}
        viewport={{ once: true }}
        transition={{ duration: 0.6, ease: "easeOut" }}
        className="relative overflow-hidden rounded-[2.5rem] bg-luxury-card shadow-2xl border border-luxury-border/30 cursor-pointer group/banner"
      >
        {/* Background Image with Gradient Overlay */}
        <div className="absolute inset-0 flex">
          <div className="w-full md:w-1/2 relative h-full overflow-hidden">
            {/* Sharp Image */}
            <img
              src="https://enjoytokyo.co.kr/wp-content/uploads/2025/09/%ED%9B%84%EA%B8%B0_3.png"
              alt="Massage Guide"
              className="w-full h-full object-cover transition-transform duration-700 group-hover/banner:scale-105"
              referrerPolicy="no-referrer"
            />
          </div>
          <div className="hidden md:block w-1/2 bg-luxury-card" />
        </div>

        {/* Content */}
        <div className="relative z-10 flex flex-col md:flex-row items-center justify-end min-h-[420px] px-8 py-12 md:py-0">
          <div className="w-full md:w-[65%] flex flex-col items-start md:items-start text-left md:pl-64 p-6 rounded-3xl transition-colors group-hover/banner:bg-white/5">
            <div className="inline-block px-4 py-1.5 bg-primary/20 rounded-full mb-6 shadow-lg border border-primary/30">
              <span className="text-xs font-bold text-primary tracking-widest uppercase">
                MASSAGE NO.1 GUIDE
              </span>
            </div>
            
            <h2 className="text-3xl md:text-4xl font-bold text-luxury-ink mb-4 leading-tight">
              전국 출장마사지<br />
              <span className="text-primary">지역별 바로가기</span>
            </h2>
            
            <p className="text-luxury-text text-sm md:text-base mb-8 max-w-md leading-relaxed group-hover/banner:text-luxury-ink transition-colors">
              내 지역 출장마사지 정보를 한눈에!<br />
              가격 · 코스 · 후기까지 투명하게 확인하세요.
            </p>
            
            <Link 
              to="/search"
              className="flex items-center gap-2 px-8 py-4 bg-primary text-white font-bold rounded-2xl hover:bg-primary-dark transition-all shadow-xl shadow-primary/20 group"
            >
              <Home className="w-5 h-5 group-hover:scale-110 transition-transform" />
              <span>바로가기</span>
            </Link>
          </div>
        </div>
      </motion.div>
    </section>
  );
};

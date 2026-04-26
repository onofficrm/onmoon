import React, { useState } from 'react';
import { Search, Star } from 'lucide-react';
import * as Icons from 'lucide-react';
import { Link, useNavigate } from 'react-router-dom';
import { motion } from 'motion/react';

export const Hero = () => {
  const [searchQuery, setSearchQuery] = useState('');
  const navigate = useNavigate();

  const handleSearch = (e?: React.FormEvent) => {
    if (e) e.preventDefault();
    if (searchQuery.trim()) {
      navigate(`/search?q=${encodeURIComponent(searchQuery.trim())}`);
    }
  };

  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter') {
      handleSearch();
    }
  };

  return (
    <div className="relative bg-white text-black overflow-hidden">
      <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-12 lg:pt-24">
        <div className="flex flex-col lg:flex-row items-center gap-16">
          <div className="flex-1 text-center lg:text-left">
            <motion.div 
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6 }}
            >
              <span className="inline-block px-4 py-1.5 rounded-full bg-white text-black text-xs font-bold uppercase tracking-widest mb-4 border border-gray-200 shadow-sm">
                Premium Home Care & Wellness
              </span>
              <h1 className="text-5xl md:text-7xl font-extrabold leading-[1.1] tracking-tighter mb-6 text-balance flex flex-col gap-2">
                <span className="text-black">신뢰할 수 있는</span>
                <span className="text-black">프리미엄 홈케어</span>
                <span className="text-black">공감마사지</span>
              </h1>
              <p className="text-lg text-gray-700 max-w-xl mx-auto lg:mx-0 leading-relaxed mb-8 font-bold">
                엄격한 기준으로 선별된 최고의 테라피스트들이<br className="hidden md:block" />
                당신의 공간으로 직접 찾아가는 프리미엄 힐링 서비스입니다.
              </p>
            </motion.div>

            <motion.div 
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.6, delay: 0.2 }}
              className="max-w-2xl mx-auto lg:mx-0"
            >
              <div className="relative group">
                <form onSubmit={handleSearch} className="relative flex items-center bg-white rounded-full p-1.5 shadow-xl shadow-gray-200/70 border border-gray-200">
                  <div className="pl-6 flex items-center pointer-events-none">
                    <Search className="h-5 w-5 text-black stroke-[3px]" />
                  </div>
                  <input
                    type="text"
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    onKeyDown={handleKeyDown}
                    className="block w-full pl-4 pr-4 py-3 bg-transparent border-none focus:ring-0 text-black placeholder-gray-400 sm:text-base font-bold"
                    placeholder="지역명 또는 업체명을 검색하세요"
                  />
                  <button 
                    type="submit"
                    className="px-8 py-3 bg-black text-white font-bold rounded-full hover:bg-gray-800 transition-all shadow-lg active:scale-95"
                  >
                    검색하기
                  </button>
                </form>
              </div>

              <div className="mt-6 flex items-center justify-center lg:justify-start gap-4 text-xs font-medium text-gray-700">
                <span className="flex items-center gap-1.5">
                  <Icons.Flame className="w-3.5 h-3.5 text-black" />
                  인기지역
                </span>
                <div className="flex gap-3">
                  {['강남', '인천', '수원', '용인'].map((city) => (
                    <button 
                      key={city} 
                      onClick={() => navigate(`/search?q=${encodeURIComponent(city)}`)}
                      className="hover:text-black transition-colors border-b border-transparent hover:border-black pb-0.5"
                    >
                      {city}
                    </button>
                  ))}
                </div>
              </div>
            </motion.div>
          </div>

          <motion.div 
            initial={{ opacity: 0, scale: 0.9 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.8 }}
            className="flex-1 relative hidden lg:block"
          >
            <div className="relative z-10 rounded-[3rem] overflow-hidden shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-700">
              <img 
                src="https://enjoytokyo.co.kr/wp-content/uploads/2025/09/%EC%B6%9C%EC%9E%A5%EB%A7%88%EC%82%AC%EC%A7%80_%EB%B0%B0%EB%84%882.png" 
                alt="Premium Massage Therapy" 
                className="w-full h-[500px] object-cover"
                referrerPolicy="no-referrer"
                onError={(e) => {
                  const target = e.target as HTMLImageElement;
                  target.onerror = null;
                  target.src = 'https://images.unsplash.com/photo-1600334129128-685c5582fd35?auto=format&fit=crop&q=80&w=1000';
                }}
              />
              <div className="absolute inset-0 bg-gradient-to-t from-white/20 via-transparent to-transparent" />
              <div className="absolute bottom-8 left-8 right-8">
                <div className="bg-white/40 backdrop-blur-xl border border-white/40 rounded-2xl p-6 shadow-2xl">
                  <div className="flex items-center gap-4">
                    <div className="w-12 h-12 bg-black rounded-full flex items-center justify-center shadow-lg">
                      <Icons.Star className="w-6 h-6 text-white fill-current" />
                    </div>
                    <div>
                      <p className="text-luxury-bg font-bold text-lg drop-shadow-sm">평균 만족도 4.9/5.0</p>
                      <p className="text-luxury-bg/90 text-sm font-medium">실제 이용 고객들의 생생한 리뷰</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="absolute -bottom-10 -left-10 bg-white shadow-xl rounded-3xl p-6 border border-gray-200 z-20">
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center">
                  <Icons.CheckCircle2 className="w-6 h-6 text-black" />
                </div>
                <div>
                  <p className="text-sm font-bold text-black">검증된 테라피스트</p>
                  <p className="text-xs text-gray-600">100% 자격증 보유</p>
                </div>
              </div>
            </div>
          </motion.div>
        </div>

        <div className="mt-16 flex items-center justify-between border-b border-gray-200 pb-6">
          <div className="flex items-center gap-3">
            <div className="w-1 h-8 bg-black rounded-full" />
            <h2 className="text-3xl font-extrabold text-black tracking-tight">지역별 카테고리</h2>
          </div>
          <Link to="/search" className="group flex items-center gap-2 text-sm font-bold text-black">
            전체보기
            <Icons.ArrowRight className="w-4 h-4 group-hover:translate-x-1 transition-transform" />
          </Link>
        </div>
      </div>
    </div>
  );
};

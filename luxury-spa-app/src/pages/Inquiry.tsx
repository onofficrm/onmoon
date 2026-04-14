import React from 'react';
import { MessageSquarePlus, Send, CheckCircle2 } from 'lucide-react';

export const Inquiry = () => {
  return (
    <main className="min-h-screen bg-luxury-bg py-16 px-4">
      <div className="max-w-3xl mx-auto mb-20">
        <div className="text-center mb-12">
          <div className="inline-flex items-center justify-center w-16 h-16 bg-primary/10 rounded-2xl mb-6">
            <MessageSquarePlus className="w-8 h-8 text-primary" />
          </div>
          <h1 className="text-3xl font-extrabold text-luxury-ink mb-4">입점문의</h1>
          <p className="text-luxury-text">
            저희와 함께할 최고의 파트너를 기다립니다.<br />
            아래 양식을 작성해 주시면 담당자가 확인 후 연락드리겠습니다.
          </p>
        </div>

        <div className="bg-luxury-card rounded-3xl shadow-xl shadow-luxury-ink/10 border border-luxury-border overflow-hidden">
          <div className="p-8 md:p-12">
            <form className="space-y-6" onSubmit={(e) => e.preventDefault()}>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label className="block text-sm font-bold text-luxury-text mb-2">업체명 (상호)</label>
                  <input
                    type="text"
                    className="w-full px-4 py-3 bg-stone-50 border border-luxury-border rounded-xl text-luxury-ink focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                    placeholder="업체명을 입력하세요"
                  />
                </div>
                <div>
                  <label className="block text-sm font-bold text-luxury-text mb-2">대표자명</label>
                  <input
                    type="text"
                    className="w-full px-4 py-3 bg-stone-50 border border-luxury-border rounded-xl text-luxury-ink focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                    placeholder="성함을 입력하세요"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <label className="block text-sm font-bold text-luxury-text mb-2">활동 지역</label>
                  <select className="w-full px-4 py-3 bg-stone-50 border border-luxury-border rounded-xl text-luxury-ink focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none">
                    <option className="bg-luxury-card">서울</option>
                    <option className="bg-luxury-card">경기</option>
                    <option className="bg-luxury-card">인천</option>
                    <option className="bg-luxury-card">수원</option>
                    <option className="bg-luxury-card">용인</option>
                    <option className="bg-luxury-card">기타</option>
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-bold text-luxury-text mb-2">연락처</label>
                  <input
                    type="tel"
                    className="w-full px-4 py-3 bg-stone-50 border border-luxury-border rounded-xl text-luxury-ink focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                    placeholder="010-0000-0000"
                  />
                </div>
              </div>

              <div>
                <label className="block text-sm font-bold text-luxury-text mb-2">문의 내용</label>
                <textarea
                  rows={5}
                  className="w-full px-4 py-3 bg-stone-50 border border-luxury-border rounded-xl text-luxury-ink focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"
                  placeholder="문의하실 내용을 상세히 적어주세요."
                ></textarea>
              </div>

              <div className="pt-4">
                <button className="w-full py-4 bg-primary text-white font-bold rounded-2xl hover:bg-primary-dark transition-all shadow-lg shadow-primary/20 flex items-center justify-center gap-2">
                  <Send className="w-5 h-5" />
                  문의 신청하기
                </button>
              </div>
            </form>
          </div>

          <div className="bg-stone-50 p-8 border-t border-luxury-border">
            <h3 className="text-sm font-bold text-luxury-ink mb-4 flex items-center gap-2">
              <CheckCircle2 className="w-4 h-4 text-primary" />
              입점 프로세스
            </h3>
            <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
              <div className="bg-luxury-card p-4 rounded-xl border border-luxury-border">
                <p className="text-xs font-bold text-primary mb-1">STEP 01</p>
                <p className="text-sm font-bold text-luxury-ink">문의 접수</p>
              </div>
              <div className="bg-luxury-card p-4 rounded-xl border border-luxury-border">
                <p className="text-xs font-bold text-primary mb-1">STEP 02</p>
                <p className="text-sm font-bold text-luxury-ink">담당자 상담</p>
              </div>
              <div className="bg-luxury-card p-4 rounded-xl border border-luxury-border">
                <p className="text-xs font-bold text-primary mb-1">STEP 03</p>
                <p className="text-sm font-bold text-luxury-ink">입점 완료</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  );
};

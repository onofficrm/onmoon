import React from 'react';
import { motion } from 'motion/react';
import { Star, Quote, User, X } from 'lucide-react';
import { AnimatePresence } from 'motion/react';

const REVIEWS = [
  {
    id: 1,
    name: '김*현',
    rating: 5,
    avatar: 'https://picsum.photos/seed/person1/100/100',
    text: '처음 이용해봤는데 정말 만족스러웠습니다. 관리사분이 너무 친절하시고 실력도 좋으셔서 피로가 싹 풀렸네요. 특히 어깨와 목 부분이 많이 뭉쳐있었는데, 그 부분을 집중적으로 케어해주셔서 정말 시원했습니다. 집에서 이렇게 편하게 고퀄리티 마사지를 받을 수 있다는 게 큰 장점인 것 같아요. 다음에도 꼭 다시 이용할 예정입니다. 주변 지인들에게도 적극 추천하고 싶네요!',
    date: '2024.03.25',
    service: '프리미엄 아로마'
  },
  {
    id: 2,
    name: '이*우',
    rating: 5,
    avatar: 'https://picsum.photos/seed/person2/100/100',
    text: '시간 약속도 잘 지켜주시고 매너가 너무 좋으셨어요. 집에서 편하게 받을 수 있어서 너무 좋네요. 강추합니다! 무엇보다 위생 관리가 철저하신 것 같아 안심하고 서비스를 받을 수 있었습니다. 마사지 압도 딱 적당해서 받는 내내 힐링되는 기분이었어요. 바쁜 일상 속에서 나만을 위한 소중한 시간을 보낸 것 같아 매우 만족스럽습니다. 앞으로도 자주 이용할게요.',
    date: '2024.03.22',
    service: '스웨디시 마사지'
  },
  {
    id: 3,
    name: '박*아',
    rating: 4,
    avatar: 'https://picsum.photos/seed/person3/100/100',
    text: '시설 가는 것보다 훨씬 편하고 좋네요. 관리사님 손길이 정말 꼼꼼하셔서 뭉친 근육이 다 풀린 기분이에요. 감사합니다. 처음에는 출장 마사지라고 해서 조금 걱정도 했었는데, 상담부터 예약까지 너무 친절하게 안내해주셔서 믿음이 갔습니다. 관리사님의 전문적인 테크닉 덕분에 그동안 쌓였던 스트레스가 한 번에 날아가는 것 같았어요. 정말 정성스럽게 관리해주셔서 감동받았습니다.',
    date: '2024.03.20',
    service: '타이 마사지'
  },
  {
    id: 4,
    name: '최*준',
    rating: 5,
    avatar: 'https://picsum.photos/seed/person4/100/100',
    text: '여러 곳 이용해봤지만 여기가 제일 깔끔하고 서비스가 좋네요. 상담해주시는 분도 친절하시고 관리사분 실력도 최고입니다. 특히 아로마 오일 향이 너무 좋아서 심신이 안정되는 효과도 있었던 것 같아요. 마사지 후에 따뜻한 차 한 잔 마시니 몸이 노곤노곤해지면서 꿀잠 잘 수 있었습니다. 서비스 품질이 일정하게 유지되는 것 같아 신뢰가 갑니다. 강력 추천드려요!',
    date: '2024.03.18',
    service: '딥티슈 테라피'
  },
  {
    id: 5,
    name: '정*민',
    rating: 5,
    avatar: 'https://picsum.photos/seed/person5/100/100',
    text: '정말 시원하게 잘 받았습니다. 몸이 한결 가벼워졌어요. 정기적으로 이용하고 싶네요. 평소에 자세가 안 좋아서 허리 통증이 심했는데, 관리사님이 그 부분을 정확히 파악하시고 교정해주시는 느낌으로 마사지를 해주셨습니다. 받고 나니 통증이 많이 완화되었고 움직임이 훨씬 부드러워졌어요. 실력 있는 관리사님을 만나서 정말 행운이었습니다. 조만간 또 연락드릴게요.',
    date: '2024.03.15',
    service: '아로마 테라피'
  },
  {
    id: 6,
    name: '한*지',
    rating: 5,
    avatar: 'https://picsum.photos/seed/person6/100/100',
    text: '관리사님이 너무 전문적이셔서 놀랐어요. 통증 부위를 정확히 짚어주시네요. 최고입니다. 해부학적인 지식도 있으신 것 같고, 근육의 흐름을 잘 이해하고 계신 것 같았습니다. 단순히 누르는 게 아니라 뭉친 곳을 풀어주는 기술이 남다르시더라고요. 마사지 받는 동안 설명도 잘 해주셔서 제 몸 상태에 대해 더 잘 알게 되었습니다. 전문적인 케어를 원하시는 분들께 강력 추천합니다.',
    date: '2024.03.12',
    service: '스포츠 마사지'
  },
  {
    id: 7,
    name: '윤*성',
    rating: 4,
    avatar: 'https://picsum.photos/seed/person7/100/100',
    text: '집에서 이렇게 퀄리티 높은 마사지를 받을 수 있다니 세상 참 좋아졌네요. 만족합니다. 이동하는 시간도 아낄 수 있고, 마사지 직후에 바로 내 침대에서 쉴 수 있다는 게 정말 큰 행복이네요. 서비스 구성도 알차고 가격 대비 만족도가 매우 높습니다. 관리사님도 인상이 너무 좋으시고 정중하셔서 기분 좋게 서비스를 마칠 수 있었습니다. 번창하세요!',
    date: '2024.03.10',
    service: '스웨디시'
  },
  {
    id: 8,
    name: '송*혜',
    rating: 5,
    avatar: 'https://picsum.photos/seed/person8/100/100',
    text: '피로가 누적되어 힘들었는데 마사지 받고 컨디션 회복했습니다. 친절한 서비스 감사합니다. 예약 과정도 간편하고 피드백도 빨라서 좋았습니다. 관리사님이 오실 때 필요한 물품들을 다 챙겨오셔서 제가 따로 준비할 게 전혀 없더라고요. 세심한 배려에 감사드립니다. 덕분에 이번 주말을 아주 상쾌하게 시작할 수 있을 것 같아요. 다음에 또 뵙겠습니다.',
    date: '2024.03.08',
    service: '프리미엄 코스'
  }
];

export const Testimonials = () => {
  const [selectedReview, setSelectedReview] = React.useState<typeof REVIEWS[0] | null>(null);
  
  // Duplicate for infinite marquee
  const marqueeReviews = [...REVIEWS, ...REVIEWS];

  return (
    <section className="py-20 bg-luxury-bg relative overflow-hidden">
      {/* Background Decorative Elements */}
      <div className="absolute top-0 left-0 w-full h-full pointer-events-none opacity-5">
        <div className="absolute top-10 left-10 w-64 h-64 bg-primary rounded-full blur-3xl" />
        <div className="absolute bottom-10 right-10 w-64 h-64 bg-primary rounded-full blur-3xl" />
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 mb-16">
        <div className="flex items-center gap-3">
          <div className="w-1 h-8 bg-gold rounded-full" />
          <h2 className="text-3xl md:text-4xl font-bold text-luxury-ink">고객 후기</h2>
        </div>
      </div>

      <div className="relative max-w-full overflow-hidden">
        {/* Marquee Container */}
        <div className="flex animate-marquee whitespace-nowrap gap-6 px-4">
          {marqueeReviews.map((review, index) => (
            <div
              key={`${review.id}-${index}`}
              onClick={() => setSelectedReview(review)}
              className="flex-none w-[300px] sm:w-[350px] bg-luxury-card p-8 rounded-[2rem] shadow-2xl shadow-black/20 border border-luxury-border/30 relative group cursor-pointer hover:border-primary/50 transition-all"
            >
              <Quote className="absolute top-6 right-8 w-10 h-10 text-primary/5 group-hover:text-primary/10 transition-colors" />
              
              <div className="flex gap-1 mb-4">
                {[...Array(5)].map((_, i) => (
                  <Star
                    key={i}
                    className={`w-4 h-4 ${
                      i < review.rating ? 'text-primary fill-primary' : 'text-luxury-border'
                    }`}
                  />
                ))}
              </div>

              <p className="text-luxury-text text-sm leading-relaxed mb-6 italic whitespace-normal line-clamp-3 font-medium">
                "{review.text}"
              </p>

              <div className="flex items-center justify-between pt-4 border-t border-luxury-border/20">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 bg-primary/10 rounded-full flex items-center justify-center overflow-hidden border border-primary/20">
                    {review.avatar ? (
                      <img 
                        src={review.avatar} 
                        alt={review.name} 
                        className="w-full h-full object-cover"
                        referrerPolicy="no-referrer"
                      />
                    ) : (
                      <User className="w-5 h-5 text-primary" />
                    )}
                  </div>
                  <div>
                    <h4 className="text-sm font-bold text-luxury-ink">{review.name}</h4>
                    <div className="flex items-center gap-2">
                      <span className="text-[10px] text-primary font-bold uppercase tracking-tighter">{review.service}</span>
                      <span className="text-[10px] text-luxury-text/40 font-bold">{review.date}</span>
                    </div>
                  </div>
                </div>
                <span className="text-[10px] text-primary font-bold uppercase opacity-0 group-hover:opacity-100 transition-opacity">더보기 +</span>
              </div>
            </div>
          ))}
        </div>

        {/* Gradient Edges */}
        <div className="absolute top-0 left-0 h-full w-24 bg-gradient-to-r from-luxury-bg to-transparent pointer-events-none z-10" />
        <div className="absolute top-0 right-0 h-full w-24 bg-gradient-to-l from-luxury-bg to-transparent pointer-events-none z-10" />
      </div>

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <motion.div
          initial={{ opacity: 0 }}
          whileInView={{ opacity: 1 }}
          viewport={{ once: true }}
          transition={{ delay: 0.5 }}
          className="mt-16 text-center"
        >
          <p className="text-gray-400 text-sm">
            실제 이용 고객님들이 남겨주신 소중한 후기입니다. (후기를 클릭하시면 전체 내용을 보실 수 있습니다.)
          </p>
        </motion.div>
      </div>

      {/* Review Detail Modal */}
      <AnimatePresence>
        {selectedReview && (
          <div className="fixed inset-0 z-[100] flex items-center justify-center px-4">
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 1 }}
              exit={{ opacity: 0 }}
              onClick={() => setSelectedReview(null)}
              className="absolute inset-0 bg-black/60 backdrop-blur-sm"
            />
            <motion.div
              initial={{ opacity: 0, scale: 0.9, y: 20 }}
              animate={{ opacity: 1, scale: 1, y: 0 }}
              exit={{ opacity: 0, scale: 0.9, y: 20 }}
              className="relative w-full max-w-lg bg-luxury-card rounded-[2.5rem] p-8 md:p-12 shadow-2xl border border-luxury-border/30 overflow-hidden"
            >
              <Quote className="absolute -top-4 -right-4 w-32 h-32 text-primary/5 pointer-events-none" />

              <div className="relative z-10">
                <div className="flex gap-1 mb-6">
                  {[...Array(5)].map((_, i) => (
                    <Star
                      key={i}
                      className={`w-5 h-5 ${
                        i < selectedReview.rating ? 'text-primary fill-primary' : 'text-luxury-border'
                      }`}
                    />
                  ))}
                </div>

                <p className="text-luxury-ink text-lg md:text-xl leading-relaxed mb-10 italic font-bold">
                  "{selectedReview.text}"
                </p>

                <div className="flex items-center gap-4 pt-8 border-t border-luxury-border/20">
                  <div className="w-14 h-14 bg-primary/10 rounded-full flex items-center justify-center overflow-hidden border border-primary/20">
                    {selectedReview.avatar ? (
                      <img 
                        src={selectedReview.avatar} 
                        alt={selectedReview.name} 
                        className="w-full h-full object-cover"
                        referrerPolicy="no-referrer"
                      />
                    ) : (
                      <User className="w-7 h-7 text-primary" />
                    )}
                  </div>
                  <div>
                    <h4 className="text-lg font-bold text-luxury-ink">{selectedReview.name}</h4>
                    <div className="flex items-center gap-3">
                      <span className="text-sm text-primary font-bold uppercase tracking-tighter">{selectedReview.service}</span>
                      <span className="text-sm text-luxury-text/40 font-bold">{selectedReview.date}</span>
                    </div>
                  </div>
                </div>
              </div>

              <button
                onClick={(e) => {
                  e.stopPropagation();
                  setSelectedReview(null);
                }}
                className="absolute top-6 right-6 p-2 hover:bg-white/5 rounded-full transition-colors z-20"
                aria-label="Close"
              >
                <X className="w-6 h-6 text-luxury-text/40" />
              </button>
            </motion.div>
          </div>
        )}
      </AnimatePresence>
    </section>
  );
};

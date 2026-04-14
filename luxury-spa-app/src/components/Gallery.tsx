import React from 'react';
import { motion } from 'motion/react';

const galleryItems = [
  {
    title: '태국식 마사지',
    image: 'https://onmoon.co.kr/data/editor/2512/thumb-6be7981a180c0dbd5c6299d45aba48da_1765260954_5087_835x470.jpg',
  },
  {
    title: '아로마 테라피',
    image: 'https://onmoon.co.kr/data/editor/2512/thumb-6be7981a180c0dbd5c6299d45aba48da_1765260956_3011_835x470.jpg',
  },
  {
    title: '힐링 스웨디시',
    image: 'https://onmoon.co.kr/data/editor/2512/thumb-6be7981a180c0dbd5c6299d45aba48da_1765260958_8943_835x470.jpg',
  },
];

export const Gallery = () => {
  return (
    <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
      <div className="bg-luxury-card rounded-[2.5rem] p-8 md:p-12 shadow-2xl shadow-black/20 border border-luxury-border/30">
        <div className="mb-10">
          <div className="flex items-center gap-3">
            <div className="w-1 h-8 bg-gold rounded-full" />
            <h2 className="text-3xl font-bold text-white uppercase tracking-tight">마사지 갤러리</h2>
          </div>
        </div>

        <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
          {galleryItems.map((item, idx) => (
            <motion.div
              key={item.title}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: idx * 0.1 }}
              className="flex flex-col overflow-hidden rounded-3xl shadow-xl border border-luxury-border/20 group"
            >
              <div className="aspect-[16/10] overflow-hidden">
                <img
                  src={item.image}
                  alt={item.title}
                  className="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                  referrerPolicy="no-referrer"
                />
              </div>
              <div className="bg-gradient-to-b from-gold to-rose-gold py-5 px-6">
                <h3 className="text-luxury-bg font-bold text-lg text-center tracking-tight">
                  {item.title}
                </h3>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
};

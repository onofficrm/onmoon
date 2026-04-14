import React from 'react';
import { Phone, ExternalLink, Star } from 'lucide-react';
import { Listing } from '../types';

interface ListingCardProps {
  listing: Listing;
}

export const ListingCard: React.FC<ListingCardProps> = ({ listing }) => {
  return (
    <div className="bg-luxury-card border border-luxury-border rounded-2xl overflow-hidden hover:shadow-xl hover:shadow-luxury-ink/10 transition-all group">
      <div className="relative aspect-[4/3] overflow-hidden">
        <img
          src={listing.imageUrl}
          alt={listing.name}
          className="w-full h-full object-cover transition-transform duration-500"
          referrerPolicy="no-referrer"
          onError={(e) => {
            const target = e.target as HTMLImageElement;
            target.onerror = null; // Prevent infinite loop
            target.src = 'https://onmoon.co.kr/data/editor/2512/1e18e42db0d520bc7b11124400f58ed7_1765266428_7841.png';
          }}
        />
        <div className="absolute top-3 right-3 bg-luxury-card/80 backdrop-blur-md px-2 py-1 rounded-lg flex items-center gap-1 shadow-lg border border-white/10">
          <Star className="w-3 h-3 text-gold fill-gold" />
          <span className="text-xs font-bold text-luxury-ink">{listing.rating}</span>
        </div>
      </div>
      
      <div className="p-4">
        <div className="mb-3">
          <h3 className="text-base font-bold text-luxury-ink mb-1 group-hover:text-gold transition-colors truncate">
            {listing.name}
          </h3>
          <p className="text-xs text-luxury-text line-clamp-1 leading-relaxed">
            {listing.description}
          </p>
        </div>

        <div className="flex flex-col gap-2">
          <a
            href={`tel:${listing.phone.replace(/-/g, '')}`}
            className="flex items-center justify-center gap-2 w-full py-2.5 bg-gradient-to-r from-gold to-rose-gold text-luxury-bg font-bold rounded-xl hover:shadow-lg hover:shadow-gold/30 transition-all shadow-sm active:scale-95 text-xs"
          >
            <Phone className="w-3.5 h-3.5" />
            전화걸기 ({listing.phone})
          </a>
          
          <a
            href={listing.link}
            target="_blank"
            rel="noopener noreferrer"
            className="flex items-center justify-center gap-2 w-full py-2.5 bg-luxury-bg/50 text-luxury-text font-bold rounded-xl hover:bg-luxury-bg transition-all border border-luxury-border text-xs"
          >
            <ExternalLink className="w-3.5 h-3.5" />
            상세보기
          </a>
        </div>
      </div>
    </div>
  );
};

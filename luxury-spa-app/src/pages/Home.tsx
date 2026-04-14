import React from 'react';
import { Hero } from '../components/Hero';
import { RegionGrid } from '../components/RegionGrid';
import { ListingCard } from '../components/ListingCard';
import { PriceSection } from '../components/PriceSection';
import { Features } from '../components/Features';
import { Gallery } from '../components/Gallery';
import { HowToUse } from '../components/HowToUse';
import { RegionBanner } from '../components/RegionBanner';
import { Testimonials } from '../components/Testimonials';
import { LISTINGS } from '../data/listings';
import { Sparkles } from 'lucide-react';

export const Home = () => {
  // Recommended listings (top rated)
  const recommendedListings = [...LISTINGS]
    .sort((a, b) => b.rating - a.rating)
    .slice(0, 10);

  // Duplicate for infinite marquee
  const marqueeListings = [...recommendedListings, ...recommendedListings];

  return (
    <main>
      <Hero />
      <RegionGrid />
      
      <section className="max-w-full overflow-hidden py-16 bg-luxury-card/30">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
          <div className="flex items-center gap-3">
            <div className="w-1 h-7 bg-gold rounded-full" />
            <h2 className="text-2xl font-bold text-luxury-ink tracking-tight">추천 마사지 업체</h2>
          </div>
        </div>
        
        <div className="relative">
          {/* Marquee Container */}
          <div className="flex animate-marquee whitespace-nowrap gap-6 px-4">
            {marqueeListings.map((listing, index) => (
              <div 
                key={`${listing.id}-${index}`} 
                className="flex-none w-[280px] sm:w-[320px]"
              >
                <ListingCard listing={listing} />
              </div>
            ))}
          </div>
          
          {/* Gradient Edges for better visual transition */}
          <div className="absolute top-0 left-0 h-full w-24 bg-gradient-to-r from-luxury-bg/80 to-transparent pointer-events-none z-10" />
          <div className="absolute top-0 right-0 h-full w-24 bg-gradient-to-l from-luxury-bg/80 to-transparent pointer-events-none z-10" />
        </div>
      </section>

      <PriceSection />
      <Features />
      <Gallery />
      <RegionBanner />
      <Testimonials />
      <HowToUse />
    </main>
  );
};

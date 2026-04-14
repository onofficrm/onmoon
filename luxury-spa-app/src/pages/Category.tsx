import React, { useEffect } from 'react';
import { useParams, Link } from 'react-router-dom';
import { LISTINGS, REGIONS } from '../data/listings';
import { ListingCard } from '../components/ListingCard';
import { PriceSection } from '../components/PriceSection';
import { ChevronRight, MapPin, Info } from 'lucide-react';

export const Category = () => {
  const { regionId } = useParams<{ regionId: string }>();
  const region = REGIONS.find((r) => r.id === regionId);
  const filteredListings = LISTINGS.filter((l) => l.region === regionId);

  useEffect(() => {
    if (region && filteredListings.length > 0) {
      const schema = {
        "@context": "https://schema.org",
        "@type": "ItemList",
        "name": `${region.name} 홈타이 마사지 추천 리스트`,
        "description": `${region.name} 지역 최고의 홈케어 마사지 업체 목록입니다.`,
        "itemListElement": filteredListings.map((listing, index) => ({
          "@type": "ListItem",
          "position": index + 1,
          "item": {
            "@type": "LocalBusiness",
            "name": listing.name,
            "telephone": listing.phone,
            "url": listing.link,
            "image": listing.imageUrl
          }
        }))
      };

      const script = document.createElement('script');
      script.type = 'application/ld+json';
      script.text = JSON.stringify(schema);
      document.head.appendChild(script);

      return () => {
        document.head.removeChild(script);
      };
    }
  }, [region, filteredListings]);

  if (!region) {
    return (
      <div className="min-h-[60vh] flex flex-col items-center justify-center px-4">
        <h2 className="text-2xl font-bold text-luxury-ink mb-4">지역을 찾을 수 없습니다</h2>
        <Link to="/" className="text-primary font-bold hover:underline">홈으로 돌아가기</Link>
      </div>
    );
  }

  return (
    <main className="min-h-screen bg-luxury-bg">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12">
        <div className="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8">
          <div>
            <div className="flex items-center gap-2 text-primary font-bold mb-1 uppercase tracking-widest">
              <MapPin className="w-5 h-5" />
              <span>{region.name} 지역</span>
            </div>
            <h1 className="text-4xl md:text-5xl font-extrabold text-luxury-ink tracking-tight">
              {region.name} <span className="text-primary">최고의 홈타이 마사지</span>
            </h1>
            <p className="mt-2 text-luxury-text font-medium max-w-2xl leading-relaxed">
              {region.name} 전지역 30분 내 방문 가능한 검증된 업체들을 소개합니다. 
              전문 테라피스트의 정성스런 케어로 지친 몸과 마음을 힐링하세요.
            </p>
          </div>
          <div className="bg-luxury-card p-6 rounded-2xl border border-luxury-border/30 shadow-2xl shadow-black/20 flex items-center gap-4">
            <div className="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center shadow-lg border border-primary/20">
              <Info className="w-6 h-6 text-primary" />
            </div>
            <div>
              <p className="text-xs text-luxury-text font-bold uppercase tracking-tighter">등록된 업체</p>
              <p className="text-2xl font-bold text-primary">{filteredListings.length}개</p>
            </div>
          </div>
        </div>

        {filteredListings.length > 0 ? (
          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            {filteredListings.map((listing) => (
              <ListingCard key={listing.id} listing={listing} />
            ))}
          </div>
        ) : (
          <div className="bg-luxury-card rounded-3xl p-20 text-center border-2 border-dashed border-luxury-border/30 shadow-2xl shadow-black/20">
            <p className="text-luxury-ink font-bold text-xl">현재 이 지역에 등록된 업체가 없습니다.</p>
            <Link to="/" className="mt-6 inline-block bg-primary text-luxury-bg px-8 py-3 rounded-full font-bold uppercase tracking-widest hover:bg-primary-dark transition-all shadow-xl shadow-primary/20">
              홈으로 돌아가기
            </Link>
          </div>
        )}
      </div>
    </main>
  );
};

import React from 'react';
import { useSearchParams, Link } from 'react-router-dom';
import { LISTINGS } from '../data/listings';
import { ListingCard } from '../components/ListingCard';
import { PriceSection } from '../components/PriceSection';
import { ChevronRight, Search as SearchIcon, Info } from 'lucide-react';

export const Search = () => {
  const [searchParams] = useSearchParams();
  const query = searchParams.get('q') || '';

  const filteredListings = LISTINGS.filter((l) => {
    const searchStr = (l.name + l.description).toLowerCase();
    const lowerQuery = query.toLowerCase().trim();
    
    if (!lowerQuery) return true;

    // 1. 기본 포함 검색 (전체 문자열이 포함되는지)
    if (searchStr.includes(lowerQuery)) return true;
    
    // 2. 키워드 정제 검색 (불필요한 단어 제거 후 핵심 지역명/업체명으로 검색)
    // 출장, 마사지, 홈타이 등 흔한 접미사/접두사 제거
    const cleanedQuery = lowerQuery
      .replace(/(출장|마사지|출장마사지|홈타이|홈케어|타이|스웨디시|1인샵|건마|힐링|방문)/g, '')
      .trim();
    
    if (cleanedQuery && searchStr.includes(cleanedQuery)) return true;

    // 3. 띄어쓰기 기준 단어별 검색 (검색어의 각 단어가 포함되는지)
    const queryWords = lowerQuery.split(/\s+/).filter(word => 
      word.length > 1 && !['출장', '마사지', '홈타이', '타이'].includes(word)
    );
    
    if (queryWords.length > 0 && queryWords.some(word => searchStr.includes(word))) return true;
    
    return false;
  });

  return (
    <main className="min-h-screen bg-luxury-bg">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div className="flex flex-col items-center text-center gap-8 mb-16">
          <div className="flex flex-col items-center">
            <div className="flex items-center gap-2 text-primary font-bold mb-4">
              <SearchIcon className="w-6 h-6" />
              <span className="text-lg">'{query}' 검색 결과</span>
            </div>
            <h1 className="text-4xl md:text-5xl font-extrabold text-luxury-ink tracking-tight mb-6">
              {query ? `"${query}" 검색 결과` : '전체 업체 목록'}
            </h1>
            <p className="text-lg text-luxury-text max-w-2xl leading-relaxed">
              검색하신 키워드와 관련된 최고의 홈타이 마사지 업체들을 확인해보세요.<br />
              엄선된 업체들만이 <span className="text-[#E6B8A2] font-bold">최고의 힐링</span>을 선사합니다.
            </p>
          </div>
          
          <div className="bg-luxury-card px-8 py-4 rounded-2xl border border-luxury-border shadow-xl shadow-luxury-ink/10 flex items-center gap-4">
            <div className="w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
              <Info className="w-6 h-6 text-primary" />
            </div>
            <div className="text-left">
              <p className="text-xs font-bold text-luxury-text uppercase tracking-wider">검색된 업체</p>
              <p className="text-2xl font-black text-luxury-ink">{filteredListings.length} <span className="text-sm font-normal text-luxury-text">개</span></p>
            </div>
          </div>
        </div>

        {filteredListings.length > 0 ? (
          <div className="flex flex-wrap justify-center gap-8">
            {filteredListings.map((listing) => (
              <div key={listing.id} className="w-full sm:w-[calc(50%-1rem)] lg:w-[calc(33.333%-1.5rem)] max-w-sm">
                <ListingCard listing={listing} />
              </div>
            ))}
          </div>
        ) : (
          <div className="bg-luxury-card rounded-3xl p-20 text-center border border-dashed border-luxury-border">
            <p className="text-luxury-text font-medium">검색 결과가 없습니다. 다른 키워드로 검색해보세요.</p>
            <Link to="/" className="mt-4 inline-block text-primary font-bold hover:underline">
              홈으로 돌아가기
            </Link>
          </div>
        )}
      </div>
    </main>
  );
};

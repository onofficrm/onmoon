import React from 'react';
import { Link } from 'react-router-dom';
import * as Icons from 'lucide-react';
import { REGIONS } from '../data/listings';

export const RegionGrid = () => {
  return (
    <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12 pt-6">
      <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
        {REGIONS.map((region) => {
          const IconComponent = (Icons as any)[region.icon] || Icons.MapPin;
          return (
            <Link
              key={region.id}
              to={`/category/${region.id}`}
              className="flex flex-col items-center justify-center p-6 bg-luxury-card border border-luxury-border/30 rounded-2xl hover:border-gold/50 shadow-xl shadow-black/20 transition-all group"
            >
              <div className="w-14 h-14 bg-yellow-400/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-yellow-400 transition-all duration-300">
                <IconComponent className="w-7 h-7 text-yellow-400 group-hover:text-luxury-bg transition-colors" />
              </div>
              <span className="text-lg font-bold text-luxury-ink tracking-tight group-hover:text-yellow-400 transition-colors">{region.name}</span>
            </Link>
          );
        })}
      </div>
    </section>
  );
};

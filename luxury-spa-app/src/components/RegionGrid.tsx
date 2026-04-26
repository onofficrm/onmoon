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
              className="flex flex-col items-center justify-center p-6 bg-white border border-gray-200 rounded-2xl hover:border-black shadow-sm transition-all group"
            >
              <div className="w-14 h-14 bg-gray-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-black transition-all duration-300">
                <IconComponent className="w-7 h-7 text-black group-hover:text-white transition-colors" />
              </div>
              <span className="text-lg font-bold text-black tracking-tight">{region.name}</span>
            </Link>
          );
        })}
      </div>
    </section>
  );
};

import React from 'react';
import { Link } from 'react-router-dom';

export const Header = () => {
  return (
    <header className="sticky top-0 z-50 w-full bg-white/95 backdrop-blur-md border-b border-gray-200 shadow-sm">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-center items-center h-20 text-black">
          <Link to="/" className="flex items-center group">
            <span className="text-xl font-black tracking-tight">GONGGAM 공감마사지</span>
          </Link>
        </div>
      </div>
    </header>
  );
};

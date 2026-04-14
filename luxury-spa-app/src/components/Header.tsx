import React from 'react';
import { Link } from 'react-router-dom';

export const Header = () => {
  return (
    <header className="sticky top-0 z-50 w-full bg-luxury-bg/90 backdrop-blur-md border-b border-luxury-border shadow-lg">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex justify-center items-center h-20">
          <Link to="/" className="flex items-center group">
            {/* Logo or text removed as requested */}
          </Link>
        </div>
      </div>
    </header>
  );
};

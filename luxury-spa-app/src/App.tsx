import React from 'react';
import { BrowserRouter as Router, Routes, Route, useLocation } from 'react-router-dom';
import { Header } from './components/Header';
import { Footer } from './components/Footer';
import { Home } from './pages/Home';
import { Category } from './pages/Category';
import { Inquiry } from './pages/Inquiry';
import { Search } from './pages/Search';
import { useEffect } from 'react';

const ScrollToTop = () => {
  const { pathname } = useLocation();
  useEffect(() => {
    window.scrollTo(0, 0);
  }, [pathname]);
  return null;
};

export default function App() {
  return (
    <Router>
      <ScrollToTop />
      <div className="min-h-screen flex flex-col bg-luxury-bg">
        <div className="flex-grow">
          <Routes>
            <Route path="/" element={<Home />} />
            <Route path="/category/:regionId" element={<Category />} />
            <Route path="/inquiry" element={<Inquiry />} />
            <Route path="/search" element={<Search />} />
          </Routes>
        </div>
        <Footer />
      </div>
    </Router>
  );
}

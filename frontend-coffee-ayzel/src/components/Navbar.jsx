import { useState } from 'react';
import { Link, useLocation } from 'react-router-dom';

const navLinks = [
  { path: '/', label: 'Home' },
  { path: '/about', label: 'About' },
  { path: '/products', label: 'Produk' },
  { path: '/testimonials', label: 'Testimoni' },
  { path: '/contact', label: 'Kontak' },
];

export default function Navbar() {
  const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
  const location = useLocation();

  return (
    <nav
        className="fixed top-0 left-0 right-0 z-50 bg-white shadow-md border-b border-amber-100 transition-all duration-300"
        role="navigation"
        aria-label="Main navigation"
      >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16 lg:h-20">
          <Link
            to="/"
            className="flex items-center gap-2 text-2xl font-bold text-amber-900 hover:text-amber-700 transition-colors"
            aria-label="Coffee Ayzel Home"
          >
            <img src="/logo.png" alt="Logo Company" className="w-12 h-12" />
            <span className="hidden sm:block text-amber-400">Coffee Ayzel</span>
          </Link>

          <div className="hidden lg:flex items-center gap-8">
            {navLinks.map((link) => (
              <Link
                key={link.path}
                to={link.path}
                className={`relative px-3 py-2 text-sm font-medium transition-all duration-200 ${
                  location.pathname === link.path
                    ? 'text-amber-500'
                    : 'text-gray-500 hover:text-amber-500'
                }`}
              >
                {link.label}
                {location.pathname === link.path && (
                  <span
                    className="absolute bottom-0 left-1/2 -translate-x-1/2 w-1 h-1 bg-amber-500 rounded-full"
                    aria-hidden="true"
                  />
                )}
              </Link>
            ))}
          </div>

          <div className="hidden lg:flex items-center gap-4">
            <Link
              to="/products"
              className="px-5 py-2.5 bg-amber-500 text-white font-medium rounded-full hover:bg-amber-700 transition-all duration-200 shadow-lg hover:shadow-amber-500/30 transform hover:-translate-y-0.5"
            >
              Order Sekarang
            </Link>
          </div>

          <button
            className="lg:hidden p-2 text-gray-700 hover:text-amber-600 transition-colors"
            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
            aria-expanded={mobileMenuOpen}
            aria-controls="mobile-menu"
            aria-label="Toggle menu"
          >
            <svg
              className="w-6 h-6"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              aria-hidden="true"
            >
              {mobileMenuOpen ? (
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M6 18L18 6M6 6l12 12"
                />
              ) : (
                <path
                  strokeLinecap="round"
                  strokeLinejoin="round"
                  strokeWidth={2}
                  d="M4 6h16M4 12h16M4 18h16"
                />
              )}
            </svg>
          </button>
        </div>

        <div
          id="mobile-menu"
          className={`lg:hidden overflow-hidden transition-all duration-300 ease-in-out ${
            mobileMenuOpen ? 'max-h-96 opacity-100 pb-6' : 'max-h-0 opacity-0'
          }`}
          role="navigation"
          aria-label="Mobile navigation"
        >
          <div className="flex flex-col gap-2 pt-4">
            {navLinks.map((link) => (
              <Link
                key={link.path}
                to={link.path}
                className={`px-4 py-3 rounded-lg text-base font-medium transition-all ${
                  location.pathname === link.path
                    ? 'bg-amber-50 text-amber-500'
                    : 'text-gray-700 hover:bg-amber-50 hover:text-amber-500'
                }`}
                onClick={() => setMobileMenuOpen(false)}
              >
                {link.label}
              </Link>
            ))}
            <Link
              to="/products"
              className="mx-4 mt-2 px-5 py-3 bg-amber-500 text-white font-medium rounded-full text-center hover:bg-amber-500 transition-all"
              onClick={() => setMobileMenuOpen(false)}
            >
              Order Sekarang
            </Link>
          </div>
        </div>
      </div>
    </nav>
  );
}

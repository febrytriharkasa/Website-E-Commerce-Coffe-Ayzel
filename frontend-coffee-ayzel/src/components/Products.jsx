import { useState, useEffect, useMemo } from 'react';
import { motion } from 'framer-motion';
import { ShoppingCart } from 'lucide-react';
import { useCart } from '../context/CartContext';
import { formatCurrency } from '../context/cartUtils';

function tagColor(tag) {
  const map = { 'Best Seller': 'bg-red-500', Favorit: 'bg-pink-500', New: 'bg-emerald-500', Limited: 'bg-violet-500', Premium: 'bg-gray-800' };
  return map[tag] || 'bg-amber-500';
}

export default function Products() {
  const { products, cart, addToCart, removeFromCart, getCartKey } = useCart();
  const [selectedSizes, setSelectedSizes] = useState({});
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('Semua');
  const [showBackToTop, setShowBackToTop] = useState(false);

  useEffect(() => {
    const handleScroll = () => setShowBackToTop(window.scrollY > 400);
    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const filteredProducts = useMemo(() => {
    return products
      .filter((p) => p.stok > 0)
      .filter((p) => {
        const matchSearch = !searchTerm.trim() || p.name.toLowerCase().includes(searchTerm.toLowerCase());
        const matchCategory = selectedCategory === 'Semua' || p.jenis?.toLowerCase() === selectedCategory.toLowerCase();
        return matchSearch && matchCategory;
      });
  }, [products, searchTerm, selectedCategory]);

  const categories = ['Semua', 'kopi', 'non-kopi'];

  return (
    <main className="pt-16 lg:pt-20">
      <section className="py-16 md:py-24 bg-gradient-to-b from-amber-50 to-white">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="text-center mb-16"
          >
            <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">Pilih Kopi <span className="text-amber-400">Favoritmu</span></h1>
            <p className="text-lg md:text-xl text-amber-400 font-bold max-w-3xl mx-auto mb-2 leading-relaxed">All Your Zero-Stress Everyday Latte</p>
            <p className="text-gray-600 max-w-2xl mx-auto text-lg">Pilih beberapa varian sekaligus, atur jumlah, lalu pesan langsung via WhatsApp!</p>
          </motion.div>

          <div className="flex flex-col lg:flex-row gap-8 items-start">
            <aside className="w-full lg:w-64 lg:sticky lg:top-24 bg-white/80 backdrop-blur-sm p-5 rounded-2xl border border-amber-100 shadow-sm shrink-0">
              <div className="mb-6">
                <label htmlFor="search-product" className="block text-sm font-bold text-gray-900 mb-2">
                  Cari Produk
                </label>
                <div className="relative">
                  <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg className="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2.5} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </div>
                  <input
                    id="search-product"
                    type="text"
                    placeholder="Nama produk..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 focus:ring-2 focus:ring-amber-500 focus:border-amber-500 outline-none bg-white text-sm font-medium placeholder:text-gray-400 transition-all"
                  />
                </div>
              </div>

              <div>
                <h2 className="text-sm font-bold text-gray-900 mb-3">Kategori Produk</h2>
                <div className="flex flex-row lg:flex-col gap-2 overflow-x-auto pb-1 lg:pb-0">
                  {categories.map((cat) => {
                    const isActive = selectedCategory === cat;
                    return (
                      <button
                        key={cat}
                        type="button"
                        onClick={() => setSelectedCategory(cat)}
                        className={`min-h-[44px] px-4 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 flex items-center justify-between gap-3 text-left whitespace-nowrap capitalize ${
                          isActive
                            ? 'bg-amber-500 text-gray-900 shadow-md'
                            : 'bg-amber-50/50 text-gray-900 hover:bg-amber-100/60'
                        }`}
                      >
                        {cat}
                      </button>
                    );
                  })}
                </div>
              </div>
            </aside>

            <div className="flex-1 w-full">
              <div className="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 gap-4 sm:gap-6">
                {filteredProducts.map((product) => {
                  const currentSize = selectedSizes[product.id] || product.sizes[0];
                  const currentPrice = product.prices[currentSize];
                  const originalPrice = product.originalPrices?.[currentSize];
                  const hasDiscount = originalPrice && currentPrice < originalPrice;
                  const maxStock = product.stocks?.[currentSize] || 0;
                  const key = getCartKey(product.id, currentSize);
                  const qty = cart[key] || 0;

                  return (
                    <motion.div
                      key={product.id}
                      initial={{ opacity: 0, y: 20 }}
                      whileInView={{ opacity: 1, y: 0 }}
                      viewport={{ once: true }}
                      transition={{ duration: 0.3 }}
                      className="group bg-white rounded-2xl shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden flex flex-col border border-gray-100"
                    >
                      <div className={`aspect-square w-full bg-gradient-to-br ${product.color} flex items-center justify-center relative rounded-t-2xl shrink-0 overflow-hidden`}>
                        <span className="absolute top-2.5 right-2.5 z-10 bg-amber-500 text-gray-900 text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm capitalize">
                          {product.jenis}
                        </span>
                        {product.tag && (
                          <span className={`absolute top-9 right-2.5 z-10 ${tagColor(product.tag)} text-white text-[10px] font-bold px-2.5 py-1 rounded-full shadow-sm`}>
                            {product.tag}
                          </span>
                        )}
                        {product.image ? (
                          <img
                            src={product.image}
                            alt={product.name}
                            width="400"
                            height="400"
                            className="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                            loading="lazy"
                          />
                        ) : (
                          <svg className="w-16 h-16 text-white/30" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18.5 3H6c-1.1 0-2 .9-2 2v5.71c0 3.83 2.95 7.18 6.78 7.29 3.96.12 7.22-3.06 7.22-7v-1h.5c1.93 0 3.5-1.57 3.5-3.5S20.43 3 18.5 3zM16 5v3H6V5h10zm2.5 3H18V5h.5c.83 0 1.5.67 1.5 1.5S19.33 8 18.5 8zM4 19h16v2H4v-2z" />
                          </svg>
                        )}
                      </div>
                      <div className="p-4 flex flex-col flex-1">
                        <h3 className="text-sm sm:text-base md:text-lg font-bold text-gray-900 leading-tight mb-1">
                          {product.name}
                        </h3>
                        <p className="text-gray-600 text-xs leading-snug min-h-[2.5rem] mb-3">
                          {product.desc}
                        </p>
                        <div className="mt-auto pt-2">
                          <div className="flex flex-wrap gap-1.5 mb-3" role="group" aria-label={`Pilihan ukuran ${product.name}`}>
                            {product.sizes.map((sizeOption, idx) => (
                              <button
                                key={idx}
                                type="button"
                                onClick={() => setSelectedSizes((prev) => ({ ...prev, [product.id]: sizeOption }))}
                                aria-label={`Pilih ukuran ${sizeOption}`}
                                aria-pressed={currentSize === sizeOption}
                                className={`min-h-[44px] min-w-[40px] text-xs px-2.5 py-1 rounded-lg font-semibold transition-colors ${
                                  currentSize === sizeOption
                                    ? 'bg-amber-500 text-gray-900 shadow-sm'
                                    : 'bg-gray-100 text-gray-900 hover:bg-gray-200'
                                }`}
                              >
                                {sizeOption}
                              </button>
                            ))}
                          </div>
                          <div className="flex flex-col xl:flex-row xl:items-center justify-between gap-3 pt-3 border-t border-gray-100">
                            <div>
                              <span className="text-[10px] text-gray-500 block uppercase font-medium">Harga</span>
                              {hasDiscount && (
                                <span className="text-xs text-gray-400 line-through block">{formatCurrency(originalPrice)}</span>
                              )}
                              <p className="text-sm sm:text-base md:text-lg font-bold text-amber-700 truncate">
                                {formatCurrency(currentPrice)}
                              </p>
                              <span className={`text-[10px] font-medium ${maxStock > 0 ? 'text-emerald-600' : 'text-red-500'}`}>
                                {maxStock > 0 ? `Sisa: ${maxStock}` : 'Habis'}
                              </span>
                            </div>

                            <div className="shrink-0 w-full xl:w-auto mt-1 xl:mt-0">
                              {qty === 0 ? (
                                <button
                                  type="button"
                                  onClick={() => addToCart(product.id, currentSize, maxStock)}
                                  disabled={maxStock === 0}
                                  aria-label={`Tambah ${product.name} ${currentSize} ke keranjang`}
                                  className="w-full xl:w-auto min-h-[44px] bg-amber-500 text-white font-medium rounded-full hover:bg-amber-600 transition-all duration-200 active:scale-95 flex items-center justify-center shadow-sm gap-2 py-2.5 px-6 disabled:bg-gray-300 disabled:cursor-not-allowed cursor-pointer"
                                >
                                  <ShoppingCart className="w-5 h-5" />
                                </button>
                              ) : (
                                <div className="flex items-center justify-between xl:justify-center gap-2 bg-amber-50 px-2 py-1.5 rounded-full border border-amber-200 w-full xl:w-auto">
                                  <button
                                    type="button"
                                    onClick={() => removeFromCart(product.id, currentSize)}
                                    className="w-9 h-9 rounded-full bg-white text-amber-700 font-bold hover:bg-amber-100 transition-colors flex items-center justify-center text-lg shadow-sm active:scale-95 cursor-pointer"
                                    aria-label={`Kurangi jumlah ${product.name} ${currentSize}`}
                                  >
                                    -
                                  </button>
                                  <span className="min-w-[32px] px-1 text-center font-bold text-gray-900 text-base" aria-live="polite">
                                    {qty}
                                  </span>
                                  <button
                                    type="button"
                                    onClick={() => addToCart(product.id, currentSize, maxStock)}
                                    disabled={qty >= maxStock}
                                    className="w-9 h-9 rounded-full bg-amber-600 text-white font-bold hover:bg-amber-700 transition-colors flex items-center justify-center text-lg shadow-sm active:scale-95 disabled:bg-gray-300 disabled:cursor-not-allowed cursor-pointer"
                                    aria-label={`Tambah jumlah ${product.name} ${currentSize}`}
                                  >
                                    +
                                  </button>
                                </div>
                              )}
                            </div>
                          </div>
                        </div>
                      </div>
                    </motion.div>
                  );
                })}
              </div>
              {filteredProducts.length === 0 && products.length > 0 && (
                <div className="text-center bg-white rounded-2xl p-12 border border-gray-100 shadow-sm my-6">
                  <p className="text-gray-700 text-base font-medium">
                    Tidak ada produk ditemukan untuk &ldquo;{searchTerm}&rdquo;.
                  </p>
                </div>
              )}
            </div>
          </div>
        </div>
      </section>

      {showBackToTop && (
        <button
          onClick={() => window.scrollTo({ top: 0, behavior: 'smooth' })}
          className="fixed bottom-6 left-6 z-40 flex items-center justify-center w-11 h-11 bg-white text-gray-900 border border-gray-200 rounded-full shadow-lg hover:bg-gray-50 transition-all duration-300 hover:scale-105 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500 cursor-pointer"
          aria-label="Kembali ke atas"
        >
          <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 15l7-7 7 7" />
          </svg>
        </button>
      )}
    </main>
  );
}

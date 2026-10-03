import { useState, useEffect, useMemo, useCallback } from 'react';
import { motion } from 'framer-motion';
import { ShoppingCart, Loader2 } from 'lucide-react';
import { getProductsFromAPI, createTransaction, getSosialMediaAPI } from '../api/api';

const CART_STORAGE_KEY = 'ayzel_cart';

function formatCurrency(n) {
  if (n === null || n === undefined || isNaN(n)) return 'Segera Hadir';
  return 'Rp ' + n.toLocaleString('id-ID');
}

function tagColor(tag) {
  const map = { 'Best Seller': 'bg-red-500', Favorit: 'bg-pink-500', New: 'bg-emerald-500', Limited: 'bg-violet-500', Premium: 'bg-gray-800' };
  return map[tag] || 'bg-amber-500';
}

function loadCart() {
  try {
    const stored = localStorage.getItem(CART_STORAGE_KEY);
    return stored ? JSON.parse(stored) : {};
  } catch {
    return {};
  }
}

function saveCart(cart) {
  try {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
  } catch {
    // Silently fail
  }
}

export default function Products() {
  const [products, setProducts] = useState([]);
  const [cart, setCart] = useState(loadCart);
  const [showCart, setShowCart] = useState(false);
  const [selectedSizes, setSelectedSizes] = useState({});
  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('Semua');
  const [showBackToTop, setShowBackToTop] = useState(false);
  const [waNumber, setWaNumber] = useState('');
  const [isCheckoutLoading, setIsCheckoutLoading] = useState(false);

  const getCartKey = useCallback((id, size) => `${id}|${size}`, []);

  useEffect(() => {
    getProductsFromAPI().then(setProducts).catch(console.error);
    getSosialMediaAPI().then(data => {
      if (data?.whatsapp) setWaNumber(data.whatsapp);
    }).catch(console.error);
  }, []);

  useEffect(() => {
    saveCart(cart);
  }, [cart]);

  useEffect(() => {
    const handleScroll = () => setShowBackToTop(window.scrollY > 400);
    window.addEventListener('scroll', handleScroll, { passive: true });
    return () => window.removeEventListener('scroll', handleScroll);
  }, []);

  const addToCart = useCallback((id, size, maxStock) => {
    const key = getCartKey(id, size);
    setCart((prev) => {
      const currentQty = prev[key] || 0;
      if (currentQty < maxStock) return { ...prev, [key]: currentQty + 1 };
      return prev;
    });
  }, [getCartKey]);

  const removeFromCart = useCallback((id, size) => {
    setCart((prev) => {
      const next = { ...prev };
      const key = getCartKey(id, size);
      if (next[key] > 1) { next[key] -= 1; } else { delete next[key]; }
      return next;
    });
  }, [getCartKey]);

  const deleteFromCart = useCallback((id, size) => {
    setCart((prev) => {
      const next = { ...prev };
      delete next[getCartKey(id, size)];
      return next;
    });
  }, [getCartKey]);

  const totalItems = useMemo(() => Object.values(cart).reduce((a, b) => a + b, 0), [cart]);

  const totalPrice = useMemo(() => {
    return Object.entries(cart).reduce((sum, [key, qty]) => {
      const [idStr, size] = key.split('|');
      const p = products.find((pr) => String(pr.id) === idStr);
      const price = p ? (p.prices[size] || 0) : 0;
      return sum + price * qty;
    }, 0);
  }, [cart, products]);

  const cartItems = useMemo(() => {
    return Object.entries(cart)
      .map(([key, qty]) => {
        const [idStr, size] = key.split('|');
        const p = products.find((pr) => String(pr.id) === idStr);
        if (!p) return null;
        return { ...p, qty, displaySize: size, price: p.prices[size] || 0 };
      })
      .filter(Boolean);
  }, [cart, products]);

  const sendToWhatsApp = useCallback(async () => {
    if (cartItems.length === 0) return;
    setIsCheckoutLoading(true);

    const payload = {
      kode_transaksi: `TRX-${Date.now()}`,
      total_pembayaran: totalPrice,
      items: cartItems.map((item) => ({
        size_product_id: item.variantIds[item.displaySize],
        qty: item.qty,
        harga_modal: item.modalPrices[item.displaySize],
        harga_satuan: item.price,
      })),
    };

    try {
      await createTransaction(payload);
      let message = 'Halo Ayzel Coffee! Saya mau pesan:\n\n';
      cartItems.forEach((item) => {
        message += `- ${item.name} (${item.displaySize}) x${item.qty} = ${formatCurrency(item.price * item.qty)}\n`;
      });
      message += `\nTotal: ${formatCurrency(totalPrice)}`;
      message += '\n\nMohon konfirmasi ketersediaan dan ongkir. Terima kasih!';
      window.open(`https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`, '_blank');
      setCart({});
      setShowCart(false);
      const newData = await getProductsFromAPI();
      setProducts(newData);
    } catch (error) {
      alert('Gagal membuat transaksi: ' + error.message);
    } finally {
      setIsCheckoutLoading(false);
    }
  }, [cartItems, totalPrice, waNumber]);

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
                          <div className="flex items-center justify-between gap-2 pt-1 border-t border-gray-100">
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
                            <div>
                              {qty === 0 ? (
                                <button
                                  type="button"
                                  onClick={() => addToCart(product.id, currentSize, maxStock)}
                                  disabled={maxStock === 0}
                                  aria-label={`Tambah ${product.name} ${currentSize} ke keranjang`}
                                  className="min-w-[44px] min-h-[44px] px-3.5 py-2 bg-amber-500 text-white font-medium rounded-full hover:bg-amber-600 transition-all duration-200 active:scale-95 flex items-center justify-center shadow-sm disabled:bg-gray-300 disabled:cursor-not-allowed"
                                >
                                  <ShoppingCart className="w-5 h-5" />
                                </button>
                              ) : (
                                <div className="flex items-center gap-1 bg-amber-50 p-1 rounded-full border border-amber-200">
                                  <button
                                    type="button"
                                    onClick={() => removeFromCart(product.id, currentSize)}
                                    className="w-9 h-9 min-w-[36px] min-h-[36px] rounded-full bg-white text-amber-700 font-bold hover:bg-amber-100 transition-colors flex items-center justify-center text-sm shadow-sm"
                                    aria-label={`Kurangi jumlah ${product.name} ${currentSize}`}
                                  >
                                    -
                                  </button>
                                  <span className="w-6 text-center font-bold text-gray-900 text-xs" aria-live="polite">
                                    {qty}
                                  </span>
                                  <button
                                    type="button"
                                    onClick={() => addToCart(product.id, currentSize, maxStock)}
                                    disabled={qty >= maxStock}
                                    className="w-9 h-9 min-w-[36px] min-h-[36px] rounded-full bg-amber-600 text-white font-bold hover:bg-amber-700 transition-colors flex items-center justify-center text-sm shadow-sm disabled:bg-gray-300 disabled:cursor-not-allowed"
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
          className="fixed bottom-6 left-6 z-40 flex items-center justify-center w-11 h-11 bg-white text-gray-900 border border-gray-200 rounded-full shadow-lg hover:bg-gray-50 transition-all duration-300 hover:scale-105 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-500"
          aria-label="Kembali ke atas"
        >
          <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 15l7-7 7 7" />
          </svg>
        </button>
      )}

      {totalItems > 0 && (
        <button onClick={() => setShowCart(true)} className="fixed bottom-6 right-6 z-40 flex items-center gap-3 px-6 py-4 bg-amber-600 text-white font-semibold rounded-full shadow-2xl hover:bg-amber-700 transition-all duration-300 hover:scale-105">
          <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0020 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" /></svg>
          <span>{totalItems} item</span>
          <span className="hidden sm:inline">{formatCurrency(totalPrice)}</span>
        </button>
      )}

      {showCart && (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-label="Keranjang pesanan">
          <div className="absolute inset-0 bg-black/50 backdrop-blur-sm" onClick={() => setShowCart(false)} />
          <div className="relative bg-white w-full max-w-lg mx-4 rounded-3xl shadow-2xl max-h-[80vh] flex flex-col">
            <div className="flex items-center justify-between p-6 border-b border-gray-100">
              <h2 className="text-xl font-bold text-gray-900">Keranjang Pesanan</h2>
              <button onClick={() => setShowCart(false)} className="p-2 hover:bg-gray-100 rounded-full transition-colors" aria-label="Tutup keranjang">
                <svg className="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
              </button>
            </div>

            <div className="flex-1 overflow-y-auto p-6 space-y-4">
              {cartItems.length === 0 ? (
                <p className="text-center text-gray-500 py-8">Keranjang kosong</p>
              ) : (
                cartItems.map((item) => (
                  <div key={`${item.id}-${item.displaySize}`} className="flex items-center gap-4 p-4 bg-gray-50 rounded-xl">
                    <div className={`w-14 h-14 rounded-xl bg-gradient-to-br ${item.color} flex items-center justify-center flex-shrink-0 overflow-hidden`}>
                      {item.image ? (
                        <img src={item.image} alt={item.name} className="w-full h-full object-cover" loading="lazy"/>
                      ) : (
                        <svg className="w-7 h-7 text-white/50" fill="currentColor" viewBox="0 0 24 24"><path d="M18.5 3H6c-1.1 0-2 .9-2 2v5.71c0 3.83 2.95 7.18 6.78 7.29 3.96.12 7.22-3.06 7.22-7v-1h.5c1.93 0 3.5-1.57 3.5-3.5S20.43 3 18.5 3z" /></svg>
                      )}
                    </div>
                    <div className="flex-1 min-w-0">
                      <h3 className="font-semibold text-gray-900 text-sm">{item.name}</h3>
                      <p className="text-xs text-gray-500">{item.displaySize}</p>
                      <p className="text-amber-700 font-bold text-sm">{formatCurrency(item.price * item.qty)}</p>
                    </div>
                    <div className="flex items-center gap-2">
                      <button onClick={() => removeFromCart(item.id, item.displaySize)} className="w-7 h-7 rounded-full bg-amber-100 text-amber-700 font-bold hover:bg-amber-200 transition-colors flex items-center justify-center text-sm">-</button>
                      <span className="w-6 text-center font-bold text-sm">{item.qty}</span>
                      <button onClick={() => addToCart(item.id, item.displaySize, item.stocks?.[item.displaySize] || 0)} className="w-7 h-7 rounded-full bg-amber-600 text-white font-bold hover:bg-amber-700 transition-colors flex items-center justify-center text-sm">+</button>
                    </div>
                    <button onClick={() => deleteFromCart(item.id, item.displaySize)} className="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all" aria-label={`Hapus ${item.name}`}>
                      <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" /></svg>
                    </button>
                  </div>
                ))
              )}
            </div>

            <div className="p-6 border-t border-gray-100 bg-gray-50 rounded-b-3xl">
              <div className="p-3 mb-4 bg-amber-100/50 text-amber-800 text-xs sm:text-sm rounded-xl text-center font-medium border border-amber-200/50">
                Ketersediaan stok akan dikonfirmasi via WhatsApp.
              </div>
              <div className="flex justify-between items-center mb-4">
                <span className="text-gray-600 font-medium">Total ({totalItems} item)</span>
                <span className="text-2xl font-bold text-amber-700">{formatCurrency(totalPrice)}</span>
              </div>
              <button
                onClick={sendToWhatsApp}
                disabled={isCheckoutLoading}
                className="w-full flex items-center justify-center gap-3 px-6 py-4 bg-green-500 text-white font-bold rounded-full hover:bg-green-600 transition-all duration-200 shadow-lg hover:shadow-xl active:scale-[0.98] mt-2 disabled:bg-gray-400 disabled:cursor-not-allowed"
              >
                {isCheckoutLoading ? (
                  <>
                    <Loader2 className="w-6 h-6 animate-spin" />
                    Memproses...
                  </>
                ) : (
                  <>
                    <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" /></svg>
                    Pesan via WhatsApp
                  </>
                )}
              </button>
            </div>
          </div>
        </div>
      )}
    </main>
  );
}

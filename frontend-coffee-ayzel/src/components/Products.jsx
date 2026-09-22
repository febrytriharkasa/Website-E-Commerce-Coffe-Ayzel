import { useEffect, useState } from 'react';
import { motion, AnimatePresence } from 'framer-motion';
import { Element } from 'react-scroll';
import { ShoppingCart, Search } from 'lucide-react';
import { getProductsFromAPI } from '../api/api';

const WA_NUMBER = '6285829211582';

const fadeUpVariant = {
  hidden: { opacity: 0, y: 30 },
  visible: (custom = 0) => ({
    opacity: 1,
    y: 0,
    transition: { duration: 0.5, delay: custom, ease: 'easeOut' },
  }),
};

function formatCurrency(n) {
  return 'Rp ' + n.toLocaleString('id-ID');
}

function tagColor(tag) {
  const map = {
    'Best Seller': 'bg-red-500',
    Favorit: 'bg-pink-500',
    New: 'bg-emerald-500',
    Limited: 'bg-violet-500',
    Premium: 'bg-gray-800',
  };
  return map[tag] || 'bg-amber-500';
}

export default function Products() {
  const [products, setProducts] = useState([]);
  const [cart, setCart] = useState({});
  const [showCart, setShowCart] = useState(false);
  const [selectedSizes, setSelectedSizes] = useState({});
  const [search, setSearch] = useState('');

  useEffect(() => {
    const fetchData = async () => {
      const data = await getProductsFromAPI();
      setProducts(data);
    };
    fetchData();
  }, []);

  const getCartKey = (id, size) => `${id}|${size}`;

  const addToCart = (id, size, maxStock) => {
    const key = getCartKey(id, size);
    setCart((prev) => {
      const currentQty = prev[key] || 0;
      // Hanya tambah jika qty saat ini masih di bawah stok maksimal
      if (currentQty < maxStock) {
        return { ...prev, [key]: currentQty + 1 };
      }
      return prev; // Jika sudah mentok, kembalikan state apa adanya
    });
  };

  const removeFromCart = (id, size) => {
    const key = getCartKey(id, size);
    setCart((prev) => {
      const next = { ...prev };
      if (next[key] > 1) {
        next[key] -= 1;
      } else {
        delete next[key];
      }
      return next;
    });
  };

  const deleteFromCart = (id, size) => {
    const key = getCartKey(id, size);
    setCart((prev) => {
      const next = { ...prev };
      delete next[key];
      return next;
    });
  };

  const totalItems = Object.values(cart).reduce((a, b) => a + b, 0);

  // Perhitungan total harga menyesuaikan p.prices[size]
  const totalPrice = Object.entries(cart).reduce((sum, [key, qty]) => {
    const [idStr, size] = key.split('|');
    const p = products.find((pr) => String(pr.id) === String(idStr));
    const price = p ? (p.prices[size] || 0) : 0;
    return sum + price * qty;
   }, 0);

   // Produk yang ditampilkan setelah filter stok dan pencarian nama
   const filteredProducts = products
     .filter((product) => product.stok > 0)
     .filter((product) =>
       product.name.toLowerCase().includes(search.trim().toLowerCase())
     );


  // Menyiapkan item keranjang dengan harga sesuai varian ukuran
  const cartItems = Object.entries(cart)
    .map(([key, qty]) => {
      const [idStr, size] = key.split('|');
      const p = products.find((pr) => String(pr.id) === String(idStr));
      if (!p) return null;
      const unitPrice = p.prices[size] || 0;
      return { ...p, qty, displaySize: size, price: unitPrice };
    })
    .filter(Boolean);

  const sendToWhatsApp = () => {
    if (cartItems.length === 0) return;
    let message = 'Halo Coffee Ayzel! Saya mau pesan:\n\n';
    cartItems.forEach((item) => {
      message += `- ${item.name} (${item.displaySize}) x${item.qty} = ${formatCurrency(item.price * item.qty)}\n`;
    });
    message += `\nTotal: ${formatCurrency(totalPrice)}`;
    message += '\n\nMohon konfirmasi ketersediaan dan ongkir. Terima kasih!';
    const url = `https://wa.me/${WA_NUMBER}?text=${encodeURIComponent(message)}`;
    window.open(url, '_blank');
  };

  return (
    <Element name="products">
      <main className="pt-16 lg:pt-20">
        <section className="py-16 md:py-24 bg-gradient-to-b from-amber-50 to-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <motion.div
              className="text-center mb-16"
              initial="hidden"
              whileInView="visible"
              viewport={{ once: true, amount: 0.3 }}
              variants={fadeUpVariant}
            >
              <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Pilih Kopi <span className="text-amber-500">Favoritmu</span>
              </h1>
              <div className="text-gray-600 max-w-2xl mx-auto text-lg">
                <p className="text-lg md:text-xl bg-gradient-to-r from-amber-200 via-yellow-400 to-yellow-600 bg-clip-text text-transparent font-bold max-w-3xl mx-auto mb-5 leading-relaxed">
                All Your Zero-Stress Everyday Latte
                </p>
                <p>
                  Pilih beberapa varian sekaligus, atur jumlah, lalu pesan langsung via WhatsApp!
                </p>
              </div>
            </motion.div>

          <div className="relative mb-6 max-w-md mx-auto">
            <label htmlFor="search-products" className="sr-only">Cari produk</label>
            <input
              id="search-products"
              type="text"
              placeholder="Cari nama produk..."
              value={search}
              onChange={(e) => setSearch(e.target.value)}
              className="w-full rounded-full border border-gray-300 bg-white py-3 pl-11 pr-10 text-sm shadow-sm outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-200"
            />
            <Search className="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
          </div>

          <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6 mr-4 ml-4">
            {filteredProducts.map((product, idx) => {
              const currentSize = selectedSizes[product.id] || product.sizes[0];
              const currentPrice = currentSize ? product.prices[currentSize] : 0;
              // Tambahkan 2 variabel baru ini di bawahnya:
              const originalPrice = currentSize && product.originalPrices ? product.originalPrices[currentSize] : currentPrice;
              const hasDiscount = currentPrice < originalPrice; // Cek apakah ada diskon
              const key = getCartKey(product.id, currentSize);
              const qty = cart[key] || 0;
              const maxStock = product.stocks ? product.stocks[currentSize] : 0;

              return (
                  <motion.div
                    key={product.id}
                    className="group bg-white rounded-xl shadow-md hover:shadow-lg transition-all duration-300 overflow-visible hover:-translate-y-1 hover:scale-105 flex flex-col"
                    initial="hidden"
                    whileInView="visible"
                    viewport={{ once: true, amount: 0.2 }}
                    custom={0.1 * (idx % 4)}
                    variants={fadeUpVariant}
                  >
                    <div className={`h-36 sm:h-40 bg-gradient-to-br ${product.color} flex items-center justify-center relative rounded-t-xl shrink-0`}>
                      {product.tag && (
                        <span className={`absolute -top-3 -right-3 z-0 ${tagColor(product.tag)} text-white text-xs font-bold px-3 py-1 rounded-full`}>
                          {product.tag}
                        </span>
                      )}
                      {product.image ? (
                        <img
                          src={product.image}
                          alt={product.name}
                          className="w-full h-full object-cover overflow-hidden rounded-t-xl" loading="lazy"
                        />
                      ) : (
                        <svg className="w-14 h-14 sm:w-16 sm:h-16 text-white/30" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M18.5 3H6c-1.1 0-2 .9-2 2v5.71c0 3.83 2.95 7.18 6.78 7.29 3.96.12 7.22-3.06 7.22-7v-1h.5c1.93 0 3.5-1.57 3.5-3.5S20.43 3 18.5 3zM16 5v3H6V5h10zm2.5 3H18V5h.5c.83 0 1.5.67 1.5 1.5S19.33 8 18.5 8zM4 19h16v2H4v-2z" />
                        </svg>
                      )}
                    </div>
                    
                    <div className="p-4 flex flex-col flex-1">
                      <h3 className="text-sm sm:text-base md:text-lg lg:text-xl font-bold text-gray-900 leading-tight flex items-start mb-1.5">
                        {product.name}
                      </h3>
                      <p className="text-gray-600 text-[9px] sm:text-[10px] leading-snug min-h-[2.5rem]">
                        {product.desc}
                      </p>

                      <div className="mt-auto pt-3">
                        <div className="flex flex-wrap gap-1 mb-2">
                          {product.sizes.map((sizeOption, idx) => (
                            <button
                              key={idx}
                              type="button"
                              onClick={() => setSelectedSizes((prev) => ({ ...prev, [product.id]: sizeOption }))}
                              className={`text-xs px-3 py-1.5 rounded-full font-semibold transition-colors ${
                                currentSize === sizeOption
                                  ? 'bg-amber-500 text-white shadow-sm'
                                  : 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                              }`}
                            >
                              {sizeOption} L
                            </button>
                          ))}
                        </div>

                        {/* HTML Tampilan Harga Sebelumnya */}
                        <div className="flex items-center justify-between pt-1">
                          <div className="flex flex-col">
                            <span className={`text-[10px] sm:text-xs font-medium mb-1 ${maxStock > 0 ? 'text-emerald-600' : 'text-red-500'}`}>
                              {maxStock > 0 ? `Sisa Stok: ${maxStock}` : 'Stok Habis'}
                            </span>
                            {/* Tampilkan Harga Coret (Jika ada diskon) */}
                            {hasDiscount && (
                              <span className="text-xs text-gray-400 line-through">
                                {formatCurrency(originalPrice)}
                              </span>
                            )}
                            {/* Tampilkan Harga Final / Diskon */}
                            <p className="text-sm sm:text-base md:text-lg lg:text-xl font-bold text-amber-700 truncate leading-none">
                              {formatCurrency(currentPrice)}
                            </p>
                          </div>
                        </div>

                        <div className="flex justify-end mt-4">
                          {qty === 0 ? (
                            <button
                              // Masukkan maxStock ke fungsi
                              onClick={() => addToCart(product.id, currentSize, maxStock)} 
                              disabled={maxStock === 0} // Disable jika stok varian ini 0
                              className={`flex items-center justify-center w-26 px-3 py-2.5 text-white text-xs font-medium rounded-full transition-all duration-200 active:scale-95 ${maxStock === 0 ? 'bg-gray-400 cursor-not-allowed' : 'bg-amber-500 hover:bg-amber-700'}`}
                            >
                              <ShoppingCart className="w-4 h-4" />
                              {maxStock === 0 && <span className="ml-2">Habis</span>}
                            </button>
                          ) : (
                            <div className="flex items-center gap-1.5">
                              {/* ... tombol kurang (-) tetap sama ... */}
                              <button
                                onClick={() => removeFromCart(product.id, currentSize, maxStock)}
                                disabled={qty >= maxStock} // Disable tombol + jika qty sudah sama dengan stok
                                className={`w-7 h-7 rounded-full text-white font-bold transition-colors flex items-center justify-center text-xs ${qty >= maxStock ? 'bg-gray-400 cursor-not-allowed' : 'bg-amber-600 hover:bg-amber-700'}`}
                              >
                                -
                              </button>
                              <span className="w-6 text-center font-bold text-sm">{qty}</span>
                              <button
                                onClick={() => addToCart(product.id, currentSize, maxStock)}
                                disabled={qty >= maxStock} // Disable tombol + jika qty sudah sama dengan stok
                                className={`w-7 h-7 rounded-full text-white font-bold transition-colors flex items-center justify-center text-xs ${qty >= maxStock ? 'bg-gray-400 cursor-not-allowed' : 'bg-amber-600 hover:bg-amber-700'}`}
                              >
                                +
                              </button>
                            </div>
                          )}
                        </div>
                      </div>
</div>
                    </motion.div>
                );
              })}
          </div>
        </div>
      </section>

      {totalItems > 0 && (
        <button
          onClick={() => setShowCart(true)}
          className="fixed bottom-6 right-6 z-40 flex items-center gap-3 px-6 py-4 bg-amber-600 text-white font-semibold rounded-full shadow-2xl hover:bg-amber-700 transition-all duration-300 hover:scale-105 animate-bounce-in"
        >
          <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49A1 1 0 0020 4H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z" />
          </svg>
          <span>{totalItems} item</span>
          <span className="hidden sm:inline">{formatCurrency(totalPrice)}</span>
        </button>
      )}

      <AnimatePresence>
        {showCart && (
          <motion.div
            className="fixed inset-0 z-50 flex items-center justify-center p-4"
            role="dialog"
            aria-modal="true"
            aria-label="Keranjang pesanan"
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
          >
            <div
              className="absolute inset-0 bg-black/50 backdrop-blur-sm"
              onClick={() => setShowCart(false)}
            />
            <motion.div
              className="relative bg-white w-full max-w-lg mx-4 rounded-3xl shadow-2xl max-h-[80vh] flex flex-col"
              initial={{ opacity: 0, scale: 0.95, y: 20 }}
              animate={{ opacity: 1, scale: 1, y: 0 }}
              exit={{ opacity: 0, scale: 0.95, y: 20 }}
              transition={{ duration: 0.2 }}
            >
            <div className="flex items-center justify-between p-6 border-b border-gray-100">
              <h2 className="text-xl font-bold text-gray-900">
                Keranjang Pesanan
              </h2>
              <button
                onClick={() => setShowCart(false)}
                className="p-2 hover:bg-gray-100 rounded-full transition-colors"
                aria-label="Tutup keranjang"
              >
                <svg className="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div className="flex-1 overflow-y-auto p-6 space-y-4">
              {cartItems.map((item) => { 
                const maxStock = item.stocks ? item.stocks[item.displaySize] : 0;
                
                return (

                  <div
                    key={`${item.id}-${item.displaySize}`}
                    className="flex items-center gap-4 p-4 bg-gray-50 rounded-xl"
                  >
                    <div className={`w-14 h-14 rounded-xl bg-gradient-to-br ${item.color} flex items-center justify-center flex-shrink-0 overflow-hidden`}>
                      {item.image ? (
                        <img
                          src={item.image}
                          alt={item.name}
                          className="w-full h-full object-cover"
                        />
                      ) : (
                        <svg className="w-7 h-7 text-white/50" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M18.5 3H6c-1.1 0-2 .9-2 2v5.71c0 3.83 2.95 7.18 6.78 7.29 3.96.12 7.22-3.06 7.22-7v-1h.5c1.93 0 3.5-1.57 3.5-3.5S20.43 3 18.5 3z" />
                        </svg>
                      )}
                    </div>
                    <div className="flex-1 min-w-0">
                      <h3 className="font-semibold text-gray-900 text-sm">
                        {item.name}
                      </h3>
                      <p className="text-xs text-gray-500">{item.displaySize}</p>
                      <p className="text-amber-700 font-bold text-sm">
                        {formatCurrency(item.price * item.qty)}
                      </p>
                    </div>
                    <div className="flex items-center gap-2">
                      <button
                        onClick={() => removeFromCart(item.id, item.displaySize)}
                        className="w-7 h-7 rounded-full bg-amber-100 text-amber-700 font-bold hover:bg-amber-200 transition-colors flex items-center justify-center text-sm"
                      >
                        -
                      </button>
                      <span className="w-6 text-center font-bold text-sm">{item.qty}</span>
                      <button
                        onClick={() => addToCart(item.id, item.displaySize, maxStock)}
                        disabled={item.qty >= maxStock}
                        className={`w-7 h-7 rounded-full text-white font-bold transition-colors flex items-center justify-center text-sm ${item.qty >= maxStock ? 'bg-gray-400 cursor-not-allowed' : 'bg-amber-600 hover:bg-amber-700'}`}
                      >
                        +
                      </button>
                    </div>
                    <button
                      onClick={() => deleteFromCart(item.id, item.displaySize)}
                      className="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all"
                      aria-label={`Hapus ${item.name}`}
                    >
                      <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                      </svg>
                    </button>
                  </div>
                );
              })}
            </div>

            <div className="p-6 border-t border-gray-100 bg-gray-50 rounded-b-3xl">
              <div className="flex justify-between items-center mb-4">
                <span className="text-gray-600 font-medium">Total ({totalItems} item)</span>
                <span className="text-2xl font-bold text-amber-700">
                  {formatCurrency(totalPrice)}
                </span>
              </div>
              <button
                onClick={sendToWhatsApp}
                className="w-full flex items-center justify-center gap-3 px-6 py-4 bg-green-500 text-white font-bold rounded-full hover:bg-green-600 transition-all duration-200 shadow-lg hover:shadow-xl active:scale-[0.98]"
              >
                <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                </svg>
                Pesan via WhatsApp
              </button>
            </div>
          </motion.div>
        </motion.div>
      )}
    </AnimatePresence>
  </main>
</Element>
  );
}

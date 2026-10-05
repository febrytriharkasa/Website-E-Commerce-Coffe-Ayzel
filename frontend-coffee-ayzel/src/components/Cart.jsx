import { useCart } from '../context/CartContext';
import { formatCurrency } from '../context/cartUtils';
import { ShoppingCart, Loader2 } from 'lucide-react';

export default function Cart() {
  const {
    showCart,
    setShowCart,
    totalItems,
    totalPrice,
    cartItems,
    addToCart,
    removeFromCart,
    deleteFromCart,
    sendToWhatsApp,
    isCheckoutLoading,
  } = useCart();

  return (
    <>
      {/* Floating Cart Button */}
      {totalItems > 0 && !showCart && (
        <button
          onClick={() => setShowCart(true)}
          className="fixed bottom-6 right-6 z-40 flex items-center gap-3 px-6 py-4 bg-amber-600 text-white font-semibold rounded-full shadow-2xl hover:bg-amber-700 transition-all duration-300 hover:scale-105 active:scale-95 cursor-pointer"
          aria-label="Buka keranjang pesanan"
        >
          <ShoppingCart className="w-6 h-6" />
          <span>{totalItems} item</span>
          <span className="hidden sm:inline font-bold">{formatCurrency(totalPrice)}</span>
        </button>
      )}

      {/* Cart Modal */}
      {showCart && (
        <div
          className="fixed inset-0 z-50 flex items-center justify-center p-4"
          role="dialog"
          aria-modal="true"
          aria-label="Keranjang pesanan"
        >
          <div
            className="absolute inset-0 bg-black/50 backdrop-blur-sm"
            onClick={() => setShowCart(false)}
          />
          <div className="relative bg-white w-full max-w-lg mx-4 rounded-3xl shadow-2xl max-h-[85vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200">
            <div className="flex items-center justify-between p-6 border-b border-gray-100">
              <div className="flex items-center gap-2">
                <ShoppingCart className="w-5 h-5 text-amber-600" />
                <h2 className="text-xl font-bold text-gray-900">Keranjang Pesanan</h2>
              </div>
              <button
                onClick={() => setShowCart(false)}
                className="p-2 hover:bg-gray-100 text-gray-500 rounded-full transition-colors cursor-pointer"
                aria-label="Tutup keranjang"
              >
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>

            <div className="flex-1 overflow-y-auto p-6 space-y-4">
              {cartItems.length === 0 ? (
                <div className="text-center py-12">
                  <div className="w-16 h-16 mx-auto mb-4 bg-amber-50 text-amber-500 rounded-full flex items-center justify-center">
                    <ShoppingCart className="w-8 h-8" />
                  </div>
                  <p className="text-gray-500 font-medium">Keranjang Anda masih kosong</p>
                </div>
              ) : (
                cartItems.map((item) => (
                  <div
                    key={`${item.id}-${item.displaySize}`}
                    className="flex items-center gap-4 p-4 bg-gray-50 rounded-2xl border border-gray-100/80"
                  >
                    <div
                      className={`w-14 h-14 rounded-xl bg-gradient-to-br ${item.color || 'from-amber-100 to-amber-200'} flex items-center justify-center shrink-0 overflow-hidden`}
                    >
                      {item.image ? (
                        <img
                          src={item.image}
                          alt={item.name}
                          className="w-full h-full object-cover"
                          loading="lazy"
                        />
                      ) : (
                        <svg className="w-7 h-7 text-white/50" fill="currentColor" viewBox="0 0 24 24">
                          <path d="M18.5 3H6c-1.1 0-2 .9-2 2v5.71c0 3.83 2.95 7.18 6.78 7.29 3.96.12 7.22-3.06 7.22-7v-1h.5c1.93 0 3.5-1.57 3.5-3.5S20.43 3 18.5 3zM16 5v3H6V5h10zm2.5 3H18V5h.5c.83 0 1.5.67 1.5 1.5S19.33 8 18.5 8zM4 19h16v2H4v-2z" />
                        </svg>
                      )}
                    </div>
                    <div className="flex-1 min-w-0">
                      <h3 className="font-semibold text-gray-900 text-sm truncate">{item.name}</h3>
                      <p className="text-xs text-gray-500">{item.displaySize}</p>
                      <p className="text-amber-700 font-bold text-sm">
                        {formatCurrency(item.price * item.qty)}
                      </p>
                    </div>
                    <div className="flex items-center gap-2">
                      <button
                        onClick={() => removeFromCart(item.id, item.displaySize)}
                        className="w-7 h-7 rounded-full bg-amber-100 text-amber-700 font-bold hover:bg-amber-200 transition-colors flex items-center justify-center text-sm cursor-pointer"
                        aria-label={`Kurangi ${item.name}`}
                      >
                        -
                      </button>
                      <span className="w-6 text-center font-bold text-gray-900 text-sm">
                        {item.qty}
                      </span>
                      <button
                        onClick={() =>
                          addToCart(
                            item.id,
                            item.displaySize,
                            item.stocks?.[item.displaySize] || 999
                          )
                        }
                        className="w-7 h-7 rounded-full bg-amber-600 text-white font-bold hover:bg-amber-700 transition-colors flex items-center justify-center text-sm cursor-pointer"
                        aria-label={`Tambah ${item.name}`}
                      >
                        +
                      </button>
                    </div>
                    <button
                      onClick={() => deleteFromCart(item.id, item.displaySize)}
                      className="p-1.5 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-all cursor-pointer"
                      aria-label={`Hapus ${item.name}`}
                    >
                      <svg className="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                      </svg>
                    </button>
                  </div>
                ))
              )}
            </div>

            {cartItems.length > 0 && (
              <div className="p-6 border-t border-gray-100 bg-gray-50 rounded-b-3xl">
                <div className="p-3 mb-4 bg-amber-100/50 text-amber-800 text-xs sm:text-sm rounded-xl text-center font-medium border border-amber-200/50">
                  Ketersediaan stok akan dikonfirmasi via WhatsApp.
                </div>
                <div className="flex justify-between items-center mb-4">
                  <span className="text-gray-600 font-medium">Total ({totalItems} item)</span>
                  <span className="text-2xl font-bold text-amber-700">
                    {formatCurrency(totalPrice)}
                  </span>
                </div>
                <button
                  onClick={sendToWhatsApp}
                  disabled={isCheckoutLoading}
                  className="w-full flex items-center justify-center gap-3 px-6 py-4 bg-green-500 text-white font-bold rounded-full hover:bg-green-600 transition-all duration-200 shadow-lg hover:shadow-xl active:scale-[0.98] cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed"
                >
                  {isCheckoutLoading ? (
                    <>
                      <Loader2 className="w-6 h-6 animate-spin" />
                      Memproses...
                    </>
                  ) : (
                    <>
                      <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                      </svg>
                      Pesan via WhatsApp
                    </>
                  )}
                </button>
              </div>
            )}
          </div>
        </div>
      )}
    </>
  );
}

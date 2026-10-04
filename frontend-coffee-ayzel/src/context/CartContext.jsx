import { createContext, useContext, useState, useEffect, useMemo, useCallback } from 'react';
import { getProductsFromAPI, createTransaction, getSosialMediaAPI } from '../api/api';

const CART_STORAGE_KEY = 'ayzel_cart';

export function formatCurrency(n) {
  if (n === null || n === undefined || isNaN(n)) return 'Segera Hadir';
  return 'Rp ' + n.toLocaleString('id-ID');
}

const CartContext = createContext(null);

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

export function CartProvider({ children }) {
  const [products, setProducts] = useState([]);
  const [cart, setCart] = useState(loadCart);
  const [showCart, setShowCart] = useState(false);
  const [waNumber, setWaNumber] = useState('');
  const [isCheckoutLoading, setIsCheckoutLoading] = useState(false);

  const refreshProducts = useCallback(async () => {
    try {
      const data = await getProductsFromAPI();
      setProducts(data);
      return data;
    } catch (err) {
      console.error('Gagal mengambil data produk:', err);
    }
  }, []);

  useEffect(() => {
    refreshProducts();
    getSosialMediaAPI()
      .then((data) => {
        if (data?.whatsapp) setWaNumber(data.whatsapp);
      })
      .catch(console.error);
  }, [refreshProducts]);

  useEffect(() => {
    saveCart(cart);
  }, [cart]);

  const getCartKey = useCallback((id, size) => `${id}|${size}`, []);

  const addToCart = useCallback((id, size, maxStock) => {
    const key = `${id}|${size}`;
    setCart((prev) => {
      const currentQty = prev[key] || 0;
      if (maxStock !== undefined && currentQty >= maxStock) return prev;
      return { ...prev, [key]: currentQty + 1 };
    });
  }, []);

  const removeFromCart = useCallback((id, size) => {
    const key = `${id}|${size}`;
    setCart((prev) => {
      const next = { ...prev };
      if (next[key] > 1) {
        next[key] -= 1;
      } else {
        delete next[key];
      }
      return next;
    });
  }, []);

  const deleteFromCart = useCallback((id, size) => {
    const key = `${id}|${size}`;
    setCart((prev) => {
      const next = { ...prev };
      delete next[key];
      return next;
    });
  }, []);

  const clearCart = useCallback(() => {
    setCart({});
    try {
      localStorage.removeItem(CART_STORAGE_KEY);
    } catch {
      // Silently fail
    }
  }, []);

  const totalItems = useMemo(() => {
    return Object.values(cart).reduce((a, b) => a + b, 0);
  }, [cart]);

  const totalPrice = useMemo(() => {
    return Object.entries(cart).reduce((sum, [key, qty]) => {
      const [idStr, size] = key.split('|');
      const p = products.find((pr) => String(pr.id) === idStr);
      const price = p ? p.prices[size] || 0 : 0;
      return sum + price * qty;
    }, 0);
  }, [cart, products]);

  const cartItems = useMemo(() => {
    return Object.entries(cart)
      .map(([key, qty]) => {
        const [idStr, size] = key.split('|');
        const p = products.find((pr) => String(pr.id) === idStr);
        if (!p) return null;
        return {
          ...p,
          qty,
          displaySize: size,
          price: p.prices[size] || 0,
        };
      })
      .filter(Boolean);
  }, [cart, products]);

  const sendToWhatsApp = useCallback(async () => {
    if (cartItems.length === 0) return;
    setIsCheckoutLoading(true);

    const payload = {
      total_pembayaran: totalPrice,
      items: cartItems.map((item) => ({
        size_product_id: item.variantIds[item.displaySize],
        qty: item.qty,
        harga_modal: item.modalPrices[item.displaySize],
        harga_satuan: item.price,
      })),
    };

    try {
      const response = await createTransaction(payload);
      const kodeResmi = response.kode_transaksi;

      let message = 'Halo Ayzel Coffee! Saya mau pesan:\n\n';
      message += `Kode Transaksi: ${kodeResmi}\n`;

      cartItems.forEach((item) => {
        message += `- ${item.name} (${item.displaySize}) x${item.qty} = ${formatCurrency(item.price * item.qty)}\n`;
      });

      message += `\nTotal: ${formatCurrency(totalPrice)}`;
      message += '\n\nMohon konfirmasi ketersediaan dan ongkir. Terima kasih!';

      window.open(`https://wa.me/${waNumber}?text=${encodeURIComponent(message)}`, '_blank');

      clearCart();
      setShowCart(false);
    } catch (error) {
      console.error('Gagal membuat transaksi:', error.message);
      alert('Maaf, sistem gagal memproses pesanan Anda. Silakan coba lagi.');
    } finally {
      await refreshProducts();
      setIsCheckoutLoading(false);
    }
  }, [cartItems, totalPrice, waNumber, clearCart, refreshProducts]);

  const value = {
    cart,
    products,
    setProducts,
    refreshProducts,
    addToCart,
    removeFromCart,
    deleteFromCart,
    clearCart,
    totalItems,
    totalPrice,
    cartItems,
    showCart,
    setShowCart,
    isCheckoutLoading,
    sendToWhatsApp,
    getCartKey,
  };

  return <CartContext.Provider value={value}>{children}</CartContext.Provider>;
}

export function useCart() {
  const context = useContext(CartContext);
  if (!context) {
    throw new Error('useCart must be used within a CartProvider');
  }
  return context;
}

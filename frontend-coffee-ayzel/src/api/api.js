// Import library axios untuk melakukan HTTP request (GET, POST, dll) ke backend
import axios from 'axios';

// Definisi URL dasar (base URL) dari server backend lokal Anda
const API_BASE_URL = 'http://localhost:8080';

// Export fungsi asynchronous agar bisa dipanggil di komponen React/Vue lain
export const getProductsFromAPI = async () => {
  try {
    const response = await axios.get(`${API_BASE_URL}/api/produk`);
    const apiData = response.data.data;

    return apiData.map((item) => {
      const sizesArray = item.sizes.map(s => s.ukuran);

      const pricesObject = {};
      const originalPricesObject = {}; 
      const stocksObject = {};
      
      // 1. TAMBAHKAN OBJEK BARU UNTUK ID VARIAN & HARGA MODAL
      const variantIdsObject = {};
      const modalPricesObject = {};

      let totalStokProduk = 0;

      item.sizes.forEach(s => { 
        pricesObject[s.ukuran] = parseInt(s.harga_akhir || s.harga_jual);
        originalPricesObject[s.ukuran] = parseInt(s.harga_jual);
        stocksObject[s.ukuran] = parseInt(s.stok || 0);
        
        // 2. SIMPAN ID VARIAN DAN HARGA MODAL BERDASARKAN UKURAN
        variantIdsObject[s.ukuran] = s.id; 
        modalPricesObject[s.ukuran] = parseInt(s.harga_modal || 0);

        totalStokProduk += parseInt(s.stok || 0);
      });

      return {
        id: item.id,
        name: item.nama,
        desc: item.deskripsi,
        image: item.gambar ? `${API_BASE_URL}/imgProducts/${item.gambar}` : null,
        tag: item.tag || null,
        color: 'from-amber-100 to-amber-200',
        sizes: sizesArray,
        stok: totalStokProduk,
        stocks: stocksObject,
        prices: pricesObject,
        originalPrices: originalPricesObject,
        
        // 3. MASUKKAN KE DALAM RETURN OBJECT
        variantIds: variantIdsObject,
        modalPrices: modalPricesObject
      };
    });
  } catch (error) {
    console.error("Error fetching data:", error);
    return []; 
  }
};

export const createTransaction = async (payload) => {
    try {
        const response = await fetch(`${API_BASE_URL}/api/transaksi`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
        });

        const result = await response.json();

        // Jika HTTP status bukan 200-299, lempar error agar ditangkap oleh komponen React
        if (!response.ok) {
            throw new Error(result.messages?.error || result.message || 'Terjadi kesalahan pada server');
        }

        // Kembalikan data jika sukses
        return result;
    } catch (error) {
        // Tangkap error jaringan (seperti server mati / CORS)
        throw error;
    }
};
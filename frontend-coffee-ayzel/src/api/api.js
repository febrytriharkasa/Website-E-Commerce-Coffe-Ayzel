// Import library axios untuk melakukan HTTP request (GET, POST, dll) ke backend
import axios from 'axios';

// Definisi URL dasar (base URL) dari server backend lokal Anda
const API_BASE_URL = 'http://localhost:8080';

// Export fungsi asynchronous agar bisa dipanggil di komponen React/Vue lain
export const getProductsFromAPI = async () => {
  try {
    // Meminta (GET) data produk dari endpoint API backend
    const response = await axios.get(`${API_BASE_URL}/api/produk`);
    
    // Mengambil array data produk dari struktur respons JSON (response.data.data)
    const apiData = response.data.data;

    // Melakukan iterasi (looping) pada setiap produk untuk diubah bentuk datanya (transformasi)
    return apiData.map((item) => {
      
      // Mengambil semua nama ukuran (misal: ['Small', 'Large']) dari array item.sizes
      const sizesArray = item.sizes.map(s => s.ukuran);

      // Inisialisasi objek kosong untuk menyimpan pasangan { ukuran: harga_diskon/akhir }
      const pricesObject = {};
      
      // Inisialisasi objek kosong untuk menyimpan pasangan { ukuran: harga_asli }
      const originalPricesObject = {}; 

      // Looping setiap varian ukuran untuk mengisi objek harga
      item.sizes.forEach(s => { 
        // Mengisi harga diskon/akhir (jika ada harga_akhir gunakan itu, jika tidak gunakan harga biasa) lalu diubah ke angka (integer)
        pricesObject[s.ukuran] = parseInt(s.harga_akhir || s.harga);
        
        // Mengisi harga asli (sebelum diskon) lalu diubah ke angka (integer)
        originalPricesObject[s.ukuran] = parseInt(s.harga);
      });

      // Mengembalikan format objek baru yang disesuaikan dengan kebutuhan tampilan frontend
      return {
        id: item.id, // ID unik produk
        name: item.nama, // Nama produk
        desc: item.deskripsi, // Deskripsi singkat produk
        
        // Menyarankan URL gambar lengkap (jika gambar ada, gabungkan dengan API_BASE_URL)
        image: item.gambar ? `${API_BASE_URL}/imgProducts/${item.gambar}` : null,
        
        tag: item.tag || null, // Label/tag khusus (misal: 'Best Seller', 'New')
        color: 'from-amber-100 to-amber-200', // Default warna gradient Tailwind untuk card produk
        sizes: sizesArray, // Daftar ukuran (array string)
        prices: pricesObject, // Objek harga jual/akhir per ukuran
        originalPrices: originalPricesObject // Objek harga asli per ukuran
      };
    });
  } catch (error) {
    // Menangkap dan menampilkan pesan error di console browser jika koneksi ke API gagal
    console.error("Error fetching data:", error);
    
    // Mengembalikan array kosong agar aplikasi frontend tidak crash jika API gagal/down
    return []; 
  }
};
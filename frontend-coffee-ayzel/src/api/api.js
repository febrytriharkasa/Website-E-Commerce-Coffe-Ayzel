// Import library axios untuk melakukan HTTP request (GET, POST, dll) ke backend
import axios from 'axios';
import { mapApiProduct } from './productMapper';

const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost:8080';

// Export fungsi asynchronous agar bisa dipanggil di komponen React/Vue lain
export const getProductsFromAPI = async () => {
  try {
    const response = await axios.get(`${API_BASE_URL}/api/produk`);
    const apiData = response.data.data;

    return apiData.map((item) => mapApiProduct(item, API_BASE_URL));
  } catch (error) {
    console.error("API error:", error);
        throw new Error('Gagal mengambil data produk server.', { cause: error });
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
        console.error("API error:", error);
    throw new Error('Gagal mengambil data produk server.', { cause: error });
    }
};

export const getSosialMediaAPI = async () => {
  try {
    const response = await axios.get(`${API_BASE_URL}/api/settings`);

    return response.data.data;
  } catch {
    console.error('API sosial media error');
    throw new Error('Gagal mengambil data sosail media.');
  }
}
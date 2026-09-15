import { useState, useEffect } from 'react';
import bgImage from '../assets/bg.webp';
import butterscotch from '../assets/products/butterscotch.webp';

const heroText = [
  'Premium Kopi Susu Literan',
  'Siap Saji Ready-to-Drink',
  'Nikmati Kenyamanan di Rumah',
];

export default function Home() {
  const [currentTextIndex, setCurrentTextIndex] = useState(0);

  useEffect(() => {
    const interval = setInterval(() => {
      setCurrentTextIndex((prev) => (prev + 1) % heroText.length);
    }, 3000);
    return () => clearInterval(interval);
  }, []);

  return (
    <main className="pt-16 lg:pt-20">
      <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
        <div
            className="absolute inset-0 bg-cover bg-center bg-no-repeat scale-110 filter blur-xl opacity-50"
            loading="lazy"
            style={{ backgroundImage: `url(${bgImage})` }}
            aria-hidden="true"
          />
        <div
          className="absolute inset-0 bg-contain lg:bg-cover bg-top bg-no-repeat z-0 transition-all duration-300"
          loading="lazy"
          style={{ backgroundImage: `url(${bgImage})` }}
          aria-hidden="true"
        />
        <div className="absolute inset-0 bg-black/40 z-0" />

        <div className="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
          <div className="animate-fade-up">
            <h1 className="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 text-white drop-shadow-lg">
              Coffee Ayzel
            </h1>
            <div className="h-12 mb-8 overflow-hidden">
              {heroText.map((text, index) => (
                <p
                  key={index}
                  className={`text-2xl md:text-3xl text-amber-200 font-light transition-all duration-700 ease-in-out ${
                    index === currentTextIndex
                      ? 'opacity-100 translate-y-0'
                      : 'opacity-0 translate-y-8'
                  }`}
                >
                  {text}
                </p>
              ))}
            </div>
            <p className="text-lg md:text-xl text-gray-200 max-w-3xl mx-auto mb-10 leading-relaxed">
              Nikmati kelezatan kopi susu premium kami yang disajikan dalam
              botol siap minum sempurna untuk segala momen, dari pagi yang
              cerah hingga sore yang santai.
            </p>
             <p className="text-lg md:text-xl bg-gradient-to-r from-amber-200 via-yellow-400 to-yellow-600 bg-clip-text text-transparent font-bold max-w-3xl mx-auto mb-10 leading-relaxed">
              All Your Zero-Stress Everyday Latte
              </p>
            <div className="flex flex-col sm:flex-row justify-center gap-4">
              <a
                href="/products"
                className="inline-block px-8 py-4 bg-amber-500 hover:bg-amber-700 text-white font-semibold rounded-full shadow-xl hover:shadow-2xl transition-all duration-300 hover:scale-105"
              >
                Pesan Sekarang
              </a>
              <a
                href="/about"
                className="inline-block px-8 py-4 border-2 border-amber-400 text-amber-200 hover:bg-amber-600/20 rounded-full font-semibold transition-all duration-300 hover:scale-105"
              >
                Cerita Kami
              </a>
            </div>
          </div>
        </div>
        <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-900 via-transparent to-transparent" aria-hidden="true" />
      </section>

      <section className="py-16 md:py-24 bg-white">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-12 animate-fade-up" style={{ animationDelay: '0.2s' }}>
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
              Apa yang Membuat Kami Istimewa?
            </h2>
            <p className="text-gray-600 max-w-2xl mx-auto">
              Komitmen pada kualitas, inovasi rasa, dan kenyamanan Anda
            </p>
          </div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div
              className="text-center p-8 bg-amber-50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 animate-fade-up"
              style={{ animationDelay: '0.3s' }}
            >
              <div className="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg className="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                </svg>
              </div>
              <h3 className="text-xl font-bold text-gray-900 mb-3">Bahan Premium</h3>
              <p className="text-gray-600">
                Kopi pilihan langsung dari perkebunan sertifikat di dataran tinggi.
              </p>
            </div>
            <div
              className="text-center p-8 bg-amber-50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 animate-fade-up"
              style={{ animationDelay: '0.4s' }}
            >
              <div className="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg className="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M13 2a9 9 0 0 0-9 9v7l-3 2v-9a12 12 0 0 1 12-12h4a1 1 0 0 1 0 2h-4zm0 6a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm9 3a1 1 0 0 1 1 1v5a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-5a1 1 0 0 1 1-1h4z" />
                </svg>
              </div>
              <h3 className="text-xl font-bold text-gray-900 mb-3">Siap Saji</h3>
              <p className="text-gray-600">
                Cukup buka botol, nikmati tak perlu proses apa pun lagi.
              </p>
            </div>
            <div
              className="text-center p-8 bg-amber-50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2 animate-fade-up"
              style={{ animationDelay: '0.5s' }}
            >
              <div className="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-5">
                <svg className="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M4 4h16c2.21 0 4 1.79 4 4v8c0 2.21-1.79 4-4 4H8a4 4 0 0 1-4-4V8c0-2.21 1.79-4 4-4h2v2H8c-1.1 0-2 .9-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-2V4z" />
                </svg>
              </div>
              <h3 className="text-xl font-bold text-gray-900 mb-3">Value for Money</h3>
              <p className="text-gray-600">
                Botol Ekonomis, Andalan Segala Suasana.
              </p>
            </div>
          </div>
        </div>
      </section>

      <section className="py-16 md:py-24 bg-amber-50">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col lg:flex-row items-center gap-12">
            <div className="lg:w-1/2 animate-fade-left">
              <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Nikmati Kompleksitas Rasa,
                <span className="text-amber-500 block">Tanpa Repot!
                </span>
              </h2>
              <p className="text-gray-700 mb-6 leading-relaxed">
                Coffee Ayzel adalah merek kopi susu RTD (Ready-to-Drink) yang
                hadir dengan teknologi pasteurisasi modern untuk menjaga kualitas
                rasa dan nilai gizi kopi terbaik. Setiap botol kami mengandung
                ekstrak kopi Arabika sejati yang dicampur dengan susu pilihan yang creamy.
              </p>
              <a
                href="/products"
                className="inline-block px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition-all duration-300"
              >
                Eksplor Produk
              </a>
            </div>
            <div className="lg:w-1/2 animate-fade-right" style={{ animationDelay: '0.2s' }}>
              <div className="relative">
                <div className="bg-white p-8 rounded-3xl shadow-2xl">
                  <div className="aspect-[16/9] rounded-2xl flex items-center justify-center">
                    <img src={butterscotch} alt="butterscotch" className="object-contain rounded-2xl"/>
                  </div>
                </div>
                <div className="absolute -bottom-6 -right-6 bg-amber-500 text-white p-4 rounded-xl shadow-xl animate-bounce-slow">
                  <p className="font-bold text-2xl">0% Pengawet</p>
                  <p className="text-sm opacity-90">100% Natural</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  );
}

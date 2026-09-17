import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { Element } from 'react-scroll';
import bgImage from '../assets/bg.webp';
import butterscotch from '../assets/products/butterscotch.webp';

const heroText = [
  'Premium Kopi Susu Literan',
  'Siap Saji Ready-to-Drink',
  'Nikmati Kenyamanan di Rumah',
];

const fadeUpVariant = {
  hidden: { opacity: 0, y: 30 },
  visible: (custom = 0) => ({
    opacity: 1,
    y: 0,
    transition: { duration: 0.6, delay: custom, ease: 'easeOut' },
  }),
};

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
      <Element name="home">
        <section className="relative min-h-screen flex items-center justify-center overflow-hidden">
          <div
            className="absolute inset-0 bg-cover bg-center bg-no-repeat scale-110 filter blur-xl opacity-50"
            style={{ backgroundImage: `url(${bgImage})` }}
            aria-hidden="true"
          />
          <div
            className="absolute inset-0 bg-contain lg:bg-cover bg-top bg-no-repeat z-0 transition-all duration-300"
            style={{ backgroundImage: `url(${bgImage})` }}
            aria-hidden="true"
          />
          <div className="absolute inset-0 bg-black/40 z-0" />

          <div className="container mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <motion.div
              initial="hidden"
              whileInView="visible"
              viewport={{ once: true }}
              variants={fadeUpVariant}
            >
              <h1 className="text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 text-amber-400 drop-shadow-lg">
                Ayzel Coffee 
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
                Nikmati kelezatan kopi dengan berbagai rasa yang premium 
                kami yang disajikan dalam botol siap minum sempurna untuk segala 
                momen, dari pagi yang cerah hingga sore yang santai.
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
            </motion.div>
          </div>
          <div className="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-gray-900 via-transparent to-transparent" aria-hidden="true" />
        </section>
      </Element>

      <section className="py-16 md:py-24 bg-white">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <motion.div
            className="text-center mb-12"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.3 }}
            variants={fadeUpVariant}
          >
            <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
              Apa yang Membuat Kami Istimewa?
            </h2>
            <p className="text-gray-600 max-w-2xl mx-auto">
              Komitmen pada kualitas, inovasi rasa, dan kenyamanan Anda
            </p>
          </motion.div>
          <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
            {[
              {
                title: 'Bahan Premium',
                desc: 'Kopi pilihan langsung dari perkebunan sertifikat di dataran tinggi.',
                icon: 'M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z',
              },
              {
                title: 'Siap Saji',
                desc: 'Cukup buka botol, nikmati tak perlu proses apa pun lagi.',
                icon: 'M13 2a9 9 0 0 0-9 9v7l-3 2v-9a12 12 0 0 1 12-12h4a1 1 0 0 1 0 2h-4zm0 6a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm9 3a1 1 0 0 1 1 1v5a2 2 0 0 1-2 2h-2a2 2 0 0 1-2-2v-5a1 1 0 0 1 1-1h4z',
              },
              {
                title: 'Value for Money',
                desc: 'Botol Ekonomis, Andalan Segala Suasana.',
                icon: 'M4 4h16c2.21 0 4 1.79 4 4v8c0 2.21-1.79 4-4 4H8a4 4 0 0 1-4-4V8c0-2.21 1.79-4 4-4h2v2H8c-1.1 0-2 .9-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2h-2V4z',
              },
            ].map((feature, i) => (
              <motion.div
                key={i}
                className="text-center p-8 bg-amber-50 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-2"
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true, amount: 0.3 }}
                custom={i * 0.15}
                variants={fadeUpVariant}
              >
                <div className="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-5">
                  <svg className="w-8 h-8 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d={feature.icon} />
                  </svg>
                </div>
                <h3 className="text-xl font-bold text-gray-900 mb-3">{feature.title}</h3>
                <p className="text-gray-600">{feature.desc}</p>
              </motion.div>
            ))}
          </div>
        </div>
      </section>

      <section className="py-16 md:py-24 bg-amber-50">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="flex flex-col lg:flex-row items-center gap-12">
            <motion.div
              className="lg:w-1/2"
              initial={{ opacity: 0, x: -50 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true, amount: 0.3 }}
              transition={{ duration: 0.6 }}
            >
              <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                Nikmati Kompleksitas Rasa,
                <span className="text-amber-400 block">Tanpa Repot!</span>
              </h2>
              <p className="text-gray-700 mb-6 leading-relaxed">
                Ayzel Coffee menghadirkan pengalaman menikmati kopi Ready-to-Drink 
                berkelas melalui paduan ekstrak kopi kurasi dan rasa pilihan yang lembut di lidah. 
                Diolah menggunakan teknologi pasteurisasi modern, setiap botolnya mengunci kesegaran 
                alami, keunikan cita rasa, serta kebaikan nutrisi kopi terbaik yang siap menemani setiap momen Anda
              </p>
              <a
                href="/products"
                className="inline-block px-6 py-3 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-full shadow-lg hover:shadow-xl transition-all duration-300"
              >
                Eksplor Produk
              </a>
            </motion.div>

            <motion.div
              className="lg:w-1/2"
              initial={{ opacity: 0, x: 50 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true, amount: 0.3 }}
              transition={{ duration: 0.6, delay: 0.2 }}
            >
              <div className="relative">
                <div className="bg-white p-8 rounded-3xl shadow-2xl">
                  <div className="aspect-[16/9] rounded-2xl flex items-center justify-center">
                    <img src={butterscotch} alt="butterscotch" className="object-contain rounded-2xl" />
                  </div>
                </div>
                <div className="absolute -bottom-6 -right-6 bg-amber-500 text-white p-4 rounded-xl shadow-xl animate-bounce-slow">
                  <p className="font-bold text-2xl">0% Pengawet</p>
                  <p className="text-sm opacity-90">100% Natural</p>
                </div>
              </div>
            </motion.div>
          </div>
        </div>
      </section>
    </main>
  );
}

import { motion } from 'framer-motion';
import { Element } from 'react-scroll';
import logo from "../assets/logo.jpeg";

const fadeUpVariant = {
  hidden: { opacity: 0, y: 30 },
  visible: (custom = 0) => ({
    opacity: 1,
    y: 0,
    transition: { duration: 0.6, delay: custom, ease: 'easeOut' },
  }),
};

export default function About() {
  return (
    <Element name="about">
      <main className="pt-16 lg:pt-20">
        <section className="py-8 md:py-24 bg-gradient-to-b from-amber-50 to-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <motion.div
              className="text-center mb-16"
              initial="hidden"
              whileInView="visible"
              viewport={{ once: true, amount: 0.3 }}
              variants={fadeUpVariant}
            >
              <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                Cerita di Balik <span className="text-amber-400">Ayzel Coffee</span>
              </h1>
              <p className="text-gray-600 max-w-3xl mx-auto text-lg leading-relaxed">
                Berawal dari kecintaan pada kopi dan keinginan membuat kopi berkualitas
                bisa dinikmati siapa saja, kapan saja.
              </p>
            </motion.div>
          </div>
        </section>

        <section className="py-2 md:py-4 bg-white">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8 pb-6 pt-6">
            <div className="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
              <motion.div
                initial={{ opacity: 0, x: -50 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true, amount: 0.3 }}
                transition={{ duration: 0.6 }}
              >
                <div className="rounded-3xl shadow-2xl overflow-hidden bg-black flex items-center justify-center">
                  <img 
                    className="w-full h-auto max-w-lg" 
                    loading="lazy"
                    src={logo} 
                    alt="Logo Ayzel Coffee"
                  />
                </div>
              </motion.div>
              <motion.div
                initial={{ opacity: 0, x: 50 }}
                whileInView={{ opacity: 1, x: 0 }}
                viewport={{ once: true, amount: 0.3 }}
                transition={{ duration: 0.6, delay: 0.2 }}
              >
                <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                  Perjalanan Kami
                </h2>
                <p className="text-gray-700 mb-5 leading-relaxed">
                  Ayzel Coffee didirikan tahun 2026 oleh sekelompok pecinta 
                  kopi yang percaya bahwa kopi berkualitas tinggi bisa dinikmati 
                  tanpa harus keluar rumah atau antre di kafe.
                </p>
                <p className="text-gray-700 leading-relaxed">
                  Kini, kami menghadirkan cita rasa autentik tersebut dalam kemasan botol 
                  premium 1 Liter yang siap menemani setiap ruang dan waktu Anda baik di 
                  rumah, di sela kesibukan kantor, hingga momen berkumpul bersama. Setiap 
                  tetesnya diseduh segar setiap hari freshly brewed tanpa bahan pengawet,
                  demi menjaga kemurnian rasa alami yang istimewa
                </p>
              </motion.div>
            </div>
          </div>
        </section>

        <section className="py-16 md:py-24 bg-amber-50">
          <div className="container mx-auto px-4 sm:px-6 lg:px-8">
            <motion.div
              className="text-center mb-12"
              initial="hidden"
              whileInView="visible"
              viewport={{ once: true, amount: 0.3 }}
              variants={fadeUpVariant}
            >
              <h2 className="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                Kenapa Memilih Kami?
              </h2>
            </motion.div>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              {[
                {
                  icon: (
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                  ),
                  title: 'Biji Kopi Pilihan',
                  desc: 'Arabika Grade 1 dari dataran tinggi Gayo, Aceh.',
                },
                {
                  icon: (
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                  ),
                  title: 'Tanpa Pengawet',
                  desc: 'Fresh daily, diproduksi setiap hari tanpa bahan pengawet.',
                },
                {
                  icon: (
                    <path d="M11.8 10.9c-2.27-.59-3-1.2-3-2.15 0-1.09 1.01-1.85 2.7-1.85 1.78 0 2.44.85 2.5 2.1h2.21c-.07-1.72-1.12-3.3-3.21-3.81V3h-3v2.16c-1.94.42-3.5 1.68-3.5 3.61 0 2.31 1.91 3.46 4.7 4.13 2.5.6 3 1.48 3 2.41 0 .69-.49 1.79-2.7 1.79-2.06 0-2.87-.92-2.98-2.1h-2.2c.12 2.19 1.76 3.42 3.68 3.83V21h3v-2.15c1.95-.37 3.5-1.5 3.5-3.55 0-2.84-2.43-3.81-4.7-4.4z" />
                  ),
                  title: 'Harga Bersahabat',
                  desc: 'Kualitas kafe premium dengan harga yang lebih terjangkau.',
                },
                {
                  icon: (
                    <path d="M19 7c0-1.1-.9-2-2-2h-3v2h3v2.65L13.52 14H10V9H6c-2.21 0-4 1.79-4 4v3h2c0 1.66 1.34 3 3 3s3-1.34 3-3h4.48L19 10.35V7zM7 17c-.55 0-1-.45-1-1h2c0 .55-.45 1-1 1zm10-2c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zm0 4c-.55 0-1-.45-1-1s.45-1 1-1 1 .45 1 1-.45 1-1 1z" />
                  ),
                  title: 'Pengiriman Cepat',
                  desc: 'Surabaya, Sidoarjo, Mojokerto, dan sekitarnya',
                },
              ].map((item, index) => (
                <motion.div
                  key={index}
                  className="bg-white p-6 rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 hover:-translate-y-2"
                  initial="hidden"
                  whileInView="visible"
                  viewport={{ once: true, amount: 0.3 }}
                  custom={0.1 * (index + 1)}
                  variants={fadeUpVariant}
                >
                  <div className="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                    <svg className="w-6 h-6 text-amber-600" fill="currentColor" viewBox="0 0 24 24">
                      {item.icon}
                    </svg>
                  </div>
                  <h3 className="text-lg font-bold text-gray-900 mb-2">{item.title}</h3>
                  <p className="text-gray-600 text-sm">{item.desc}</p>
                </motion.div>
              ))}
            </div>
          </div>
        </section>

        <section className="py-16 md:py-24 bg-gray-900 text-white">
          <motion.div
            className="container mx-auto px-4 sm:px-6 lg:px-8 text-center"
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.3 }}
            variants={fadeUpVariant}
          >
            <div className="grid grid-cols-2 md:grid-cols-4 gap-8">
              {[
                ['100+', 'Botol Terjual'],
                ['8+', 'Varian Rasa'],
                ['4.9/5', 'Rating'],
                ['100%', 'kopi'],
              ].map(([num, label]) => (
                <div key={label}>
                  <p className="text-4xl md:text-5xl font-bold text-amber-400 mb-2">{num}</p>
                  <p className="text-gray-400 text-sm">{label}</p>
                </div>
              ))}
            </div>
          </motion.div>
        </section>
      </main>
    </Element>
  );
}

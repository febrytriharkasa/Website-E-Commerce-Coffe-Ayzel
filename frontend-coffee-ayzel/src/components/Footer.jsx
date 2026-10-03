import { useState, useEffect } from 'react';
import { motion } from 'framer-motion';
import { Link as RouterLink } from 'react-router-dom';
import { getSosialMediaAPI } from '../api/api';

export default function Footer() {
  const currentYear = new Date().getFullYear();
  const [socials, setSocials] = useState({ whatsapp: '', instagram: '', tiktok: '' });

  useEffect(() => {
    getSosialMediaAPI().then(data => {
      if (data) setSocials({
        whatsapp: data.whatsapp || '',
        instagram: data.instagram || '',
        tiktok: data.tiktok || ''
      });
    }).catch(console.error);
  }, []);

  const socialLinks = [
    { href: socials.instagram || '#', label: 'Instagram', icon: 'M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z', show: !!socials.instagram },
    { href: `https://wa.me/${socials.whatsapp}?text=${encodeURIComponent('Halo Ayzel Coffee! Saya tertarik untuk bertanya mengenai produk.')}`, label: 'WhatsApp', icon: 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z', show: !!socials.whatsapp },
    { href: socials.tiktok || '#', label: 'TikTok', icon: 'M19.59 6.69a4.83 4.83 0 0 1-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 1 1-5.2-1.74 2.89 2.89 0 0 1 2.31-4.64 2.93 2.93 0 0 1 .88.13V5.8a6.84 6.84 0 0 0-1-.05A6.33 6.33 0 0 0 3 12a6.34 6.34 0 0 0 6.34 6.34 6.34 6.34 0 0 0 6.34-6.34V9.37a8.16 8.16 0 0 0 4.91 1.62v-3.5a4.85 4.85 0 0 1-1-.8z', show: !!socials.tiktok },
  ];

  const navLinks = [
    ['/', 'Beranda'],
    ['/about', 'Tentang Kami'],
    ['/products', 'Produk'],
    ['/testimonials', 'Testimoni'],
    ['/contact', 'Kontak'],
  ];

  const columnVariants = {
    hidden: {},
    visible: {
      transition: { staggerChildren: 0.15 },
    },
  };

  const brandVariant = {
    hidden: { opacity: 0, scale: 0.9 },
    visible: { opacity: 1, scale: 1, transition: { duration: 0.5, ease: 'easeOut' } },
  };

  const slideFromLeft = {
    hidden: { opacity: 0, x: -40, y: 20 },
    visible: { opacity: 1, x: 0, y: 0, transition: { duration: 0.5, ease: [0.22, 1, 0.36, 1] } },
  };

  const slideFromRight = {
    hidden: { opacity: 0, x: 40, y: 20 },
    visible: { opacity: 1, x: 0, y: 0, transition: { duration: 0.5, ease: [0.22, 1, 0.36, 1] } },
  };

  const dividerVariant = {
    hidden: { scaleX: 0, originX: 0 },
    visible: { scaleX: 1, originX: 0, transition: { duration: 0.7, ease: [0.22, 1, 0.36, 1] } },
  };

  const copyrightVariant = {
    hidden: { opacity: 0 },
    visible: { opacity: 1, transition: { duration: 0.4, delay: 0.2 } },
  };

  return (
    <footer
      className="bg-gray-900 text-white pt-16 pb-8"
      role="contentinfo"
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <motion.div
          variants={columnVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.2 }}
          className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12 mb-12"
        >
          <motion.div variants={brandVariant} className="lg:col-span-1">
            <div className="flex items-center gap-2 text-2xl font-bold mb-4">
              <img src="/logo.webp" alt="Logo Ayzel Coffee" className="w-8 h-8 object-contain" />
              <span className="text-amber-400">Ayzel Coffee</span>
            </div>
            <p className="text-gray-400 text-sm leading-relaxed mb-6">
              Ayzel Coffee memadukan kekayaan varian rasa kopi Ready-to-Drink berkualitas tinggi dari perkebunan terbaik di Indonesia. Higienis, segar, dan murni tanpa pengawet untuk menemani momen Anda.
            </p>
            <div className="flex gap-2">
              {socialLinks.filter(s => s.show).map((s, i) => (
                <motion.a
                  key={s.label}
                  href={s.href}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="flex items-center justify-center w-11 h-11 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-amber-400"
                  aria-label={s.label}
                  initial={{ opacity: 0, y: 10 }}
                  whileInView={{ opacity: 1, y: 0 }}
                  viewport={{ once: true }}
                  transition={{ duration: 0.3, delay: 0.4 + i * 0.1 }}
                >
                  <svg className="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d={s.icon} /></svg>
                </motion.a>
              ))}
            </div>
          </motion.div>

          <motion.nav variants={slideFromLeft} aria-label="Footer navigation">
            <h4 className="font-semibold text-amber-400 mb-3">Navigasi</h4>
            <ul className="space-y-1">
              {navLinks.map(([path, label]) => (
                <li key={path}>
                  <RouterLink to={path} className="inline-block py-2 text-gray-400 hover:text-white transition-colors">
                    {label}
                  </RouterLink>
                </li>
              ))}
            </ul>
          </motion.nav>

          <motion.div variants={slideFromRight}>
            <h4 className="font-semibold text-amber-400 mb-4">Kontak</h4>
            <address className="not-italic text-gray-400 text-sm space-y-3">
              <p>Sidoarjo, Jawa Timur</p>
              <p>
                {socials.whatsapp}
              </p>
              <p>Senin&ndash;Jumat 08:00&ndash;20:00</p>
              <p>Sabtu 09:00&ndash;18:00 &middot; Minggu 10:00&ndash;16:00</p>
            </address>
          </motion.div>
        </motion.div>

        <div className="overflow-hidden py-1">
          <motion.div
            variants={dividerVariant}
            initial="hidden"
            whileInView="visible"
            viewport={{ once: true, amount: 0.8 }}
            className="h-px bg-gray-800 w-full"
          />
        </div>
        <motion.div
          variants={copyrightVariant}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true }}
          className="pt-8 text-center text-gray-400 text-sm"
        >
          &copy; {currentYear} Ayzel Coffee. Hak cipta dilindungi undang-undang.
        </motion.div>
      </div>
    </footer>
  );
}
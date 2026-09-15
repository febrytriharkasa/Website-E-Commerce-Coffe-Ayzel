const testimonials = [
  {
    name: 'S*** D***',
    role: null,
    text: 'Kopi Susu Gula Aren-nya beneran nagih! Udah langganan 3 bulan dan nggak pernah kecewa. Rasanya konsisten enak setiap botol.',
    rating: 5,
    avatar: 'SD',
    color: 'bg-pink-500',
  },
  {
    name: 'B*** S******',
    role: null,
    text: 'Sebagai programmer, kopi tuh wajib. Coffee Ayzel bikin saya nggak perlu keluar kantor buat dapetin kopi berkualitas. 1 liter cukup buat seharian!',
    rating: 5,
    avatar: 'BS',
    color: 'bg-blue-500',
  },
  {
    name: 'R*** H******',
     role: null,
    text: 'Praktis banget! Tinggal tuang ke gelas, nggak perlu bikin sendiri. Suami dan anak-anak juga suka. Harganya worth it buat kualitas segini.',
    rating: 5,
    avatar: 'RH',
    color: 'bg-emerald-500',
  },
  {
    name: 'A**** P********',
    role: null,
    text: 'Harga ramah kantong mahasiswa tapi rasanya premium banget. Varian Hazelnut favorit saya! Pengiriman juga cepet.',
    rating: 4,
    avatar: 'AP',
    color: 'bg-amber-500',
  },
  {
    name: 'M**** C***',
    role: null,
    text: 'Sebagai barista profesional, saya akui kualitas kopi ini luar biasa. Arabikanya terasa, body-nya pas, dan after taste-nya clean.',
    rating: 5,
    avatar: 'MC',
    color: 'bg-violet-500',
  },
  {
    name: 'H***** W*****',
    role: null,
    text: 'Sering pesen buat meeting di kantor. Tampilannya elegan dan rasanya bikin client impressed. Recommended!',
    rating: 5,
    avatar: 'HW',
    color: 'bg-rose-500',
  },
];

function StarRating({ rating }) {
  return (
    <div className="flex gap-0.5" aria-label={`Rating ${rating} dari 5`}>
      {[1, 2, 3, 4, 5].map((star) => (
        <svg
          key={star}
          className={`w-4 h-4 ${star <= rating ? 'text-amber-400' : 'text-gray-300'}`}
          fill="currentColor"
          viewBox="0 0 24 24"
        >
          <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
        </svg>
      ))}
    </div>
  );
}

export default function Testimonials() {
  return (
    <main className="pt-16 lg:pt-20">
      <section className="py-16 md:py-24 bg-gradient-to-b from-amber-50 to-white">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8">
          <div className="text-center mb-16 animate-fade-up">
            <h1 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
              Apa Kata <span className="text-amber-500">Mereka</span>?
            </h1>
            <p className="text-gray-600 max-w-2xl mx-auto text-lg">
              Ratusan pelanggan sudah merasakan kelezatan Coffee Ayzel. Ini cerita
              mereka.
            </p>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            {testimonials.map((t, index) => (
              <div
                key={index}
                className="bg-white p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 animate-fade-up"
                style={{ animationDelay: `${0.1 * (index + 1)}s` }}
              >
                <div className="flex items-center gap-3 mb-4">
                  <div
                    className={`w-12 h-12 ${t.color} rounded-full flex items-center justify-center text-white font-bold text-sm`}
                  >
                    {t.avatar}
                  </div>
                  <div>
                    <h3 className="font-bold text-gray-900">{t.name}</h3>
                    <p className="text-sm text-gray-500">{t.role}</p>
                  </div>
                </div>
                <StarRating rating={t.rating} />
                <p className="text-gray-700 mt-4 leading-relaxed text-sm">
                  &ldquo;{t.text}&rdquo;
                </p>
              </div>
            ))}
          </div>
        </div>
      </section>

      <section className="py-16 md:py-24 bg-amber-600">
        <div className="container mx-auto px-4 sm:px-6 lg:px-8 text-center animate-fade-up">
          <h2 className="text-3xl md:text-4xl font-bold text-white mb-6">
            Bergabung dengan 50,000+ Pecinta Kopi
          </h2>
          <p className="text-amber-100 text-lg max-w-2xl mx-auto mb-8">
            Pesan sekarang dan rasakan sendiri kenapa Coffee Ayzel jadi pilihan
            utama kopi susu literan di Jakarta!
          </p>
          <a
            href="/products"
            className="inline-block px-8 py-4 bg-white text-amber-700 font-bold rounded-full shadow-xl hover:shadow-2xl hover:scale-105 transition-all duration-300"
          >
            Pesan Sekarang
          </a>
        </div>
      </section>
    </main>
  );
}

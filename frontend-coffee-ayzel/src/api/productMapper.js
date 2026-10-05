export function parseSizeToMl(str) {
  const lower = str.toLowerCase();
  const val = parseFloat(lower);
  if (lower.includes('ml')) return val;
  if (lower.includes('l')) return val * 1000;
  return val;
}

export function mapApiProduct(item, apiBaseUrl) {
  const sortedSizes = [...item.sizes].sort((a, b) => parseSizeToMl(a.ukuran) - parseSizeToMl(b.ukuran));
  const sizes = sortedSizes.map((size) => size.ukuran);
  const prices = {};
  const originalPrices = {};
  const stocks = {};
  const variantIds = {};
  const modalPrices = {};
  let stok = 0;

  for (const size of item.sizes) {
    prices[size.ukuran] = parseInt(size.harga_akhir || size.harga_jual);
    originalPrices[size.ukuran] = parseInt(size.harga_jual);
    stocks[size.ukuran] = parseInt(size.stok || 0);
    variantIds[size.ukuran] = size.id;
    modalPrices[size.ukuran] = parseInt(size.harga_modal || 0);
    stok += parseInt(size.stok || 0);
  }

  return {
    id: item.id,
    name: item.nama,
    desc: item.deskripsi,
    jenis: item.jenis || 'kopi',
    image: item.gambar ? `${apiBaseUrl}/imgProducts/${item.gambar}` : null,
    tag: item.tag || null,
    color: 'from-amber-100 to-amber-200',
    sizes,
    stok,
    stocks,
    prices,
    originalPrices,
    variantIds,
    modalPrices,
  };
}

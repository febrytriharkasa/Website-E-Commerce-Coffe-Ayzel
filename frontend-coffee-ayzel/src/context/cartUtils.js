export function formatCurrency(n) {
  if (n === null || n === undefined || isNaN(n)) return 'Segera Hadir';
  return 'Rp ' + n.toLocaleString('id-ID');
}

export function buildTransactionPayload(cartItems, totalPrice) {
  return {
    total_pembayaran: totalPrice,
    items: cartItems.map((item) => ({
      size_product_id: item.variantIds[item.displaySize],
      qty: item.qty,
      harga_modal: item.modalPrices[item.displaySize],
      harga_satuan: item.price,
    })),
  };
}

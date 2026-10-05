import test from 'node:test';
import assert from 'node:assert/strict';
import { buildTransactionPayload, formatCurrency } from '../context/cartUtils.js';

test('E2E simulation: User selects product, adds to cart, and checks out', () => {
  // 1. Initial State
  const cart = {};
  const products = [
    {
      id: 1,
      name: 'Ayzel Aren Coffee',
      sizes: ['250ml', '500ml'],
      prices: { '250ml': 18000, '500ml': 32000 },
      modalPrices: { '250ml': 9000, '500ml': 16000 },
      variantIds: { '250ml': 101, '500ml': 102 },
      stocks: { '250ml': 20, '500ml': 10 },
    },
  ];

  // 2. User selects 2x 250ml
  const product = products[0];
  const selectedSize = '250ml';
  const key = `${product.id}|${selectedSize}`;

  cart[key] = (cart[key] || 0) + 2;

  assert.equal(cart[key], 2);

  // 3. User views cart summary
  const cartItems = Object.entries(cart).map(([k, qty]) => {
    const [idStr, size] = k.split('|');
    const p = products.find((item) => String(item.id) === idStr);
    return {
      ...p,
      qty,
      displaySize: size,
      price: p.prices[size],
    };
  });

  const totalPrice = cartItems.reduce((sum, item) => sum + item.price * item.qty, 0);

  assert.equal(cartItems.length, 1);
  assert.equal(totalPrice, 36000);
  assert.equal(formatCurrency(totalPrice).replace(/\s/g, ' '), 'Rp 36.000');

  // 4. User triggers Checkout -> API Payload creation
  const payload = buildTransactionPayload(cartItems, totalPrice);

  assert.deepEqual(payload, {
    total_pembayaran: 36000,
    items: [
      {
        size_product_id: 101,
        qty: 2,
        harga_modal: 9000,
        harga_satuan: 18000,
      },
    ],
  });

  // 5. WhatsApp Message simulation
  const kodeResmi = 'CAY-20261005120000';
  let message = 'Halo Ayzel Coffee! Saya mau pesan:\n\n';
  message += `Kode Transaksi: ${kodeResmi}\n`;
  cartItems.forEach((item) => {
    message += `- ${item.name} (${item.displaySize}) x${item.qty} = ${formatCurrency(item.price * item.qty)}\n`;
  });
  message += `\nTotal: ${formatCurrency(totalPrice)}`;

  assert.match(message, /Kode Transaksi: CAY-20261005120000/);
  assert.match(message, /Ayzel Aren Coffee \(250ml\) x2/);
});

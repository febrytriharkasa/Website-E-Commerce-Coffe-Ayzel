import test from 'node:test';
import assert from 'node:assert/strict';
import { mapApiProduct } from '../api/productMapper.js';
import { buildTransactionPayload } from '../context/cartUtils.js';

test('API product mapper correctly structures backend data for frontend', () => {
  const rawApiItem = {
    id: 1,
    nama: 'Caffe Latte',
    deskripsi: 'Espresso with steamed milk',
    jenis: 'kopi',
    gambar: 'latte.webp',
    sizes: [
      { id: 10, ukuran: '500ml', harga_jual: '25000', harga_akhir: '22000', stok: '5', harga_modal: '12000' },
      { id: 9, ukuran: '250ml', harga_jual: '20000', harga_akhir: '20000', stok: '10', harga_modal: '10000' },
    ],
  };

  const mapped = mapApiProduct(rawApiItem, 'http://localhost:8080');

  assert.equal(mapped.id, 1);
  assert.equal(mapped.name, 'Caffe Latte');
  assert.equal(mapped.jenis, 'kopi');
  assert.deepEqual(mapped.sizes, ['250ml', '500ml']);
  assert.equal(mapped.prices['250ml'], 20000);
  assert.equal(mapped.prices['500ml'], 22000);
  assert.equal(mapped.stocks['250ml'], 10);
  assert.equal(mapped.stocks['500ml'], 5);
  assert.equal(mapped.stok, 15);
  assert.equal(mapped.variantIds['250ml'], 9);
  assert.equal(mapped.variantIds['500ml'], 10);
  assert.equal(mapped.modalPrices['250ml'], 10000);
});

test('Transaction payload builder constructs correct payload for API', () => {
  const cartItems = [
    {
      id: 1,
      name: 'Caffe Latte',
      displaySize: 'Regular',
      price: 20000,
      qty: 2,
      variantIds: { Regular: 9 },
      modalPrices: { Regular: 10000 },
    },
  ];

  const totalPrice = 40000;
  const payload = buildTransactionPayload(cartItems, totalPrice);

  assert.equal(payload.total_pembayaran, 40000);
  assert.equal(payload.items.length, 1);
  assert.equal(payload.items[0].size_product_id, 9);
  assert.equal(payload.items[0].qty, 2);
  assert.equal(payload.items[0].harga_modal, 10000);
  assert.equal(payload.items[0].harga_satuan, 20000);
});

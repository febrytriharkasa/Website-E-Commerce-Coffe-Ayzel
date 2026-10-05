import test from 'node:test';
import assert from 'node:assert/strict';
import { formatCurrency } from '../context/cartUtils.js';
import { parseSizeToMl } from '../api/productMapper.js';

test('formatCurrency handles valid numbers correctly', () => {
  assert.equal(formatCurrency(20000).replace(/\s/g, ' '), 'Rp 20.000');
  assert.equal(formatCurrency(0), 'Rp 0');
});

test('formatCurrency handles invalid/null values gracefully', () => {
  assert.equal(formatCurrency(null), 'Segera Hadir');
  assert.equal(formatCurrency(undefined), 'Segera Hadir');
  assert.equal(formatCurrency(NaN), 'Segera Hadir');
});

test('parseSizeToMl converts size strings correctly for sorting', () => {
  assert.equal(parseSizeToMl('250ml'), 250);
  assert.equal(parseSizeToMl('500ML'), 500);
  assert.equal(parseSizeToMl('1L'), 1000);
  assert.equal(parseSizeToMl('1.5L'), 1500);
});

test('Cart total calculation logic', () => {
  const cart = {
    '1|Regular': 2,
    '1|Large': 1,
  };
  const product = {
    id: 1,
    prices: {
      Regular: 15000,
      Large: 20000,
    },
  };

  const totalItems = Object.values(cart).reduce((a, b) => a + b, 0);
  const totalPrice = Object.entries(cart).reduce((sum, [key, qty]) => {
    const [, size] = key.split('|');
    return sum + product.prices[size] * qty;
  }, 0);

  assert.equal(totalItems, 3);
  assert.equal(totalPrice, 50000);
});

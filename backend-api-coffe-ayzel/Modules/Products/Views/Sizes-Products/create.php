<?php /** @var array $product */ ?>
<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Alert Error Global -->
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php 
// 1. AMBIL DATA OLD() YANG KINI BERBENTUK ARRAY
$oldUkuran      = old('ukuran');
$oldHargaModal  = old('harga_modal');
$oldHargaJual   = old('harga_jual');
$oldStok        = old('stok');
$oldTipeDiskon  = old('tipe_diskon');
$oldDiskon      = old('diskon');

// 2. HITUNG JUMLAH VARIAN YANG HARUS DITAMPILKAN
// Jika ada data old(), hitung jumlahnya. Jika tidak ada (baru buka halaman), default 1.
$jumlahVarian = ($oldUkuran && is_array($oldUkuran)) ? count($oldUkuran) : 1;
?>

<!-- START: Page Header -->
<div class="page-header">
  <div>
    <h1 class="page-title">Tambah Daftar Varian Kopi Baru</h1>
  </div>
</div>
<!-- END: Page Header -->

<div class="row g-4 mb-4">
  <form action="/sizes-product/store" method="POST">
    <?= csrf_field(); ?> 
    
    <div class="col-lg-8 col-md-10">
      <div class="card border-light shadow-sm p-4 h-100">
        
        <!-- PILIH PRODUK INDUK -->
        <div class="mb-4 pb-3 border-bottom">
          <label for="produk_id" class="form-label-custom fw-bold fs-5">1. Pilih Kopi Induk</label>
          <select class="form-select-custom <?= (validation_show_error('produk_id')) ? 'is-invalid' : ''; ?>" id="produk_id" name="produk_id" required>
            <option selected disabled>>--- Pilih Kopi ---<</option>
            <?php foreach($product as $p): ?>
              <option value="<?= $p['id']; ?>" <?= old('produk_id') == $p['id'] ? 'selected' : ''; ?>>
                <?= $p['nama']; ?>
              </option>
            <?php endforeach; ?>
          </select>
          <?php if(validation_show_error('produk_id')): ?>
              <div class="invalid-feedback d-block"><?= validation_show_error('produk_id'); ?></div>
          <?php endif; ?>
        </div>

        <h5 class="card-title mb-3">2. Isi Detail Varian (Bisa Lebih Dari Satu)</h5>

        <!-- WADAH VARIAN DINAMIS -->
        <div id="dynamic-variant-container">
            
            <?php for ($i = 0; $i < $jumlahVarian; $i++) : ?>
                <?php 
                    // Setel ulang variabel default setiap looping
                    $angka  = '';
                    $satuan = 'ml';
                    
                    // Jika data old ukuran ada di indeks ini, pecah jadi angka & satuan
                    if (isset($oldUkuran[$i])) {
                        $parts  = explode(' ', $oldUkuran[$i]);
                        $angka  = $parts[0] ?? '';
                        $satuan = $parts[1] ?? 'ml';
                    }
                ?>
                <!-- ITEM VARIAN -->
                <div class="variant-item bg-light p-3 rounded mb-3 border border-secondary border-opacity-25">
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                        <h6 class="mb-0 fw-bold variant-number">Varian <?= $i + 1; ?></h6>
                        <!-- Tombol hapus hanya muncul jika bukan form pertama ($i > 0) -->
                        <button type="button" class="btn btn-sm btn-outline-danger remove-variant <?= $i === 0 ? 'd-none' : ''; ?>">Hapus Varian</button>
                    </div>

                    <!-- Ukuran -->
                    <div class="mb-3">
                      <label class="form-label-custom">Ukuran Varian Kopi</label>
                      <input type="hidden" name="ukuran[]" class="ukuran_final" value="<?= isset($oldUkuran[$i]) ? $oldUkuran[$i] : ''; ?>">
                      <div class="input-group">
                          <input type="number" class="w-full px-4 py-3 text-sm bg-white border rounded-xl focus:outline-none focus:ring-1 transition-all form-control ukuran_angka" value="<?= $angka; ?>" placeholder="Contoh: 250" required>
                          <select class="w-full px-4 py-3 text-sm bg-white border rounded-xl focus:outline-none focus:ring-1 transition-allukuran_satuan" style="max-width: 100px; cursor: pointer;">
                              <option value="ml" <?= $satuan == 'ml' ? 'selected' : ''; ?>>ml</option>
                              <option value="L" <?= $satuan == 'L' ? 'selected' : ''; ?>>L</option>
                          </select>
                      </div>
                    </div>

                    <!-- Harga Modal & Jual -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                          <label class="form-label-custom">Harga Modal</label>
                          <div class="input-group-custom">
                            <span class="input-group-text-custom">Rp.</span>
                            <input type="number" class="form-control-custom" name="harga_modal[]" value="<?= isset($oldHargaModal[$i]) ? $oldHargaModal[$i] : ''; ?>" placeholder="Harga modal" required>
                          </div>
                        </div>
                        <div class="col-md-6 mb-3">
                          <label class="form-label-custom">Harga Jual</label>
                          <div class="input-group-custom">
                            <span class="input-group-text-custom">Rp.</span>
                            <input type="number" class="form-control-custom" name="harga_jual[]" value="<?= isset($oldHargaJual[$i]) ? $oldHargaJual[$i] : ''; ?>" placeholder="Harga jual" required>
                          </div>
                        </div>
                    </div>

                    <!-- Stok -->
                    <div class="mb-3">
                      <label class="form-label-custom">Stok Varian Kopi</label>
                      <input type="number" class="w-full px-4 py-3 text-sm bg-white border rounded-xl focus:outline-none focus:ring-1 transition-all" name="stok[]" value="<?= isset($oldStok[$i]) ? $oldStok[$i] : ''; ?>" placeholder="Masukkan stok" required>
                    </div>

                    <!-- Diskon -->
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Tipe Diskon</label>
                        <select class="form-select-custom" name="tipe_diskon[]" required>
                          <?php $valTipeDiskon = isset($oldTipeDiskon[$i]) ? $oldTipeDiskon[$i] : 'nominal'; ?>
                          <option value="nominal" <?= $valTipeDiskon == 'nominal' ? 'selected' : ''; ?>>Nominal (Rp)</option>
                          <option value="persen" <?= $valTipeDiskon == 'persen' ? 'selected' : ''; ?>>Persentase (%)</option>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label class="form-label-custom">Jumlah Diskon</label>
                        <input type="number" class="w-full px-3 py-2.5 text-sm bg-white border rounded-xl focus:outline-none focus:ring-1 transition-all" name="diskon[]" value="<?= isset($oldDiskon[$i]) ? $oldDiskon[$i] : '0'; ?>" required>
                      </div>
                    </div>
                </div>
                <!-- END: ITEM VARIAN -->
            <?php endfor; ?>

        </div>

        <!-- Tombol Tambah Form Varian -->
        <div class="mb-4">
            <button type="button" id="btn-add-variant" class="btn btn-outline-primary w-100 py-2 border-dashed" style="border-style: dashed;">
                + Tambah Ukuran / Varian Lainnya
            </button>
        </div>

        <!-- Submit & Back -->
        <div class="d-flex justify-content-between pt-3">
          <a href="/sizes-product" class="btn-custom btn-custom-light">Kembali</a>
          <button class="btn-custom btn-custom-primary" type="submit">Simpan Semua Varian</button>
        </div>
        
      </div>
    </div>
  </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('dynamic-variant-container');
    const btnAdd = document.getElementById('btn-add-variant');

    // 1. Fungsi untuk menggabungkan Angka + Satuan
    function attachUkuranListener(variantItem) {
        const angkaInput = variantItem.querySelector('.ukuran_angka');
        const satuanInput = variantItem.querySelector('.ukuran_satuan');
        const finalInput = variantItem.querySelector('.ukuran_final');

        const updateValue = () => {
            if (angkaInput.value !== "") {
                finalInput.value = angkaInput.value + ' ' + satuanInput.value;
            } else {
                finalInput.value = '';
            }
        };

        angkaInput.addEventListener('input', updateValue);
        satuanInput.addEventListener('change', updateValue);
        
        // Setup Hapus event listener jika ada tombol hapus yang aktif
        const removeBtn = variantItem.querySelector('.remove-variant');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                variantItem.remove();
                updateVariantNumbering();
            });
        }
    }

    // TERAPKAN LISTENER KE SEMUA FORM YANG SUDAH ADA (Karena PHP mungkin me-render > 1 form dari data old())
    container.querySelectorAll('.variant-item').forEach(item => {
        attachUkuranListener(item);
    });

    // 2. Logika Menambah Varian Baru
    btnAdd.addEventListener('click', function() {
        const firstVariant = container.querySelector('.variant-item');
        const newVariant = firstVariant.cloneNode(true);
        
        // Reset nilai input pada hasil clone
        newVariant.querySelectorAll('input').forEach(input => {
            if (input.name === 'diskon[]') {
                input.value = '0';
            } else {
                input.value = '';
            }
        });
        
        newVariant.querySelectorAll('select').forEach(select => {
            select.selectedIndex = 0;
        });

        // Pastikan tombol hapus muncul untuk form tambahan
        const removeBtn = newVariant.querySelector('.remove-variant');
        removeBtn.classList.remove('d-none');

        // Pasang ulang listener
        attachUkuranListener(newVariant);

        container.appendChild(newVariant);
        updateVariantNumbering();
    });

    // 3. Update penomoran
    function updateVariantNumbering() {
        const items = container.querySelectorAll('.variant-item');
        items.forEach((item, index) => {
            item.querySelector('.variant-number').innerText = 'Varian ' + (index + 1);
        });
    }
});
</script>

<?= $this->endSection(); ?>
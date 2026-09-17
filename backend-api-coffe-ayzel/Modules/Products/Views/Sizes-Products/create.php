<?php /** @var array $product */ ?>
<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<?php
    // Logika PHP untuk memecah nilai old() jika form gagal validasi
    // Contoh: "250 ml" dipecah menjadi angka "250" dan satuan "ml"
    $oldUkuran = old('ukuran');
    $angka     = '';
    $satuan    = 'ml'; // Default satuan

    if ($oldUkuran) {
        $parts  = explode(' ', $oldUkuran);
        $angka  = $parts[0] ?? '';
        $satuan = $parts[1] ?? 'ml';
    }
?>

<!-- ==========================================
        START: Main Content Area
        ========================================== -->
  <!-- START: Page Header Banner -->
<div class="page-header">
  <div>
    <h1 class="page-title">Tambah Daftar Varian Kopi Baru</h1>
  </div>
</div>
<!-- END: Page Header Banner -->

<!-- START: Form Component Row Grid Layout -->
<div class="row g-4 mb-4">

  <form action="/sizes-product/store" method="POST">
    <?= csrf_field(); ?> 
    <!-- Column 1: Basic controls -->
    <div class="col-6">
      <div class="card border-light shadow-sm p-4 h-100">
        <h5 class="card-title mb-4">Isi Produk Dengan Benar</h5>

        <!-- Nama Varian Kopi -->
        <div class="mb-3">
          <label for="produk_id" class="form-label-custom">Pilih Kopi</label>
          <select class="form-select-custom <?= (validation_errors('produk_id')) ? 'is-invalid' : ''; ?>" id="produk_id" name="produk_id" required>
            <option selected disabled>>--- Pilih Kopi---<</option>
            <?php foreach($product as $p): ?>
              <option value="<?= $p['id']; ?>" <?= old('produk_id') == $p['id'] ? 'selected' : ''; ?>>
                <?= $p['nama']; ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>

        <!-- Ukuran -->
        <div class="mb-3">
          <label for="ukuran_angka" class="form-label-custom">Ukuran Varian Kopi</label>
          
          <!-- Input Hidden: Ini yang sebenarnya dikirim ke Controller dan Database -->
          <input type="hidden" name="ukuran" id="ukuran_final" value="<?= old('ukuran'); ?>">

          <!-- Tampilan Visual (Input Group Bootstrap) -->
          <div class="input-group">
              <!-- Input Angka -->
              <input 
                  type="number" 
                  class="form-control-custom form-control <?= (validation_show_error('ukuran')) ? 'is-invalid' : ''; ?>" 
                  id="ukuran_angka" 
                  value="<?= $angka; ?>" 
                  placeholder="Contoh: 250" 
                  oninput="gabungkanUkuran()"
                  required>
              
              <!-- Dropdown Satuan -->
              <select 
                  class="form-select <?= (validation_show_error('ukuran')) ? 'is-invalid' : ''; ?>" 
                  id="ukuran_satuan" 
                  onchange="gabungkanUkuran()" 
                  style="max-width: 100px; cursor: pointer;">
                  <option value="ml" <?= ($satuan == 'ml') ? 'selected' : ''; ?>>ml</option>
                  <option value="L" <?= ($satuan == 'L') ? 'selected' : ''; ?>>L</option>
              </select>
          </div>

          <!-- Pesan Error Validasi -->
          <?php if(validation_show_error('ukuran')): ?>
              <div class="form-text text-danger mt-1">
                  <?= validation_show_error('ukuran'); ?>
              </div>
          <?php endif; ?>
      </div>

        <!-- Harga -->
        <div class="mb-3">
          <label for="Harga" class="form-label-custom">Harga Varian Kopi</label>
          <div class="input-group-custom">
            <span class="input-group-text-custom">Rp.</span>
            <input 
              type="number" 
              class="form-control-custom <?= (validation_show_error('harga')) ? 'is-invalid' : ''; ?>" 
              id="harga"
              name="harga"
              value="<?= old('harga'); ?>"
              placeholder="Masukkan harga varian kopi" 
              required>
          </div>
          <div class="form-feedback-custom invalid-custom">
            </i><?= validation_show_error('harga'); ?>
          </div>
        </div>

         <!-- Stok -->
        <div class="mb-3">
          <label for="stok" class="form-label-custom">Stok Varian Kopi</label>
          <input 
            type="number" 
            class="form-control-custom <?= (validation_show_error('stok')) ? 'is-invalid' : ''; ?>" 
            id="stok" 
            name="stok" 
            value="<?= old('stok'); ?>" 
            placeholder="Masukkan stok varian kopi" 
            required>
          <div class="form-feedback-custom invalid-custom">
            </i><?= validation_show_error('stok'); ?>
          </div>
        </div>

        <!-- Diskon -->
        <div class="row">

          <!-- Tipe Diskon -->
          <div class="col-md-6 mb-3">
            <label for="tipe_diskon" class="form-label-custom">Pilih TIpe Diskon</label>
            <select class="form-select-custom <?= (validation_errors('tipe_diskon')) ? 'is-invalid' : ''; ?>" id="tipe_diskon" name="tipe_diskon" required>
              <option value="nominal" <?= old('tipe_diskon') == 'nominal' ? 'selected' : ''; ?>>Nominal (Rp)</option>
              <option value="persen" <?= old('tipe_diskon') == 'persen' ? 'selected' : ''; ?>>Persentase (%)</option>
            </select>
            <div class="invalid-feedback"><?= validation_show_error('tipe_diskon'); ?></div>
          </div>
          
          <!-- Jumlah Diskon -->
          <div class="col-md-6 mb-3">
            <label for="tipe_diskon" class="form-label-custom">Jumlah Diskon</label>
            <input 
              type="number" 
              class="form-control-custom <?= (validation_show_error('diskon')) ? 'is-invalid' : ''; ?>" 
              id="diskon" 
              name="diskon" 
              value="<?= old('diskon', 0); ?>" 
              placeholder="Masukkan diskon varian kopi" 
              required>
            <div class="form-feedback-custom invalid-custom">
              </i><?= validation_show_error('diskon'); ?>
            </div>
          </div>
        </div>

        <div class="d-flex justify-content-between">
          <a href="/sizes-product" class="btn-custom btn-custom-light" type="button">Kembali</a>
          <button class="btn-custom btn-custom-primary" type="submit" id="btnSubmit">Simpan Varian Produk</button>
        </div>
      </div>
    </div>
  </form>

</div>
<!-- END: Form Component Row Grid Layout -->

<script>
    function gabungkanUkuran() {
        const angka = document.getElementById('ukuran_angka').value;
        const satuan = document.getElementById('ukuran_satuan').value;
        const finalInput = document.getElementById('ukuran_final');
        
        // Jika angka diisi, gabungkan dengan satuan (Contoh hasil: "250 ml")
        if (angka !== "") {
            finalInput.value = angka + ' ' + satuan;
        } else {
            finalInput.value = ''; // Kosongkan jika angka dihapus
        }
    }
</script>

<?= $this->endSection(); ?>
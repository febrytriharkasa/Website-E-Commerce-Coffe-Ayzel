<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- ==========================================
        START: Main Content Area
        ========================================== -->
  <!-- START: Page Header Banner -->
<div class="page-header">
  <div>
    <h1 class="page-title">Tambah Daftar Kopi Baru</h1>
  </div>
</div>
<!-- END: Page Header Banner -->

<!-- START: Form Component Row Grid Layout -->
<div class="row g-4 mb-4">

  <form action="/product/store" method="POST" enctype="multipart/form-data">
    <?= csrf_field(); ?> 
    <!-- Column 1: Basic controls -->
    <div class="col-6">
      <div class="card border-light shadow-sm p-4 h-100">
        <h5 class="card-title mb-4">Isi Produk Dengan Benar</h5>

        <!-- Nama Varian Kopi -->
        <div class="mb-3">
          <label for="nama" class="form-label-custom">Nama Varian Kopi</label>
          <input 
            type="text" 
            class="form-control-custom <?= (validation_show_error('nama')) ? 'is-invalid' : ''; ?>" 
            id="nama" 
            name="nama" 
            value="<?= old('nama'); ?>" 
            placeholder="Masukkan nama varian kopi" 
            required>
          <div class="form-feedback-custom invalid-custom">
            </i><?= validation_show_error('nama'); ?>
          </div>
        </div>

        <!-- Deskripsi Kopi -->
        <div class="mb-3">
          <label for="deskripsi" class="form-label-custom">Deskripsi Varian Kopi</label>
          <textarea
            rows="3"
            class="form-control-custom <?= (validation_show_error('deskripsi')) ? 'is-invalid' : ''; ?>" 
            id="deskripsi" 
            name="deskripsi"
            placeholder="Masukkan deskripsi tentang varian kopi"><?= old('deskripsi'); ?></textarea>
          <div class="form-feedback-custom invalid-custom">
            </i><?= validation_show_error('deskripsi'); ?>
          </div>
        </div>

        <!-- Gambar Produk -->
        <div class="mb-3">
          <label for="gambar" class="form-label-custom">Gambar Produk</label>
          <!-- Preview Gambar -->
          <div class="mb-2">
              <img src="<?= base_url('imgProducts/default.png'); ?>" 
                  alt="Preview Gambar" 
                  class="img-thumbnail img-preview" 
                  style="max-width: 150px; max-height: 150px;">
          </div>
          <input 
            type="file" 
            class="form-control-custom <?= (validation_show_error('gambar')) ? 'is-invalid' : ''; ?>" 
            id="gambar" 
            name="gambar" 
            accept="image/*" 
            onchange="previewImg()"
            required>
          <div class="form-feedback-custom invalid-custom">
            </i><?= validation_show_error('gambar'); ?>
          </div>
          <div class="form-text mt-2">Maksimal 2MB. Format: JPG, JPEG, PNG.</div>
        </div>
        <div class="d-flex justify-content-between">
          <a href="/product" class="btn-custom btn-custom-light" type="button">Kembali</a>
          <button class="btn-custom btn-custom-primary" type="submit" id="btnSubmit">Simpan Varian Produk</button>
        </div>
    </div>
  </form>

</div>
<!-- END: Form Component Row Grid Layout -->

<?= $this->endSection(); ?>
<?php /** @var array $product */ ?>

<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Edit Produk Baru</h5>
            </div>
            <div class="card-body">
                <!-- Form dengan enctype multipart untuk upload file -->
                <form action="/product/update/<?= $product['id']; ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field(); ?> <!-- Keamanan Anti CSRF -->


                    <!-- Simpan nama gambar lama agar tidak hilang jika pengguna tidak mengganti gambar -->
                    <input type="hidden" name="gambar_lama" value="<?= $product['gambar']; ?>">

                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= (validation_show_error('nama')) ? 'is-invalid' : ''; ?>" id="nama" name="nama" value="<?= (old('nama')) ? old('nama') : $product['nama']?>" required>
                        <div class="invalid-feedback">
                            <?= validation_show_error('nama'); ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control <?= (validation_show_error('deskripsi')) ? 'is-invalid' : ''; ?>" id="deskripsi" name="deskripsi" rows="4"><?= (old('deskripsi')) ? old('deskripsi') : $product['deskripsi']?></textarea>
                        <div class="invalid-feedback">
                            <?= validation_show_error('deskripsi'); ?>
                        </div>
                    </div>

                    <div class="mb-4">
                        <!-- Preview Gambar -->
                        <div class="mb-2">
                            <img src="<?= base_url('imgProducts/' . $product['gambar']); ?>" 
                                alt="Preview Gambar" 
                                class="img-thumbnail img-preview" 
                                style="max-width: 150px; max-height: 150px;">
                        </div>
                        <label for="gambar" class="form-label">Gambar Produk</label>
                        <input class="form-control <?= (validation_show_error('gambar')) ? 'is-invalid' : ''; ?>" type="file" id="gambar" name="gambar" accept="image/*" onchange="previewImg()">
                        <div class="form-text">Maksimal 2MB. Format: JPG, JPEG, PNG.</div>
                        <div class="invalid-feedback">
                            <?= validation_show_error('gambar'); ?>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="/product" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-primary">Simpan Produk</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    function previewImg() {
        const gambar = document.querySelector('#gambar');
        const imgPreview = document.querySelector('.img-preview');

        // Membaca file gambar yang dipilih
        const fileGambar = new FileReader();
        fileGambar.readAsDataURL(gambar.files[0]);

        fileGambar.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }
</script>

<?= $this->endSection(); ?>
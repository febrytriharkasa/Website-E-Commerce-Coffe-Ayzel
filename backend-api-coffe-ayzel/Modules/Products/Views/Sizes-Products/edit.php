<?php /** @var array $products */ /** @var array $sizes */?>

<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<div class="row">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Edit Varian & Diskon Produk</h5>
            </div>
            <div class="card-body">
                
                <!-- Menampilkan pesan error umum dari controller jika ada -->
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error'); ?>
                    </div>
                <?php endif; ?>

                <!-- Form diarahkan ke method store varian -->
                <form action="/sizes-product/update/<?= $sizes['id']; ?>" method="POST">
                    <?= csrf_field(); ?> 

                    <div class="mb-3">
                        <label for="produk_id" class="form-label">Pilih Produk <span class="text-danger">*</span></label>
                        
                        <!-- 1. Select diberi atribut 'disabled' (nama 'name' dihapus agar tidak bentrok) -->
                        <select class="form-select <?= (validation_show_error('produk_id')) ? 'is-invalid' : ''; ?>" id="produk_id" disabled>
                            <option value="">-- Pilih Produk --</option>
                            <?php foreach($products as $p): ?>
                                <option value="<?= $p['id']; ?>" <?= old('produk_id', $sizes['produk_id']) == $p['id'] ? 'selected' : ''; ?>>
                                    <?= $p['nama']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <div class="invalid-feedback"><?= validation_show_error('produk_id'); ?></div>
                    </div>

                    <!-- 2. Ukuran -->
                    <div class="mb-3">
                        <label for="ukuran" class="form-label">Ukuran <span class="text-danger">*</span></label>
                        <input 
                            type="text" 
                            class="form-control <?= (validation_show_error('ukuran')) ? 'is-invalid' : ''; ?>" 
                            id="ukuran" 
                            name="ukuran" 
                            value="<?= old('ukuran' , $sizes['ukuran']); ?>" 
                            placeholder="Contoh: 200gr" 
                            required>
                        <div class="invalid-feedback"><?= validation_show_error('ukuran'); ?></div>
                    </div>

                    <!-- 3. Harga Modal -->
                    <div class="mb-3">
                        <label for="harga_modal" class="form-label">Harga Modal Satuan (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-group-text-custom">Rp.</span>
                            <input 
                                type="number" 
                                class="form-control-custom <?= (validation_show_error('harga_modal')) ? 'is-invalid' : ''; ?>" 
                                id="harga_modal" 
                                name="harga_modal" 
                                value="<?= old('harga_modal', $sizes['harga_modal']); ?>" 
                                required>
                        </div>
                        <div class="invalid-feedback"><?= validation_show_error('harga_modal'); ?></div>
                    </div>

                    <!-- 3. Harga Jual -->
                    <div class="mb-3">
                        <label for="harga_jual" class="form-label">Harga Modal Satuan (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group-custom">
                            <span class="input-group-text-custom">Rp.</span>
                            <input 
                            type="number" 
                            class="form-control-custom <?= (validation_show_error('harga_jual')) ? 'is-invalid' : ''; ?>" 
                            id="harga_jual" 
                            name="harga_jual" 
                            value="<?= old('harga_jual', $sizes['harga_jual']); ?>" 
                            required>
                        </div>
                        <div class="invalid-feedback"><?= validation_show_error('harga_jual'); ?></div>
                    </div>

                    <!-- 4. Stok -->
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok Awal <span class="text-danger">*</span></label>
                        <input type="number" class="form-control <?= (validation_show_error('stok')) ? 'is-invalid' : ''; ?>" id="stok" name="stok" value="<?= old('stok', $sizes['stok']); ?>" required>
                        <div class="invalid-feedback"><?= validation_show_error('stok'); ?></div>
                    </div>

                    <div class="row">
                        <!-- 5. Tipe Diskon -->
                        <div class="col-md-6 mb-3">
                            <label for="tipe_diskon" class="form-label">Tipe Diskon <span class="text-danger">*</span></label>
                            <select class="form-select <?= (validation_show_error('tipe_diskon')) ? 'is-invalid' : ''; ?>" id="tipe_diskon" name="tipe_diskon" required>
                                <option value="nominal" <?= old('tipe_diskon', $sizes['tipe_diskon']) == 'nominal' ? 'selected' : ''; ?>>Nominal (Rp)</option>
                                <option value="persen" <?= old('tipe_diskon', $sizes['tipe_diskon']) == 'persen' ? 'selected' : ''; ?>>Persentase (%)</option>
                            </select>
                            <div class="invalid-feedback"><?= validation_show_error('tipe_diskon'); ?></div>
                        </div>

                        <!-- 6. Jumlah Diskon -->
                        <div class="col-md-6 mb-3">
                            <label for="diskon" class="form-label">Jumlah Diskon</label>
                            <input 
                                type="number" 
                                class="form-control <?= (validation_show_error('diskon')) ? 'is-invalid' : ''; ?>" 
                                id="diskon" 
                                name="diskon" 
                                value="<?= old('diskon', $sizes['diskon']); ?>">
                            <div class="invalid-feedback"><?= validation_show_error('diskon'); ?></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">
                        <a href="/sizes-product" class="btn btn-secondary">Batal</a>
                        <button type="submit" id="btnSubmit" class="btn btn-primary">Simpan Varian</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
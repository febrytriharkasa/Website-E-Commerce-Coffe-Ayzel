<!-- Memberitahu CI4 bahwa file ini menggunakan layout template.php -->
 <?php /** @var array $products*/ ?>
 
<?= $this->extend('layout/template'); ?>

<!-- Memulai bagian 'content' yang dipanggil di template.php -->
<?= $this->section('content'); ?>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Total Transaksi</h5>
                <h2><?= $total_transaksi; ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0">Daftar Produk & Stok</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Ukuran</th>
                        <th>Harga</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; ?>
                    <!-- Looping data products dari controller -->
                    <?php foreach ($products as $p) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= $p['nama']; ?></td>
                            <td>
                                <!-- Pengecekan jika produk belum punya ukuran -->
                                <?= $p['ukuran'] ? $p['ukuran'] : '<span class="text-danger">Belum diset</span>'; ?>
                            </td>
                            <td>Rp <?= number_format($p['harga'], 0, ',', '.'); ?></td>
                            <td>
                                <?php if ($p['stok'] > 0) : ?>
                                    <span class="badge bg-success"><?= $p['stok']; ?></span>
                                <?php else : ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if(empty($products)): ?>
                        <tr>
                            <td colspan="5" class="text-center">Belum ada data produk.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
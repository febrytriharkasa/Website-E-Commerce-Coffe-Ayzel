<?php /** @var array $product */ ?>

<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Alert Sukses -->
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Daftar Produk</h5>
        <a href="/sizes-product/create" class="btn btn-primary btn-sm">
            <i class="bi bi-plus-lg"></i> Tambah Produk
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <!-- Bagian Thead: Tambahkan kolom Varian -->
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="20%">Nama Produk</th>
                        <!-- Kolom Baru -->
                        <th>Ukuran</th> 
                        <th>Harga</th>
                        <th>Stok</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>

                <!-- Bagian Tbody: Tambahkan loop untuk menampilkan varian -->
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($product as $p) : ?>
                        
                        <!-- Menghitung jumlah varian pada produk saat ini -->
                        <?php $jmlVarian = count($p['varian'] ?? []); ?>
                        
                        <?php if ($jmlVarian > 0) : ?>
                            <!-- Jika produk memiliki varian, jalankan loop ke bawah -->
                            <?php foreach ($p['varian'] as $index => $v) : ?>
                                <tr>
                                    <!-- Kolom No & Nama hanya dicetak di baris varian pertama, lalu di-merge (rowspan) ke bawah -->
                                    <?php if ($index == 0) : ?>
                                        <td rowspan="<?= $jmlVarian; ?>" class="align-middle"><?= $no++; ?></td>
                                        <td rowspan="<?= $jmlVarian; ?>" class="align-middle fw-bold"><?= $p['nama']; ?></td>
                                    <?php endif; ?>
                                    
                                    <td class="align-middle"><?= $v['ukuran']; ?></td>
                                    <td class="align-middle">
                                        <?php if ($v['harga_akhir'] < $v['harga']) : ?>
                                            <!-- Jika ada diskon: Coret harga asli (text-decoration-line-through) -->
                                            <span class="text-muted text-decoration-line-through" style="font-size: 0.85rem;">
                                                Rp <?= number_format($v['harga'], 0, ',', '.'); ?>
                                            </span>
                                            <br>
                                            <!-- Tampilkan Harga Setelah Diskon -->
                                            <strong class="text-danger">
                                                Rp <?= number_format($v['harga_akhir'], 0, ',', '.'); ?>
                                            </strong>
                                        <?php else : ?>
                                            <!-- Jika tidak ada diskon: Tampilkan harga asli biasa -->
                                            <strong>Rp <?= number_format($v['harga'], 0, ',', '.'); ?></strong>
                                        <?php endif; ?>

                                    </td>
                                    <td class="align-middle"><?= $v['stok']; ?></td>
                                    
                                    <!-- Tombol Aksi HARUS berada di dalam loop varian agar ID Modal sesuai -->
                                    <td class="align-middle d-flex gap-1">
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#tambahStokModal<?= $v['id']; ?>" title="Tambah Stok">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                        <a href="/sizes-product/edit/<?= $v['id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                         <form action="/sizes-product/delete/<?= $v['id']; ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin?');">
                                            <?= csrf_field(); ?>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                <div class="modal fade" id="tambahStokModal<?= $v['id']; ?>" tabindex="-1" aria-labelledby="tambahStokLabel<?= $v['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="tambahStokLabel<?= $v['id']; ?>">
                                                    Tambah Stok - <?= $p['nama']; ?> (<?= $v['ukuran']; ?>)
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <!-- Sesuaikan action route ini dengan controller Anda -->
                                            <form action="/sizes-product/update-stok/<?= $v['id']; ?>" method="POST">
                                                <?= csrf_field(); ?>
                                                <div class="modal-body">
                                                    <div class="alert alert-info py-2">
                                                        Sisa stok saat ini: <strong><?= $v['stok']; ?></strong>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="tambahan_stok_<?= $v['id']; ?>" class="form-label">Jumlah Stok Baru</label>
                                                        <input type="number" class="form-control" id="tambahan_stok_<?= $v['id']; ?>" name="tambah_stok" min="1" required placeholder="Masukkan jumlah yang ditambahkan">
                                                        <div class="form-text">Masukkan jumlah stok yang masuk (akan diakumulasikan).</div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Stok</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <!-- Tampilan jika produk tersebut belum punya varian sama sekali -->
                            <tr>
                                <td class="align-middle"><?= $no++; ?></td>
                                <td class="align-middle fw-bold"><?= $p['nama']; ?></td>
                                <td colspan="4" class="text-muted fst-italic text-center" style="font-size: 0.85rem;">Belum ada varian</td>
                            </tr>
                        <?php endif; ?>

                    <?php endforeach; ?>
                    
                    <!-- Pengecekan data kosong diperbaiki dari $products menjadi $product -->
                    <?php if(empty($product)): ?>
                        <tr>
                            <td colspan="6" class="text-center">Belum ada data produk.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>
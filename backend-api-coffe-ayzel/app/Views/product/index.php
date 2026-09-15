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
        <a href="/product/create" class="btn btn-primary btn-sm">
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
                        <th width="10%">Gambar</th>
                        <th width="20%">Nama Produk</th> 
                        <th>Deskripsi</th>
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>

                <!-- Bagian Tbody: Tambahkan loop untuk menampilkan varian -->
                <tbody>
                    <?php $no = 1; ?>
                    <?php foreach ($product as $p) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td>
                                <img src="/imgProducts/<?= $p['gambar']; ?>" alt="<?= $p['nama']; ?>" class="img-thumbnail" style="max-width: 80px;">
                            </td>
                            <td class="fw-bold"><?= $p['nama']; ?></td>
                            <td><?= $p['deskripsi']; ?></td>
                            <td class="d-flex gap-1">
                                <!-- Tombol Atur Size, Edit, Delete yang sudah Anda buat sebelumnya -->
                                <a href="/product/sizes/<?= $p['id']; ?>" class="btn btn-info btn-sm text-white" title="Kelola Varian">
                                    <i class="bi bi-tags"></i>
                                </a>
                                <a href="/product/edit/<?= $p['id']; ?>" class="btn btn-warning btn-sm" title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="/product/delete/<?= $p['id']; ?>" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin?');">
                                    <?= csrf_field(); ?>
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    
                    <?php if(empty($products)): ?>
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
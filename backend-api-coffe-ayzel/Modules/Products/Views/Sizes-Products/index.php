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

<!-- Alert Error -->
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- START: Basic Table Card Container -->
<div class="table-card-custom">
  <!-- Header Controls -->
  <div class="table-header-control">
    <!-- Search bar -->
    <div class="table-search-box">
      <i class="bi bi-search table-search-icon"></i>
      <input type="text" class="table-search-input" placeholder="Search orders or products...">
    </div>
    <!-- Action buttons / Filter options -->
    <div class="table-filter-group">
      <div class="dropdown">
        <button class="btn-table-action dropdown-toggle" type="button" id="dropdownFilterStatus"
          data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-funnel"></i> Status Filter
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownFilterStatus">
          <li><a class="dropdown-item" href="#">All Statuses</a></li>
          <li><a class="dropdown-item" href="#">Paid / Success</a></li>
          <li><a class="dropdown-item" href="#">Processing</a></li>
          <li><a class="dropdown-item" href="#">Cancelled / Failed</a></li>
        </ul>
      </div>
      <a href="/sizes-product/create" class="btn-table-action" type="button">
        <i class="bi bi-plus-lg"></i> Tambah Varian
      </a>
    </div>
  </div>

  <!-- Responsive Table Wrapper -->
  <div class="table-responsive">
    <table class="table-custom">
      <thead>
        <tr>
            <th width="5%">No</th>
            <th width="20%">Nama Produk</th>
            <th>Ukuran</th> 
            <th>Harga Modal</th>
            <th>Harga Jual</th>
            <th>Stok</th>
            <th width="20%">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1 + ($pager->getPerPage('size_product') * ($pager->getCurrentPage('size_product') - 1)); ?>
        <?php foreach ($product as $p) : ?>

          <!-- Menghitung jumlah varian pada produk saat ini -->
          <?php $jmlVarian = count($p['varian'] ?? []); ?>
          <?php if ($jmlVarian > 0) : ?>
            <!-- Jika produk memiliki varian, jalankan loop ke bawah -->
            <?php foreach ($p['varian'] as $index => $v) : ?>
              <tr>
                 <!-- Kolom No & Nama hanya dicetak di baris varian pertama, lalu di-merge (rowspan) ke bawah -->
                <?php if ($index == 0) : ?>
                  <td rowspan="<?= $jmlVarian; ?>"><?= $no++; ?></td>
                  <td rowspan="<?= $jmlVarian; ?>" class="table-product-name"><?= $p['nama']; ?></td>
                <?php endif; ?>
    
                <td class="align-middle"><?= $v['ukuran']; ?></td>
                <td class="align-middle">Rp <?= number_format($v['harga_modal'], 0, ',', '.'); ?></td>
                <td class="align-middle">
                  <?php if ($v['harga_akhir'] < $v['harga_jual']) : ?>
                      <!-- Jika ada diskon: Coret harga asli (text-decoration-line-through) -->
                      <span class="text-muted text-decoration-line-through" style="font-size: 0.85rem;">
                          Rp <?= number_format($v['harga_jual'], 0, ',', '.'); ?>
                      </span>
                      <br>
                      <!-- Tampilkan Harga Setelah Diskon -->
                      <strong class="text-danger">
                          Rp <?= number_format($v['harga_akhir'], 0, ',', '.'); ?>
                      </strong>
                  <?php else : ?>
                      <!-- Jika tidak ada diskon: Tampilkan harga asli biasa -->
                      <strong>Rp <?= number_format($v['harga_jual'], 0, ',', '.'); ?></strong>
                  <?php endif; ?>
                </td>
                <td class="align-middle"><?= $v['stok']; ?></td>

                <td>
                  <!-- Aksi -->
                  <div class="d-flex justify-content-center gap-1">
                    <!-- Tambah Stok -->
                    <button type="button" class="btn-custom btn-custom-primary btn-custom-sm" title="Tambah Stok" data-bs-toggle="modal" data-bs-target="#tambahStokModal<?= $v['id']; ?>">
                        <i class="bi bi-plus-circle"></i>
                    </button>
                    <!-- Edit -->
                    <a href="/sizes-product/edit/<?= $v['id']; ?>" class="btn-custom btn-custom-warning btn-custom-sm" title="Edit row"><i class="bi bi-pencil"></i></a>
                    <!-- Delete -->
                    <button type="button" class="btn-custom btn-custom-danger btn-custom-sm" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteUkuran<?= $v['id']; ?>">
                        <i class="bi bi-trash"></i>
                    </button>
                  </div>

                  <!-- Delete Confirmation Pop Up -->
                  <div class="modal fade" id="deleteUkuran<?= $v['id']; ?>" tabindex="-1" aria-labelledby="deleteUkuranLabel<?= $v['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="modal-header border-0 pb-0">
                          <h5 class="modal-title font-weight-bold" id="deleteUkuranLabel<?= $p['id']; ?>">Konfirmasi Hapus</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                          <div class="mb-3">
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                          </div>
                          <p class="mb-1 text-muted">Apakah Anda yakin ingin menghapus produk ini?</p>
                          <h6 class="fw-bold text-dark"><?= $p['nama']; ?> - <?= $v['ukuran']; ?></h6>
                          <small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>
                        </div>
                        <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                          <button type="button" class="btn-custom btn-custom-light px-4" data-bs-dismiss="modal">Batal</button>
                          <form action="/sizes-product/delete/<?= $v['id']; ?>" method="POST" class="d-inline">
                            <?= csrf_field(); ?>
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn-custom btn-custom-danger px-4">Hapus Produk</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Tambah Stok Pop Up -->
                  <div class="modal fade" id="tambahStokModal<?= $v['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $p['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="modal-header border-0 pb-0">
                          <h5 class="modal-title font-weight-bold" id="deleteModalLabel<?= $p['id']; ?>">Konfirmasi Hapus</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                          <p class="mb-1 text-muted">Apakah Anda yakin ingin menambah stok <?= $p['nama']; ?>?</p>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                          <!-- Form dibuat w-100 (lebar 100%) agar membungkus seluruh area footer -->
                          <form action="/sizes-product/update-stok/<?= $v['id']; ?>" method="POST" class="w-100">
                            <?= csrf_field(); ?>
                            
                            <!-- Input Field -->
                            <div class="mb-4">
                              <input type="number" 
                                    class="form-control text-center" 
                                    id="tambahan_stok_<?= $v['id']; ?>" 
                                    name="tambah_stok" 
                                    min="1" 
                                    required 
                                    placeholder="Masukkan jumlah yang ditambahkan">
                            </div>
                            
                            <!-- Area Tombol -->
                            <div class="d-flex justify-content-center gap-2">
                              <button type="button" class="btn-custom btn-custom-light px-4" data-bs-dismiss="modal">Batal</button>
                              <button type="submit" class="btn-custom btn-custom-primary px-4">Tambah Stok</button>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
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
        <?php if(empty($product)): ?>
          <tr>
              <td colspan="7" class="text-center">Belum ada data produk.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <!-- Footer Controls / Pagination -->
  <?= $pager->links('size_product', 'bootstrap_pagination'); ?>
</div>
<!-- END: Basic Table Card Container -->
<?= $this->endSection(); ?>
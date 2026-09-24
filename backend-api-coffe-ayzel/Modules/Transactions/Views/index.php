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
      <input type="text" class="table-search-input" placeholder="Cari kode transaksi...">
    </div>
    
    <!-- Action buttons / Filter options -->
    <div class="table-filter-group">
      <div class="dropdown">
        <button class="btn-table-action dropdown-toggle" type="button" id="dropdownFilterStatus"
          data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-funnel"></i> Filter Waktu
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownFilterStatus">
          <li><a class="dropdown-item" href="#">Semua Waktu</a></li>
          <li><a class="dropdown-item" href="#">Bulan Ini</a></li>
          <li><a class="dropdown-item" href="#">Bulan Lalu</a></li>
        </ul>
      </div>
      <a href="/transaksi/create" class="btn-table-action" type="button">
        <i class="bi bi-plus-lg"></i> Tambah Transaksi
      </a>
    </div>
  </div>

  <!-- Responsive Table Wrapper -->
  <div class="table-responsive">
    <table class="table-custom">
      <thead>
        <tr>
            <th width="5%">No</th>
            <th width="15%">Tanggal</th>
            <th width="20%">Kode Transaksi</th> 
            <th>Total Pembayaran</th>
            <th>Status Transaksi</th>
            <th width="20%" class="text-center">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1 + ($pager->getPerPage('transaksi') * ($pager->getCurrentPage('transaksi') - 1)); ?>
        <?php foreach ($transaksi as $t) : ?>
          <tr>
            <td><?= $no++; ?></td>
            <td><?= date('d M Y, H:i', strtotime($t['tgl_transaksi'])); ?></td>
            <td class="table-product-name">
                <span class="badge bg-primary rounded-pill px-3 py-2"><?= $t['kode_transaksi']; ?></span>
            </td>
            <td class="fw-bold text-success">
                Rp <?= number_format($t['total_pembayaran'], 0, ',', '.'); ?>
            </td>
            <?php if ($t['status_transaksi'] == 'batal') : ?>
            <td><span class="badge-table failed"><?= $t['status_transaksi']; ?></span></td>
            <?php elseif ($t['status_transaksi'] == 'pending') : ?>
            <td><span class="badge-table pending"><?= $t['status_transaksi']; ?></span></td>
            <?php elseif ($t['status_transaksi'] == 'selesai') : ?>
            <td><span class="badge-table success"><?= $t['status_transaksi']; ?></span></td>
            <?php endif; ?>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <!-- Tombol Detail -->
                <a href="/transaksi/show/<?= $t['id']; ?>" class="btn-custom btn-custom-secondary btn-custom-sm" title="Detail Transaksi">
                    <i class="bi bi-eye"></i>
                </a>
                
                <!-- Tombol Edit -->
                <a href="/transaksi/edit/<?= $t['id']; ?>" class="btn-custom btn-custom-warning btn-custom-sm" title="Edit Transaksi">
                    <i class="bi bi-pencil"></i>
                </a>
                
                <!-- Tombol Hapus -->
                <button type="button" class="btn-custom btn-custom-danger btn-custom-sm" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteTranksasi<?= $t['id']; ?>">
                    <i class="bi bi-trash"></i>
                </button>
              </div>

              <!-- Delete Confirmation Modal -->
              <div class="modal fade" id="deleteTranksasi<?= $t['id']; ?>" tabindex="-1" aria-labelledby="deleteTranksasiLabel<?= $t['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header border-0 pb-0">
                      <h5 class="modal-title font-weight-bold" id="deleteTranksasiLabel<?= $t['id']; ?>">Konfirmasi Hapus</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                      <div class="mb-3">
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                      </div>
                      <p class="mb-1 text-muted">Apakah Anda yakin ingin menghapus transaksi ini?</p>
                      <h5 class="fw-bold text-dark mt-2"><?= $t['kode_transaksi']; ?></h5>
                      <p class="mb-0 text-muted small">Tanggal: <?= date('d/m/Y', strtotime($t['tgl_transaksi'])); ?></p>
                      
                      <!-- BAGIAN YANG DIPERBAIKI -->
                      <small class="text-danger mt-3 d-block text-wrap" style="white-space: normal; word-break: break-word;">
                        Perhatian: Semua data detail barang di dalam transaksi ini juga akan ikut terhapus permanen!
                      </small>
                      
                    </div>
                    <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                      <button type="button" class="btn-custom btn-custom-light px-4" data-bs-dismiss="modal">Batal</button>
                      <form action="/transaksi/delete/<?= $t['id']; ?>" method="POST" class="d-inline">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn-custom btn-custom-danger px-4">Ya, Hapus Data</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        
        <?php if(empty($transaksi)): ?>
          <tr>
              <td colspan="6" class="text-center py-4 text-muted">Belum ada data transaksi.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  
  <!-- Footer Controls / Pagination -->
  <div class="mt-3">
      <?= $pager->links('transaksi', 'bootstrap_pagination'); ?>
  </div>
</div>
<!-- END: Basic Table Card Container -->
<?= $this->endSection(); ?>
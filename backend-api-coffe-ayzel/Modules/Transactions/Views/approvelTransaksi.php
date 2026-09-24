<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- Alert Sukses -->
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- Alert Error -->
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- START: Basic Table Card Container -->
<div class="table-card-custom">
  <!-- Header Controls -->
  <div class="table-header-control">
    <div class="table-search-box">
      <i class="bi bi-search table-search-icon"></i>
      <input type="text" class="table-search-input" placeholder="Cari kode transaksi...">
    </div>
    
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
            <th>Status</th>
            <th width="20%" class="text-center">Aksi (Approval)</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($transaksi)) : ?>
            <tr>
                <td colspan="6" class="text-center py-4 text-muted">
                    <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                    Belum ada data transaksi yang berstatus Pending.
                </td>
            </tr>
        <?php else : ?>
            
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
                
                <td><span class="badge-table pending">Pending</span></td>
                
                <td>
                  <div class="d-flex justify-content-center gap-2"> 
                        <!-- Tombol Pemicu Modal Accept -->
                        <button type="button" class="btn-custom btn-custom-primary btn-custom-sm" title="Setujui Transaksi" 
                            data-bs-toggle="modal" data-bs-target="#acceptModal<?= $t['id']; ?>"
                            style="border-radius: 8px; padding: 0.25rem 0.5rem;">
                            <i class="bi bi-check-lg"></i>
                        </button>

                        <!-- Tombol Pemicu Modal Reject -->
                        <button type="button" class="btn-custom btn-custom-warning btn-custom-sm text-white" title="Tolak Transaksi" 
                            data-bs-toggle="modal" data-bs-target="#rejectModal<?= $t['id']; ?>"
                            style="border-radius: 8px; padding: 0.25rem 0.5rem; background-color: #dc3545; border: none;">
                            <i class="bi bi-x-lg"></i>
                        </button>
                  </div>

                  <!-- ================= MODAL SETUJUI (ACCEPT) ================= -->
                  <div class="modal fade" id="acceptModal<?= $t['id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="modal-header border-0 pb-0">
                          <h5 class="modal-title fw-bold">Konfirmasi Persetujuan</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                          <div class="mb-3">
                            <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                          </div>
                          <p class="mb-1 text-muted">Apakah Anda yakin ingin menyetujui transaksi ini?</p>
                          <h5 class="fw-bold text-dark mt-2"><?= $t['kode_transaksi']; ?></h5>
                          <p class="mb-0 text-muted small">Total: Rp <?= number_format($t['total_pembayaran'], 0, ',', '.'); ?></p>
                        </div>
                        <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                          <button type="button" class="btn-custom btn-custom-light px-4" data-bs-dismiss="modal">Batal</button>
                          <form action="/transaksi-approvel/approvel-accept/<?= $t['id']; ?>" method="POST" class="d-inline">
                              <?= csrf_field(); ?>
                              <button type="submit" class="btn-custom btn-custom-danger px-4">Ya, Setujui</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- ================= MODAL TOLAK (REJECT) ================= -->
                  <div class="modal fade" id="rejectModal<?= $t['id']; ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                      <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                        <div class="modal-header border-0 pb-0">
                          <h5 class="modal-title fw-bold">Konfirmasi Penolakan</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                          <div class="mb-3">
                            <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                          </div>
                          <p class="mb-1 text-muted">Apakah Anda yakin ingin menolak/membatalkan transaksi ini?</p>
                          <h5 class="fw-bold text-dark mt-2"><?= $t['kode_transaksi']; ?></h5>
                          <small class="text-danger mt-3 d-block text-wrap">
                            Perhatian: Stok barang akan otomatis dikembalikan ke sistem!
                          </small>
                        </div>
                        <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                          <button type="button" class="btn-custom btn-custom-light px-4" data-bs-dismiss="modal">Batal</button>
                          <form action="/transaksi-approvel/approvel-reject/<?= $t['id']; ?>" method="POST" class="d-inline">
                              <?= csrf_field(); ?>
                              <button type="submit" class="btn-custom btn-custom-danger px-4" style="background-color: #dc3545; border: none;">Ya, Tolak Transaksi</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>

                </td>
              </tr>
            <?php endforeach; ?>

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
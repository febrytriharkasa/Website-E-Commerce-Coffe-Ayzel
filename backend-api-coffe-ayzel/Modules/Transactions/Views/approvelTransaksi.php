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
    <div class="relative">
      <i class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" data-lucide="search"></i>
      <input class="text-xs pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-brand-500 focus:border-brand-500 w-44 md:w-56 transition" placeholder="Cari Transaksi..." type="text" id="searchStock"/>
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
            <th width="20%" class="text-center">Detail</th>
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
                
                <td class="text-center">
                  <div class="d-flex justify-content-center gap-1">
                    <!-- Tombol Detail -->
                    <a href="/transaksi/show/<?= $t['id']; ?>" class="btn-custom btn-custom-secondary btn-custom-sm" title="Detail Transaksi">
                        <i class="bi bi-receipt-cutoff"></i>
                    </a>
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
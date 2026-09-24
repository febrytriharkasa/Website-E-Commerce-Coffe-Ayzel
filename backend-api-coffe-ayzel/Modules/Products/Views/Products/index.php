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
      <a href="/product/create" class="btn-table-action" type="button">
        <i class="bi bi-plus-lg"></i> Tambah Produk
      </a>
    </div>
  </div>

  <!-- Responsive Table Wrapper -->
  <div class="table-responsive">
    <table class="table-custom">
      <thead>
        <tr>
            <th width="5%">No</th>
            <th width="10%">Gambar</th>
            <th width="20%">Nama Produk</th> 
            <th>Deskripsi</th>
            <th width="15%">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $no = 1 + ($pager->getPerPage('produk') * ($pager->getCurrentPage('produk') - 1)); ?>
        <?php foreach ($product as $p) : ?>
          <tr>
            <td><?= $no++; ?></td>
            <td>
                <img src="<?= base_url('imgProducts/' . $p['gambar']); ?>" alt="<?= $p['nama']; ?>" class="img-thumbnail" style="max-width: 80px;">
            </td>
            <td class="table-product-name"><?= $p['nama']; ?></td>
            <td><?= $p['deskripsi']; ?></td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <a href="/product/edit/<?= $p['id']; ?>" class="btn-custom btn-custom-warning btn-custom-sm" title="Edit row"><i class="bi bi-pencil"></i></a>
                <button type="button" class="btn-custom btn-custom-danger btn-custom-sm" title="Hapus" data-bs-toggle="modal" data-bs-target="#deleteModal<?= $p['id']; ?>">
                    <i class="bi bi-trash"></i>
                </button>
              </div>

              <!-- Delete Confirmation Modal -->
              <div class="modal fade" id="deleteModal<?= $p['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $p['id']; ?>" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                  <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                    <div class="modal-header border-0 pb-0">
                      <h5 class="modal-title font-weight-bold" id="deleteModalLabel<?= $p['id']; ?>">Konfirmasi Hapus</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                      <div class="mb-3">
                        <i class="bi bi-exclamation-triangle text-danger" style="font-size: 3rem;"></i>
                      </div>
                      <p class="mb-1 text-muted">Apakah Anda yakin ingin menghapus produk ini?</p>
                      <h6 class="fw-bold text-dark"><?= $p['nama']; ?></h6>
                      <small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>
                    </div>
                    <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                      <button type="button" class="btn-custom btn-custom-light px-4" data-bs-dismiss="modal">Batal</button>
                      <form action="/product/delete/<?= $p['id']; ?>" method="POST" class="d-inline">
                        <?= csrf_field(); ?>
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn-custom btn-custom-danger px-4">Hapus Produk</button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if(empty($product)): ?>
          <tr>
              <td colspan="6" class="text-center">Belum ada data produk.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
  <!-- Footer Controls / Pagination -->
  <?= $pager->links('produk', 'bootstrap_pagination'); ?>
</div>
<!-- END: Basic Table Card Container -->
<?= $this->endSection(); ?>
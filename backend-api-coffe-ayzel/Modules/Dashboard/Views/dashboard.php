<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

<!-- ======================================================= -->
<!-- NOTIFIKASI STOK MENIPIS (< 5) -->
<!-- ======================================================= -->
<?php if (isset($low_stock_count) && $low_stock_count > 0 && isset($low_stock_items)) : ?>
    <div class="alert alert-warning alert-dismissible fade show shadow-sm mb-4" role="alert" style="border-left: 5px solid #ffc107; border-radius: 10px;">
        <div class="d-flex align-items-start">
            <i class="bi bi-exclamation-triangle-fill text-warning me-3 mt-1" style="font-size: 2rem;"></i>
            <div>
                <h6 class="alert-heading fw-bold mb-1 text-dark">Peringatan Stok Menipis!</h6>
                <p class="mb-2 text-dark">Terdapat <strong><?= $low_stock_count; ?> varian produk</strong> yang stoknya kurang dari 5:</p>
                <ul class="mb-0 text-dark" style="padding-left: 1.2rem;">
                    <?php foreach($low_stock_items as $item): ?>
                        <li>
                            <?= $item['nama']; ?> (<?= $item['ukuran']; ?>) - Sisa Stok: 
                            <strong class="text-danger"><?= $item['stok']; ?> Unit</strong>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>
<!-- ======================================================= -->

<!-- Page Header -->
<div class="page-header d-flex justify-content-between align-items-center mb-4">
  <div>
    <h1 class="page-title fw-bold text-dark">Dashboard Analitik</h1>
    <p class="page-subtitle text-muted mb-0">Selamat datang kembali, <?= esc(session()->get('nama')); ?></p>
  </div>
  <div class="dropdown">
    <button class="btn btn-light border dropdown-toggle shadow-sm px-3 py-2 rounded-pill" type="button" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="bi bi-calendar-range me-1 text-primary"></i> Filter Waktu: <span class="fw-semibold"><?= ucfirst($period); ?></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
      <li><a class="dropdown-item py-2" href="?period=all"><i class="bi bi-globe me-2 text-muted"></i>Semua Waktu</a></li>
      <li><a class="dropdown-item py-2" href="?period=hari"><i class="bi bi-clock me-2 text-muted"></i>Hari Ini</a></li>
      <li><a class="dropdown-item py-2" href="?period=minggu"><i class="bi bi-calendar-week me-2 text-muted"></i>Minggu Ini</a></li>
      <li><a class="dropdown-item py-2" href="?period=bulan"><i class="bi bi-calendar-month me-2 text-muted"></i>Bulan Ini</a></li>
      <li><a class="dropdown-item py-2" href="?period=tahun"><i class="bi bi-calendar-check me-2 text-muted"></i>Tahun Ini</a></li>
    </ul>
  </div>
</div>

<!-- Stats Row (4 Kolom Simetris) -->
<div class="row g-3 mb-4">
  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-4 p-3 h-100 border-start border-success border-4">
      <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <span class="stat-label text-muted small text-uppercase fw-bold">Total Keuntungan</span>
          <div class="stat-value fs-5 fw-bold text-dark mt-1">Rp <?= number_format($total_keuntungan, 0, ',', '.'); ?></div>
        </div>
        <div class="ms-3 bg-success bg-opacity-10 p-3 rounded-circle text-success">
          <i class="bi bi-wallet2 fs-4"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-4 p-3 h-100 border-start border-danger border-4">
      <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <span class="stat-label text-muted small text-uppercase fw-bold">Stok Menipis</span>
          <div class="stat-value fs-5 fw-bold text-danger mt-1"><?= $low_stock_count; ?> <span class="fs-6 fw-normal text-muted">Produk</span></div>
        </div>
        <div class="ms-3 bg-danger bg-opacity-10 p-3 rounded-circle text-danger">
          <i class="bi bi-exclamation-triangle fs-4"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-4 p-3 h-100 border-start border-primary border-4">
      <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <span class="stat-label text-muted small text-uppercase fw-bold">Total Transaksi</span>
          <div class="stat-value fs-5 fw-bold text-dark mt-1"><?= number_format($total_transaksi, 0, ',', '.'); ?> <span class="fs-6 fw-normal text-muted">Nota</span></div>
        </div>
        <div class="ms-3 bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
          <i class="bi bi-receipt fs-4"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="col-xl-3 col-md-6">
    <div class="card border-0 shadow-sm rounded-4 p-3 h-100 border-start border-warning border-4">
      <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <span class="stat-label text-muted small text-uppercase fw-bold">Total Varian Produk</span>
          <div class="stat-value fs-5 fw-bold text-dark mt-1"><?= count($products); ?> <span class="fs-6 fw-normal text-muted">Item</span></div>
        </div>
        <div class="ms-3 bg-warning bg-opacity-10 p-3 rounded-circle text-warning">
          <i class="bi bi-box-seam fs-4"></i>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Chart & Quick Info Row -->
<div class="row g-4 mb-4">
  <div class="col-lg-8">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="card-title fw-bold mb-0 text-dark">Grafik Penjualan</h5>
        <span class="badge bg-light text-muted border px-2 py-1">Real-time update</span>
      </div>
      <div id="sales-chart" style="min-height: 330px;"></div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-dark text-white d-flex flex-column justify-content-between">
      <div>
        <div class="d-flex align-items-center justify-content-between mb-3">
          <h5 class="fw-bold mb-0 text-white">Ringkasan Kedai</h5>
          <i class="bi bi-cup-hot fs-3 text-warning"></i>
        </div>
        <p class="text-white-50 small">Kelola stok dan pantau laporan transaksi harian Anda langsung dari panel kontrol ini.</p>
      </div>
      <div class="bg-white bg-opacity-10 p-3 rounded-3 mt-3">
        <div class="d-flex justify-content-between align-items-center">
          <span class="small text-white-50">Status Sistem</span>
          <span class="badge bg-success">Aktif / Normal</span>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Table Row -->
<div class="row">
  <div class="col-md-12">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-header bg-white border-0 py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h5 class="card-title fw-bold mb-0 text-dark">Stok Produk per Ukuran</h5>
        <div class="d-flex align-items-center gap-2">
          <label for="stockFilter" class="small text-muted mb-0">Filter:</label>
          <select id="stockFilter" class="form-select form-select-sm w-auto shadow-none" onchange="filterStock()">
            <option value="all">Semua Stok</option>
            <option value="tersedia">Tersedia (>0)</option>
            <option value="habis">Habis (0)</option>
          </select>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase fs-7">
              <tr>
                <th class="py-3 px-4">Nama Produk</th>
                <th class="py-3 px-4">Ukuran</th>
                <th class="py-3 px-4">Sisa Stok</th>
              </tr>
            </thead>
            <tbody>
              <?php if(empty($products)): ?>
                <tr>
                  <td colspan="3" class="text-center py-4 text-muted">Belum ada data produk tersedia.</td>
                </tr>
              <?php else: ?>
                <?php foreach($products as $p): ?>
                <tr class="product-row" data-stock="<?= $p['stok']; ?>">
                  <td class="px-4 fw-semibold text-dark"><?= esc($p['nama']); ?></td>
                  <td class="px-4"><span class="badge bg-light text-dark border px-2 py-1"><?= esc($p['ukuran']); ?></span></td>
                  <td class="px-4">
                    <?php if($p['stok'] > 0): ?>
                      <span class="badge bg-success bg-opacity-10 text-success px-2 py-1 fw-bold"><?= $p['stok']; ?> Unit</span>
                    <?php else: ?>
                      <span class="badge bg-danger bg-opacity-10 text-danger px-2 py-1 fw-bold">Habis</span>
                    <?php endif; ?>
                  </td>
                </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  // Filter Stok
  function filterStock() {
    const filter = document.getElementById('stockFilter').value;
    document.querySelectorAll('.product-row').forEach(row => {
      const stock = parseInt(row.dataset.stock);
      row.style.display = (filter === 'all' || (filter === 'tersedia' && stock > 0) || (filter === 'habis' && stock === 0)) ? '' : 'none';
    });
  }

  // Chart Sales
  document.addEventListener('DOMContentLoaded', () => {
    const options = {
      series: [{ name: 'Penjualan', data: <?= $chart_data ?? '[]'; ?> }],
      chart: { type: 'area', height: 330, toolbar: { show: false } },
      xaxis: { categories: <?= $chart_labels ?? '[]'; ?> },
      stroke: { curve: 'smooth', width: 3 },
      colors: ['#0f5132'],
      fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05 } }
    };
    new ApexCharts(document.querySelector("#sales-chart"), options).render();
  });
</script>

<?= $this->endSection(); ?>
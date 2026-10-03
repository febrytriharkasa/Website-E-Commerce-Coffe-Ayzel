<?= $this->extend('layout/template'); ?>
<?= $this->section('content'); ?>

  <!-- BEGIN: MainDashboardContainer -->
  <div class="max-w-7xl mx-auto space-y-6">
    
    <!-- BEGIN: HeaderSection -->
    <header class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-2" data-purpose="dashboard-header">
      <div>
        <div class="flex items-center gap-2 mb-1">
          <h1 class="text-2xl md:text-3xl font-bold tracking-tight text-slate-900">Dashboard Analitik</h1>
          <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 glow-pulse"></span> Live Update
          </span>
        </div>
        <p class="text-sm text-slate-500 flex items-center gap-2">
          Selamat datang kembali, <span class="font-semibold text-slate-700"><?= esc(session()->get('nama')); ?></span>
          <span class="text-slate-300">•</span>
          <span class="flex items-center gap-1 text-slate-400 text-xs">
            <i class="w-3.5 h-3.5" data-lucide="clock"></i> Pembaruan terakhir: Hari ini, <?= date('H:i'); ?> WIB
          </span>
        </p>
      </div>
      
      <!-- Controls & Filter Toolbar -->
      <div class="flex flex-wrap items-center gap-2.5">
        <div class="inline-flex items-center bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
          <a href="?period=hari" class="px-3 py-1.5 text-xs font-semibold rounded-lg <?= $period === 'hari' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:text-slate-900' ?> transition">Hari Ini</a>
          <a href="?period=minggu" class="px-3 py-1.5 text-xs font-medium <?= $period === 'minggu' ? 'bg-slate-900 text-white rounded-lg' : 'text-slate-600 hover:text-slate-900' ?> transition">7 Hari</a>
          <a href="?period=bulan" class="px-3 py-1.5 text-xs font-medium <?= $period === 'bulan' ? 'bg-slate-900 text-white rounded-lg' : 'text-slate-600 hover:text-slate-900' ?> transition">Bulan Ini</a>
          <a href="?period=all" class="px-3 py-1.5 text-xs font-medium <?= $period === 'all' ? 'bg-slate-900 text-white rounded-lg' : 'text-slate-600 hover:text-slate-900' ?> transition">Semua</a>
        </div>
        <div class="relative">
          <button class="flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 text-xs font-semibold rounded-xl shadow-sm transition">
            <i class="w-3.5 h-3.5 text-slate-400" data-lucide="calendar"></i>
            <span><?= date('d M Y'); ?></span>
            <i class="w-3.5 h-3.5 text-slate-400" data-lucide="chevron-down"></i>
          </button>
        </div>
      </div>
    </header>
    <!-- END: HeaderSection -->

    <!-- BEGIN: LowStockAlert -->
    <?php if (isset($low_stock_count) && $low_stock_count > 0): ?>
      <div class="bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200/80 rounded-2xl p-3.5 px-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-xs" data-purpose="inventory-banner-alert">
        <div class="flex items-center gap-3">
          <div class="w-9 h-9 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-600 shrink-0">
            <i class="w-5 h-5" data-lucide="alert-triangle"></i>
          </div>
          <div>
            <h4 class="text-xs font-bold uppercase tracking-wider text-amber-900">Perhatian: Stok Kritis Terdeteksi</h4>
            <p class="text-xs text-amber-800">Terdapat <strong class="underline decoration-amber-400 font-semibold"><?= $low_stock_count; ?> varian produk</strong> tersisa di bawah batas aman (< 5 unit). Segera lakukan restock.</p>
          </div>
        </div>
        <div class="flex items-center gap-2 shrink-0 self-end sm:self-center">
          <button class="px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-medium text-xs rounded-lg transition shadow-sm">
            Periksa Varian Kritis
          </button>
        </div>
      </div>
    <?php endif; ?>
    <!-- END: LowStockAlert -->

    <!-- BEGIN: KpiMetricsGrid -->
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4" data-purpose="kpi-metrics-grid">
      <!-- Card 1: Total Keuntungan -->
      <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Keuntungan</span>
          <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center border border-emerald-100 group-hover:scale-105 transition">
            <i class="w-5 h-5" data-lucide="wallet"></i>
          </div>
        </div>
        <div class="flex items-baseline gap-2 mb-2">
          <span class="text-2xl font-bold text-slate-900">Rp <?= number_format($total_keuntungan, 0, ',', '.'); ?></span>
        </div>
        <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100">
          <span class="text-slate-400">Yang didapat</span>
        </div>
      </div>

      <!-- Card 2: Stok Menipis -->
      <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Stok Menipis</span>
          <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center border border-rose-100 group-hover:scale-105 transition">
            <i class="w-5 h-5" data-lucide="alert-circle"></i>
          </div>
        </div>
        <div class="flex items-baseline gap-2 mb-2">
          <span class="text-2xl font-bold text-rose-600"><?= $low_stock_count; ?> <span class="text-sm font-normal text-slate-600">Varian</span></span>
        </div>
        <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100">
          <span class="inline-flex items-center text-rose-600 font-semibold gap-0.5">
            <i class="w-3.5 h-3.5" data-lucide="alert-octagon"></i> Segera Restock
          </span>
          <span class="text-slate-400">Batas < 5 item</span>
        </div>
      </div>

      <!-- Card 3: Total Transaksi -->
      <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Transaksi</span>
          <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 group-hover:scale-105 transition">
            <i class="w-5 h-5" data-lucide="receipt"></i>
          </div>
        </div>
        <div class="flex items-baseline gap-2 mb-2">
          <span class="text-2xl font-bold text-slate-900"><?= number_format($total_transaksi, 0, ',', '.'); ?> <span class="text-sm font-normal text-slate-600">Nota</span></span>
        </div>
        <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100">
          <span class="text-slate-400">Rerata Rp <?= $total_transaksi > 0 ? number_format($total_keuntungan / $total_transaksi, 0, ',', '.') : '0'; ?>/nota</span>
        </div>
      </div>

      <!-- Card 4: Total Varian Produk -->
      <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition relative overflow-hidden group">
        <div class="flex items-center justify-between mb-3">
          <span class="text-xs font-bold uppercase tracking-wider text-slate-500">Total Varian Produk</span>
          <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center border border-amber-100 group-hover:scale-105 transition">
            <i class="w-5 h-5" data-lucide="package"></i>
          </div>
        </div>
        <div class="flex items-baseline gap-2 mb-2">
          <span class="text-2xl font-bold text-slate-900"><?= count($products); ?> <span class="text-sm font-normal text-slate-600">Item</span></span>
        </div>
        <div class="flex items-center justify-between text-xs pt-2 border-t border-slate-100">
          <span class="text-emerald-600 font-semibold flex items-center gap-1">
            <i class="w-3.5 h-3.5" data-lucide="check-circle-2"></i> <?= count($products) - $low_stock_count; ?> Aman
          </span>
        </div>
      </div>
    </section>
    <!-- END: KpiMetricsGrid -->

    <!-- BEGIN: MainContentGrid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6" data-purpose="charts-and-feed-grid">
      
      <!-- Chart Column (8 Columns) -->
      <div class="lg:col-span-8 bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs flex flex-col justify-between" data-purpose="sales-chart-card">
        <div>
          <!-- Chart Header -->
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
            <div>
              <div class="flex items-center gap-2">
                <h3 class="font-bold text-base text-slate-900">Grafik Penjualan & Arus Kas</h3>
                <span class="px-2 py-0.5 text-[10px] uppercase font-bold tracking-wider bg-slate-100 text-slate-600 rounded">Real-time</span>
              </div>
              <p class="text-xs text-slate-500 mt-0.5">Monitoring pergerakan omzet kedai Mama Ayzel per hari</p>
            </div>
          </div>
          
          <!-- Chart Canvas -->
          <div class="relative w-full h-64 select-none">
            <canvas id="sales-chart" style="max-height: 300px;"></canvas>
          </div>

          <!-- Bottom Time Axis Labels -->
          <div class="grid grid-cols-7 text-center text-xs text-slate-400 pt-4 border-t border-slate-100 mt-4">
            <?php 
            $days = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            $dates = [];
            for ($i = 6; $i >= 0; $i--) {
                $dates[] = date('d', strtotime("-$i days"));
            }
            foreach ($days as $idx => $day): 
            ?>
              <span class="<?= $idx === 6 ? 'font-bold text-brand-600' : ''; ?>"><?= $day . ' (' . $dates[$idx] . ')'; ?></span>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Micro Summary Footer -->
        <!-- <div class="mt-4 pt-4 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-3 gap-3">
          <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
            <span class="text-[11px] text-slate-500 block">Jam Ramai (Peak Hour)</span>
            <strong class="text-xs sm:text-sm font-bold text-slate-800">14:00 - 18:30 WIB</strong>
          </div>
          <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100">
            <span class="text-[11px] text-slate-500 block">Metode Pembayaran #1</span>
            <strong class="text-xs sm:text-sm font-bold text-slate-800">QRIS Dinamis (71%)</strong>
          </div>
          <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 col-span-2 sm:col-span-1">
            <span class="text-[11px] text-slate-500 block">Target Bulanan Tercapai</span>
            <strong class="text-xs sm:text-sm font-bold text-emerald-600">84.2%</strong>
          </div>
        </div> -->
      </div>

      <!-- Right Column: Ringkasan Kedai & Status (4 Columns) -->
      <div class="lg:col-span-4 bg-slate-900 text-white rounded-2xl p-5 shadow-md flex flex-col justify-between" data-purpose="store-live-feed-card">
        <div>
          <!-- Header -->
          <div class="flex items-center justify-between pb-4 border-b border-slate-800">
            <div>
              <div class="flex items-center gap-2">
                <i class="w-5 h-5 text-amber-400" data-lucide="coffee"></i>
                <h3 class="font-bold text-base text-white">Ringkasan Kedai</h3>
              </div>
              <p class="text-xs text-slate-400 mt-0.5">Operasional shift aktif Mama Ayzel</p>
            </div>
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 glow-pulse" title="Sistem Aktif"></span>
          </div>

          <!-- Quick Cashier Status -->
          <!-- <div class="my-4 p-3 rounded-xl bg-slate-800/80 border border-slate-700/60 flex items-center justify-between">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 rounded-lg bg-amber-500/20 text-amber-400 flex items-center justify-center font-bold text-xs">
                POS
              </div>
              <div>
                <p class="text-xs font-semibold text-white">Kasir Utama: Shift Sore</p>
                <p class="text-[11px] text-slate-400">Barista: Budi & Siska (Aktif)</p>
              </div>
            </div>
            <span class="text-[10px] font-medium bg-emerald-950 text-emerald-300 border border-emerald-800 px-2 py-0.5 rounded-md">Buka</span>
          </div> -->

          <!-- Recent Orders Feed -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pesanan Masuk Terbaru</h4>
              <a class="text-[11px] text-amber-400 hover:text-amber-300" href="/transaksi-approvel">Lihat Semua</a>
            </div>
            <div class="space-y-2.5 custom-scrollbar max-h-56 overflow-y-auto pr-1">
              <?php foreach (array_slice($transaksi, 0, 3) as $t): ?>
                <!-- Item Order -->
                <!-- Jika p-1 digunakan untuk jarak antar list/card, gunakan div pembungkus -->
                <div class="p-0.5">
                  <!-- Tag <a> sekarang bertindak langsung sebagai Card -->
                  <a href="/transaksi-approvel/show/<?= $t['id']; ?>" class="flex items-center justify-between p-2.5 rounded-xl bg-slate-800/40 border border-slate-800 hover:bg-slate-800 transition group">
                    
                    <!-- Bagian Kiri (Teks) -->
                    <div>
                      <p class="text-xs font-semibold text-white"><?= esc($t['kode_transaksi']); ?></p>
                      <p class="text-[11px] text-slate-400 mt-0.5">
                        <?= ucfirst($t['status_transaksi']); ?> • <?= date('H:i', strtotime($t['tgl_transaksi'])); ?> WIB
                      </p>
                    </div>
                    
                    <!-- Bagian Kanan (Badge) -->
                    <div class="text-right">
                      <span class="inline-block text-[9px] px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-300 font-medium group-hover:bg-emerald-500/20 transition">
                        <?php if ($t['status_transaksi'] === 'selesai'): ?>
                          Selesai
                        <?php elseif ($t['status_transaksi'] === 'pending'): ?>
                          Pending
                        <?php else: ?>
                          <?= ucfirst($t['status_transaksi']); ?>
                        <?php endif; ?>
                      </span>
                    </div>
                    
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>

        <!-- Footer Card Status -->
        <div class="pt-4 mt-4 border-t border-slate-800 flex items-center justify-between text-xs">
          <div class="flex items-center gap-1.5 text-slate-400">
            <i class="w-4 h-4 text-emerald-400" data-lucide="shield-check"></i>
            <span>Status Sistem Kedai</span>
          </div>
          <span class="text-emerald-400 font-semibold bg-emerald-950/60 border border-emerald-800/80 px-2 py-0.5 rounded-md text-[11px]">
            Aktif / Normal
          </span>
        </div>
      </div>
    </div>
    <!-- END: MainContentGrid -->

    <!-- BEGIN: StockInventorySection -->
    <section class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-xs" data-purpose="inventory-table-container">
      <!-- Section Header & Filters -->
      <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-slate-100">
        <div>
          <h3 class="font-bold text-base text-slate-900">Stok Produk per Ukuran</h3>
          <p class="text-xs text-slate-500">Pantau sisa persediaan botol minuman Mama Ayzel secara akurat</p>
        </div>
        
        <!-- Search and Filter Pills -->
        <div class="flex flex-wrap items-center gap-2.5">
          <!-- Quick Search -->
          <div class="relative">
            <i class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" data-lucide="search"></i>
            <input class="text-xs pl-9 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-1 focus:ring-brand-500 focus:border-brand-500 w-44 md:w-56 transition" placeholder="Cari varian kopi..." type="text" id="searchStock"/>
          </div>
          <!-- Dropdown Filter for Category -->
          <div class="relative">
            <select class="text-xs py-2 pl-3 pr-8 bg-slate-50 border border-slate-200 rounded-xl font-medium text-slate-700 focus:ring-brand-500 focus:border-brand-500 cursor-pointer" id="filterStock">
              <option value="all">Semua Stok</option>
              <option value="low">Hampir Habis (< 5)</option>
              <option value="safe">Stok Aman (> 15)</option>
            </select>
          </div>
        </div>
      </div>

      <!-- Modern Responsive Table -->
      <div class="overflow-x-auto mt-2">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="text-[11px] font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100">
              <th class="py-3 px-3">Nama Produk</th>
              <th class="py-3 px-3 text-center">Kategori</th>
              <th class="py-3 px-3 text-center">Ukuran Botol</th>
              <th class="py-3 px-3">Tingkat Persediaan</th>
              <th class="py-3 px-3 text-center">Sisa Stok</th>
              <th class="py-3 px-3 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 text-xs">
            <?php foreach ($products as $p): ?>
              <tr class="hover:bg-slate-50/80 transition group product-row" data-stock="<?= $p['stok']; ?>">
                <td class="py-3.5 px-3">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-100/60 text-amber-700 flex items-center justify-center font-bold text-xs shrink-0">
                      <?= strtoupper(substr($p['nama'], 0, 2)); ?>
                    </div>
                    <div>
                      <span class="font-bold text-slate-800 block"><?= esc($p['nama']); ?></span>
                      <span class="text-[11px] text-slate-400">SKU: <?= esc($p['ukuran']); ?></span>
                    </div>
                  </div>
                </td>
                <td class="py-3.5 px-3 text-center">
                  <span class="inline-flex tems-center px-2 py-0.5 rounded-md bg-slate-100 text-slate-700 text-[10px] font-semibold"><?= ucwords(str_replace('-', ' ', $p['jenis'])); ?></span>
                </td>
                <td class="py-3.5 px-3 text-center">
                  <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                    <?= esc($p['ukuran']); ?>
                  </span>
                </td>
                <td class="py-3.5 px-3 w-48">
                  <?php 
                  $percentage = $p['stok'] > 0 ? min(100, ($p['stok'] / 20) * 100) : 0;
                  $barColor = $percentage > 50 ? 'bg-emerald-500' : ($percentage > 20 ? 'bg-amber-500' : 'bg-rose-500');
                  ?>
                  <div class="flex items-center gap-2">
                    <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                      <div class="<?= $barColor; ?> h-full rounded-full" style="width: <?= $percentage; ?>%"></div>
                    </div>
                    <span class="text-[10px] text-slate-400 shrink-0"><?= round($percentage); ?>%</span>
                  </div>
                </td>
                <td class="py-3.5 px-3 text-center">
                  <?php if ($p['stok'] > 5): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                      <?= $p['stok']; ?> Unit
                    </span>
                  <?php elseif ($p['stok'] > 0): ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200 animate-pulse">
                      <?= $p['stok']; ?> Unit
                    </span>
                  <?php else: ?>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                      Habis
                    </span>
                  <?php endif; ?>
                </td>
                <td class="py-3.5 px-3 text-right">
                <?php if ($p['stok'] < 5): ?>
                  <!-- Tambahkan data-bs-toggle="modal" di sini -->
                  <button class="px-2.5 py-1 text-xs font-semibold rounded-lg btn-custom btn-custom-danger" data-bs-toggle="modal" data-bs-target="#tambahStokModalDashboard<?= $p['id']; ?>">
                    Restock Segera
                  </button>
                <?php else: ?>
                  <!-- Tambahkan data-bs-toggle="modal" di sini -->
                  <button class="px-2.5 py-1 text-xs font-semibold rounded-lg btn-custom btn-custom-primary" data-bs-toggle="modal" data-bs-target="#tambahStokModalDashboard<?= $p['id']; ?>">
                    Restock
                  </button>
                <?php endif; ?>
                  <!-- Tambah Stok Pop Up -->
                  <div class="modal fade" id="tambahStokModalDashboard<?= $p['id']; ?>" tabindex="-1" aria-labelledby="deleteModalLabel<?= $p['id']; ?>" aria-hidden="true">
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
                          <form action="/sizes-product/update-stok/<?= $p['id']; ?>" method="POST" class="w-100">
                            <?= csrf_field(); ?>
                            
                            <!-- Input Field -->
                            <div class="mb-4">
                              <input type="number" 
                                    class="w-full px-4 py-3 text-sm bg-white border rounded-xl focus:outline-none focus:ring-1 transition-all text-center" 
                                    id="tambahan_stok_<?= $p['id']; ?>" 
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
          </tbody>
        </table>
      </div>

      <!-- Pagination and Table Metadata -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-4 border-t border-slate-100 text-xs text-slate-500">
        <p>Menampilkan <strong><?= count($products); ?></strong> dari <strong><?= count($products); ?></strong> varian produk kedai</p>
      </div>
    </section>
    <!-- END: StockInventorySection -->

    <!-- BEGIN: DashboardFooter -->
    <footer class="pt-2 pb-6 text-center text-xs text-slate-400 border-t border-slate-200/60" data-purpose="system-footer">
      <div class="flex flex-col sm:flex-row items-center justify-between gap-2">
        <p>© 2026 Mama Ayzel Coffee & Beverages POS • Cloud Sync Aktif</p>
        <div class="flex items-center gap-4 text-[11px]">
          <a class="hover:text-slate-600" href="#">Panduan POS</a>
          <a class="hover:text-slate-600" href="#">Laporan Akuntansi</a>
          <a class="hover:text-slate-600" href="#">Bantuan Teknis</a>
        </div>
      </div>
    </footer>
    <!-- END: DashboardFooter -->

  </div>
  <!-- END: MainDashboardContainer -->

  <!-- Chart.js for Sales Chart -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <!-- Initialize Lucide Icons -->
  <script>
    lucide.createIcons();
    
    // Stock search filter
    document.getElementById('searchStock').addEventListener('keyup', function() {
      const searchTerm = this.value.toLowerCase();
      document.querySelectorAll('.product-row').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
      });
    });

    // Stock level filter
    document.getElementById('filterStock').addEventListener('change', function() {
      const filter = this.value;
      document.querySelectorAll('.product-row').forEach(row => {
        const stock = parseInt(row.dataset.stock);
        let show = false;
        if (filter === 'all') show = true;
        else if (filter === 'low' && stock < 5) show = true;
        else if (filter === 'safe' && stock > 15) show = true;
        row.style.display = show ? '' : 'none';
      });
    });

    // Sales Chart
    document.addEventListener('DOMContentLoaded', () => {
      const chartData = <?= $chart_data ?? '[]'; ?>;
      const chartLabels = <?= $chart_labels ?? '[]'; ?>;
      
      if (document.querySelector('#sales-chart') && chartData.length > 0) {
        const ctx = document.getElementById('sales-chart').getContext('2d');
        new Chart(ctx, {
          type: 'line',
          data: {
            labels: chartLabels,
            datasets: [{
              label: 'Penjualan',
              data: chartData,
              borderColor: '#b47b38',
              backgroundColor: 'rgba(180, 123, 56, 0.1)',
              borderWidth: 3,
              fill: true,
              tension: 0.4,
              pointBackgroundColor: '#b47b38',
              pointBorderColor: '#fff',
              pointBorderWidth: 2,
              pointRadius: 5
            }]
          },
          options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
              legend: { display: false }
            },
            scales: {
              y: {
                beginAtZero: true,
                ticks: { callback: function(v) { return 'Rp ' + (v/1000).toFixed(0) + 'k'; } }
              }
            }
          }
        });
      }
    });
  </script>
<?= $this->endSection(); ?>
<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Alert Error -->
<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- ==========================================
        START: Main Content Area
        ========================================== -->
<!-- START: Page Header Banner -->
<div class="page-header">
  <div>
    <h1 class="page-title">Tambah Transaksi Baru</h1>
  </div>
</div>
<!-- END: Page Header Banner -->

<!-- START: Form Component Row Grid Layout -->
<form action="/transaksi/store" method="POST">
  <?= csrf_field(); ?> 
  
  <div class="row g-4 mb-4">
    <!-- Column 1: Informasi Transaksi (Kiri) -->
    <div class="col-md-4">
      <div class="card border-light shadow-sm p-4 h-100">
        <h5 class="card-title mb-4">Informasi Transaksi</h5>

        <!-- Tanggal Transaksi -->
        <div class="mb-3">
          <label for="tgl_transaksi" class="form-label-custom">Tanggal Transaksi</label>
          <input 
            type="datetime-local" 
            class="w-full px-4 py-3 text-sm bg-white border rounded-xl focus:outline-none focus:ring-1 transition-all <?= (validation_show_error('tgl_transaksi')) ? 'is-invalid' : ''; ?>" 
            id="tgl_transaksi" 
            name="tgl_transaksi" 
            value="<?= old('tgl_transaksi', date('Y-m-d\TH:i')); ?>" 
            required>
          <div class="form-feedback-custom invalid-custom">
            <?= validation_show_error('tgl_transaksi'); ?>
          </div>
          <div class="form-text mt-2">Format: Bulan/Tanggal/Tahun Jam:Menit</div>
        </div>

        <!-- Dropdown Status Transaksi -->
        <div class="mb-3">
          <label for="status_transaksi" class="form-label-custom">Status Transaksi</label>
          <select name="status_transaksi" id="status_transaksi" class="w-full px-4 py-3 text-sm bg-white border rounded-xl focus:outline-none focus:ring-1 transition-all <?= (validation_show_error('status_transaksi')) ? 'is-invalid' : ''; ?>" required>
              <option value="pending" <?= old('status_transaksi') ; ?>>Pending (Menunggu Pembayaran)</option>
              <option value="selesai" <?= old('status_transaksi') ; ?>>Selesai (Sudah Lunas)</option>
          </select>
          <div class="form-feedback-custom invalid-custom">
            <?= validation_show_error('status_transaksi'); ?>
          </div>
        </div>
        
      </div>
    </div>

    <!-- Column 2: Detail Produk yang dibeli (Kanan) -->
    <div class="col-md-8">
      <div class="card border-light shadow-sm p-4 h-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Daftar Produk</h5>
            <button type="button" class="btn-table-action" id="btnTambahProduk">
                <i class="bi bi-plus-lg"></i> Tambah Baris Baru
            </button>
        </div>

        <!-- Tabel Form Dinamis -->
        <div class="table-responsive">
            <table class="table-custom" id="tabelProduk">
                <thead>
                    <tr>
                        <th width="60%">Pilih Produk (Nama - Ukuran)</th>
                        <th width="25%">Kuantitas (Qty)</th>
                        <th width="15%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbodyProduk">
                    <!-- Baris Pertama (Default) -->
                    <tr>
                        <td>
                            <select name="size_product_id[]" class="select2-search text-sm bg-white border rounded-xl focus:outline-none focus:ring-1 transition-all" required>
                                <option value="" selected disabled>-- Pilih Produk --</option>
                                <?php foreach($proudcts as $p) : ?>
                                    <!-- Menampilkan Nama Produk, Ukuran, dan Harga Jual di dropdown -->
                                    <option value="<?= $p['id']; ?>">
                                        <?= $p['nama']; ?> - <?= $p['ukuran']; ?> (Rp <?= number_format($p['harga_jual'], 0, ',', '.'); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="qty[]" class="w-full px-4 py-3 text-sm bg-white border rounded-xl focus:outline-none focus:ring-1 transition-all" min="1" value="1" placeholder="Qty" required>
                        </td>
                        <td class="text-center">
                            <button type="button" class="btn-custom btn-custom-danger btn-custom-sm btnHapusBaris" disabled title="Minimal 1 produk">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-between mt-4">
          <a href="/transaksi" class="btn-custom btn-custom-light" type="button">Kembali</a>
          <button class="btn-custom btn-custom-primary" type="submit" id="btnSubmit">Simpan Transaksi</button>
        </div>
      </div>
    </div>
  </div>
</form>
<!-- END: Form Component Row Grid Layout -->

<!-- Script untuk Dynamic Form (Tambah/Hapus Baris Produk) -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnTambah = document.getElementById('btnTambahProduk');
    const tbody = document.getElementById('tbodyProduk');

    // 1. Inisialisasi Select2 pada baris pertama saat halaman dimuat
    $('.select2-search').select2({
        placeholder: "-- Pilih Produk --",
        allowClear: true,
        width: '100%'
    });

    // 2. Fungsi Tambah Baris
    btnTambah.addEventListener('click', function() {
        // Ambil elemen baris pertama
        const firstRow = tbody.firstElementChild;
        const newRow = firstRow.cloneNode(true);

        // A. Hapus elemen pembungkus desain Select2 yang ikut ter-copy
        const select2Container = newRow.querySelector('.select2-container');
        if (select2Container) {
            select2Container.remove();
        }

        // B. Cari elemen select di dalam baris baru
        const select = newRow.querySelector('.select2-search');
        
        // C. BERSIHKAN SEMUA ATRIBUT 'data-select2-id' (Ini penyebab utama bug-nya)
        newRow.querySelectorAll('[data-select2-id]').forEach(el => {
            el.removeAttribute('data-select2-id');
        });

        // D. Bersihkan atribut sisa Select2 dari tag <select>
        select.classList.remove('select2-hidden-accessible');
        select.removeAttribute('tabindex');
        select.removeAttribute('aria-hidden');
        
        // E. Hapus status "selected" dari option yang tidak sengaja terbawa dari baris pertama
        newRow.querySelectorAll('option').forEach(opt => {
            opt.selected = false;
        });

        // F. Reset pilihan produk ke default (kosong)
        select.value = ""; 

        // G. Reset input kuantitas kembali ke angka 1
        const qtyInput = newRow.querySelector('input[type="number"]');
        if (qtyInput) {
            qtyInput.value = 1;
        }

        // Masukkan baris yang sudah bersih ke dalam tabel
        tbody.appendChild(newRow);

        // 3. Inisialisasi ulang Select2 HANYA pada baris yang baru saja ditambahkan
        $(select).select2({
            placeholder: "-- Pilih Produk --",
            allowClear: true,
            width: '100%'
        });
        
        // Perbarui status tombol hapus
        updateDeleteButtons();
    });

    // 4. Fungsi Hapus Baris (Event Delegation)
    tbody.addEventListener('click', function(e) {
        const btnHapus = e.target.closest('.btnHapusBaris');
        
        if (btnHapus) {
            if (tbody.children.length > 1) {
                // Hancurkan instance Select2 sebelum menghapus baris dari DOM untuk mencegah memory leak
                const selectInRow = btnHapus.closest('tr').querySelector('.select2-search');
                if ($(selectInRow).data('select2')) {
                    $(selectInRow).select2('destroy');
                }
                
                btnHapus.closest('tr').remove();
                updateDeleteButtons();
            }
        }
    });

    // 5. Fungsi untuk disable/enable tombol hapus
    function updateDeleteButtons() {
        const rows = tbody.children;
        const deleteButtons = tbody.querySelectorAll('.btnHapusBaris');
        
        if (rows.length === 1) {
            deleteButtons[0].disabled = true;
            deleteButtons[0].setAttribute('title', 'Minimal 1 produk');
            deleteButtons[0].classList.add('opacity-50', 'cursor-not-allowed'); // Opsional: Tambahan styling disable
        } else {
            deleteButtons.forEach(btn => {
                btn.disabled = false;
                btn.removeAttribute('title');
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            });
        }
    }
});
</script>

<?= $this->endSection(); ?>
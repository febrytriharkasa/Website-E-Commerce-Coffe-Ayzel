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
    <h1 class="page-title">Edit Transaksi</h1>
  </div>
</div>
<!-- END: Page Header Banner -->

<!-- START: Form Component Row Grid Layout -->
<form action="/transaksi/update/<?= $transaksi['id']; ?>" method="POST">
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
            class="form-control-custom <?= (validation_show_error('tgl_transaksi')) ? 'is-invalid' : ''; ?>" 
            id="tgl_transaksi" 
            name="tgl_transaksi" 
            value="<?= old('tgl_transaksi', date('Y-m-d\TH:i', strtotime($transaksi['tgl_transaksi']))); ?>" 
            required>
          <div class="form-feedback-custom invalid-custom">
            <?= validation_show_error('tgl_transaksi'); ?>
          </div>
          <div class="form-text mt-2">Format: Bulan/Tanggal/Tahun Jam:Menit</div>
        </div>

        <!-- Dropdown Status Transaksi -->
        <div class="mb-3">
          <label for="status_transaksi" class="form-label-custom">Status Transaksi</label>
          <?php 
               $statusSaatIni = old('status_transaksi', $transaksi['status_transaksi']);
            
            ?>
          <select name="status_transaksi" id="status_transaksi" class="form-control-custom <?= (validation_show_error('status_transaksi')) ? 'is-invalid' : ''; ?>" required>
              <option value="pending" <?= ($statusSaatIni == 'pending') ? 'selected' : ''; ?>>Pending (Menunggu Pembayaran)</option>
              <option value="selesai" <?= ($statusSaatIni == 'selesai') ? 'selected' : ''; ?>>Selesai (Sudah Lunas)</option>
              <option value="batal" <?= ($statusSaatIni == 'batal') ? 'selected' : ''; ?>>Batal (Dibatalkan)</option>
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
            <button type="button" class="btn-custom btn-custom-info btn-custom-sm" id="btnTambahProduk">
                <i class="bi bi-plus-lg"></i> Tambah Baris
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
                <!-- 1. Perulangan diletakkan di LUAR tag <tr> agar setiap barang yang dibeli dibuatkan barisnya -->
                <?php foreach ($detail as $index => $d) : ?>
                    <tr>
                        <!-- Kolom Produk & Ukuran -->
                        <td>
                            <select name="size_product_id[]" class="form-control-custom" required>
                                <option value="" disabled>-- Pilih Produk --</option>
                                <?php foreach($products as $p) : ?>
                                    <?php 
                                        // 2. Logika perbandingan yang benar:
                                        // Cek apakah ID opsi ini sama dengan size_product_id dari tabel detail transaksi
                                        $isMatch = ($d['size_product_id'] == $p['id']);
                                        
                                        // Gunakan index array pada old() agar data tidak tertukar saat error validasi
                                        $isSelected = (old('size_product_id.'.$index, $d['size_product_id']) == $p['id']) ? 'selected' : ''; 
                                    ?>
                                    <option value="<?= $p['id']; ?>" <?= $isSelected; ?>>
                                        <?= $p['nama']; ?> - <?= $p['ukuran']; ?> (Rp <?= number_format($p['harga_jual'], 0, ',', '.'); ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        
                        <!-- Kolom Qty -->
                        <td>
                            <input type="number" name="qty[]" class="form-control-custom" min="1" value="<?= old('qty.'.$index, $d['qty']) ?>" placeholder="Qty" required>
                        </td>
                        
                        <!-- Kolom Aksi Hapus -->
                        <td class="text-center">
                            <button type="button" class="btn-custom btn-custom-danger btn-custom-sm btnHapusBaris" <?= (count($detail) == 1) ? 'disabled title="Minimal 1 produk"' : ''; ?>>
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
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

    // Mengambil HTML dari baris pertama untuk dicopy (kloning)
    const rowTemplate = tbody.firstElementChild.outerHTML;

    // Fungsi Tambah Baris
    btnTambah.addEventListener('click', function() {
        // Append baris baru
        tbody.insertAdjacentHTML('beforeend', rowTemplate);
        
        // Aktifkan semua tombol hapus (karena baris > 1)
        updateDeleteButtons();
    });

    // Fungsi Hapus Baris (Event Delegation)
    tbody.addEventListener('click', function(e) {
        // Cari tombol hapus terdekat yang diklik
        const btnHapus = e.target.closest('.btnHapusBaris');
        
        if (btnHapus) {
            // Jangan hapus jika sisa 1 baris
            if (tbody.children.length > 1) {
                btnHapus.closest('tr').remove();
                updateDeleteButtons();
            }
        }
    });

    // Fungsi untuk disable/enable tombol hapus
    function updateDeleteButtons() {
        const rows = tbody.children;
        const deleteButtons = tbody.querySelectorAll('.btnHapusBaris');
        
        if (rows.length === 1) {
            // Jika sisa 1 baris, matikan tombol hapus
            deleteButtons[0].disabled = true;
            deleteButtons[0].setAttribute('title', 'Minimal 1 produk');
        } else {
            // Jika lebih dari 1 baris, nyalakan semua tombol hapus
            deleteButtons.forEach(btn => {
                btn.disabled = false;
                btn.removeAttribute('title');
            });
        }
    }
});

</script>

<?= $this->endSection(); ?>
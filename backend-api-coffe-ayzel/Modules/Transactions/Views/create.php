<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

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
            class="form-control-custom <?= (validation_show_error('tgl_transaksi')) ? 'is-invalid' : ''; ?>" 
            id="tgl_transaksi" 
            name="tgl_transaksi" 
            value="<?= old('tgl_transaksi', date('Y-m-d\TH:i')); ?>" 
            required>
          <div class="form-feedback-custom invalid-custom">
            <?= validation_show_error('tgl_transaksi'); ?>
          </div>
          <div class="form-text mt-2">Format: Bulan/Tanggal/Tahun Jam:Menit</div>
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
                    <!-- Baris Pertama (Default) -->
                    <tr>
                        <td>
                            <select name="size_product_id[]" class="form-control-custom" required>
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
                            <input type="number" name="qty[]" class="form-control-custom" min="1" value="1" placeholder="Qty" required>
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
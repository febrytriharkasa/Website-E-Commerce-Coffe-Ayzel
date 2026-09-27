<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- START: Page Header Banner -->
<div class="page-header d-flex justify-content-between align-items-center">
  <div>
    <h1 class="page-title">Detail Transaksi</h1>
  </div>
</div>
<!-- END: Page Header Banner -->

<!-- START: Invoice Card Layout -->
<div class="row justify-content-center mb-4">
    <div class="col-md-10">
        <div class="card border-light shadow-sm p-4 h-100">
            
            <!-- Bagian Atas: Info Transaksi & Status -->
            <div class="row border-bottom pb-4 mb-4 align-items-center">
                <div class="col-sm-6">
                    <h4 class="fw-bold mb-1 text-primary">INVOICE</h4>
                    <!-- Asumsi field kode transaksi bernama 'kode_transaksi' di tb_transaksi -->
                    <span class="text-muted fw-semibold">#<?= $transaksi['kode_transaksi'] ?? 'TRX-'.$transaksi['id']; ?></span>
                    <div class="mt-2 text-muted small">
                        Tanggal: <?= date('d M Y, H:i', strtotime($transaksi['tgl_transaksi'])); ?>
                    </div>
                </div>
                <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                    <?php 
                        // Logika warna badge status
                        $status = strtolower($transaksi['status_transaksi']);
                        $badgeBg = 'bg-secondary';
                        if ($status == 'selesai') $badgeBg = 'bg-success';
                        if ($status == 'pending') $badgeBg = 'bg-warning text-dark';
                        if ($status == 'batal') $badgeBg = 'bg-danger';
                    ?>
                    <span class="badge <?= $badgeBg; ?> rounded-pill px-4 py-2 text-uppercase">
                        <?= $status; ?>
                    </span>
                </div>
            </div>

            <!-- Bagian Tengah: Tabel Item Produk -->
            <div class="table-responsive">
                <table class="table-custom w-100">
                    <thead>
                        <tr class="bg-light">
                            <th width="5%" class="text-center">No</th>
                            <th width="45%">Deskripsi Produk</th>
                            <th width="15%" class="text-end">Harga Satuan</th>
                            <th width="10%" class="text-center">Qty</th>
                            <th width="25%" class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($detail)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Data detail produk tidak ditemukan.</td>
                            </tr>
                        <?php else: ?>
                            <?php $no = 1; foreach ($detail as $d) : ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                <td>
                                    <span class="fw-bold d-block"><?= $d['nama']; ?></span>
                                    <span class="text-muted small">Ukuran: <?= $d['ukuran']; ?></span>
                                </td>
                                <!-- Pastikan field di tabel detail Anda adalah 'harga_satuan' dan 'subtotal' -->
                                <td class="text-end">Rp <?= number_format($d['harga_satuan'] ?? 0, 0, ',', '.'); ?></td>
                                <td class="text-center"><?= $d['qty']; ?></td>
                                <td class="text-end fw-semibold">Rp <?= number_format($d['subtotal'] ?? 0, 0, ',', '.'); ?></td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                    <!-- Bagian Bawah Tabel: Kalkulasi Total -->
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end fw-bold py-3 text-muted">Total Pembayaran</td>
                            <!-- Pastikan field di tabel transaksi Anda adalah 'total_pembayaran' -->
                            <td class="text-end fw-bold fs-5 py-3 text-primary">
                                Rp <?= number_format($transaksi['total_pembayaran'] ?? 0, 0, ',', '.'); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                <a href="/transaksi-approvel" class="btn-custom btn-custom-light">
                    <i class="bi bi-arrow-left me-1"></i> Kembali
                </a>
                
                <?php if($transaksi['status_transaksi'] == 'selesai') : ?>
                    <button type="button" onclick="window.print()" class="btn-custom btn-custom-info">
                        <i class="bi bi-printer me-1"></i> Cetak Invoice
                    </button>
                    
                <?php elseif($transaksi['status_transaksi'] == 'pending') : ?>
                    <!-- Tombol Pemicu Modal Accept -->
                    <button type="button" class="btn-custom btn-custom-primary" title="Setujui Transaksi" 
                        data-bs-toggle="modal" data-bs-target="#acceptModal<?= $transaksi['id']; ?>">
                        <i class="bi bi-check-lg me-1"></i> Selesai
                    </button>

                    <!-- Tombol Pemicu Modal Reject -->
                    <button type="button" class="btn-custom btn-custom-danger" title="Tolak Transaksi" 
                        data-bs-toggle="modal" data-bs-target="#rejectModal<?= $transaksi['id']; ?>">
                        <i class="bi bi-x-lg me-1"></i> Batal
                    </button>
                <?php endif ; ?>
            </div>

            <!-- ================= MODAL SETUJUI (ACCEPT) ================= -->
            <div class="modal fade" id="acceptModal<?= $transaksi['id']; ?>" tabindex="-1" aria-hidden="true">
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
                    <h5 class="fw-bold text-dark mt-2"><?= $transaksi['kode_transaksi']; ?></h5>
                    <p class="mb-0 text-muted small">Total: Rp <?= number_format($transaksi['total_pembayaran'], 0, ',', '.'); ?></p>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                    <button type="button" class="btn-custom btn-custom-light px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="/transaksi-approvel/approvel-accept/<?= $transaksi['id']; ?>" method="POST" class="d-inline">
                        <?= csrf_field(); ?>
                        <button type="submit" class="btn-custom btn-custom-danger px-4">Ya, Setujui</button>
                    </form>
                </div>
                </div>
            </div>
            </div>

            <!-- ================= MODAL TOLAK (REJECT) ================= -->
            <div class="modal fade" id="rejectModal<?= $transaksi['id']; ?>" tabindex="-1" aria-hidden="true">
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
                    <h5 class="fw-bold text-dark mt-2"><?= $transaksi['kode_transaksi']; ?></h5>
                    <small class="text-danger mt-3 d-block text-wrap">
                    Perhatian: Stok barang akan otomatis dikembalikan ke sistem!
                    </small>
                </div>
                <div class="modal-footer border-0 pt-0 justify-content-center gap-2">
                    <button type="button" class="btn-custom btn-custom-light px-4" data-bs-dismiss="modal">Batal</button>
                    <form action="/transaksi-approvel/approvel-reject/<?= $transaksi['id']; ?>" method="POST" class="d-inline">
                        <?= csrf_field(); ?>
                        <button type="submit" class="btn-custom btn-custom-danger px-4" style="background-color: #dc3545; border: none;">Ya, Tolak Transaksi</button>
                    </form>
                </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<!-- END: Invoice Card Layout -->

<?= $this->endSection(); ?>
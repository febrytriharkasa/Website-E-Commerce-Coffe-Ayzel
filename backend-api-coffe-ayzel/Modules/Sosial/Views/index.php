<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>

<!-- Alert Success & Error -->
<?php if (session()->getFlashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <?= session()->getFlashdata('success'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
        <?= session()->getFlashdata('error'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<!-- START: Page Header -->
<div class="page-header mb-4">
  <div>
    <h1 class="page-title">Pengaturan Sosial Media & Kontak</h1>
    <p class="text-sm text-slate-500 mt-1">Atur tautan yang akan dihubungkan ke frontend website via API.</p>
  </div>
</div>
<!-- END: Page Header -->

<div class="row g-4 mb-4">
  
  <!-- KIRI: Form Tambah Baru -->
  <div class="col-md-4">
    <form action="/settings/store" method="POST">
      <?= csrf_field(); ?> 
      <div class="card border-light shadow-sm p-4 h-100">
        <h5 class="card-title mb-4">Tambah Platform Baru</h5>

        <!-- Nama Platform -->
        <div class="mb-4">
          <label for="key_name" class="block text-sm font-semibold text-slate-800 mb-2">Nama Platform</label>
          <input 
            type="text" 
            class="w-full px-4 py-3 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:border-slate-500 focus:ring-slate-500 transition-all" 
            id="key_name" 
            name="key_name" 
            placeholder="Contoh: Instagram, TikTok, WhatsApp" 
            required>
        </div>

        <!-- Nilai / Link -->
        <div class="mb-4">
          <label for="key_value" class="block text-sm font-semibold text-slate-800 mb-2">Tautan (URL) / Nomor</label>
          <input 
            type="text" 
            class="w-full px-4 py-3 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:ring-1 focus:border-slate-500 focus:ring-slate-500 transition-all" 
            id="key_value" 
            name="key_value" 
            placeholder="Masukkan link lengkap atau nomor WA" 
            required>
        </div>
        
        <div class="mt-auto pt-3 border-t border-slate-100">
            <button class="w-full btn-custom btn-custom-primary py-2.5 rounded-xl" type="submit">
                <i class="bi bi-plus-lg me-1"></i> Tambah Platform
            </button>
        </div>
      </div>
    </form>
  </div>

  <!-- KANAN: Form Edit & Daftar (Mass Update) -->
  <div class="col-md-8">
    <form action="/settings/update" method="POST">
      <?= csrf_field(); ?> 
      <div class="card border-light shadow-sm p-4 h-100 flex flex-col">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="card-title mb-0">Daftar & Edit Tautan</h5>
        </div>

        <!-- Tabel Daftar Sosial Media -->
        <div class="table-responsive flex-grow-1">
            <table class="table-custom w-100">
                <thead>
                    <tr>
                        <th width="30%" class="text-left py-3 text-xs uppercase tracking-wider text-slate-500">Platform</th>
                        <th width="60%" class="text-left py-3 text-xs uppercase tracking-wider text-slate-500">Tautan / Nilai</th>
                        <th width="10%" class="text-center py-3 text-xs uppercase tracking-wider text-slate-500">Hapus</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($sosial)) : ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-slate-400 text-sm fst-italic">Belum ada data sosial media.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach($sosial as $s) : ?>
                            <tr class="border-b border-slate-50">
                                <td class="py-3">
                                    <!-- Menampilkan Nama Platform (Capitalized & tanpa underscore) -->
                                    <span class="font-semibold text-slate-700 text-sm">
                                        <?= ucwords(str_replace('_', ' ', esc($s['key_name']))); ?>
                                    </span>
                                    
                                    <!-- Input hidden id untuk dikirim ke fungsi updateBatch -->
                                    <input type="hidden" name="id[]" value="<?= $s['id']; ?>">
                                </td>
                                <td class="py-3 pr-4">
                                    <!-- Input Edit Link -->
                                    <input 
                                        type="text" 
                                        name="key_value[]" 
                                        class="w-full px-3 py-2 text-sm bg-slate-50 border border-slate-200 rounded-lg focus:bg-white focus:outline-none focus:ring-1 focus:border-slate-500 transition-all" 
                                        value="<?= esc($s['key_value']); ?>" 
                                        required>
                                </td>
                                <td class="py-3 text-center">
                                    <!-- Tombol Hapus (Diarahkan ke method delete) -->
                                    <a href="/sosial/delete/<?= $s['id']; ?>" class="btn-custom btn-custom-danger px-2 py-1.5 rounded-lg text-xs hover:bg-red-600 transition" onclick="return confirm('Yakin ingin menghapus platform ini?');" title="Hapus">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Tombol Simpan Perubahan -->
        <div class="mt-4 pt-4 border-t border-slate-100 text-end">
          <button class="btn-custom btn-custom-primary px-4 py-2.5 rounded-xl" type="submit">
            <i class="bi bi-save me-1"></i> Simpan Semua Perubahan
          </button>
        </div>

      </div>
    </form>
  </div>

</div>

<?= $this->endSection(); ?>
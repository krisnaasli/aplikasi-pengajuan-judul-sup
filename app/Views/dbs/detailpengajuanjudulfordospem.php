<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm rounded-4">
                <!-- Card Header -->
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3>Detail Pengajuan Judul Skripsi</h3>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <!-- Form Pengajuan -->
                    <form action="<?= base_url('/daftar-pengajuan-judul/dospem/detail/' . $pengajuan['id']); ?>" method="post">
                        <?= csrf_field() ?>

                        <!-- Nama Mahasiswa -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">Nama Mahasiswa:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" value="<?= esc($pengajuan['nama_mahasiswa']) ?>" readonly>
                            </div>
                        </div>

                        <!-- NIM -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">NIM:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" value="<?= esc($pengajuan['nim']) ?>" readonly>
                            </div>
                        </div>

                        <!-- Program Studi -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">Program Studi:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" value="<?= esc($pengajuan['program_studi']) ?>" readonly>
                            </div>
                        </div>

                        <!-- Jenjang -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">Jenjang:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" value="<?= esc($pengajuan['jenjang']) ?>" readonly>
                            </div>
                        </div>

                        <!-- Topik Penelitian -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">Topik Penelitian:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" value="<?= esc($pengajuan['topik_penelitian']) ?>" readonly>
                            </div>
                        </div>

                        <!-- Judul Penelitian -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">Judul Penelitian:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" value="<?= esc($pengajuan['judul_penelitian']) ?>" readonly>
                            </div>
                        </div>

                        <!-- Dosen Pembimbing 1 -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">Dosen Pembimbing 1:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" value="<?= esc($dosenPembimbing1['dosen_name']) ?>" readonly>
                            </div>
                        </div>

                        <!-- Dosen Pembimbing 2 -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">Dosen Pembimbing 2:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" value="<?= esc($dosenPembimbing2['dosen_name']) ?>" readonly>
                            </div>
                        </div>

                        <!-- Link untuk File Ringkasan Dasar -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">File Ringkasan Dasar:</label>
                            <div class="col-md-9">
                                <?php if ($pengajuan['ringkasan_file']): ?>
                                    <a href="<?= base_url('/daftar-pengajuan-judul/dospem/detail/uploads/ringkasan/' . $pengajuan['ringkasan_file']) ?>" target="_blank">Lihat Ringkasan</a>
                                <?php else: ?>
                                    Tidak ada file
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Link untuk File Jurnal Pendukung -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">File Jurnal Pendukung:</label>
                            <div class="col-md-9">
                                <?php if ($pengajuan['jurnal_file']): ?>
                                    <a href="<?= base_url('/daftar-pengajuan-judul/dospem/detail/uploads/jurnal/' . $pengajuan['jurnal_file']) ?>" target="_blank">Lihat Jurnal</a>
                                <?php else: ?>
                                    Tidak ada file
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Status Persetujuan -->
                        <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">Status Persetujuan:</label>
                            <div class="col-md-9">
                                <label>
                                    <input type="radio" name="status" value="approved" required>
                                    Setuju
                                </label>
                                <label>
                                    <input type="radio" name="status" value="rejected" required>
                                    Tolak
                                </label>
                            </div>
                        </div>

                        <!-- Komentar -->
                        <!-- <div class="row mb-4">
                            <label class="col-md-3 col-form-label text-start">Komentar:</label>
                            <div class="col-md-9">
                                <textarea name="komentar" rows="4" class="form-control" placeholder="Masukkan komentar jika diperlukan..."></textarea>
                            </div>
                        </div> -->

                        <!-- Button Kirim -->
                        <div class="form-group row mb-4">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn btn-success btn-lg w-100">Kirim</button>
                            </div>
                        </div>

                        <!-- Button Kembali -->
                        <div class="form-group row mb-4">
                            <div class="col-md-9 offset-md-3">
                                <a href="<?= base_url('/dashboard/dospem/'); ?>" class="btn btn-secondary btn-lg w-100">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

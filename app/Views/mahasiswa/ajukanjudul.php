<div class="alert alert-info mt-3">
    <?= $statusMessage ?>
</div>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3>Ajukan Judul Skripsi</h3>
                </div>

                <div class="card-body">
                    <form action="<?= base_url('ajukan-judul') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <!-- Nama Mahasiswa (Auto-filled) -->
                        <div class="row mb-4">
                            <label for="namaMahasiswa" class="col-md-3 col-form-label text-start">Nama Mahasiswa:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" id="namaMahasiswa" name="namaMahasiswa" value="<?= esc($namaMahasiswa) ?>" readonly>
                            </div>
                        </div>

                        <!-- NIM Mahasiswa -->
                        <div class="row mb-4">
                            <label for="nim" class="col-md-3 col-form-label text-start">NIM:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" id="nim" name="nim" value="<?= esc($nim) ?>" readonly>
                            </div>
                        </div>

                        <!-- Program Studi -->
                        <div class="row mb-4">
                            <label for="programStudi" class="col-md-3 col-form-label text-start">Program Studi:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" id="programStudi" name="programStudi" value="<?= esc($programStudi) ?>" readonly>
                            </div>
                        </div>

                        <!-- Jenjang -->
                        <div class="row mb-4">
                            <label for="jenjang" class="col-md-3 col-form-label text-start">Jenjang:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" id="jenjang" name="jenjang" value="<?= esc($jenjang) ?>" readonly>
                            </div>
                        </div>

                        <!-- Topik Penelitian -->
                        <div class="row mb-4">
                            <label for="topikPenelitian" class="col-md-3 col-form-label text-start">Topik Penelitian:</label>
                            <div class="col-md-9">
                                <select class="form-select form-select-lg" id="topikPenelitian" name="topikPenelitian" <?= $isFormLocked ? 'disabled' : '' ?>>
                                    <option value="" selected>Pilih Topik Penelitian</option>
                                    <option value="ai" <?= isset($pengajuanJudul['topik_penelitian']) && $pengajuanJudul['topik_penelitian'] == 'ai' ? 'selected' : '' ?>>Artificial Intelligence</option>
                                    <option value="iot" <?= isset($pengajuanJudul['topik_penelitian']) && $pengajuanJudul['topik_penelitian'] == 'iot' ? 'selected' : '' ?>>Internet of Things</option>
                                    <option value="big-data" <?= isset($pengajuanJudul['topik_penelitian']) && $pengajuanJudul['topik_penelitian'] == 'big-data' ? 'selected' : '' ?>>Big Data</option>
                                </select>
                            </div>
                        </div>

                        <!-- Judul Penelitian -->
                        <div class="row mb-4">
                            <label for="judulPenelitian" class="col-md-3 col-form-label text-start">Usulan Judul Penelitian:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" id="judulPenelitian" name="judulPenelitian" value="<?= isset($pengajuanJudul['judul_penelitian']) ? esc($pengajuanJudul['judul_penelitian']) : '' ?>" placeholder="Masukkan Usulan Judul Penelitian" <?= $isFormLocked ? 'readonly' : '' ?> required>
                            </div>
                        </div>

                        <!-- Dosen Pembimbing 1 -->
                        <div class="row mb-4">
                            <label for="dosenPembimbing1" class="col-md-3 col-form-label text-start">Dosen Pembimbing 1:</label>
                            <div class="col-md-9">
                                <select class="form-select form-select-lg" id="dosenPembimbing1" name="dosenPembimbing1" <?= $isFormLocked ? 'disabled' : '' ?>>
                                    <option value="" selected>Pilih Dosen Pembimbing 1</option>
                                    <?php foreach ($dosen as $item): ?>
                                        <option value="<?= esc($item['dosen_id']) ?>" <?= isset($pengajuanJudul['dosen_pembimbing_1']) && $pengajuanJudul['dosen_pembimbing_1'] == esc($item['dosen_id']) ? 'selected' : '' ?>><?= esc($item['dosen_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Dosen Pembimbing 2 -->
                        <div class="row mb-4">
                            <label for="dosenPembimbing2" class="col-md-3 col-form-label text-start">Dosen Pembimbing 2:</label>
                            <div class="col-md-9">
                                <select class="form-select form-select-lg" id="dosenPembimbing2" name="dosenPembimbing2" <?= $isFormLocked ? 'disabled' : '' ?>>
                                    <option value="" selected>Pilih Dosen Pembimbing 2</option>
                                    <?php foreach ($dosen as $item): ?>
                                        <option value="<?= esc($item['dosen_id']) ?>" <?= isset($pengajuanJudul['dosen_pembimbing_2']) && $pengajuanJudul['dosen_pembimbing_2'] == esc($item['dosen_id']) ? 'selected' : '' ?>><?= esc($item['dosen_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <?php if (isset($pengajuanJudul) && $pengajuanJudul): ?>
    <!-- Menampilkan link untuk file Ringkasan Dasar -->
    <div class="row mb-4">
        <label for="ringkasanDasar" class="col-md-3 col-form-label text-start">Ringkasan Dasar Penelitian:</label>
        <div class="col-md-9">
            <a href="<?= base_url('/daftar-pengajuan-judul/dbs/detail/uploads/ringkasan/' . $pengajuanJudul['ringkasan_file']) ?>" target="_blank">Lihat Ringkasan</a>
        </div>
    </div>

    <!-- Menampilkan link untuk file Jurnal Pendukung -->
    <div class="row mb-4">
        <label for="jurnalPendukung" class="col-md-3 col-form-label text-start">Jurnal Pendukung Penelitian:</label>
        <div class="col-md-9">
            <a href="<?= base_url('/daftar-pengajuan-judul/dbs/detail/uploads/jurnal/' . $pengajuanJudul['jurnal_file']) ?>" target="_blank">Lihat Jurnal</a>
        </div>
    </div>
<?php else: ?>
    <!-- Upload Ringkasan Dasar -->
    <div class="row mb-4">
        <label for="ringkasanDasar" class="col-md-3 col-form-label text-start">Ringkasan Dasar Penelitian:</label>
        <div class="col-md-9">
            <input type="file" class="form-control" id="ringkasanDasar" name="ringkasanDasar" accept=".pdf" <?= $isFormLocked ? 'disabled' : '' ?> required>
            <small class="form-text text-muted">Upload file dalam format .pdf.</small>
        </div>
    </div>

    <!-- Upload Jurnal Pendukung Penelitian -->
    <div class="row mb-4">
        <label for="jurnalPendukung" class="col-md-3 col-form-label text-start">Jurnal Pendukung Penelitian:</label>
        <div class="col-md-9">
            <input type="file" class="form-control" id="jurnalPendukung" name="jurnalPendukung" accept=".pdf" <?= $isFormLocked ? 'disabled' : '' ?> required>
            <small class="form-text text-muted">Upload file dalam format .pdf.</small>
        </div>
    </div>
<?php endif; ?>



                        <!-- Submit Button -->
                        <div class="row mb-4">
                            <div class="col-md-9 offset-md-3">
                                <?php if (!$isFormLocked): ?>
                                    <button type="submit" class="btn btn-primary btn-lg w-100">Ajukan</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

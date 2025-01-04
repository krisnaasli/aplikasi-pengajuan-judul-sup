<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm rounded-4">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3>Pengajuan Judul Skripsi</h3>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('pengajuan-judul') ?>" method="post" enctype="multipart/form-data">
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
                                <select class="form-select form-select-lg" id="topikPenelitian" name="topikPenelitian" required>
                                    <option value="" selected>Pilih Topik Penelitian</option>
                                    <option value="ai">Artificial Intelligence</option>
                                    <option value="iot">Internet of Things</option>
                                    <option value="big-data">Big Data</option>
                                </select>
                            </div>
                        </div>

                        <!-- Judul Penelitian -->
                        <div class="row mb-4">
                            <label for="judulPenelitian" class="col-md-3 col-form-label text-start">Usulan Judul Penelitian:</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg" id="judulPenelitian" name="judulPenelitian" placeholder="Masukkan Usulan Judul Penelitian" required>
                            </div>
                        </div>

                        <!-- Dosen Pembimbing 1 -->
                        <div class="row mb-4">
                            <label for="dosenPembimbing1" class="col-md-3 col-form-label text-start">Dosen Pembimbing 1:</label>
                            <div class="col-md-9">
                                <select class="form-select form-select-lg" id="dosenPembimbing1" name="dosenPembimbing1" required>
                                    <option value="" selected>Pilih Dosen Pembimbing 1</option>
                                    <?php foreach ($dosen as $item): ?>
                                        <option value="<?= esc($item['id']) ?>"><?= esc($item['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Dosen Pembimbing 2 -->
                        <div class="row mb-4">
                            <label for="dosenPembimbing2" class="col-md-3 col-form-label text-start">Dosen Pembimbing 2:</label>
                            <div class="col-md-9">
                                <select class="form-select form-select-lg" id="dosenPembimbing2" name="dosenPembimbing2" required>
                                    <option value="" selected>Pilih Dosen Pembimbing 2</option>
                                    <?php foreach ($dosen as $item): ?>
                                        <option value="<?= esc($item['id']) ?>"><?= esc($item['name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="row mb-4">
                            <div class="col-md-9 offset-md-3">
                                <button type="submit" class="btn btn-primary btn-lg w-100">Ajukan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

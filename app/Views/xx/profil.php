<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm rounded-4 border border-2">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3>Edit Profil</h3>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('profil/update') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <!-- Nama Lengkap -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-3 col-form-label text-start">Nama Lengkap</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg border-2" id="name" name="name" value="<?= $profil['name'] ?? '' ?>" required>
                            </div>
                        </div>


                         

                        <?php if (session()->get('role') === 'mahasiswa'): ?>
                            <div class="row mb-3">
                                <label for="nim" class="col-md-3 col-form-label text-start">NIM</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control-lg border-2" id="nim" name="nim" value="<?= $profil['nim'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="programstudi" class="col-md-3 col-form-label text-start">Program Studi</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control-lg border-2" id="programstudi" name="programstudi" value="<?= $profil['program_studi'] ?? '' ?>" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <label for="jenjang" class="col-md-3 col-form-label text-start">Jenjang</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control-lg border-2" id="jenjang" name="jenjang" value="<?= $profil['jenjang'] ?? '' ?>" required>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row mb-3">
                                <label for="nik" class="col-md-3 col-form-label text-start">NIK</label>
                                <div class="col-md-9">
                                    <input type="text" class="form-control form-control-lg border-2" id="nik" name="nik" value="<?= $profil['nik'] ?? '' ?>" required>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="row mb-3">
                            <label for="nomorhp" class="col-md-3 col-form-label text-start">Nomor HP</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg border-2" id="nomorhp" name="nomorhp" value="<?= $profil['nomor_hp'] ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="alamat" class="col-md-3 col-form-label text-start">Alamat</label>
                            <div class="col-md-9">
                                <textarea class="form-control form-control-lg border-2" id="alamat" name="alamat" rows="3" required><?= $profil['alamat'] ?? '' ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="email" class="col-md-3 col-form-label text-start">Email</label>
                            <div class="col-md-9">
                                <input type="email" class="form-control form-control-lg border-2" id="email" name="email" value="<?= $profil['email'] ?? '' ?>" required>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="foto" class="col-md-3 col-form-label text-start">Foto Profil</label>
                            <div class="col-md-9">
                                <?php if (!empty($profil['foto'])): ?>
                                    <div class="mb-3">
                                        <img src="<?= base_url('uploads/' . $profil['foto']) ?>" alt="Foto Profil" class="img-thumbnail mb-2" style="max-width: 150px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" class="form-control form-control-lg border-2" id="foto" name="foto">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

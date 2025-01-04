<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm rounded-4 border border-2">
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3>Edit Profil</h3>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('/edit-profil/dbs') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>
                        
                        <!-- Nama Lengkap -->
                        <div class="row mb-3">
                            <label for="name" class="col-md-3 col-form-label text-start">Nama Lengkap</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg border-2" id="name" name="name" value="<?= $profil['name'] ?? '' ?>" required>
                            </div>
                        </div>

                        <!-- NIM -->
                        <div class="row mb-3">
                            <label for="nik" class="col-md-3 col-form-label text-start">NIK</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg border-2" id="nik" name="nik" value="<?= $profil['nik'] ?? '' ?>" required>
                            </div>
                        </div>

                        <!-- Nomor HP -->
                        <div class="row mb-3">
                            <label for="nomorhp" class="col-md-3 col-form-label text-start">Nomor HP</label>
                            <div class="col-md-9">
                                <input type="text" class="form-control form-control-lg border-2" id="nomorhp" name="nomorhp" value="<?= $profil['nomor_hp'] ?? '' ?>" required>
                            </div>
                        </div>

                        <!-- Alamat -->
                        <div class="row mb-3">
                            <label for="alamat" class="col-md-3 col-form-label text-start">Alamat</label>
                            <div class="col-md-9">
                                <textarea class="form-control form-control-lg border-2" id="alamat" name="alamat" rows="3" required><?= $profil['alamat'] ?? '' ?></textarea>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="row mb-3">
                            <label for="email" class="col-md-3 col-form-label text-start">Email</label>
                            <div class="col-md-9">
                                <input type="email" class="form-control form-control-lg border-2" id="email" name="email" value="<?= $profil['email'] ?? '' ?>" required>
                            </div>
                        </div>

                        <!-- Foto Profil -->
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

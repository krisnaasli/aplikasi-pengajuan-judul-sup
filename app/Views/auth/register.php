<?= $this->include('mahasiswa/layouts/header'); ?>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="row border rounded-5 p-3 bg-white shadow box-area">
    <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #094d8e; background-image: url('<?= base_url('assets/images/logo_ti-fkom-uniku.png'); ?>'); background-size: cover; background-position: center; background-repeat: no-repeat;">
    <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;"></p>
    <small class="text-white text-wrap text-center" style="width: 17rem;font-family: 'Courier New', Courier, monospace;"></small>
</div>

        <div class="col-md-6 right-box">
            <div class="row align-items-center">
                <div class="header-text mb-4">
                    <h2>Create an Account</h2>
                </div>

                <!-- Menampilkan pesan error jika ada -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Form Pendaftaran -->
                <form action="<?= base_url('register') ?>" method="post">
                    <?= csrf_field() ?>

                    <!-- Input nama lengkap -->
                    <div class="input-group mb-3">
                    <input type="text" name="name" class="form-control form-control-lg" placeholder="Full Name" required>
                    </div>

                    <!-- Input email -->
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="Email Address" required>
                    </div>
                    
                     <!-- Input Role -->
                     <div class="input-group mb-3">
                        <select name="role" class="form-control form-control-lg" required>
                            <option value="" disabled selected>Choose Role</option>
                            <option value="mahasiswa">Mahasiswa</option>
                            <option value="dospem">Dosen Pembimbing</option>
                            <option value="dbs">Dewan Bimbingan Skripsi</option>
                            <option value="kaprodi">Kaprodi</option>
                        </select>
                    </div>

                    <!-- Input password -->
                    <div class="input-group mb-3">
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required>
                    </div>

                    <!-- Input konfirmasi password -->
                    <div class="input-group mb-3">
                        <input type="password" name="password_confirm" class="form-control form-control-lg" placeholder="Confirm Password" required>
                    </div>

                    </div>

                     <!-- CSS kustom untuk tombol Login -->
                    <style>
                    .custom-btn {
                        background-color: #094d8e !important; /* Warna latar belakang kustom */
                        color: white !important; /* Warna teks hitam */
                        border: none !important; /* Menghapus border default */
                        padding: 15px; /* Menambahkan padding untuk ukuran tombol */
                        font-size: 18px; /* Ukuran font */
                        font-weight: bold; /* Menebalkan teks */
                        text-align: center; /* Menyusun teks di tengah */
                        cursor: pointer; /* Menambahkan pointer saat hover */
                        border-radius: 5px; /* Membuat sudut tombol menjadi bulat */
                        transition: background-color 0.3s ease; /* Transisi saat hover */
                    }
                    .custom-btn:hover {
                        background-color: #083e6d !important; /* Warna saat hover */
                    }
                    </style>

                    <!-- Tombol daftar -->
                    <div class="input-group mb-3">
                        <button class="btn btn-lg custom-btn w-100">Sign Up</button>
                    </div>

                    <!-- Link login untuk pengguna yang sudah punya akun -->
                    <div class="row">
                    <small>Already have an account? <a href="<?= base_url('login') ?>">Login</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('mahasiswa/layouts/footer'); ?>

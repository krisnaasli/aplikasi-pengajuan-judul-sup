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
                </div>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('login') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="input-group mb-3">
                        <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Email Address" required>
                    </div>
                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Password" required>
                    </div>

                     <!-- CSS kustom untuk tombol Login -->
                    <style>
                    .custom-btn {
                        background-color: #094d8e !important; /* Warna latar belakang kustom */
                        color: white !important; /* Warna teks putih */
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

                    <div class="input-group mb-3">
                        <button class="btn btn-lg custom-btn w-100">Login</button>
                    </div>

                    <div class="row">
                        <small>Don't have an account? <a href="<?= base_url('register') ?>">Sign Up</a></small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->include('mahasiswa/layouts/footer'); ?>

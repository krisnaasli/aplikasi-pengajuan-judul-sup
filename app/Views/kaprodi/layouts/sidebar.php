<div class="wrapper">
<aside id="sidebar" class="expand">
        <div class="d-flex">
            <button class="toggle-btn" type="button">
                <i class="lni lni-grid-alt"></i>
            </button>
            <div class="sidebar-logo">
                <a href="<?= base_url(); ?>">TI-FKOM-UNIKU</a>
            </div>
        </div>
        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a href="<?= base_url('/dashboard/kaprodi'); ?>" class="sidebar-link">
                    <i class="lni lni-user"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="sidebar-item">
                    <a href="#" class="sidebar-link collapsed has-dropdown" data-bs-toggle="collapse"
                        data-bs-target="#auth" aria-expanded="false" aria-controls="auth">
                        <i class="lni lni-layout"></i>
                        <span>Judul - Masuk</span>
                    </a>
                    <ul id="auth" class="sidebar-dropdown list-unstyled collapse" data-bs-parent="#sidebar">
                        <li class="sidebar-item">
                            <a href="<?= base_url('/daftar-pengajuan-judul/dosenpembimbing'); ?>" class="sidebar-link">Untuk Dosen Pembimbing </a>
                        </li>
                        <li class="sidebar-item">
                            <a href="<?= base_url('/daftar-pengajuan-judul/kaprodi'); ?>" class="sidebar-link">Untuk Kepala Program Studi</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-item">
                <a href="<?= base_url('/dashboard/kaprodi'); ?>" class="sidebar-link">
                    <i class="lni lni-layout"></i>
                    <span>Daftar PengajuanJudul </span>
                </a>
            </li>
        </ul>
    </aside>


<!-- app/Views/dospem/detailpengajuanjudul.php -->
<h1><?= esc($title) ?></h1>
<form action="<?= base_url('/daftar-pengajuan-judul/dosenpembimbing/detail/' . $pengajuan['id']); ?>" method="post">
    <?= csrf_field() ?>
    <table class="table">
        <tr>
            <td>Nama Mahasiswa</td>
            <td><?= esc($pengajuan['nama_mahasiswa']) ?></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td><?= esc($pengajuan['nim']) ?></td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td><?= esc($pengajuan['program_studi']) ?></td>
        </tr>
        <tr>
            <td>Jenjang</td>
            <td><?= esc($pengajuan['jenjang']) ?></td>
        </tr>
        <tr>
            <td>Topik Penelitian</td>
            <td><?= esc($pengajuan['topik_penelitian']) ?></td>
        </tr>
        <tr>
            <td>Judul Penelitian</td>
            <td><?= esc($pengajuan['judul_penelitian']) ?></td>
        </tr>
        <tr>
            <td>Dosen Pembimbing 1</td>
            <td><?= esc($dosenPembimbing1['dosen_name']) ?></td>
        </tr>
        <tr>
            <td>Dosen Pembimbing 2</td>
            <td><?= esc($dosenPembimbing2['dosen_name']) ?></td>
        </tr>

        <!-- Menampilkan link untuk file Ringkasan Dasar -->
        <tr>
            <td>File Ringkasan Dasar</td>
            <td>
                <?php if ($pengajuan['ringkasan_file']): ?>
                    <a href="<?= base_url('/daftar-pengajuan-judul/kaprodi/detail/uploads/ringkasan/' . $pengajuan['ringkasan_file']) ?>" target="_blank">Lihat Ringkasan</a>
                <?php else: ?>
                    Tidak ada file
                <?php endif; ?>
            </td>
        </tr>

        <!-- Menampilkan link untuk file Jurnal Pendukung -->
        <tr>
            <td>File Jurnal Pendukung</td>
            <td>
                <?php if ($pengajuan['jurnal_file']): ?>
                    <a href="<?= base_url('/daftar-pengajuan-judul/kaprodi/detail/uploads/jurnal/' . $pengajuan['jurnal_file']) ?>" target="_blank">Lihat Jurnal</a>
                <?php else: ?>
                    Tidak ada file
                <?php endif; ?>
            </td>
        </tr>

        <tr>
            <td>Status Persetujuan</td>
            <td>
                <label>
                    <input type="radio" name="status" value="approved" required>
                    Setuju
                </label>
                <label>
                    <input type="radio" name="status" value="rejected" required>
                    Tolak
                </label>
            </td>
        </tr>
        <tr>
            <td>Komentar</td>
            <td>
                <textarea name="komentar" rows="4" class="form-control" placeholder="Masukkan komentar jika diperlukan..."></textarea>
            </td>
        </tr>
    </table>
    <div class="form-group">
        <button type="submit" class="btn btn-success">Kirim</button>
        <a href="<?= base_url('daftar-pengajuan-judul/dosenpembimbing'); ?>" class="btn btn-secondary">Kembali</a>
    </div>
</form>

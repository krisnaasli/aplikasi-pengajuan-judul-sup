<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Judul</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Detail Pengajuan Judul</h2>

        <table class="table table-bordered">
            <tr>
                <th>Nama Mahasiswa</th>
                <td><?= $pengajuan['nama_mahasiswa'] ?></td>
            </tr>
            <tr>
                <th>NIM</th>
                <td><?= $pengajuan['nim'] ?></td>
            </tr>
            <tr>
                <th>Program Studi</th>
                <td><?= $pengajuan['program_studi'] ?></td>
            </tr>
            <tr>
                <th>Jenjang</th>
                <td><?= $pengajuan['jenjang'] ?></td>
            </tr>
            <tr>
                <th>Topik Penelitian</th>
                <td><?= $pengajuan['topik_penelitian'] ?></td>
            </tr>
            <tr>
                <th>Judul Penelitian</th>
                <td><?= $pengajuan['judul_penelitian'] ?></td>
            </tr>
            <tr>
                <th>Dosen Pembimbing 1</th>
                <td><?= $pengajuan['dosen_pembimbing_1'] ?></td>
            </tr>
            <tr>
                <th>Dosen Pembimbing 2</th>
                <td><?= $pengajuan['dosen_pembimbing_2'] ?></td>
            </tr>
            <tr>
                <th>Tanggal Pengajuan</th>
                <td><?= date('d-m-Y H:i:s', strtotime($pengajuan['tanggal_pengajuan'])) ?></td>
            </tr>
        </table>

        <a href="<?= base_url('listpengajuanjudul') ?>" class="btn btn-secondary">Kembali ke List</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<h2>List Pengajuan Judul</h2>
<table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Pengajuan</th>
                <th>Judul Penelitian</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($listpengajuanjudul)) : ?>
                <?php foreach ($listpengajuanjudul as $index => $pengajuan) : ?>
                    <tr>
                        <td><?= $index + 1; ?></td>
                        <td><?= $pengajuan['tanggal_pengajuan']; ?></td>
                        <td><?= $pengajuan['judul_penelitian']; ?></td>
                        <td>
                        <a href="<?= base_url('listpengajuanjudul/detail/' . $pengajuan['id']); ?>" class="btn btn-primary btn-sm">Lihat Detail</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data pengajuan judul.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <h2>Data Session</h2>
<ul>
    <li><strong>Role:</strong> <?= session()->get('role'); ?></li>
    <li><strong>User ID:</strong> <?= session()->get('user_id'); ?></li>
</ul>
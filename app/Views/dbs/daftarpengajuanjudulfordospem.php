<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <!-- Card dengan tampilan yang lebih rapi -->
            <div class="card shadow-sm rounded-4 border border-2">
                <!-- Card Header -->
                <div class="card-header bg-primary text-white text-center rounded-top-4">
                    <h3>Daftar Pengajuan Judul</h3>
                </div>
                <!-- Card Body -->
                <div class="card-body">
                    <!-- Tabel Daftar Pengajuan -->
                    <table class="table table-striped table-hover">
                        <thead class="table-primary text-center">
                            <tr>
                                <th>No</th>
                                <th>Topik Penelitian</th>
                                <th>Judul Penelitian</th>
                                <th>Tanggal Pengajuan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($pengajuanJudul)): ?>
                                <?php foreach ($pengajuanJudul as $index => $judul): ?>
                                    <tr>
                                        <td class="text-center"><?= $index + 1; ?></td>
                                        <td><?= esc($judul['topik_penelitian']) ?></td>
                                        <td><?= esc($judul['judul_penelitian']) ?></td>
                                        <td class="text-center"><?= esc($judul['tanggal_pengajuan']) ?></td>
                                        <td class="text-center">
                                            <a href="<?= base_url('/daftar-pengajuan-judul/dosen-pembimbing/detail/' . $judul['id']); ?>" class="btn btn-sm btn-primary">Lihat Detail</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data pengajuan judul.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

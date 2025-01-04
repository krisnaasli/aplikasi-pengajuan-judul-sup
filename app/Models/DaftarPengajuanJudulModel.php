<?php

namespace App\Models;

use CodeIgniter\Model;

class DaftarPengajuanJudulModel extends Model
{
    protected $table = 'tbl_daftarpengajuanjudul';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'mahasiswa_id', 'topik_penelitian', 'judul_penelitian', 'dosen_pembimbing_1', 'dosen_pembimbing_2', 
        'tanggal_pengajuan', 'ringkasan_file', 'jurnal_file', 'status_dospem1', 'komentar_dospem1', 'timestamp_dospem1', 
        'status_dospem2', 'komentar_dospem2', 'timestamp_dospem2', 'status_dbs', 'komentar_dbs', 'timestamp_dbs', 
        'status_kaprodi', 'komentar_kaprodi', 'timestamp_kaprodi'
    ];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk mengambil pengajuan judul berdasarkan mahasiswa_id
    public function getPengajuanByMahasiswaId($mahasiswaId)
    {
        return $this->where('mahasiswa_id', $mahasiswaId)->findAll();
    }

    // Fungsi untuk mengupdate status dospem1
    public function updateStatusDospem1($id, $status, $komentar)
{
    return $this->update($id, [
        'status_dospem1' => $status,
        'komentar_dospem1' => $komentar,
        'timestamp_dospem1' => date('Y-m-d H:i:s')
    ]);
}

    // Fungsi untuk mengupdate status dospem2
    public function updateStatusDospem2($id, $status, $komentar)
{
    return $this->update($id, [
        'status_dospem2' => $status,
        'komentar_dospem2' => $komentar,
        'timestamp_dospem2' => date('Y-m-d H:i:s')
    ]);
}

    // Fungsi untuk mengupdate status dosbs
    public function updateStatusDbs($id, $status, $komentar)
    {
        return $this->update($id, [
            'status_dbs' => $status,
            'komentar_dbs' => $komentar,
            'timestamp_dbs' => date('Y-m-d H:i:s')
        ]);
    }

    // Fungsi untuk mengupdate status kaprodi
    public function updateStatusKaprodi($id, $status, $komentar)
    {
        return $this->update($id, [
            'status_kaprodi' => $status,
            'komentar_kaprodi' => $komentar,
            'timestamp_kaprodi' => date('Y-m-d H:i:s')
        ]);
    }
}

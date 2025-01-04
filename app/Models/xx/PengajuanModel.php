<?php namespace App\Models;

use CodeIgniter\Model;

class PengajuanModel extends Model
{
    protected $table      = 'tbl_listpengajuanjudul';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama_mahasiswa', 'nim', 'program_studi', 'jenjang', 'topik_penelitian', 
        'judul_penelitian', 'dosen_pembimbing_1', 'dosen_pembimbing_2', 'tanggal_pengajuan'
    ];
    protected $useTimestamps = true; // untuk otomatis menggunakan waktu saat data dimasukkan
}

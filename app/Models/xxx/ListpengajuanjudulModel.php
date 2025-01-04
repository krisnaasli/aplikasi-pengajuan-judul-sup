<?php

namespace App\Models;

use CodeIgniter\Model;

class ListpengajuanjudulModel extends Model
{
    protected $table = 'tbl_listpengajuanjudul'; // Nama tabel di database
    protected $primaryKey = 'id'; // Primary key tabel
    protected $allowedFields = [
        'nama_mahasiswa',
        'nim',
        'program_studi',
        'jenjang',
        'topik_penelitian',
        'judul_penelitian',
        'dosen_pembimbing_1',
        'dosen_pembimbing_2',
        'tanggal_pengajuan',
        'created_at',
        'updated_at',
        'status_dospem1',
        'status_dospem2',
        'status_dbs',
        'status_kaprodi'
    ]; // Kolom yang dapat diisi

    // Fungsi untuk mengambil data dengan nama mahasiswa dan nama dosen
    public function getPengajuanJudulWithNamaMahasiswaAndDosen($id)
    {
        return $this->select('tbl_listpengajuanjudul.*, 
                            tbl_mahasiswa.name as nama_mahasiswa, 
                            dospem1.name as dosen_pembimbing_1, 
                            dospem2.name as dosen_pembimbing_2')  // Menambahkan nama dosen pembimbing 1 dan 2
                    ->join('tbl_mahasiswa', 'tbl_mahasiswa.id = tbl_listpengajuanjudul.nama_mahasiswa', 'left')  // Join dengan tbl_mahasiswa
                    ->join('tbl_dosen as dospem1', 'dospem1.id = tbl_listpengajuanjudul.dosen_pembimbing_1', 'left')  // Join dengan dosen pembimbing 1
                    ->join('tbl_dosen as dospem2', 'dospem2.id = tbl_listpengajuanjudul.dosen_pembimbing_2', 'left')  // Join dengan dosen pembimbing 2
                    ->where('tbl_listpengajuanjudul.id', $id)
                    ->first();
    }
}

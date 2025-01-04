<?php

namespace App\Models;

use CodeIgniter\Model;

class DospemModel extends Model
{
    protected $table = 'tbl_dosen';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'nama', 'email', 'program_studi'];

    // Mendapatkan detail dosen berdasarkan user_id
    public function getDosenDetails($userId)
    {
        return $this->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id')
                    ->select('tbl_dosen.*, tbl_users.name as dosen_name, tbl_users.email as dosen_email')
                    ->where('tbl_dosen.user_id', $userId)
                    ->first();
    }

    // Mendapatkan daftar dosen untuk dropdown
    public function getDosen()
    {
        return $this->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id')
                    ->select('tbl_dosen.id as dosen_id, tbl_users.name as dosen_name')
                    ->findAll();
    }

    // Mengajukan judul penelitian
    public function submitPengajuanJudul($data)
    {
        $dataToInsert = [
            'mahasiswa_id'       => $data['mahasiswa_id'],
            'topik_penelitian'   => $data['topik_penelitian'],
            'judul_penelitian'   => $data['judul_penelitian'],
            'dosen_pembimbing_1' => $data['dosen_pembimbing_1'],
            'dosen_pembimbing_2' => $data['dosen_pembimbing_2'],
            'tanggal_pengajuan'  => date('Y-m-d H:i:s'),
            'status_dospem1'     => 'pending',
            'status_dospem2'     => 'pending',
            'status_dbs'         => 'pending',
            'status_kaprodi'     => 'pending',
            'created_at'         => date('Y-m-d H:i:s'),
            'updated_at'         => date('Y-m-d H:i:s'),
        ];

        return $this->db->table('tbl_listpengajuanjudul')->insert($dataToInsert);
    }

    // Mendapatkan pengajuan judul penelitian beserta dosen pembimbing
    public function getPengajuanDenganDosen()
    {
        return $this->db->table('tbl_listpengajuanjudul')
                        ->join('tbl_users as mahasiswa', 'mahasiswa.id = tbl_listpengajuanjudul.mahasiswa_id')
                        ->join('tbl_dosen as dospem1', 'dospem1.id = tbl_listpengajuanjudul.dosen_pembimbing_1', 'left')
                        ->join('tbl_users as dosen1', 'dospem1.user_id = dosen1.id', 'left')
                        ->join('tbl_dosen as dospem2', 'dospem2.id = tbl_listpengajuanjudul.dosen_pembimbing_2', 'left')
                        ->join('tbl_users as dosen2', 'dospem2.user_id = dosen2.id', 'left')
                        ->select('tbl_listpengajuanjudul.*, mahasiswa.name as mahasiswa_name, dosen1.name as dospem1_name, dosen2.name as dospem2_name')
                        ->findAll();
    }
}

<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table = 'tbl_dosen';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'nik', 'nomor_hp', 'alamat', 'foto'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk mengambil data dosen berdasarkan user_id
    public function getDosenById($dosenId)
    {
        return $this->select('tbl_dosen.*, tbl_users.name as namaDosen, tbl_users.email, tbl_users.role')
                    ->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id')
                    ->where('tbl_dosen.id', $dosenId)
                    ->first();
    }

    public function getDosenWithUserDetails()
    {
        return $this->select('tbl_dosen.id as dosen_id, tbl_users.name as dosen_name, tbl_users.email, tbl_users.role')
                    ->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id')
                    ->findAll();
    }

    // Fungsi untuk mengambil data dosen lengkap dengan nama dari tabel users
    public function getDosenByUserId($userId)
    {
        return $this->select('tbl_dosen.*, tbl_users.name as namaDosen, tbl_users.email, tbl_users.role')
                    ->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id')
                    ->where('tbl_dosen.user_id', $userId)
                    ->first();
    }


}

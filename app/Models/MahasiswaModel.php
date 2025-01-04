<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table = 'tbl_mahasiswa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'nim', 'program_studi', 'jenjang', 'nomor_hp', 'alamat', 'foto'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Fungsi untuk mengambil data mahasiswa lengkap dengan nama dari tabel users
    public function getMahasiswaByUserId($userId)
    {
        return $this->select('tbl_mahasiswa.*, tbl_users.name as namaMahasiswa, tbl_users.email, tbl_users.role')
                    ->join('tbl_users', 'tbl_users.id = tbl_mahasiswa.user_id')
                    ->where('tbl_mahasiswa.user_id', $userId)
                    ->first();
    }
    
}

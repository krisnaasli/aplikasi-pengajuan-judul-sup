<?php

namespace App\Models;

use CodeIgniter\Model;

class ProfilModel extends Model
{
    protected $table;
    protected $primaryKey = 'id';
    protected $allowedFields = [];

    public function getProfilByRole($userId, $role)
    {
        if ($role === 'mahasiswa') {
            $this->table = 'tbl_mahasiswa';
        } elseif (in_array($role, ['dospem', 'dbs', 'kaprodi'])) {
            $this->table = 'tbl_dosen';
        } else {
            return null;
        }

        return $this->where('user_id', $userId)->first();
    }

    public function updateProfil($userId, $role, $data)
    {
        if ($role === 'mahasiswa') {
            $this->table = 'tbl_mahasiswa';
        } elseif (in_array($role, ['dospem', 'dbs', 'kaprodi'])) {
            $this->table = 'tbl_dosen';
        } else {
            return false;
        }

        return $this->where('user_id', $userId)->set($data)->update();
    }
}

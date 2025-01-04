<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table = 'tbl_mahasiswa';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nim', 'program_studi', 'jenjang', 'nomor_hp', 'alamat', 'foto', 'email', 'user_id'];

    // Relasi ke model User
    public function getUser($userId)
    {
        return $this->join('tbl_users', 'tbl_users.id = tbl_mahasiswa.user_id')
                    ->select('tbl_mahasiswa.*, tbl_users.name, tbl_users.email') // Select columns from tbl_users
                    ->where('tbl_mahasiswa.user_id', $userId)
                    ->first();
    }

    // Relasi ke model Dosen
    public function getDosen()
    {
        return $this->db->table('tbl_dosen')
                        ->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id')
                        ->select('tbl_dosen.id as dosen_id, tbl_users.name as dosen_name')
                        ->get()
                        ->getResultArray(); // Kembalikan hasil sebagai array
    }

    // Update Profil Mahasiswa
     // Update Profil Mahasiswa
     public function updateProfil($userId, $data)
     {
         // Update data user (seperti nama dan email) di tabel tbl_users
         $userModel = new \App\Models\UserModel();
         $userModel->update($userId, [
             'email' => $data['email'],  // Memperbarui email
             'name'  => $data['name'],   // Memperbarui nama
         ]);
 
         // Proses foto upload
         $foto = '';
         if (!empty($data['foto']) && $data['foto']->isValid()) {
             $file = $data['foto'];
             $newName = $file->getRandomName();
             $file->move(WRITEPATH . 'uploads/', $newName);
             $foto = $newName;
         }
 
         // Update data mahasiswa (di tabel tbl_mahasiswa)
         $dataToUpdate = [
             'nim' => $data['nim'],
             'program_studi' => $data['program_studi'],
             'jenjang' => $data['jenjang'],
             'nomor_hp' => $data['nomor_hp'],
             'alamat' => $data['alamat'],
             'foto' => $foto,  // Jika ada foto yang di-upload
         ];
 
         return $this->where('user_id', $userId)->set($dataToUpdate)->update();
     }

    // Mengajukan Judul Skripsi
    public function submitPengajuanJudul($data)
    {
        // Menyusun data yang akan dimasukkan ke tabel pengajuan judul
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

        // Debugging log
        log_message('debug', 'Data yang akan dimasukkan: ' . json_encode($dataToInsert));

        // Insert ke tabel tbl_listpengajuanjudul
        return $this->db->table('tbl_listpengajuanjudul')->insert($dataToInsert);
    }

    // Relasi ke model Dosen (untuk mendapatkan dosen pembimbing)
    public function getDosenDetails($userId)
    {
        return $this->join('tbl_dosen', 'tbl_dosen.user_id = tbl_users.id')
                    ->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id')
                    ->select('tbl_dosen.*, tbl_users.name, tbl_users.email')
                    ->where('tbl_dosen.user_id', $userId)
                    ->first();
    }

    // Mendapatkan pengajuan dengan dosen
    public function getPengajuanDenganDosen()
    {
        return $this->join('tbl_users', 'tbl_users.id = tbl_listpengajuanjudul.mahasiswa_id') // Join dengan mahasiswa
                    ->join('tbl_dosen', 'tbl_dosen.id = tbl_listpengajuanjudul.dosen_pembimbing_1 OR tbl_dosen.id = tbl_listpengajuanjudul.dosen_pembimbing_2', 'left') // Join dengan dosen
                    ->select('tbl_listpengajuanjudul.id, tbl_users.name as mahasiswa_name, tbl_dosen.name as dosen_name, tbl_listpengajuanjudul.topik_penelitian')
                    ->findAll();
    }
}

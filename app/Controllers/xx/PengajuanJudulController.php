<?php

namespace App\Controllers;

use App\Models\PengajuanModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\UserModel;

class PengajuanJudulController extends BaseController
{
    public function ajukanJudul(): string
    {
        $session = session();
        $user_id = $session->get('id'); // Ambil id dari session yang merujuk ke tbl_users.id

        // Ambil data mahasiswa berdasarkan user_id yang ada di session
        $mahasiswaModel = new MahasiswaModel();
        $mahasiswa = $mahasiswaModel->where('user_id', $user_id)->first(); // Mengambil data mahasiswa berdasarkan user_id

        // Ambil data dosen untuk dropdown Dosen Pembimbing
        $dosenModel = new DosenModel();
        $dosen = $dosenModel->select('tbl_dosen.id, tbl_users.name') // Mengambil id dosen dan name dari tbl_users
            ->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id') // Join dengan tbl_users untuk mendapatkan nama dosen
            ->findAll();

        // Ambil nama mahasiswa dari tbl_users berdasarkan user_id
        $userModel = new UserModel();
        $user = $userModel->find($user_id); // Ambil data user berdasarkan user_id (session)

        // Menyiapkan data untuk tampilan
        $data = array(
            'title' => 'Pengajuan Judul',
            'isi'   => 'pengajuanjudul',
            'namaMahasiswa' => $user['name'], // Nama Mahasiswa diambil dari tbl_users
            'nim'            => $mahasiswa['nim'], // NIM dari tbl_mahasiswa
            'programStudi'   => $mahasiswa['programstudi'], // Program Studi dari tbl_mahasiswa
            'jenjang'        => $mahasiswa['jenjang'], // Jenjang dari tbl_mahasiswa
            'dosen'          => $dosen, // Daftar dosen untuk dropdown
        );

        return view('layouts/wrapper', $data);
    }

    public function submitJudul()
    {
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'namaMahasiswa'    => 'required',
            'nim'              => 'required|numeric',
            'programStudi'     => 'required',
            'jenjang'          => 'required',
            'topikPenelitian'  => 'required',
            'judulPenelitian'  => 'required',
            'dosenPembimbing1' => 'required',
            'dosenPembimbing2' => 'required',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->to('/pengajuan-judul')
                             ->withInput()
                             ->with('errors', $validation->getErrors());
        }

         // Mendapatkan ID mahasiswa
        $session = session();
        $user_id = $session->get('id'); // Ambil id dari session yang merujuk ke tbl_users.id

        // Ambil data mahasiswa berdasarkan user_id yang ada di session
        $mahasiswaModel = new MahasiswaModel();
        $mahasiswa = $mahasiswaModel->where('user_id', $user_id)->first(); // Mengambil data mahasiswa berdasarkan user_id

        // Pastikan ID mahasiswa ditemukan
        if (!$mahasiswa) {
            // Jika tidak ditemukan, Anda bisa mengembalikan error atau feedback
            session()->setFlashdata('error', 'Data mahasiswa tidak ditemukan.');
            return redirect()->to('/pengajuan-judul');
        }

        // Menyimpan data ke database
        $model = new PengajuanModel();

        $data = [
            'nama_mahasiswa'    => $mahasiswa['id'],  // Gunakan ID mahasiswa, bukan nama
            'nim'               => $this->request->getVar('nim'),
            'program_studi'     => $this->request->getVar('programStudi'),
            'jenjang'           => $this->request->getVar('jenjang'),
            'topik_penelitian'  => $this->request->getVar('topikPenelitian'),
            'judul_penelitian'  => $this->request->getVar('judulPenelitian'),
            'dosen_pembimbing_1' => $this->request->getVar('dosenPembimbing1'),
            'dosen_pembimbing_2' => $this->request->getVar('dosenPembimbing2'),
            'status_dospem1'    => 'pending', // Status awal dospem1
            'status_dospem2'    => 'pending', // Status awal dospem2
            'status_dbs'        => 'pending', // Status awal dbs
            'status_kaprodi'    => 'pending', // Status awal kaprodi
            'create_at_status_dospem1' => date('Y-m-d H:i:s'),
            'create_at_status_dospem2' => date('Y-m-d H:i:s'),
            'create_at_status_dbs' => date('Y-m-d H:i:s'),
            'create_at_status_kaprodi' => date('Y-m-d H:i:s'),
        ];

 // Call submitPengajuanJudul method dari MahasiswaModel
    $result = $mahasiswaModel->submitPengajuanJudul($data);

    if ($result) {
        session()->setFlashdata('success', 'Pengajuan berhasil!');
    } else {
        session()->setFlashdata('error', 'Pengajuan gagal!');
    }

    return redirect()->to('/ajukan-judul');
}
}
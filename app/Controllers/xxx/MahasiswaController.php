<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\UserModel;
use App\Models\DosenModel;
use App\Models\DaftarPengajuanJudulModel;

class MahasiswaController extends BaseController
{
    public function index(): string
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'mahasiswa') {
            return redirect()->to('/auth')->with('error', 'Harap login terlebih dahulu.');
        }

        $data = array(
            'title' => 'Dashboard Mahasiswa',
            'isi'   => 'mahasiswa/dashboard', // View untuk dashboard mahasiswa
        );
        return view('mahasiswa/layouts/wrapper', $data);
    }

    public function editProfil(): string
    {
        $session = session();
        $userId = $session->get('id');

        $mahasiswaModel = new MahasiswaModel();
        $userModel = new UserModel();

        // Ambil data user berdasarkan ID
        $user = $userModel->find($userId);

        // Ambil data profil mahasiswa berdasarkan user_id
        $profil = $mahasiswaModel->where('user_id', $userId)->first();

        // Gabungkan data user dan profil
        $data['profil'] = array_merge($user, $profil);

        // Pastikan data yang dikirim ke view sudah lengkap
        $data = array(
            'title'  => 'Edit Profil Mahasiswa',
            'isi'    => 'mahasiswa/editprofil', // View untuk form edit profil
            'profil' => $data['profil'], // Data profil mahasiswa yang telah digabung
        );

        // Render halaman dengan data yang sudah digabung
        return view('mahasiswa/layouts/wrapper', $data);
    }


    public function updateProfil()
    {
        $session = session();
        $userId = $session->get('id');
    
        // Periksa apakah pengguna sudah login
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth')->with('error', 'Harap login terlebih dahulu.');
        }
    
        // Ambil data dari form
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $nim = $this->request->getPost('nim'); // Ambil data nim
        $programStudi = $this->request->getPost('programstudi'); // Ambil data program_studi
        $jenjang = $this->request->getPost('jenjang'); // Ambil data jenjang
        $nomorhp = $this->request->getPost('nomorhp');
        $alamat = $this->request->getPost('alamat');
    
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
            'name' => 'required|min_length[3]',
            'nim' => 'required',
            'programstudi' => 'required',
            'jenjang' => 'required',
            'nomorhp' => 'required',
            'alamat' => 'required',
            'foto' => 'mime_in[foto,image/jpg,image/jpeg,image/png]',
        ]);
    
        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal, kembali ke halaman dengan pesan error
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }
    
        // Update Nama Lengkap dan Email di tbl_users
        $userModel = new UserModel();
        $userModel->update($userId, [
            'name' => $name,
            'email' => $email
        ]);
    
        // Proses foto upload
        $foto = '';
        if ($this->request->getFile('foto')->isValid()) {
            $file = $this->request->getFile('foto');
            $newName = $file->getRandomName(); // Generate nama acak untuk foto
            $file->move(WRITEPATH . 'uploads/', $newName); // Simpan file foto ke folder uploads
            $foto = $newName;
        }
    
        // Update profil mahasiswa di tbl_mahasiswa
        $mahasiswaModel = new MahasiswaModel();
        $data = [
            'nim' => $nim, // Update nim
            'program_studi' => $programStudi, // Update program_studi
            'jenjang' => $jenjang, // Update jenjang
            'nomor_hp' => $nomorhp,
            'alamat' => $alamat,
            'foto' => $foto, // Foto yang sudah di-upload
        ];
        $mahasiswaModel->where('user_id', $userId)->set($data)->update();
    
        return redirect()->to('/dashboard/mahasiswa')->with('success', 'Profil berhasil diperbarui.');
    }
    


    
    public function ajukanJudul()
    {
        $session = session();
        $user_id = $session->get('id'); // Ambil id dari session yang merujuk ke tbl_users.id
    
        // Ambil data mahasiswa berdasarkan user_id yang ada di session
        $mahasiswaModel = new MahasiswaModel();
        $mahasiswa = $mahasiswaModel->where('user_id', $user_id)->first(); // Ambil data mahasiswa berdasarkan user_id
        
        if (!$mahasiswa) {
            session()->setFlashdata('error', 'Data mahasiswa tidak ditemukan.');
            return redirect()->to('/');
        }
    
        // Ambil data dosen untuk dropdown Dosen Pembimbing
        $dosenModel = new DosenModel();
        $dosen = $dosenModel->findAll(); // Mengambil seluruh data dosen untuk dropdown
        
        // Ambil nama mahasiswa dari tbl_users berdasarkan user_id
        $userModel = new UserModel();
        $user = $userModel->find($user_id); // Ambil data user berdasarkan user_id (session)
    
        // Menyiapkan data untuk tampilan
        $data = [
            'title' => 'Pengajuan Judul',
            'isi'   => 'mahasiswa/ajukanjudul',
            'namaMahasiswa' => $user['name'], // Nama Mahasiswa diambil dari tbl_users
            'nim'            => $mahasiswa['nim'], // NIM dari tbl_mahasiswa
            'programStudi'   => $mahasiswa['program_studi'], // Program Studi dari tbl_mahasiswa
            'jenjang'        => $mahasiswa['jenjang'], // Jenjang dari tbl_mahasiswa
            'dosen'          => $dosen, // Daftar dosen untuk dropdown
        ];
    
        return view('mahasiswa/layouts/wrapper', $data);
    }
    
    
    public function submitJudul()
{
    // Validasi input
    $validation = \Config\Services::validation();
    $validation->setRules([
        'topikPenelitian'  => 'required',
        'judulPenelitian'  => 'required',
        'dosenPembimbing1' => 'required',
        'dosenPembimbing2' => 'required',
    ]);

    if (!$validation->withRequest($this->request)->run()) {
        return redirect()->to('/ajukan-judul')
                         ->withInput()
                         ->with('errors', $validation->getErrors());
    }

    // Mendapatkan ID mahasiswa dari session
    $session = session();
    $user_id = $session->get('id'); // ID user dari session

    // Ambil data mahasiswa
    $mahasiswaModel = new MahasiswaModel();
    $mahasiswa = $mahasiswaModel->where('user_id', $user_id)->first();

    if (!$mahasiswa) {
        session()->setFlashdata('error', 'Data mahasiswa tidak ditemukan.');
        return redirect()->to('/ajukan-judul');
    }

    // Menyusun data pengajuan
    $data = [
        'mahasiswa_id'       => $mahasiswa['id'],
        'topik_penelitian'   => $this->request->getVar('topikPenelitian'),
        'judul_penelitian'   => $this->request->getVar('judulPenelitian'),
        'dosen_pembimbing_1' => $this->request->getVar('dosenPembimbing1'),
        'dosen_pembimbing_2' => $this->request->getVar('dosenPembimbing2'),
    ];

    // Memanggil model DaftarPengajuanJudulModel untuk menyimpan data pengajuan
    $daftarPengajuanJudulModel = new DaftarPengajuanJudulModel();
    $submitResult = $daftarPengajuanJudulModel->insert($data); // Insert data ke tabel pengajuan judul

    if ($submitResult) {
        session()->setFlashdata('success', 'Pengajuan judul berhasil!');
    } else {
        session()->setFlashdata('error', 'Pengajuan judul gagal!');
    }

    return redirect()->to('/ajukan-judul');
}

    

}
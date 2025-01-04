<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MahasiswaModel;
use App\Models\DosenModel;
use App\Models\DaftarPengajuanJudulModel;

class KaprodiController extends BaseController
{
    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'kaprodi') {
            return redirect()->to('/auth')->with('error', 'Harap login terlebih dahulu.');
        }

        $data = array(
            'title' => 'Dashboard Kepala Program Studi',
            'isi'   => 'kaprodi/dashboard', // View untuk dashboard kaprodi
        );
        return view('kaprodi/layouts/wrapper', $data);
    }

    public function editProfil(): string
    {
        $session = session();
        $userId = $session->get('id');

        $dosenModel = new DosenModel();
        $userModel = new UserModel();

        // Ambil data user berdasarkan ID
        $user = $userModel->find($userId);

        // Ambil data profil dosen berdasarkan user_id
        $profil = $dosenModel->where('user_id', $userId)->first();

        // Gabungkan data user dan profil
        $data['profil'] = array_merge($user, $profil);

        // Pastikan data yang dikirim ke view sudah lengkap
        $data = array(
            'title'  => 'Edit Profil Mahasiswa',
            'isi'    => 'kaprodi/editprofil', // View untuk form edit profil
            'profil' => $data['profil'], // Data profil mahasiswa yang telah digabung
        );

        // Render halaman dengan data yang sudah digabung
        return view('kaprodi/layouts/wrapper', $data);
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
        $nik = $this->request->getPost('nik'); // Ambil data nik
        $programStudi = $this->request->getPost('programstudi'); // Ambil data program_studi
        $jenjang = $this->request->getPost('jenjang'); // Ambil data jenjang
        $nomorhp = $this->request->getPost('nomorhp');
        $alamat = $this->request->getPost('alamat');
    
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
            'name' => 'required|min_length[3]',
            'nik' => 'required',
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
        $dosenModel = new DosenModel();
        $data = [
            'nik' => $nik, // Update nik
            'nomor_hp' => $nomorhp,
            'alamat' => $alamat,
            'foto' => $foto, // Foto yang sudah di-upload
        ];
        $dosenModel->where('user_id', $userId)->set($data)->update();
    
        return redirect()->to('/dashboard/kaprodi')->with('success', 'Profil berhasil diperbarui.');
    }


    public function daftarpengajuanJudulfordospem(): string
    {
    
        // Mengambil data dosen yang sedang login dari session
        $session = session();
        $userId = $session->get('id'); // ID pengguna dari tbl_users (misalnya dosen)
        
        // Cari ID dosen yang terkait dengan user ID di tbl_dosen
        $dosenModel = new DosenModel();
        $dosen = $dosenModel->where('user_id', $userId)->first(); // Anggap ada field user_id di tbl_dosen
        $dosenId = $dosen ? $dosen['id'] : null; // Ambil ID dosen atau null jika tidak ditemukan
        
    // Model untuk mengambil data pengajuan judul
    $judulModel = new DaftarPengajuanJudulModel();
    
    // Ambil data pengajuan judul yang relevan dengan dosen yang sedang login
    $pengajuanJudul = $judulModel->select('tbl_daftarpengajuanjudul.*, tbl_mahasiswa.nim, tbl_mahasiswa.program_studi, tbl_mahasiswa.jenjang')
    ->join('tbl_mahasiswa', 'tbl_mahasiswa.id = tbl_daftarpengajuanjudul.mahasiswa_id', 'inner')
    ->where('tbl_daftarpengajuanjudul.dosen_pembimbing_1', $dosenId)
    ->orWhere('tbl_daftarpengajuanjudul.dosen_pembimbing_2', $dosenId)
    ->findAll(); // Mengambil data pengajuan yang relevan dengan dosen yang login
    
    // Data untuk dikirim ke view
    $data = [
        'title' => 'List Pengajuan Judul',  // Judul halaman
        'isi'   => 'kaprodi/daftarpengajuanjudulfordospem', // Nama view untuk tampilan
        'pengajuanJudul' => $pengajuanJudul,  // Data pengajuan judul yang diambil dari database
    ];

    // Mengembalikan tampilan dengan data
    return view('kaprodi/layouts/wrapper', $data);
    }
    
    public function detailpengajuanJudulfordospem($id)
    {
        // Inisialisasi model
        $judulModel = new DaftarPengajuanJudulModel();
        $mahasiswaModel = new MahasiswaModel();
        $dosenModel = new DosenModel(); // Model untuk dosen jika diperlukan
    
        // Ambil data pengajuan judul berdasarkan ID
        $pengajuan = $judulModel->select('tbl_daftarpengajuanjudul.*, tbl_mahasiswa.id as mahasiswa_id, tbl_mahasiswa.nim, tbl_mahasiswa.program_studi, tbl_mahasiswa.jenjang, tbl_users.name as nama_mahasiswa')
                                ->join('tbl_mahasiswa', 'tbl_mahasiswa.id = tbl_daftarpengajuanjudul.mahasiswa_id', 'inner')
                                ->join('tbl_users', 'tbl_users.id = tbl_mahasiswa.user_id', 'inner') // Mengambil data mahasiswa dari tabel users
                                ->where('tbl_daftarpengajuanjudul.id', $id)
                                ->first();
    
        if (!$pengajuan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengajuan judul tidak ditemukan.');
        }
    
        // Ambil data dosen pembimbing (dosen pembimbing 1 dan 2) dengan menggabungkan data dari tabel users
        $dosenPembimbing1 = $dosenModel->select('tbl_dosen.*, tbl_users.name as dosen_name')
        ->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id', 'inner')
        ->where('tbl_dosen.id', $pengajuan['dosen_pembimbing_1'])
        ->first();

        $dosenPembimbing2 = $dosenModel->select('tbl_dosen.*, tbl_users.name as dosen_name')
        ->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id', 'inner')
        ->where('tbl_dosen.id', $pengajuan['dosen_pembimbing_2'])
        ->first();

        // Kirim data ke view
        $data = [
            'title' => 'Detail Pengajuan Judul',
            'pengajuan' => $pengajuan,
            'isi'   => 'kaprodi/detailpengajuanjudulfordospem', // Nama view untuk tampilan
            'dosenPembimbing1' => $dosenPembimbing1,
            'dosenPembimbing2' => $dosenPembimbing2,
        ];

        // Mengembalikan tampilan dengan data
        return view('kaprodi/layouts/wrapper', $data);
    }

    public function updatePengajuanJudulfordospem($id)
    {
        $session = session();
        $userId = $session->get('id'); // ID pengguna dari tbl_users

        // Inisialisasi model
        $judulModel = new DaftarPengajuanJudulModel();
        $dosenModel = new DosenModel();

        // Cari dosen yang sedang login
        $dosen = $dosenModel->where('user_id', $userId)->first();
        if (!$dosen) {
            return redirect()->back()->with('error', 'Akun dosen tidak ditemukan.');
        }

        $dosenId = $dosen['id']; // ID dosen dari tbl_dosen

        // Ambil data pengajuan judul berdasarkan ID
        $pengajuan = $judulModel->find($id);
        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan judul tidak ditemukan.');
        }

        // Periksa apakah dosen adalah dosen_pembimbing_1 atau dosen_pembimbing_2
        $dataUpdate = [];
        $timestamp = date('Y-m-d H:i:s');

        // Ambil status dan komentar dari form
        $status = $this->request->getPost('status'); // approved atau rejected
        $komentar = $this->request->getPost('komentar'); // Komentar dosen

        if ($pengajuan['dosen_pembimbing_1'] == $dosenId) {
            // Jika dosen yang login adalah pembimbing 1
            $dataUpdate = [
                'status_dospem1' => $status, // Set status dospem1
                'timestamp_dospem1' => $timestamp, // Set timestamp dospem1
                'komentar_dospem1' => $komentar, // Simpan komentar dospem1
            ];
        } elseif ($pengajuan['dosen_pembimbing_2'] == $dosenId) {
            // Jika dosen yang login adalah pembimbing 2
            $dataUpdate = [
                'status_dospem2' => $status, // Set status dospem2
                'timestamp_dospem2' => $timestamp, // Set timestamp dospem2
                'komentar_dospem2' => $komentar, // Simpan komentar dospem2
            ];
        } else {
            // Jika dosen yang login bukan pembimbing 1 atau 2
            return redirect()->back()->with('error', 'Anda bukan dosen pembimbing untuk pengajuan ini.');
        }

        // Lakukan update ke database
        $judulModel->where('id', $id)->set($dataUpdate)->update();

        // Redirect kembali ke halaman daftar pengajuan
        return redirect()->to('/daftar-pengajuan-judul/dosenpembimbing')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function daftarpengajuanJudulforkaprodi(): string
    {
    
        // Mengambil data dosen yang sedang login dari session
        $session = session();
        $userId = $session->get('id'); // ID pengguna dari tbl_users (misalnya dosen)
        
        // Cari ID dosen yang terkait dengan user ID di tbl_dosen
        $dosenModel = new DosenModel();
        $dosen = $dosenModel->where('user_id', $userId)->first(); // Anggap ada field user_id di tbl_dosen
        $dosenId = $dosen ? $dosen['id'] : null; // Ambil ID dosen atau null jika tidak ditemukan
        
    // Model untuk mengambil data pengajuan judul
    $judulModel = new DaftarPengajuanJudulModel();
    
    // Ambil data pengajuan judul yang status_dbs = 'approved'
    $pengajuanJudul = $judulModel->select('tbl_daftarpengajuanjudul.*, tbl_mahasiswa.nim, tbl_mahasiswa.program_studi, tbl_mahasiswa.jenjang')
        ->join('tbl_mahasiswa', 'tbl_mahasiswa.id = tbl_daftarpengajuanjudul.mahasiswa_id', 'inner')
        ->where('tbl_daftarpengajuanjudul.status_dbs', 'approved') // Hanya yang status_dbs = 'approved'
        ->findAll(); // Mengambil data pengajuan yang sesuai


    
    // Data untuk dikirim ke view
    $data = [
        'title' => 'List Pengajuan Judul',  // Judul halaman
        'isi'   => 'kaprodi/daftarpengajuanjudulforkaprodi', // Nama view untuk tampilan
        'pengajuanJudul' => $pengajuanJudul,  // Data pengajuan judul yang diambil dari database
    ];

    // Mengembalikan tampilan dengan data
    return view('kaprodi/layouts/wrapper', $data);
    }
    
    public function detailpengajuanJudulforkaprodi($id)
    {
        // Inisialisasi model
        $judulModel = new DaftarPengajuanJudulModel();
        $mahasiswaModel = new MahasiswaModel();
        $dosenModel = new DosenModel(); // Model untuk dosen jika diperlukan
    
        // Ambil data pengajuan judul berdasarkan ID
        $pengajuan = $judulModel->select('tbl_daftarpengajuanjudul.*, tbl_mahasiswa.id as mahasiswa_id, tbl_mahasiswa.nim, tbl_mahasiswa.program_studi, tbl_mahasiswa.jenjang, tbl_users.name as nama_mahasiswa')
                                ->join('tbl_mahasiswa', 'tbl_mahasiswa.id = tbl_daftarpengajuanjudul.mahasiswa_id', 'inner')
                                ->join('tbl_users', 'tbl_users.id = tbl_mahasiswa.user_id', 'inner') // Mengambil data mahasiswa dari tabel users
                                ->where('tbl_daftarpengajuanjudul.id', $id)
                                ->first();
    
        if (!$pengajuan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Pengajuan judul tidak ditemukan.');
        }
    
        // Ambil data dosen pembimbing (dosen pembimbing 1 dan 2) dengan menggabungkan data dari tabel users
        $dosenPembimbing1 = $dosenModel->select('tbl_dosen.*, tbl_users.name as dosen_name')
        ->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id', 'inner')
        ->where('tbl_dosen.id', $pengajuan['dosen_pembimbing_1'])
        ->first();

        $dosenPembimbing2 = $dosenModel->select('tbl_dosen.*, tbl_users.name as dosen_name')
        ->join('tbl_users', 'tbl_users.id = tbl_dosen.user_id', 'inner')
        ->where('tbl_dosen.id', $pengajuan['dosen_pembimbing_2'])
        ->first();

        // Kirim data ke view
        $data = [
            'title' => 'Detail Pengajuan Judul',
            'pengajuan' => $pengajuan,
            'isi'   => 'kaprodi/detailpengajuanjudulforkaprodi', // Nama view untuk tampilan
            'dosenPembimbing1' => $dosenPembimbing1,
            'dosenPembimbing2' => $dosenPembimbing2,
        ];

        // Mengembalikan tampilan dengan data
        return view('kaprodi/layouts/wrapper', $data);
    }

    public function updatePengajuanJudulforkaprodi($id)
    {
        $session = session();
        $userId = $session->get('id'); // ID pengguna dari tbl_users
        $userRole = $session->get('role'); // Ambil role dari session

        // Inisialisasi model
        $judulModel = new DaftarPengajuanJudulModel();
        $dosenModel = new DosenModel();

        // Cari dosen yang sedang login
        $dosen = $dosenModel->where('user_id', $userId)->first();
        if (!$dosen) {
            return redirect()->back()->with('error', 'Akun dosen tidak ditemukan.');
        }

        $dosenId = $dosen['id']; // ID dosen dari tbl_dosen

        // Ambil data pengajuan judul berdasarkan ID
        $pengajuan = $judulModel->find($id);
        if (!$pengajuan) {
            return redirect()->back()->with('error', 'Pengajuan judul tidak ditemukan.');
        }

        // Periksa apakah dosen adalah dosen_pembimbing_1 atau dosen_pembimbing_2
        $dataUpdate = [];
        $timestamp = date('Y-m-d H:i:s');

        // Ambil status dan komentar dari form
        $status = $this->request->getPost('status'); // approved atau rejected
        $komentar = $this->request->getPost('komentar'); // Komentar dosen

       // Pastikan user yang login memiliki role 'kaprodi'
        if ($userRole !== 'kaprodi') {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk melakukan aksi ini.');
        }

        // Ambil data status dan komentar dari form
        $status = $this->request->getPost('status'); // Nilai: 'approved' atau 'rejected'
        $komentar = $this->request->getPost('komentar'); // Komentar dari form
        $timestamp = date('Y-m-d H:i:s'); // Timestamp saat ini

        // Persiapkan data update untuk kolom status_kaprodi, komentar_kaprodi, dan timestamps_kaprodi
        $dataUpdate = [
            'status_kaprodi' => $status, // Set status_dbs
            'komentar_kaprodi' => $komentar, // Simpan komentar_dbs
            'timestamp_kaprodi' => $timestamp, // Set timestamp_dbs
        ];

        // Lakukan update ke database
        $judulModel->where('id', $id)->set($dataUpdate)->update();

        // Redirect kembali ke halaman daftar pengajuan
        return redirect()->to('/daftar-pengajuan-judul/kaprodi')->with('success', 'Status pengajuan berhasil diperbarui.');
    }


    public function serveRingkasan($fileName)
    {
        $path = WRITEPATH . 'uploads/ringkasan/' . $fileName;
        if (file_exists($path)) {
            return $this->response->download($path, null)->setFileName($fileName);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File tidak ditemukan.");
        }
    }

    public function serveJurnal($fileName)
    {
        $path = WRITEPATH . 'uploads/jurnal/' . $fileName;
        if (file_exists($path)) {
            return $this->response->download($path, null)->setFileName($fileName);
        } else {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("File tidak ditemukan.");
        }
    }


}

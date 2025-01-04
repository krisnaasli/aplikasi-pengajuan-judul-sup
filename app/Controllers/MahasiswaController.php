<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MahasiswaModel;
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
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'mahasiswa') {
            return redirect()->to('/auth')->with('error', 'Harap login terlebih dahulu.');
        }

        $userId = $session->get('id');
        $mahasiswaModel = new MahasiswaModel();
        $dosenModel = new DosenModel();
        $judulModel = new DaftarPengajuanJudulModel(); // Model untuk pengajuan judul

        // Ambil data mahasiswa dengan join ke tabel users
        $mahasiswa = $mahasiswaModel->getMahasiswaByUserId($userId);

        // Ambil data pengajuan judul yang ada untuk mahasiswa
        $pengajuanJudul = $judulModel->where('mahasiswa_id', $mahasiswa['id'])->first();

        // Default form state (form terbuka jika pengajuan belum ada)
        $isFormLocked = false; // Form terbuka secara default jika pengajuan belum ada

    // Generate status message
    $statusMessage = '';
    if ($pengajuanJudul) {
        $statusMessage = $this->generateStatusMessage($pengajuanJudul);

        // Form terbuka jika ada status 'rejected' pada dospem1, dospem2, dbs, atau kaprodi
        if (
            $pengajuanJudul['status_dospem1'] === 'rejected' || 
            $pengajuanJudul['status_dospem2'] === 'rejected' || 
            $pengajuanJudul['status_dbs'] === 'rejected' ||
            $pengajuanJudul['status_kaprodi'] === 'rejected'  
        ) {
            $isFormLocked = false;  // Form terbuka untuk revisi jika ada yang 'rejected'
        } else {
            // Jika tidak ada status 'rejected', maka form terkunci
            $isFormLocked = true;
        }
    

    }
        // Ambil daftar dosen pembimbing
        $dosen = $dosenModel->findAll();


        // Ambil daftar dosen pembimbing
         $dosen = $dosenModel->getDosenWithUserDetails();




        $data = [
            'title' => 'Ajukan Judul Skripsi',
            'isi' => 'mahasiswa/ajukanjudul', // Nama view
           'namaMahasiswa' => $mahasiswa['namaMahasiswa'], // Nama mahasiswa dari tbl_users
            'nim' => $mahasiswa['nim'], // NIM mahasiswa
            'programStudi' => $mahasiswa['program_studi'], // Program studi
            'jenjang' => $mahasiswa['jenjang'], // Jenjang
            'dosen' => $dosen, // Daftar dosen
            'pengajuanJudul' => $pengajuanJudul, // Kirimkan data pengajuan jika ada
            'statusMessage' => $statusMessage, // Kirim status message ke view
            'isFormLocked' => $isFormLocked, // Status form lock

            
        ];

        return view('mahasiswa/layouts/wrapper', $data);
    }

    public function submitJudul()
{
    $session = session();

    // Memeriksa apakah pengguna sudah login dan memiliki peran 'mahasiswa'
    if (!$session->get('isLoggedIn') || $session->get('role') !== 'mahasiswa') {
        return redirect()->to('/auth')->with('error', 'Harap login terlebih dahulu.');
    }

    $userId = $session->get('id');
    $mahasiswaModel = new MahasiswaModel();
    $judulModel = new DaftarPengajuanJudulModel(); // Model untuk pengajuan judul

    // Ambil data mahasiswa berdasarkan user_id
    $mahasiswa = $mahasiswaModel->where('user_id', $userId)->first();
    if (!$mahasiswa) {
        return redirect()->back()->with('error', 'Mahasiswa tidak ditemukan.');
    }

    $mahasiswaId = $mahasiswa['id']; // ID mahasiswa dari tabel tbl_mahasiswa

    // Ambil data pengajuan judul yang sudah ada
    $pengajuanJudul = $judulModel->where('mahasiswa_id', $mahasiswaId)->first();

    // Ambil file dari form
    $ringkasanDasar = $this->request->getFile('ringkasanDasar');
    $jurnalPendukung = $this->request->getFile('jurnalPendukung');

    // Variabel untuk menyimpan nama file yang baru atau lama
    $ringkasanDasarName = $pengajuanJudul ? $pengajuanJudul['ringkasan_file'] : '';
    $jurnalPendukungName = $pengajuanJudul ? $pengajuanJudul['jurnal_file'] : '';

    // Jika ada file baru yang diupload, validasi dan simpan file tersebut
    if ($ringkasanDasar && $ringkasanDasar->isValid()) {
        $ringkasanDasarName = $ringkasanDasar->getRandomName(); // Generate nama acak untuk file
        $ringkasanDasar->move(WRITEPATH . 'uploads/ringkasan', $ringkasanDasarName); // Simpan file ke folder uploads
    }

    if ($jurnalPendukung && $jurnalPendukung->isValid()) {
        $jurnalPendukungName = $jurnalPendukung->getRandomName(); // Generate nama acak untuk file
        $jurnalPendukung->move(WRITEPATH . 'uploads/jurnal', $jurnalPendukungName); // Simpan file ke folder uploads
    }

    


    // Tentukan status berdasarkan status sebelumnya (jika ada)
    $statusDospem1 = $pengajuanJudul ? $pengajuanJudul['status_dospem1'] : 'pending';
    $statusDospem2 = $pengajuanJudul ? $pengajuanJudul['status_dospem2'] : 'pending';
    $statusDbs = $pengajuanJudul ? $pengajuanJudul['status_dbs'] : 'pending';
    $statusKaprodi = $pengajuanJudul ? $pengajuanJudul['status_kaprodi'] : 'pending';

    // Jika ada yang rejected, ubah statusnya menjadi pending
    if ($statusDospem1 === 'rejected') {
        $statusDospem1 = 'pending';
    }
    if ($statusDospem2 === 'rejected') {
        $statusDospem2 = 'pending';
    }
    if ($statusDbs === 'rejected') {
        $statusDbs = 'pending';
    }
    if ($statusKaprodi === 'rejected') {
        $statusKaprodi = 'pending';
    }


    // Membuat array data yang akan diupdate pada pengajuan judul
    $data = [
        'mahasiswa_id' => $mahasiswaId,
        'topik_penelitian' => $this->request->getPost('topikPenelitian'),
        'judul_penelitian' => $this->request->getPost('judulPenelitian'),
        'dosen_pembimbing_1' => $this->request->getPost('dosenPembimbing1'),
        'dosen_pembimbing_2' => $this->request->getPost('dosenPembimbing2'),
        'ringkasan_file' => $ringkasanDasarName,
        'jurnal_file' => $jurnalPendukungName,
        'status_dospem1' => $statusDospem1,
        'status_dospem2' => $statusDospem2,
        'status_dbs' => $statusDbs,
        'status_kaprodi' => $statusKaprodi,
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    // Update pengajuan judul jika sudah ada, atau buat baru jika belum ada
    if ($pengajuanJudul) {
        // Update data pengajuan judul
        $judulModel->where('mahasiswa_id', $mahasiswaId)->set($data)->update();
    } else {
        // Insert data pengajuan judul baru
        $judulModel->insert($data);
    }

    // Jika pengajuan judul berhasil, kembalikan dengan pesan sukses
    return redirect()->to('/dashboard/mahasiswa')->with('success', 'Pengajuan judul berhasil diperbarui.');
}

    private function generateStatusMessage($pengajuan)
    {
        // Status untuk dospem1 dan dospem2
        if ($pengajuan['status_dospem1'] == 'pending' && $pengajuan['status_dospem2'] == 'pending') {
            return 'Proses untuk dospem1 pending, dospem2 pending.';
        } elseif ($pengajuan['status_dospem1'] == 'approved' && $pengajuan['status_dospem2'] == 'pending') {
            return 'Dospem 1 menyetujui, dospem2 pending.';
        } elseif ($pengajuan['status_dospem1'] == 'pending' && $pengajuan['status_dospem2'] == 'approved') {
            return 'Dospem 1 pending, dospem2 approved.';
        } elseif ($pengajuan['status_dospem1'] == 'approved' && $pengajuan['status_dospem2'] == 'approved') {
            // Status jika kedua dospem sudah approve
            if ($pengajuan['status_dbs'] == 'pending') {
                return 'Proses untuk DBS pending.';
            } elseif ($pengajuan['status_dbs'] == 'approved') {
                // Setelah DBS approved, periksa status Kaprodi
                if ($pengajuan['status_kaprodi'] == 'pending') {
                    return 'Proses untuk Kaprodi pending.';
                } elseif ($pengajuan['status_kaprodi'] == 'approved') {
                    return 'Judul disetujui prodi.';
                } elseif ($pengajuan['status_kaprodi'] == 'rejected') {
                    return 'Judul ditolak prodi.';
                }
            } elseif ($pengajuan['status_dbs'] == 'rejected') {
                return 'Proses untuk DBS rejected.';
            }
        } elseif ($pengajuan['status_dospem1'] == 'rejected' && $pengajuan['status_dospem2'] == 'pending') {
            return 'Dospem 1 rejected, dospem2 pending.';
        } elseif ($pengajuan['status_dospem1'] == 'pending' && $pengajuan['status_dospem2'] == 'rejected') {
            return 'Dospem 1 pending, dospem2 rejected.';
        } elseif ($pengajuan['status_dospem1'] == 'approved' && $pengajuan['status_dospem2'] == 'rejected') {
            return 'Dospem 1 approved, dospem2 rejected.';
        } elseif ($pengajuan['status_dospem1'] == 'rejected' && $pengajuan['status_dospem2'] == 'approved') {
            return 'Dospem 1 rejected, dospem2 approved.';
        } elseif ($pengajuan['status_dospem1'] == 'rejected' && $pengajuan['status_dospem2'] == 'rejected') {
            return 'Dospem 1 rejected, dospem2 rejected.';
        }
    
        // Status untuk DBS (proses untuk DBS hanya terjadi setelah dospem menyetujui)
        if ($pengajuan['status_dbs'] == 'pending' && $pengajuan['status_dospem1'] == 'approved' && $pengajuan['status_dospem2'] == 'approved') {
            return 'Proses untuk DBS pending.';
        } elseif ($pengajuan['status_dbs'] == 'approved') {
            return 'Proses untuk Kaprodi pending.';
        } elseif ($pengajuan['status_dbs'] == 'rejected') {
            return 'Proses untuk DBS rejected';
        }
    
        // Status untuk Kaprodi
        if ($pengajuan['status_kaprodi'] == 'pending') {
            return 'Proses untuk Kaprodi pending.';
        } elseif ($pengajuan['status_kaprodi'] == 'approved') {
            return 'Judul disetujui prodi.';
        } elseif ($pengajuan['status_kaprodi'] == 'rejected') {
            return 'Judul ditolak prodi.';
        }
    
        // Jika tidak ada yang cocok
        return 'Status tidak diketahui.';
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
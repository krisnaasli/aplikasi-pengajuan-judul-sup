<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\UserModel;

class Profil extends BaseController
{
    // Menampilkan halaman edit profil
    public function editProfil()
    {
        $session = session();
        $userId = $session->get('id');

        // Periksa apakah pengguna sudah login
        if ($session->get('isLoggedIn')) {
            // Ambil data user dari tabel tbl_users
            $userModel = new UserModel();
            $user = $userModel->find($userId);

            // Periksa apakah user adalah dosen atau mahasiswa
            if ($this->isDosen($userId)) {
                // Ambil data dosen
                $dosenModel = new DosenModel();
                $dosen = $dosenModel->where('user_id', $userId)->first();

                // Gabungkan data dosen dan user
                $data['profil'] = array_merge($dosen, $user);
            } else {
                // Ambil data mahasiswa
                $mahasiswaModel = new MahasiswaModel();
                $mahasiswa = $mahasiswaModel->where('user_id', $userId)->first();

                // Gabungkan data mahasiswa dan user
                $data['profil'] = array_merge($mahasiswa, $user);
            }

            // Data untuk layout wrapper
            $data['title'] = 'Edit Profil';
            $data['isi'] = 'profil'; // Tampilan untuk mahasiswa dan dosen

            return view('layouts/wrapper', $data);
        }

        // Jika tidak login, arahkan ke halaman login
        return redirect()->to('/auth')->with('error', 'Harap login terlebih dahulu.');
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
    $nik = $this->request->getPost('nik');
    $nomorhp = $this->request->getPost('nomorhp');
    $alamat = $this->request->getPost('alamat');
    $email = $this->request->getPost('email');

    // Update Nama Lengkap di tbl_users
    $userModel = new UserModel();
    $userModel->update($userId, ['name' => $name]);

    // Proses foto upload
    $foto = '';
    if ($this->request->getFile('foto')->isValid()) {
        $file = $this->request->getFile('foto');
        $newName = $file->getRandomName(); // Generate nama acak untuk foto
        $file->move(WRITEPATH . 'uploads/', $newName); // Simpan file foto ke folder uploads
        $foto = $newName;
    }

    // Update profil mahasiswa
    $mahasiswaModel = new MahasiswaModel();
    $data = [
        'nomor_hp' => $nomorhp,
        'alamat' => $alamat,
        'foto' => $foto,
        'email' => $email,
    ];
    $mahasiswaModel->where('user_id', $userId)->set($data)->update();

    return redirect()->to('/dashboard')->with('success', 'Profil berhasil diperbarui.');
}

}

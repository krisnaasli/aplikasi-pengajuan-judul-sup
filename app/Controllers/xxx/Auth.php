<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        // Tampilkan halaman login
        return view('auth/login');
    }

    public function login()
    {
        $session = session();
        $userModel = new UserModel();

        // Ambil data input dari form
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Validasi input
        if (empty($email) || empty($password)) {
            $session->setFlashdata('error', 'Email dan Password wajib diisi.');
            return redirect()->to('/login');
        }

        // Cari pengguna berdasarkan email
        $user = $userModel->where('email', $email)->first();

        if ($user) {
            // Verifikasi password
            if (password_verify($password, $user['password'])) {
                // Periksa status aktif
                if ($user['status_aktif'] == 0) {
                    $session->setFlashdata('error', 'Akun Anda tidak aktif. Silakan hubungi administrator.');
                    return redirect()->to('/login');
                }

                // Simpan data pengguna ke session
                $sessionData = [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role'],
                    'isLoggedIn' => true,
                ];
                $session->set($sessionData);

                // Redirect berdasarkan role
                if ($user['role'] == 'mahasiswa') {
                    return redirect()->to('/dashboard/mahasiswa');
                } elseif ($user['role'] == 'dospem') {
                    return redirect()->to('/dashboard/dospem');
                } elseif ($user['role'] == 'dbs') {
                    return redirect()->to('/dashboard/dbs');
                } elseif ($user['role'] == 'kaprodi') {
                    return redirect()->to('/dashboard/kaprodi');
                } else {
                    return redirect()->to('/dashboard');
                }
            } else {
                $session->setFlashdata('error', 'Password salah.');
                return redirect()->to('/login');
            }
        } else {
            $session->setFlashdata('error', 'Email tidak ditemukan.');
            return redirect()->to('/login');
        }
    }


    public function register()
    {
        // Tampilkan halaman registrasi
        return view('auth/register');
    }

    public function save()
    {
        $session = session();
        $userModel = new UserModel();

        // Ambil data input dari form
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role') ?? 'mahasiswa';

        // Validasi input
        if (!$this->validate([
            'email' => 'required|valid_email|is_unique[tbl_users.email]',
            'password' => 'required|min_length[6]',
        ])) {
            $session->setFlashdata('error', 'Email atau Password tidak valid.');
            return redirect()->to(base_url('register'))->withInput();
        }

        // Hash password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Simpan data pengguna ke database
        $userModel->save([
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role,
        ]);

        $session->setFlashdata('success', 'Akun berhasil dibuat, silakan login.');
        return redirect()->to(base_url('login'));
    }

    public function logout()
    {
        // Hapus semua session
        session()->destroy();
        return redirect()->to('/login');
    }
}

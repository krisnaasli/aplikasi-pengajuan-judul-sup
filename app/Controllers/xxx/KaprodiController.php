<?php

namespace App\Controllers;

use App\Models\JadwalModel;
use App\Models\LaporanModel;

class KaprodiController extends BaseController
{
    public function dashboard()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'kaprodi') {
            return redirect()->to('/auth')->with('error', 'Harap login terlebih dahulu.');
        }

        $data['title'] = 'Dashboard Kaprodi';
        return view('kaprodi/dashboard', $data);
    }

    public function aturJadwal()
    {
        $jadwalModel = new JadwalModel();
        $data['jadwal'] = $jadwalModel->findAll();

        return view('kaprodi/jadwal', $data);
    }

    public function tambahJadwal()
    {
        return view('kaprodi/tambah_jadwal');
    }

    public function simpanJadwal()
    {
        $jadwalModel = new JadwalModel();
        $data = [
            'judul' => $this->request->getPost('judul'),
            'tanggal' => $this->request->getPost('tanggal'),
        ];
        $jadwalModel->save($data);

        return redirect()->to('/kaprodi/jadwal')->with('success', 'Jadwal berhasil ditambahkan.');
    }
}

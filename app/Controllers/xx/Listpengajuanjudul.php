<?php

namespace App\Controllers;

use App\Models\ListpengajuanjudulModel;

class Listpengajuanjudul extends BaseController
{
    public function index(): string
    {
        // Inisialisasi model
        $model = new ListpengajuanjudulModel();

        // Ambil role pengguna
        $role = session()->get('role');
        $dosenId = session()->get('dosen_id');
        $userId = session()->get('user_id');

        // Ambil semua data pengajuan judul
        $listpengajuanjudul = $model->findAll();

        // Data untuk dikirim ke view
        $data = [
            'title' => 'List Pengajuan Judul',
            'isi'   => 'listpengajuanjudul',
            'listpengajuanjudul' => $listpengajuanjudul // Mengisi elemen array dengan variabel data
        ];

        return view('layouts/wrapper', $data);
    }

    
    public function detail($id)
    {
        // Inisialisasi model
        $model = new ListpengajuanjudulModel();

        // Mengambil data pengajuan berdasarkan ID, termasuk nama mahasiswa dan dosen pembimbing
        $data['pengajuan'] = $model->getPengajuanJudulWithNamaMahasiswaAndDosen($id);

        // Jika data tidak ditemukan
        if (!$data['pengajuan']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Pengajuan dengan ID $id tidak ditemukan.");
        }

        // Mengirim data ke view
        return view('detailpengajuanjudul', $data);
    }


}

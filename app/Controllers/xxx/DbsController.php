<?php

namespace App\Controllers;

use App\Models\SkripsiModel;

class DBSController extends BaseController
{
    public function dashboard()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'dbs') {
            return redirect()->to('/auth')->with('error', 'Harap login terlebih dahulu.');
        }

        $data['title'] = 'Dashboard Dosen Pembimbing Skripsi';
        return view('dbs/dashboard', $data);
    }

    public function evaluasiSkripsi($id)
    {
        $skripsiModel = new SkripsiModel();
        $data['skripsi'] = $skripsiModel->find($id);

        return view('dbs/evaluasi', $data);
    }

    public function updateEvaluasi($id)
    {
        $skripsiModel = new SkripsiModel();
        $data = ['evaluasi' => $this->request->getPost('evaluasi')];
        $skripsiModel->update($id, $data);

        return redirect()->to('/dbs/dashboard')->with('success', 'Evaluasi berhasil diperbarui.');
    }
}

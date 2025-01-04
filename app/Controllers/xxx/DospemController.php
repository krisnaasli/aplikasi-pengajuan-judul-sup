<?php

namespace App\Controllers;

class DospemController extends BaseController
{
    public function index(): string
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'dospem') {
            return redirect()->to('/auth')->with('error', 'Harap login terlebih dahulu.');
        }

        $data = array(
            'title' => 'Dashboard Dosen Pembimbing',
            'isi'   => 'dospem/dashboard', // View untuk dashboard dospem
        );
        return view('dospem/layouts/wrapper', $data);
    }
}
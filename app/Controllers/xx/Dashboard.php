<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index(): string
    {
        $data = array(
  
            'isi'   =>  'v_halaman'
        );
        return view('layouts/wrapper', $data);
    }
}
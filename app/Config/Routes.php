<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

 //Routes Auth
$routes->get('/', 'Home::index');
$routes->get('/login', 'Auth::index'); // Halaman login
$routes->post('/login', 'Auth::login'); // Proses login
$routes->get('/register', 'Auth::register'); // Halaman register
$routes->post('/register', 'Auth::save'); // Proses save registrasi
$routes->get('logout', 'Auth::logout'); // Proses logout

//Routes Mahasiswa
$routes->get('/dashboard/mahasiswa', 'MahasiswaController::index');
$routes->get('/edit-profil/mahasiswa', 'MahasiswaController::editProfil');
$routes->post('/edit-profil/mahasiswa', 'MahasiswaController::updateProfil');
$routes->get('/ajukan-judul', 'MahasiswaController::ajukanJudul');
$routes->post('/ajukan-judul', 'MahasiswaController::submitJudul');
$routes->get('/daftar-pengajuan-judul/mahasiswa/detail/uploads/ringkasan/(:segment)', 'MahasiswaController::serveRingkasan/$1');
$routes->get('/daftar-pengajuan-judul/mahasiswa/detail/uploads/jurnal/(:segment)', 'MahasiswaController::serveJurnal/$1');

// Routes Dospem
$routes->get('/dashboard/dospem', 'DospemController::index');
$routes->get('/edit-profil/dospem', 'DospemController::editProfil');
$routes->post('/edit-profil/dospem', 'DospemController::updateProfil');
$routes->get('/daftar-pengajuan-judul/dospem', 'DospemController::daftarpengajuanJudul');
$routes->get('/daftar-pengajuan-judul/dospem/detail/(:num)', 'DospemController::detailpengajuanJudul/$1');
$routes->post('/daftar-pengajuan-judul/dospem/detail/(:num)', 'DospemController::updatePengajuanJudul/$1');
$routes->get('/daftar-pengajuan-judul/dospem/detail/uploads/ringkasan/(:segment)', 'DospemController::serveRingkasan/$1');
$routes->get('/daftar-pengajuan-judul/dospem/detail/uploads/jurnal/(:segment)', 'DospemController::serveJurnal/$1');

// Routes DBS
$routes->get('/dashboard/dbs', 'DbsController::index');
$routes->get('/edit-profil/dbs', 'DbsController::editProfil');
$routes->post('/edit-profil/dbs', 'DbsController::updateProfil');
$routes->get('/daftar-pengajuan-judul/dosen-pembimbing', 'DbsController::daftarpengajuanJudulfordospem');
$routes->get('/daftar-pengajuan-judul/dosen-pembimbing/detail/(:num)', 'DbsController::detailpengajuanJudulfordospem/$1');
$routes->post('/daftar-pengajuan-judul/dosen-pembimbing/detail/(:num)', 'DbsController::updatePengajuanJudulfordospem/$1');
$routes->get('/daftar-pengajuan-judul/dbs', 'DbsController::daftarpengajuanJudulfordbs');
$routes->get('/daftar-pengajuan-judul/dbs/detail/(:num)', 'DbsController::detailpengajuanJudulfordbs/$1');
$routes->post('/daftar-pengajuan-judul/dbs/detail/(:num)', 'DbsController::updatePengajuanJudulfordbs/$1');
$routes->get('/daftar-pengajuan-judul/dbs/detail/uploads/ringkasan/(:segment)', 'DbsController::serveRingkasan/$1');
$routes->get('/daftar-pengajuan-judul/dbs/detail/uploads/jurnal/(:segment)', 'DbsController::serveJurnal/$1');

// Routes Kaprodi
$routes->get('/dashboard/kaprodi', 'KaprodiController::index');
$routes->get('/edit-profil/kaprodi', 'KaprodiController::editProfil');
$routes->post('/edit-profil/kaprodi', 'KaprodiController::updateProfil');
$routes->get('/daftar-pengajuan-judul/dosenpembimbing', 'KaprodiController::daftarpengajuanJudulfordospem');
$routes->get('/daftar-pengajuan-judul/dosenpembimbing/detail/(:num)', 'KaprodiController::detailpengajuanJudulfordospem/$1');
$routes->post('/daftar-pengajuan-judul/dosenpembimbing/detail/(:num)', 'KaprodiController::updatePengajuanJudulfordospem/$1');
$routes->get('/daftar-pengajuan-judul/kaprodi', 'KaprodiController::daftarpengajuanJudulforkaprodi');
$routes->get('/daftar-pengajuan-judul/kaprodi/detail/(:num)', 'KaprodiController::detailpengajuanJudulforkaprodi/$1');
$routes->post('/daftar-pengajuan-judul/kaprodi/detail/(:num)', 'KaprodiController::updatePengajuanJudulforkaprodi/$1');
$routes->get('/daftar-pengajuan-judul/kaprodi/detail/uploads/ringkasan/(:segment)', 'KaprodiController::serveRingkasan/$1');
$routes->get('/daftar-pengajuan-judul/kaprodi/detail/uploads/jurnal/(:segment)', 'KaprodiController::serveJurnal/$1');


<?php

require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/AdminController.php';
require_once __DIR__ . '/../app/Controllers/MandorController.php';
require_once __DIR__ . '/../app/Controllers/admin/KaryawanController.php';
require_once __DIR__ . '/../app/Controllers/admin/AdminProjectsController.php';
require_once __DIR__ . '/../app/Controllers/mandor/ProjectsController.php';

/**
 * -----------------------
 * AUTH ROUTES
 * -----------------------
 */

// Halaman utama
Router::get('/', function () {
  if (isset($_SESSION['user'])) {
    $role = $_SESSION['user']['role'];
    // Redirect berdasarkan role
    if ($role === 'admin') {
      redirect('/admin/dashboard');
    } elseif ($role === 'mandor') {
      redirect('/mandor/dashboard');
    }
  } else {
    // Jika belum login, arahkan ke halaman login
    redirect('/login');
  }
});

// Halaman login
Router::get('/login', function () {
  Middleware::guest(); // Pastikan hanya tamu yang bisa mengakses
  AuthController::index();
});

// Proses login
Router::post('/login', function () {
  Middleware::guest();
  AuthController::login();
});

// Logout
Router::post('/logout', function () {
  Middleware::auth();
  AuthController::logout();
});

/**
 * -----------------------
 * ADMIN ROUTES
 * -----------------------
 */
Router::get('/admin/dashboard', function () {
  Middleware::role('admin'); // Pastikan hanya admin yang bisa mengakses
  AdminController::dashboard();
});

Router::get('/admin/karyawan', function () {
  Middleware::role('admin'); // Pastikan hanya admin yang bisa mengakses
  KaryawanController::index();
});

Router::post('/admin/karyawan/create', function () {
  Middleware::role('admin'); // Verifikasi CSRF dan pastikan hanya admin yang bisa mengakses
  KaryawanController::store();
});

Router::post('/admin/karyawan/delete/{id}', function ($id) {
  Middleware::role('admin'); // Verifikasi CSRF dan pastikan hanya admin yang bisa mengakses
  KaryawanController::destroy($id);
});

Router::post('/admin/karyawan/update/{id}', function ($id) {
  Middleware::role('admin'); // Verifikasi CSRF dan pastikan hanya admin yang bisa mengakses
  KaryawanController::update($id);
});

Router::get('/admin/projects', function () {
  Middleware::role('admin'); // Pastikan hanya admin yang bisa mengakses
  AdminProjectsController::index();
});

Router::post('/admin/projects/comment/{id}', function ($id) {
  Middleware::role('admin'); // Pastikan hanya admin yang bisa mengakses
  AdminProjectsController::comment($id);
});

/**
 * -----------------------
 * MANDOR ROUTES
 * -----------------------
 */
Router::get('/mandor/dashboard', function () {
  Middleware::role('mandor'); // Pastikan hanya mandor yang bisa mengakses
  MandorController::dashboard();
});

Router::get('/mandor/projects', function () {
  Middleware::role('mandor'); // Pastikan hanya mandor yang bisa mengakses
  ProjectsController::index();
});

Router::post('/mandor/projects/create', function () {
  Middleware::role('mandor'); // Verifikasi CSRF dan pastikan hanya mandor yang bisa mengakses
  ProjectsController::store();
});

Router::post('/mandor/projects/update/{id}', function ($id) {
  Middleware::role('mandor'); // Verifikasi CSRF dan pastikan hanya mandor yang bisa mengakses
  ProjectsController::update($id);
});

Router::post('/mandor/projects/delete/{id}', function ($id) {
  Middleware::role('mandor'); // Verifikasi CSRF dan pastikan hanya mandor yang bisa mengakses
  ProjectsController::destroy($id);
});

Router::post('/mandor/projects/read/{id}', function ($id) {
  Middleware::role('mandor');
  ProjectsController::markAsRead($id);
});

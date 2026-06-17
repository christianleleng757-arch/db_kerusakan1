<?php
// Pengaturan Database bawaan XAMPP
$host = "localhost";
$user = "root";       // Username bawaan XAMPP
$pass = "";           // Password bawaan XAMPP (biarkan kosong)
$db   = "db_kerusakan"; // Nama database yang Anda buat di phpMyAdmin

// Membuat koneksi ke MySQL
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Mengecek apakah koneksi berhasil atau gagal
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
} else {
    // Baris ini bisa dihapus nanti jika koneksi sudah dipastikan berhasil
    // echo "Koneksi database berhasil!"; 
}
?>
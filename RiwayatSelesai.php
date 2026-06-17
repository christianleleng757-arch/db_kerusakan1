<?php
session_start();
include '../config/Koneksi.php'; // Mengacu pada file koneksi Anda [source: 1]

// 1. Proteksi Halaman: Pastikan hanya mahasiswa yang bisa akses
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'mahasiswa'){
    header("Location: ../login/HalamanLogin.php");
    exit;
}

// 2. Ambil NIM dari session yang dibuat saat login [source: 2]
$nim_user = $_SESSION['nim_user'] ?? '';

// --- LOGIKA PENGHITUNG STATISTIK (Agar tidak error Undefined Variable) ---

// Hitung Total Laporan Milik User Ini [source: 3]
$sql_total = mysqli_query($koneksi, "SELECT * FROM laporan WHERE nim = '$nim_user'");
$total_count = mysqli_num_rows($sql_total);

// Hitung Laporan Sedang Diproses (Status selain 'Selesai') [source: 3]
$sql_proses = mysqli_query($koneksi, "SELECT * FROM laporan WHERE nim = '$nim_user' AND status_perbaikan != 'Selesai'");
$proses_count = mysqli_num_rows($sql_proses);

// Hitung Laporan Selesai Perbaikan [source: 3]
$sql_selesai = mysqli_query($koneksi, "SELECT * FROM laporan WHERE nim = '$nim_user' AND status_perbaikan = 'Selesai'");
$selesai_count = mysqli_num_rows($sql_selesai);

// Ambil Riwayat Laporan Terakhir (Tampilkan semua milik user ini) [source: 3]
$query_riwayat = mysqli_query($koneksi, "SELECT * FROM laporan WHERE nim = '$nim_user' ORDER BY id_laporan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Mahasiswa - SarpraCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
    </style>
</head>
<body class="min-h-screen">
    <!-- Navbar -->
    <nav class="bg-white border-b sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="bg-green-600 p-2 rounded-lg text-white">
                    <i class="fas fa-tools"></i>
                </div>
                <span class="text-xl font-bold text-green-900">Sarpra<span class="text-yellow-500">Care</span></span>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                   <p class="text-sm font-bold text-gray-800"><?= $_SESSION['nama_user']; ?></p>
                    <span class="text-[10px] text-green-600 font-bold uppercase">Pelapor Aktif</span>
                </div>
                <button onclick="logout()" class="text-red-500 hover:bg-red-50 p-2 rounded-full transition">
                    <i class="fas fa-power-off"></i>
                </button>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <!-- Banner -->
        <div class="bg-green-900 rounded-[32px] p-10 text-white mb-10 flex flex-col md:flex-row justify-between items-center shadow-xl shadow-green-100">
            <div>
                <h1 class="text-3xl font-bold mb-2">Halo, Ada Fasilitas yang Rusak?</h1>
                <p class="text-green-100 opacity-80 mb-6">Laporkan segera agar kegiatan kampus tetap nyaman.</p>
                <a href="HalamanPelaporan.php" class="bg-yellow-500 hover:bg-yellow-400 text-green-950 font-bold px-8 py-4 rounded-xl transition inline-flex items-center gap-3">
                    <i class="fas fa-plus-circle"></i> Buat Laporan Baru
                </a>
            </div>
            <i class="fas fa-file-invoice text-8xl opacity-10 hidden lg:block"></i>
        </div>

        <!-- Statistik Cards (Dinamis dari Database) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-gray-400 text-xs font-bold uppercase mb-2">Total Laporan Anda</p>
                <h3 class="text-3xl font-bold text-gray-800"><?= str_pad($total_count, 2, "0", STR_PAD_LEFT); ?></h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-gray-400 text-xs font-bold uppercase mb-2">Sedang Diproses</p>
                <h3 class="text-3xl font-bold text-yellow-600"><?= str_pad($proses_count, 2, "0", STR_PAD_LEFT); ?></h3>
            </div>
            <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-gray-400 text-xs font-bold uppercase mb-2">Selesai Perbaikan</p>
                <h3 class="text-3xl font-bold text-green-600"><?= str_pad($selesai_count, 2, "0", STR_PAD_LEFT); ?></h3>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-50 flex justify-between items-center">
                <h2 class="font-bold text-gray-800">Riwayat Pelaporan Terakhir</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[11px] uppercase font-bold text-gray-400">
                        <tr>
                            <th class="p-6">Tanggal</th>
                            <th class="p-6">Barang & Lokasi</th>
                            <th class="p-6">Unit Tujuan</th>
                            <th class="p-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-gray-50">
                        <?php if(mysqli_num_rows($query_riwayat) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query_riwayat)): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="p-6 text-gray-500">
                                    <?= ($row['tgl_laporan'] && $row['tgl_laporan'] != '0000-00-00 00:00:00') ? date('d/m/Y', strtotime($row['tgl_laporan'])) : 'Baru saja'; ?>
                                </td>
                                <td class="p-6">
                                    <span class="font-bold text-gray-800 block"><?= $row['jenis_kerusakan']; ?></span>
                                    <span class="text-xs text-gray-400"><?= $row['lokasi_gedung']; ?> (Lt.<?= $row['Lantai']; ?>)</span>
                                </td>
                                <td class="p-6">
                                    <?php if($row['id_unit'] == '1'): ?>
                                        <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-[10px] font-bold uppercase">SARPRAS</span>
                                    <?php elseif($row['id_unit'] == '2'): ?>
                                        <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-[10px] font-bold uppercase">TEKNOLOGI INFORMASI</span>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-gray-100 text-gray-600 rounded-full text-[10px] font-bold uppercase">UNIT LAIN</span>
                                    <?php endif; ?>
                                </td>
                                <td class="p-6 text-center">
                                    <?php 
                                        $status = $row['status_perbaikan'];
                                        $badge = "bg-yellow-100 text-yellow-700";
                                        if($status == 'Selesai') $badge = "bg-green-100 text-green-700";
                                        if($status == 'Proses Perbaikan') $badge = "bg-blue-100 text-blue-700";
                                    ?>
                                    <span class="px-3 py-1 <?= $badge; ?> rounded-full text-[10px] font-bold uppercase">
                                        <?= $status; ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="p-10 text-center text-gray-400 italic">Belum ada laporan yang Anda buat.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script>
        function logout() {
            if(confirm('Keluar dari aplikasi?')) {
                window.location.href = '../login/Logout.php';
            }
        }
    </script>
</body>
</html>
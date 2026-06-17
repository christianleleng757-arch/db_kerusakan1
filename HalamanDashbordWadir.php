<?php
session_start();
include '../config/Koneksi.php'; // Mengacu pada file Koneksi.php Anda

// 1. Proteksi Halaman: Pastikan hanya Staf Wadir yang bisa akses
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'stafwadir'){
    header("Location: ../login/HalamanLogin.php");
    exit;
}

// 2. Ambil data laporan yang statusnya sudah 'Selesai'
$query = mysqli_query($koneksi, "SELECT * FROM laporan WHERE status_perbaikan = 'Selesai' ORDER BY id_laporan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Selesai - SarpraCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .sidebar-emerald { background: linear-gradient(180deg, #064e3b 0%, #065f46 100%); }
    </style>
</head>
<body class="min-h-screen flex text-sm">
    <!-- Sidebar (Konsisten dengan Dashboard Staf) -->
    <aside class="w-72 sidebar-emerald text-white hidden lg:flex flex-col p-8 sticky top-0 h-screen shadow-2xl">
        <div class="flex items-center gap-3 mb-12">
            <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-md">
                <i class="fas fa-tools text-xl"></i>
            </div>
            <span class="text-2xl font-bold tracking-tight">Sarpra<span class="text-yellow-400">Care</span></span>
        </div>
        
        <nav class="space-y-3 flex-1">
            <p class="text-[10px] font-bold text-green-300/50 uppercase tracking-widest ml-4 mb-4">Menu Utama</p>
            <a href="HalamanDashbordStaf.php" class="flex items-center gap-4 hover:bg-white/5 p-4 rounded-2xl text-green-100 transition-all group">
                <i class="fas fa-clipboard-check group-hover:text-yellow-400 transition-colors"></i> Eksekusi Arahan
            </a>
            <a href="RiwayatSelesai.php" class="flex items-center gap-4 bg-white/10 p-4 rounded-2xl font-bold border-l-4 border-yellow-400 transition-all">
                <i class="fas fa-history"></i> Riwayat Selesai
            </a>
        </nav>

        <div class="mt-auto pt-6 border-t border-white/10">
            <a href="../login/Logout.php" class="flex items-center gap-4 p-4 text-red-300 hover:text-white hover:bg-red-500/20 rounded-2xl transition-all font-bold">
                <i class="fas fa-sign-out-alt"></i> Keluar Sesi
            </a>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 lg:p-12">
        <header class="mb-10">
            <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Arsip Pengerjaan</h1>
            <p class="text-gray-500 text-sm mt-1 font-medium">Daftar semua laporan yang telah dinyatakan selesai[cite: 3].</p>
        </header>

        <!-- Tabel Riwayat -->
        <div class="bg-white rounded-[40px] shadow-xl shadow-green-900/5 border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-check-double text-green-600"></i> Laporan Rampung
                </h3>
                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-4 py-1.5 rounded-full uppercase">
                    <?= mysqli_num_rows($query); ?> Data Tersimpan
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-6">Fasilitas & Lokasi</th>
                            <th class="px-8 py-6">Instruksi Awal</th>
                            <th class="px-8 py-6">Hasil Eksekusi</th>
                            <th class="px-8 py-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if(mysqli_num_rows($query) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query)): ?>
                            <tr class="hover:bg-green-50/30 transition-all">
                                <td class="px-8 py-8">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-gray-800 text-base"><?= $row['jenis_kerusakan']; ?></span>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-md font-bold uppercase tracking-tighter">
                                                <?= $row['lokasi_gedung']; ?> (Lt.<?= $row['Lantai']; ?>) <!-- Sesuai kolom Lantai[cite: 1] -->
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <p class="text-xs text-gray-500 italic leading-relaxed">
                                        "<?= $row['arahan_wadir']; ?>"
                                    </p>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="bg-blue-50/50 p-4 rounded-2xl border-l-4 border-blue-400">
                                        <p class="text-xs text-blue-900 font-medium">
                                            <?= $row['catatan_teknisi'] ?: 'Tidak ada catatan pengerjaan.'; ?>[cite: 3]
                                        </p>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-extrabold bg-green-100 text-green-700 uppercase tracking-widest">
                                        <i class="fas fa-check-circle mr-1"></i> SELESAI
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="p-20 text-center">
                                    <div class="flex flex-col items-center opacity-30">
                                        <i class="fas fa-archive text-5xl mb-4"></i>
                                        <p class="font-bold uppercase tracking-widest">Belum ada riwayat selesai</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
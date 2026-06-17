<?php
session_start();
include '../config/Koneksi.php'; // Mengacu pada file Koneksi.php Anda

// 1. Proteksi Halaman: Pastikan hanya Staf Wadir yang bisa akses
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'stafwadir'){
    header("Location: ../login/HalamanLogin.php");
    exit;
}

// 2. Ambil data statistik untuk dashboard
$sql_antrean = mysqli_query($koneksi, "SELECT * FROM laporan WHERE status_perbaikan != 'Selesai' AND arahan_wadir IS NOT NULL");
$jumlah_antrean = mysqli_num_rows($sql_antrean);

// 3. Ambil laporan yang perlu segera diperbarui (Antrean Perbaikan)
$query_tugas = mysqli_query($koneksi, "SELECT * FROM laporan WHERE status_perbaikan != 'Selesai' AND arahan_wadir IS NOT NULL ORDER BY id_laporan DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Staf Wadir - SarpraCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F8FAFC; }
        .sidebar-emerald { background: linear-gradient(180deg, #064e3b 0%, #065f46 100%); }
    </style>
</head>
<body class="min-h-screen flex text-sm">
    <!-- Sidebar -->
    <aside class="w-72 sidebar-emerald text-white hidden lg:flex flex-col p-8 sticky top-0 h-screen shadow-2xl">
        <div class="flex items-center gap-3 mb-12">
            <div class="bg-white/20 p-2.5 rounded-xl backdrop-blur-md">
                <i class="fas fa-tools text-xl"></i>
            </div>
            <span class="text-2xl font-bold tracking-tight">Sarpra<span class="text-yellow-400">Care</span></span>
        </div>
        
        <nav class="space-y-3 flex-1">
            <p class="text-[10px] font-bold text-green-300/50 uppercase tracking-widest ml-4 mb-4">Menu Utama</p>
            
            <!-- Menu Dashboard Aktif -->
            <a href="HalamanDashbordStaf.php" class="flex items-center gap-4 bg-white/10 p-4 rounded-2xl font-bold border-l-4 border-yellow-400 transition-all">
                <i class="fas fa-clipboard-check"></i> Eksekusi Arahan
            </a>
            
            <!-- PERBAIKAN: Link Riwayat Selesai Sekarang Terhubung[cite: 3] -->
            <a href="RiwayatSelesai.php" class="flex items-center gap-4 hover:bg-white/5 p-4 rounded-2xl text-green-100 transition-all group">
                <i class="fas fa-history group-hover:text-yellow-400 transition-colors"></i> Riwayat Selesai
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
        <!-- Header -->
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight">Daftar Tugas</h1>
                <p class="text-gray-500 font-medium flex items-center gap-2">
                    <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span> Monitoring Arahan Pimpinan
                </p>
            </div>
            <div class="flex items-center gap-4 bg-white p-2 pr-6 rounded-full shadow-sm border border-gray-100">
                <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold">
                    <?= substr($_SESSION['nama_pegawai'], 0, 1); ?>
                </div>
                <div class="text-right">
                    <p class="text-xs font-bold text-gray-800"><?= $_SESSION['nama_pegawai']; ?></p>
                    <p class="text-[10px] text-green-600 font-bold uppercase tracking-tighter">Staf Eksekutor</p>
                </div>
            </div>
        </header>

        <!-- Daftar Antrean -->
        <div class="bg-white rounded-[40px] shadow-xl shadow-green-900/5 border border-gray-100 overflow-hidden">
            <div class="p-8 border-b border-gray-50 bg-gray-50/30 flex justify-between items-center">
                <h3 class="font-bold text-gray-800 flex items-center gap-3">
                    <i class="fas fa-list-ul text-green-600"></i> Antrean Perbaikan
                </h3>
                <span class="bg-green-100 text-green-700 text-[10px] font-bold px-4 py-1.5 rounded-full uppercase">
                    <?= $jumlah_antrean; ?> Perlu Tindakan[cite: 3]
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                        <tr>
                            <th class="px-8 py-6">Detail Fasilitas</th>
                            <th class="px-8 py-6">Instruksi Wadir</th>
                            <th class="px-8 py-6">Status</th>
                            <th class="px-8 py-6 text-center">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if(mysqli_num_rows($query_tugas) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($query_tugas)): ?>
                            <tr class="hover:bg-gray-50/50 transition-all">
                                <td class="px-8 py-8">
                                    <span class="font-bold text-gray-800 text-base block mb-1"><?= $row['jenis_kerusakan']; ?></span>
                                    <div class="flex gap-2">
                                        <span class="text-[10px] bg-gray-100 px-2 py-0.5 rounded text-gray-500 font-bold uppercase"><?= $row['lokasi_gedung']; ?></span>
                                        <span class="text-[10px] bg-blue-50 px-2 py-0.5 rounded text-blue-600 font-bold uppercase">Lantai <?= $row['Lantai']; ?></span> <!-- Sesuai kolom Lantai[cite: 1] -->
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="bg-yellow-50/50 p-4 rounded-2xl border-l-4 border-yellow-400">
                                        <p class="text-xs text-yellow-800 italic font-medium">"<?= $row['arahan_wadir']; ?>"</p>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <span class="px-3 py-1 bg-orange-100 text-orange-600 rounded-full text-[10px] font-extrabold uppercase">
                                        <?= $row['status_perbaikan']; ?>[cite: 3]
                                    </span>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <!-- Link Update Mengarah ke ID Laporan[cite: 3] -->
                                    <a href="UpdateStatusStaf.php?id=<?= $row['id_laporan']; ?>" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-xl font-bold transition-all shadow-lg shadow-green-600/20 inline-flex items-center gap-2">
                                        Update <i class="fas fa-chevron-right text-[10px]"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="p-20 text-center text-gray-400 italic">
                                    <i class="fas fa-check-circle text-4xl mb-4 block opacity-20"></i>
                                    Tidak ada antrean perbaikan saat ini.
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
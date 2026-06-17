<?php
session_start();
include '../config/Koneksi.php'; // Mengacu pada file Koneksi.php Anda[cite: 1]

// 1. Proteksi Halaman
if(!isset($_SESSION['login']) || $_SESSION['role'] != 'mahasiswa'){
    header("Location: ../login/HalamanLogin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 2. Ambil NIM dari SESSION[cite: 2]
    $nim = $_SESSION['nim_user']; 
    
    // 3. Tangkap data dari form
    $jurusan = mysqli_real_escape_string($koneksi, $_POST['jurusan']);
    $gedung = mysqli_real_escape_string($koneksi, $_POST['lokasi_gedung']);
    $lantai = mysqli_real_escape_string($koneksi, $_POST['lantai']);
    $ruang = mysqli_real_escape_string($koneksi, $_POST['lokasi_ruang']);
    $jenis = mysqli_real_escape_string($koneksi, $_POST['jenis_kerusakan']);
    $id_unit = mysqli_real_escape_string($koneksi, $_POST['id_unit']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsion']); 

    // --- LOGIKA UPLOAD FOTO ---
    $foto_name = "";
    if(isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $target_dir = "../assets/laporan/"; // Pastikan folder ini sudah ada
        
        // Buat folder jika belum ada
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $ext = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $foto_name = "IMG_" . time() . "_" . $nim . "." . $ext;
        $target_file = $target_dir . $foto_name;

        // Validasi ekstensi
        $allowed = ['jpg', 'jpeg', 'png'];
        if(in_array(strtolower($ext), $allowed)) {
            move_uploaded_file($_FILES['foto']['tmp_name'], $target_file);
        }
    }

    // 4. Query INSERT (Menambahkan kolom foto)
    $query = "INSERT INTO laporan (nim, jurusan, lokasi_gedung, Lantai, lokasi_ruang, jenis_kerusakan, id_unit, deskripsi, foto, status_perbaikan) 
              VALUES ('$nim', '$jurusan', '$gedung', '$lantai', '$ruang', '$jenis', '$id_unit', '$deskripsi', '$foto_name', 'Menunggu Verifikasi')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: HalamanDashbord.php?status=sukses");
        exit();
    } else {
        echo "Error: " . mysqli_error($koneksi); 
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SarpraCare - Form Laporan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #F1F5F9; }
        .form-card { background: white; border-radius: 32px; padding: 40px; box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.05); }
        .input-box { width: 100%; background: #F8FAFC; border: 1px solid #E2E8F0; border-radius: 12px; padding: 14px; outline: none; transition: 0.2s; }
        .input-box:focus { border-color: #166534; background: white; box-shadow: 0 0 0 4px rgba(22, 101, 52, 0.05); }
    </style>
</head>
<body class="p-4 md:p-10 min-h-screen flex items-center">
    <div class="max-w-4xl mx-auto w-full">
        <div class="form-card">
            <div class="flex justify-between items-center mb-10">
                <h1 class="text-3xl font-bold text-green-900">Formulir Laporan</h1>
                <a href="HalamanDashbord.php" class="text-gray-400 hover:text-gray-600 flex items-center gap-2 transition">
                    <i class="fas fa-times-circle text-xl"></i> <span>Batal</span>
                </a>
            </div>

            <!-- CRITICAL: Pastikan ada enctype="multipart/form-data" -->
            <form method="POST" action="" enctype="multipart/form-data" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Tujukan ke Unit</label>
                        <select name="id_unit" required class="input-box">
                            <option value="">Pilih Unit Tujuan</option>
                            <option value="1">Unit Sarana Prasarana</option>
                            <option value="2">Unit Teknologi Informasi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Jurusan</label>
                        <select name="jurusan" required class="input-box">
                            <option value="">Pilih Jurusan</option>
                            <option value="Teknologi Informasi">Teknologi Informasi</option>
                            <option value="Teknik Elektro">Teknik Elektro</option>
                            <option value="Teknik Mesin">Teknik Mesin</option>
                            <option value="Teknik Sipil">Teknik Sipil</option>
                            <option value="Teknik Kimia">Teknik Kimia</option>
                            <option value="Akuntansi">Akuntansi</option>
                            <option value="Administrasi Bisnis">Administrasi Bisnis</option>
                            <option value="Maritim">Maritim</option>
                            <option value="Arsitektur">Arsitektur</option>
                            <option value="Desain">Desain</option>
                            <option value="Pariwisata">Pariwisata</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Lantai</label>
                            <select name="lantai" required class="input-box">
                                <option value="">Pilih Lantai</option>
                                <option value="Lantai 1">Lantai 1</option>
                                <option value="Lantai 2">Lantai 2</option>
                                <option value="Lantai 3">Lantai 3</option>
                                <option value="Lantai 4">Lantai 4</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">Ruangan / Lab</label>
                            <input type="text" name="lokasi_ruang" required placeholder="Contoh: Lab TI 1" class="input-box">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">Kategori Kerusakan</label>
                        <select name="jenis_kerusakan" required class="input-box">
                            <option value="">Pilih Jenis Kerusakan</option>
                            <option value="Elektronik">Elektronik</option>
                            <option value="Fasilitas Umum">Fasilitas Umum</option>
                            <option value="Meubelir">Meubelir</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Nama Gedung</label>
                    <input type="text" name="lokasi_gedung" required class="input-box" placeholder="Contoh: Gedung Terpadu">
                </div>

                <!-- FITUR BARU: Input Upload Foto -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Foto Bukti Kerusakan</label>
                    <div class="relative group">
                        <input type="file" name="foto" id="foto" accept="image/*" class="hidden" onchange="updateFileName()">
                        <label for="foto" class="input-box border-dashed border-2 flex items-center justify-center gap-3 cursor-pointer hover:border-green-600 hover:bg-green-50 transition">
                            <i class="fas fa-camera text-gray-400 group-hover:text-green-600"></i>
                            <span id="file-name" class="text-gray-500 group-hover:text-green-700 text-sm">Ambil atau Pilih Foto</span>
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Deskripsi Kerusakan</label>
                    <textarea name="deskripsion" rows="4" required class="input-box resize-none" placeholder="Jelaskan secara detail apa yang rusak..."></textarea>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-green-800 hover:bg-green-900 text-white font-bold py-4 rounded-xl transition shadow-lg flex justify-center items-center gap-3">
                        Kirim Laporan <i class="fas fa-paper-plane text-sm"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function updateFileName() {
            const input = document.getElementById('foto');
            const fileNameSpan = document.getElementById('file-name');
            if (input.files.length > 0) {
                fileNameSpan.textContent = input.files[0].name;
                fileNameSpan.classList.remove('text-gray-500');
                fileNameSpan.classList.add('text-green-700', 'font-bold');
            }
        }
    </script>
</body>
</html>
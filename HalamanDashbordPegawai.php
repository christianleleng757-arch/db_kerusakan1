<?php
include '../config/Koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nim = mysqli_real_escape_string($koneksi, trim($_POST['nim']));
    $nama = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $telepon = mysqli_real_escape_string($koneksi, trim($_POST['no_telpon']));
    $email = mysqli_real_escape_string($koneksi, trim($_POST['email']));
    $password = mysqli_real_escape_string($koneksi, trim($_POST['password']));
    $konfirmasi = mysqli_real_escape_string($koneksi, trim($_POST['konfirmasi_password']));

    if ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok!";
    } else {
        // Cek apakah NIM sudah terdaftar
        $cek_nim = mysqli_query($koneksi, "SELECT * FROM customer WHERE nim_user = '$nim'");
        
        if (mysqli_num_rows($cek_nim) > 0) {
            $error = "NIM sudah terdaftar!";
        } else {
            // INSERT ke semua kolom: nim_user, nama_user, no_telpon, email, password
            $query = "INSERT INTO customer (nim_user, nama_user, no_telpon, email, password) 
                      VALUES ('$nim', '$nama', '$telepon', '$email', '$password')";
            
            if (mysqli_query($koneksi, $query)) {
                echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='HalamanLogin.php';</script>";
            } else {
                $error = "Gagal mendaftar: " . mysqli_error($koneksi);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa - SarpraCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .register-gradient { background: linear-gradient(135deg, #064e3b 0%, #065f46 100%); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-4xl w-full bg-white rounded-[40px] shadow-2xl overflow-hidden flex flex-col md:flex-row">
        
        <div class="md:w-5/12 register-gradient p-12 text-white flex flex-col justify-between">
            <div>
                <div class="bg-white/10 w-12 h-12 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-user-plus text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold mb-4">Sarpra<span class="text-yellow-400">Care</span></h1>
                <p class="text-green-100/70 text-sm">Lengkapi data diri untuk mulai melaporkan kerusakan fasilitas kampus.</p>
            </div>
            <div class="text-xs text-green-200/50">&copy; 2026 Teknologi Rekayasa Komputer</div>
        </div>

        <div class="md:w-7/12 p-10">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Registrasi Mahasiswa</h2>
            
            <?php if(isset($error)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-6 border border-red-100">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?= $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">NIM</label>
                        <input type="text" name="nim" required placeholder="246661xxx" class="w-full p-3 bg-gray-50 border rounded-xl outline-none focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama" required placeholder="Nama sesuai KTM" class="w-full p-3 bg-gray-50 border rounded-xl outline-none focus:border-green-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">No. Telepon</label>
                        <input type="text" name="no_telpon" required placeholder="08xxxx" class="w-full p-3 bg-gray-50 border rounded-xl outline-none focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Email Student</label>
                        <input type="email" name="email" required placeholder="user@student.polnes.ac.id" class="w-full p-3 bg-gray-50 border rounded-xl outline-none focus:border-green-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Password</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full p-3 bg-gray-50 border rounded-xl outline-none focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold uppercase text-gray-400 mb-1">Konfirmasi</label>
                        <input type="password" name="konfirmasi_password" required placeholder="••••••••" class="w-full p-3 bg-gray-50 border rounded-xl outline-none focus:border-green-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-800 text-white font-bold py-4 rounded-xl hover:bg-green-900 transition shadow-lg mt-4">
                    DAFTAR SEKARANG
                </button>
                <!-- Tambahkan ini di bawah tombol DAFTAR SEKARANG -->
<p class="text-center text-sm text-gray-500 mt-6">
    Sudah punya akun? 
    <a href="HalamanLogin.php" class="text-green-700 font-bold hover:underline transition">Masuk di sini</a>
</p>
            </form>
        </div>
    </div>
</body>
</html>
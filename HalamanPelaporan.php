<?php
session_start();
include '../config/Koneksi.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $username = mysqli_real_escape_string($koneksi, trim($_POST['username']));
    $password = mysqli_real_escape_string($koneksi, trim($_POST['password']));

    // 1. CARI DI TABEL PEGAWAI (NIP)
    $query_pegawai = mysqli_query($koneksi, "SELECT * FROM pegawai");
    $data_pegawai = null;
    
    while($row = mysqli_fetch_assoc($query_pegawai)){
        if(trim($row['NIP'] ?? $row['nip']) == $username){
            $data_pegawai = $row;
            break;
        }
    }

    if($data_pegawai){
        // Jika ditemukan di Pegawai, cek password
        if($password == $data_pegawai['password']){
            $_SESSION['login'] = true;
            $_SESSION['nama_pegawai'] = $data_pegawai['nama_pegawai']; 
            $_SESSION['nip'] = $username;
            
            // Perbaikan di sini: gunakan $data_pegawai, bukan $data
            $jabatan = strtolower(trim($data_pegawai['jabatan']));
            $_SESSION['role'] = $jabatan;

            if($jabatan == 'wadir'){
                header("Location: ../Wadir/HalamanDashbordWadir.php");
                exit();
            } else if($jabatan == 'stafwadir'){ 
                header("Location: ../StafWadir/HalamanDashbordStaf.php");
                exit();
            } else {
                header("Location: ../Pegawai/HalamanDashbordPegawai.php");
                exit();
            }
        } else {
            echo "<script>alert('Password Pegawai Salah!'); window.location='HalamanLogin.php';</script>";
            exit();
        }
    } else {
        // 2. CARI DI TABEL CUSTOMER (NIM) - Jika di pegawai tidak ada
        $query_customer = mysqli_query($koneksi, "SELECT * FROM customer");
        $data_customer = null;

        while($row_c = mysqli_fetch_assoc($query_customer)){
            if(trim($row_c['nim_user'] ?? $row_c['nim'] ?? $row_c['NIM']) == $username){
                $data_customer = $row_c;
                break;
            }
        }

        if($data_customer){
            if($password == $data_customer['password']){
                $_SESSION['login'] = true;
                $_SESSION['role'] = 'mahasiswa';
                $_SESSION['nama_user'] = $data_customer['nama_user'] ?? $data_customer['nama'];
                $_SESSION['nim_user'] = $username; // Sesuai kebutuhan dashboard mhs

                header("Location: ../Mahasiswa/HalamanDashbord.php");
                exit();
            } else {
                echo "<script>alert('Password Mahasiswa Salah!'); window.location='HalamanLogin.php';</script>";
                exit();
            }
        } else {
            // Jika tidak ditemukan di kedua tabel
            echo "<script>alert('NIP/NIM [$username] tidak ditemukan!'); window.location='HalamanLogin.php';</script>";
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SarpraCare</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .login-gradient { background: linear-gradient(135deg, #064e3b 0%, #065f46 100%); }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">
    <div class="max-w-4xl w-full bg-white rounded-[40px] shadow-2xl overflow-hidden flex flex-col md:flex-row">
        
        <div class="md:w-5/12 login-gradient p-12 text-white flex flex-col justify-between">
            <div>
                <div class="bg-white/10 w-12 h-12 rounded-2xl flex items-center justify-center mb-6">
                    <i class="fas fa-tools text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold mb-4">Sarpra<span class="text-yellow-400">Care</span></h1>
                <p class="text-green-100/70 text-sm">Sistem Pelaporan Kerusakan Sarana & Prasarana.</p>
            </div>
            <div class="text-xs text-green-200/50">&copy; 2026 Teknologi Rekayasa Komputer</div>
        </div>

        <div class="md:w-7/12 p-12">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Selamat Datang</h2>
            
            <?php if(isset($error)): ?>
                <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm mb-6 border border-red-100">
                    <i class="fas fa-exclamation-circle mr-2"></i> <?= $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="" class="space-y-6">
                <div class="flex bg-gray-100 p-1 rounded-2xl mb-8">
                    <button type="button" id="tabMhs" onclick="switchTab('mhs')" class="flex-1 py-3 rounded-xl text-sm font-bold transition bg-white shadow-sm text-green-800">Mahasiswa</button>
                    <button type="button" id="tabStaff" onclick="switchTab('staff')" class="flex-1 py-3 rounded-xl text-sm font-bold transition text-gray-500">Pegawai / Wadir</button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label id="labelUser" class="block text-xs font-bold uppercase text-gray-400 mb-2">NIM Mahasiswa</label>
                        <input type="text" name="username" required placeholder="Masukan NIM/NIP" class="w-full p-4 bg-gray-50 border rounded-2xl outline-none focus:border-green-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-400 mb-2">Password</label>
                        <input type="password" name="password" required placeholder="••••••••" class="w-full p-4 bg-gray-50 border rounded-2xl outline-none focus:border-green-500">
                    </div>
                </div>

                <button type="submit" class="w-full bg-green-800 text-white font-bold py-4 rounded-2xl hover:bg-green-900 transition shadow-lg">MASUK</button>
                <!-- Tambahkan ini di bawah tombol MASUK -->
<p class="text-center text-sm text-gray-500 mt-6">
    Belum punya akun mahasiswa? 
    <a href="HalamanRegister.php" class="text-green-700 font-bold hover:underline transition">Daftar di sini</a>
</p>
            </form>
        </div>
    </div>

    <script>
        function switchTab(role) {
            const tabMhs = document.getElementById('tabMhs');
            const tabStaff = document.getElementById('tabStaff');
            const labelUser = document.getElementById('labelUser');
            if(role === 'mhs') {
                tabMhs.className = "flex-1 py-3 rounded-xl text-sm font-bold bg-white shadow-sm text-green-800";
                tabStaff.className = "flex-1 py-3 rounded-xl text-sm font-bold text-gray-500";
                labelUser.innerText = "NIM Mahasiswa";
            } else {
                tabStaff.className = "flex-1 py-3 rounded-xl text-sm font-bold bg-white shadow-sm text-green-800";
                tabMhs.className = "flex-1 py-3 rounded-xl text-sm font-bold text-gray-500";
                labelUser.innerText = "NIP Pegawai";
            }
        }
    </script>
</body>
</html>
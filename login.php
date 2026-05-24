<?php
    include "config/database.php";
    session_start();

    $login_message = "";

    if (isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT * FROM admin WHERE username='$username' AND password='$password'";
 
        $result = $conn->query($sql);
        if ($result->num_rows > 0){
            $data = $result->fetch_assoc();
            $_SESSION["username"] = $data["username"];
            $_SESSION["is_login"] = true;
        } else {
            $login_message = "Login Gagal! pastikan username dan password benar";
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <title>Login Page</title>
        <style>
        .bg-sidebar { background-color: #222d32; }
        .bg-navbar { background-color: #3c8dbc; }
        .text-sidebar { color: #222d32; }
        .border-focus:focus { border-color: #3c8dbc; ring-color: #3c8dbc; }
            .alert-container {
            margin-bottom: 20px;
            animation: fadeInDown 0.5s ease; /* Efek muncul dari atas */
            }
            .alert-logout {
                background-color: #d4edda;
                color: #155724;
                padding: 10px;
                border-radius: 8px;
                font-size: 13px;
                margin-bottom: 20px;
                border: 1px solid #c3e6cb;
                transition: 0.5s;
            }      
        </style>
    </head>
<body class="bg-[#f4f6f9] min-h-screen flex items-center justify-center font-sans p-4">

    <div class="w-full max-w-md bg-white rounded-lg shadow-xl overflow-hidden border border-gray-100">
        
        <div class="bg-sidebar p-6 text-center relative">
            <div class="absolute top-0 left-0 w-full h-1 bg-navbar"></div>
            <div class="flex justify-center items-center gap-2 text-white text-2xl font-bold tracking-wide">
                <i class="fa-solid fa-boxes-stacked text-[#3c8dbc]"></i>
                <span>Inventaris<span class="text-[#3c8dbc] font-medium">App</span></span>
            </div>
            <p class="text-gray-400 text-xs mt-1 uppercase tracking-widest">Lab Informatika</p>
        </div>

        <form class="p-8 space-y-6" method="POST" action="proses_login.php"">
            
            <div class="text-center mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Selamat Datang</h2>
                <p class="text-sm text-gray-500">Silakan masuk untuk mengelola inventaris</p>
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-gray-600 block">Username / Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-user text-sm"></i>
                    </span>
                    <input type="text" name="username"  placeholder="Masukkan username" 
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#3c8dbc] focus:border-[#3c8dbc] transition text-sm text-gray-700" required>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <label class="text-sm font-semibold text-gray-600 block">Password</label>
                    <a href="#" class="text-xs text-[#3c8dbc] hover:underline font-medium">Lupa Password?</a>
                </div>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-lock text-sm"></i>
                    </span>
                    <input type="password" name="password"   placeholder="••••••••" 
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#3c8dbc] focus:border-[#3c8dbc] transition text-sm text-gray-700" required>
                </div>
            </div>

            <div class="flex items-center">
                <input type="checkbox" id="remember" class="h-4 w-4 rounded border-gray-300 text-[#3c8dbc] focus:ring-[#3c8dbc]">
                <label Sol untuk="remember" class="ml-2 text-sm text-gray-600 select-none cursor-pointer">Ingat saya di perangkat ini</label>
            </div>

            <button name="login"  type="submit" 
                    class="w-full bg-[#3c8dbc] hover:bg-[#347aa3] text-white font-medium py-2.5 rounded-md shadow transition duration-200 flex items-center justify-center gap-2 tracking-wide text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#3c8dbc]">
                <span>MASUK</span>
                <i class="fa-solid fa-right-to-bracket text-xs"></i>
            </button>

        </form>

        <div class="bg-gray-50 px-8 py-4 border-t border-gray-100 text-center">
            <p class="text-xs text-gray-500">&copy; 2026 InventarisApp Lab. All rights reserved.</p>
        </div>

    </div>
<script>
    const alertBox = document.querySelector('.alert-container');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.display = 'none';
        }, 3000); // 3000ms = 3 detik
    }
</script>
</body>
</html>
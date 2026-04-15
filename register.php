<?php
    include "config/database.php";

    $register_meessage = "";

    if (isset($_POST["register"])){
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];

        $sql = "INSERT INTO admin (username, email, password) VALUES ('$username', '$email', '$password')";

        if ($conn->query($sql)) {
            $register_meessage = "Registrasi berhasil! silahkan login";
        } else {
            $register_meessage = "Registrasi gagal! silahkan coba lagi";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Page</title>
    <style>
        body {
            background: linear-gradient(135deg, #2980b9, #2c3e50); /* Background gradasi biru gelap */
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
        }
        .welcome-header {
            text-align: center;
            color: #1e293b;
            margin-bottom: 30px;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
        }
        
        .login-container h2 {
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 700;
        }
        
        .login-container p {
            color: #7f8c8d;
            font-size: 14px;
            margin-bottom: 30px;
        }
        
        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }
        
        .input-group label {
            display: block;
            font-size: 13px;
            color: #34495e;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .input-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #dcdfe6;
            border-radius: 8px;
            box-sizing: border-box;
            transition: 0.3s;
        }
        
        .input-group input:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 8px rgba(52, 152, 219, 0.2);
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #3498db;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }
        
        .btn-login:hover {
            background: #2980b9;
            transform: translateY(-2px);
        }
        .register-text {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .register-text a {
            color: #007b;
            text-decoration: none;
            font-weight: bold;
        }
            
        .alert-container {
        margin-bottom: 20px;
        animation: fadeInDown 0.5s ease; /* Efek muncul dari atas */
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form autocomplete="off"  action="register.php" method="POST" class="login-form">
            <h2>Selamat Datang di Inventaris <br> Laboratorium Informatika </h2>
            <p>Silahkan Lengkapi Data Anda</p>
            <div class="input-group">
                <label for="username" name="username">Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="input-group">
                <label for="email" name="email">Email</label>
                <input type="text" name="email" required>
            </div>
            <div class="input-group">
                <label for="password" name="password">Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" name="register" class="btn-login">Register</button>

            <p class="register-text">
                Sudah punya akun? <a href="login.php">Login disini</a>
            </p>
            <p class="register-text">
                <a href="index.php">back to index</a>
            </p>
        </form>
    </div>
</body>
</html>
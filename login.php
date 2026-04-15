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
        <link rel="stylesheet" href="assets/style.css">
        <title>Login Page</title>
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
    </style>
</head>
<body>
    <div class="login-container">
        <?php if (isset($_GET['pesan'])): ?>
            <div class="alert-container">
                <?php if ($_GET['pesan'] == 'logout'): ?>
                    <div class="alert-logout">
                        <i class="fa fa-check-circle"></i> Anda telah berhasil <strong>keluar</strong> dari sistem.
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        <form autocomplete="off"  action="proses_login.php" method="POST" class="login-form">
            <h2>Selamat Datang di Inventaris </h2>
            <p>Laboratorium Informatika</p>
            <p><?php echo $login_message; ?></p>
            <div class="input-group">
                <label for="username" name="username">Username</label>
                <input type="text" name="username" required>
            </div>
            
            <div class="input-group">
                <label for="password" name="password">Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" name="login" class="btn-login" >Login</button>

            <p class="register-text">
                Belum punya akun? <a href="register.php">Daftar disini</a>
            </p>
            <p class="register-text">
                <a href="index.php">back to index</a>
            </p>
        </form>
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
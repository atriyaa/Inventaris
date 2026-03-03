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
            background: #f1f5f9;
            min-height: 100vh;
            padding: 20px;
        }
        .welcome-header {
            text-align: center;
            color: #1e293b;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <form autocomplete="off"  action="proses_login.php" method="POST" class="login-form">
            <h1>Selamat Datang di Inventaris Lab</h1>
            <h3>Laboratorium Informatika</h3>
        <p><?php echo $login_message; ?></p>
            <div class="input-group">
                <label for="username" name="username">Username</label>
                <input type="text" name="username" required>
            </div>

            <div class="input-group">
                <label for="password" name="password">Password</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" name="login" class="button-group" >Login</button>

            <p class="register-text">
                Belum punya akun? <a href="register.php">Daftar disini</a>
            </p>
            <p class="register-text">
                <a href="index.php">back to index</a>
            </p>
        </form>
    </div>
</body>
</html>
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
</head>
<body>
    <h2>Selamat Datang di Inventaris Lab</h2>
    <h4>Laboratorium Informatika</h4>
    <p><?php echo $register_meessage; ?></p>
    <section>
        <form action="register.php" method="POST">
            <label for="username" name="username">Username</label><br>
            <input type="text" name="username" required><br>
            <label for="email" name="email">Email</label><br>
            <input type="text" name="email" required><br>
            <label for="password" name="password">Password</label><br>
            <input type="password" name="password" required><br><br>
            <button type="submit" name="register">Register</button><br>
            <p>Sudah punya akun? <a href="login.php">Daftar disini</a></p>
            <a href="index.php">back to index</a>
        </form>
    </section>
</body>
</html>
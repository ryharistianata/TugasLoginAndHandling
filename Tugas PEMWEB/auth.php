<?php
session_start();
require 'koneksi.php';

if (isset($_SESSION['nama'])) {
    header("Location: dashboard.php");
    exit();
}

$pesan = "";

if (isset($_POST['register'])) {

    $nama = $_POST['nama'];
    $password = $_POST['password'];

    $cek = mysqli_query($conn,
        "SELECT * FROM users WHERE nama='$nama'"
    );

    if (mysqli_num_rows($cek) > 0) {

        $pesan = "Nama sudah digunakan";

    } else {

        $hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        mysqli_query($conn,
            "INSERT INTO users
            (nama,password,role)
            VALUES
            ('$nama','$hash','user')"
        );

        $pesan = "Register berhasil";
    }
}

if (isset($_POST['login'])) {

    $nama = $_POST['nama'];
    $password = $_POST['password'];

    $query = mysqli_query($conn,
        "SELECT * FROM users
        WHERE nama='$nama'"
    );

    if (mysqli_num_rows($query) > 0) {

        $data = mysqli_fetch_assoc($query);

        if (
            password_verify(
                $password,
                $data['password']
            )
        ) {

            $_SESSION['nama'] = $data['nama'];
            $_SESSION['role'] = $data['role'];

            header("Location: dashboard.php");
            exit();

        } else {

            $pesan = "Password salah";
        }

    } else {

        $pesan = "User tidak ditemukan";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login & Register</title>
</head>
<body>

<h2>Register</h2>

<form method="POST">

    <input type="text"
           name="nama"
           placeholder="Nama">

    <br><br>

    <input type="password"
           name="password"
           placeholder="Password">

    <br><br>

    <button type="submit"
            name="register">
        Register
    </button>

</form>

<hr>

<h2>Login</h2>

<form method="POST">

    <input type="text"
           name="nama"
           placeholder="Nama">

    <br><br>

    <input type="password"
           name="password"
           placeholder="Password">

    <br><br>

    <button type="submit"
            name="login">
        Login
    </button>

</form>

<br>

<?= $pesan; ?>

</body>
</html>
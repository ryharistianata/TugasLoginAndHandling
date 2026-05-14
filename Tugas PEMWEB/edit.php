<?php
session_start();

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] != 'admin'
) {
    header("Location: dashboard.php");
    exit();
}

require 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$id = $_GET['id'];

$query = mysqli_query($conn,
    "SELECT * FROM users
    WHERE id='$id'"
);

$user = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {

    $nama = $_POST['nama'];
    $password = $_POST['password'];

    if (!empty($password)) {

        $hash = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        mysqli_query($conn,
            "UPDATE users
            SET
            nama='$nama',
            password='$hash'
            WHERE id='$id'"
        );

    } else {

        mysqli_query($conn,
            "UPDATE users
            SET nama='$nama'
            WHERE id='$id'"
        );
    }

    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Data</title>
</head>
<body>

<h2>Edit Data Pengguna</h2>

<form method="POST">

    <label>Nama Pengguna:</label>

    <br>

    <input type="text"
           name="nama"
           value="<?= $user['nama']; ?>">

    <br><br>

    <label>Password Baru:</label>

    <br>

    <input type="password"
           name="password"
           placeholder="Masukkan password baru">

    <br><br>

    <button type="submit"
            name="update">
        Simpan Perubahan
    </button>

</form>

<br>

<a href="dashboard.php">
    <button>Batal</button>
</a>

</body>
</html>
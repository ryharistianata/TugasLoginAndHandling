<?php
session_start();

if (!isset($_SESSION['nama'])) {
    header("Location: auth.php");
    exit();
}

require 'koneksi.php';

$is_admin = ($_SESSION['role'] == 'admin');

if ($is_admin && isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($conn,
        "DELETE FROM users
        WHERE id='$id'"
    );

    header("Location: dashboard.php");
    exit();
}

$users = mysqli_query($conn,
    "SELECT * FROM users"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <style>

        body{
            font-family: Arial;
            background-color: #f4f4f4;
        }

        .container{
            width: 700px;
            margin: 40px auto;
            background-color: white;
            border: 2px solid #2d4f73;
            padding: 20px;
        }

        h2{
            margin-top: 0;
        }

        table{
            border-collapse: collapse;
            margin-top: 15px;
            width: 400px;
        }

        table th,
        table td{
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }

        table th{
            background-color: #e8e8e8;
        }

        .btn{
            padding: 4px 10px;
            cursor: pointer;
        }

        .logout{
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .info{
            color: #555;
            margin-bottom: 15px;
        }

    </style>

</head>

<body>

<div class="container">

    <h2>
        Halo,
        <?= $_SESSION['nama']; ?>
    </h2>

    <div class="logout">

        <a href="logout.php">
            <button class="btn">
                Logout
            </button>
        </a>

    </div>

    <hr>

    <?php if($is_admin): ?>

    <h3>Panel Admin</h3>

    <p class="info">
        Kelola seluruh data pengguna di bawah ini.
    </p>

    <table>

        <tr>
            <th>ID</th>
            <th>Nama User</th>
            <th>Aksi</th>
        </tr>

        <?php while($user = mysqli_fetch_assoc($users)): ?>

        <tr>

            <td>
                <?= $user['id']; ?>
            </td>

            <td>
                <?= $user['nama']; ?>
            </td>

            <td>

                <a href="edit.php?id=<?= $user['id']; ?>">
                    <button class="btn">
                        Edit
                    </button>
                </a>

                <a href="dashboard.php?hapus=<?= $user['id']; ?>"
                   onclick="return confirm('Yakin ingin menghapus user ini?')">

                    <button class="btn">
                        Hapus
                    </button>

                </a>

            </td>

        </tr>

        <?php endwhile; ?>

    </table>

    <?php else: ?>

    <p>
        Anda login sebagai user biasa.
    </p>

    <?php endif; ?>

</div>

</body>
</html>
<?php
session_start();
include "../config/koneksi.php";

if(isset($_POST['simpan'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    mysqli_query($koneksi,"
        INSERT INTO users(username,email,password,role)
        VALUES('$username','$email','$password','$role')
    ");

    header("Location: users.php");
}
?>

<form method="POST">
<h3>Tambah User</h3>

<input name="username" placeholder="Username" required><br><br>
<input name="email" placeholder="Email" required><br><br>
<input name="password" placeholder="Password" required><br><br>

<select name="role">
    <option value="user">User</option>
    <option value="admin">Admin</option>
</select><br><br>

<button name="simpan">Simpan</button>
<a href="users.php">Kembali</a>
</form>
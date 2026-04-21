<?php
session_start();
include "../config/koneksi.php";

$id = $_GET['id'];
$data = mysqli_fetch_assoc(mysqli_query($koneksi,"SELECT * FROM users WHERE id='$id'"));

if(isset($_POST['update'])){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    mysqli_query($koneksi,"
        UPDATE users SET
        username='$username',
        email='$email',
        role='$role'
        WHERE id='$id'
    ");

    header("Location: users.php");
}
?>

<form method="POST">
<h3>Edit User</h3>

<input name="username" value="<?= $data['username']; ?>"><br><br>
<input name="email" value="<?= $data['email']; ?>"><br><br>

<select name="role">
    <option <?= $data['role']=='user'?'selected':''; ?> value="user">User</option>
    <option <?= $data['role']=='admin'?'selected':''; ?> value="admin">Admin</option>
</select><br><br>

<button name="update">Update</button>
<a href="users.php">Kembali</a>
</form>
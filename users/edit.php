<title>Mosifier Utilisateur</title>


<?php
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $email = $_POST['email'];
    $role = $_POST['role'];

    $query = "UPDATE user SET email='$email', role='$role' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        echo "User updated successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
    header("Location: display.php");
} else {
    $id = intval($_GET['id']);
    $result = mysqli_query($conn, "SELECT * FROM user WHERE id=$id");
    $user = mysqli_fetch_assoc($result);
}
?>
<form action="edit.php" method="POST">
    <br>
    <input type="hidden" name="id" value="<?= $user['id'] ?>">
    <input type="text" name="email" value="<?= htmlspecialchars($user['email']) ?>">
    <select name="role" id="role">
        <option value="admin" <?php if ($user['role'] == 'admin') {
                                    echo 'selected';
                                } ?>>Admin</option>
        <option value="user" <?php if ($user['role'] == 'user') {
                                    echo 'selected';
                                } ?>>User</option>
    </select>
    <button type="submit">Update</button>
</form>
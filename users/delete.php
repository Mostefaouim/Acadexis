<?php
require '../config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM user WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        echo "User deleted successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
header("Location: display.php"); 
?>

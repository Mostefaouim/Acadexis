<?php
$conn = mysqli_connect("localhost", "root", "", "edusys");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

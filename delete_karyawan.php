<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $conn = new mysqli("localhost", "root", "", "gaji");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "DELETE FROM tbl_karyawan WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: karyawan.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $conn->close();
} else {
    header("Location: karyawan.php");
}
?>

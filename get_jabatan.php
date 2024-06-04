<?php
$conn = new mysqli("localhost", "root", "", "gaji");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "SELECT Gaji_Pokok, Tunjangan_Jabatan FROM tbl_jabatan WHERE id=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    echo json_encode($data);
} else {
    echo json_encode([]);
}

$conn->close();
?>

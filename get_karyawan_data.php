<?php
$conn = new mysqli("localhost", "root", "", "gaji");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$karyawan_id = $_POST['karyawan_id'];
$sql = "SELECT k.*, j.Nama_Jabatan, j.Gaji_Pokok, j.Tunjangan_Jabatan 
        FROM tbl_karyawan k 
        JOIN tbl_jabatan j ON k.Id_Jabatan = j.id 
        WHERE k.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $karyawan_id);
$stmt->execute();
$result = $stmt->get_result();
$karyawanData = $result->fetch_assoc();
$karyawanData['BPJS'] = $karyawanData['Gaji_Pokok'] * 0.04;
$karyawanData['PPh21'] = $karyawanData['Gaji_Pokok'] * 0.02;

echo json_encode($karyawanData);

$conn->close();
?>

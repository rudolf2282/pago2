<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "gaji");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $NIK = $_POST['NIK'];
    $Nama = $_POST['Nama'];
    $Id_Jabatan = $_POST['Id_Jabatan'];
    $Status = $_POST['Status'];
    $Jumlah_Anak = $_POST['Jumlah_Anak'];
    $Tunjangan_Anak = $_POST['Tunjangan_Anak'];
    $Gaji_Pokok = $_POST['Gaji_Pokok'];
    $Tunjangan_Jabatan = $_POST['Tunjangan_Jabatan'];

    $sql = "UPDATE tbl_karyawan 
            SET NIK='$NIK', Nama='$Nama', Id_Jabatan='$Id_Jabatan', Status='$Status', Jumlah_Anak='$Jumlah_Anak', Tunjangan_Anak='$Tunjangan_Anak', Gaji_Pokok='$Gaji_Pokok', Tunjangan_Jabatan='$Tunjangan_Jabatan'
            WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: karyawan.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$sql = "SELECT * FROM tbl_karyawan WHERE id=$id";
$result = $conn->query($sql);
$karyawan = $result->fetch_assoc();

$sql = "SELECT id, Nama_Jabatan, Gaji_Pokok, Tunjangan_Jabatan FROM tbl_jabatan";
$jabatans = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Karyawan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .sidebar {
            height: 100vh;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: white;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
        }

        .sidebar .nav-link:hover {
            background-color: #0056b3;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        .navbar {
            margin-left: 250px;
        }

        .form-group label {
            font-weight: bold;
        }

        .card {
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="d-flex">
        <nav class="sidebar d-flex flex-column p-3">
            <h4 class="text-center">Admin Dashboard</h4>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="karyawan.php"><i class="fas fa-users"></i> Karyawan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="transaksi.php"><i class="fas fa-exchange-alt"></i> Transaksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>

        <div class="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <div class="collapse navbar-collapse">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#">Hi, <?php echo $_SESSION['user_name']; ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="container mt-4">
                <h1 class="mb-4">Edit Karyawan</h1>
                <form action="edit_karyawan.php?id=<?= $id ?>" method="post">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="NIK">NIK:</label>
                                <input type="text" name="NIK" class="form-control" value="<?= $karyawan['NIK'] ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="Nama">Nama:</label>
                                <input type="text" name="Nama" class="form-control" value="<?= $karyawan['Nama'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="Id_Jabatan">Jabatan:</label>
                                <select name="Id_Jabatan" id="Id_Jabatan" class="form-control" required>
                                    <?php while ($jabatan = $jabatans->fetch_assoc()) : ?>
                                        <option value="<?= $jabatan['id']; ?>" <?= $jabatan['id'] == $karyawan['Id_Jabatan'] ? 'selected' : '' ?>><?= $jabatan['Nama_Jabatan']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Gaji_Pokok">Gaji Pokok:</label>
                                <input type="text" name="Gaji_Pokok" id="Gaji_Pokok" class="form-control" value="<?= $karyawan['Gaji_Pokok'] ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="Tunjangan_Jabatan">Tunjangan Jabatan:</label>
                                <input type="text" name="Tunjangan_Jabatan" id="Tunjangan_Jabatan" class="form-control" value="<?= $karyawan['Tunjangan_Jabatan'] ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="Status">Status:</label>
                                <select name="Status" id="Status" class="form-control" required>
                                    <option value="Kawin" <?= $karyawan['Status'] == 'Kawin' ? 'selected' : '' ?>>Kawin</option>
                                    <option value="Belum Kawin" <?= $karyawan['Status'] == 'Belum Kawin' ? 'selected' : '' ?>>Belum Kawin</option>
                                </select>
                            </div>
                            <div class="form-group" id="Jumlah_Anak_Group">
                                <label for="Jumlah_Anak">Jumlah Anak:</label>
                                <input type="number" name="Jumlah_Anak" id="Jumlah_Anak" class="form-control" value="<?= $karyawan['Jumlah_Anak'] ?>">
                            </div>
                            <div class="form-group" id="Tunjangan_Anak_Group">
                                <label for="Tunjangan_Anak">Tunjangan Anak:</label>
                                <input type="text" name="Tunjangan_Anak" id="Tunjangan_Anak" class="form-control" value="<?= $karyawan['Tunjangan_Anak'] ?>" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary mt-3">Update</button>
                            <a href="karyawan.php" class="btn btn-secondary mt-3">Close</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($('#Status').val() == 'Kawin') {
                $('#Jumlah_Anak_Group, #Tunjangan_Anak_Group').show();
            } else {
                $('#Jumlah_Anak_Group, #Tunjangan_Anak_Group').hide();
            }

            $('#Id_Jabatan').change(function() {
                var jabatanId = $(this).val();
                if (jabatanId) {
                    $.ajax({
                        url: 'get_jabatan.php',
                        type: 'GET',
                        data: { id: jabatanId },
                        success: function(response) {
                            var data = JSON.parse(response);
                            $('#Gaji_Pokok').val(data.Gaji_Pokok);
                            $('#Tunjangan_Jabatan').val(data.Tunjangan_Jabatan);
                        }
                    });
                } else {
                    $('#Gaji_Pokok, #Tunjangan_Jabatan').val('');
                }
            });

            $('#Status').change(function() {
                if ($(this).val() == 'Kawin') {
                    $('#Jumlah_Anak_Group, #Tunjangan_Anak_Group').show();
                } else {
                    $('#Jumlah_Anak_Group, #Tunjangan_Anak_Group').hide();
                    $('#Jumlah_Anak').val(0);
                    $('#Tunjangan_Anak').val(0);
                }
            });

            $('#Jumlah_Anak').change(function() {
                var jumlahAnak = $(this).val();
                var gajiPokok = $('#Gaji_Pokok').val();
                var tunjanganAnak = jumlahAnak * 0.05 * gajiPokok;
                $('#Tunjangan_Anak').val(tunjanganAnak);
            });
        });
    </script>
</body>

</html>

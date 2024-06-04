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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $NIK = $_POST['NIK'];
    $Nama = $_POST['Nama'];
    $Id_Jabatan = $_POST['Id_Jabatan'];
    $Status = $_POST['Status'];
    $Jumlah_Anak = $_POST['Jumlah_Anak'];
    $Tunjangan_Anak = $_POST['Tunjangan_Anak'];
    $Gaji_Pokok = $_POST['Gaji_Pokok'];
    $Tunjangan_Jabatan = $_POST['Tunjangan_Jabatan'];

    $sql = "INSERT INTO tbl_karyawan (NIK, Nama, Id_Jabatan, Status, Jumlah_Anak, Tunjangan_Anak, Gaji_Pokok, Tunjangan_Jabatan) 
            VALUES ('$NIK', '$Nama', '$Id_Jabatan', '$Status', '$Jumlah_Anak', '$Tunjangan_Anak', '$Gaji_Pokok', '$Tunjangan_Jabatan')";

    if ($conn->query($sql) === TRUE) {
        header("Location: karyawan.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Generate NIK
$sql = "SELECT COUNT(*) AS count FROM tbl_karyawan";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$count = $row['count'] + 1;
$NIK = 'KAR-' . str_pad($count, 2, '0', STR_PAD_LEFT);

$sql = "SELECT id, Nama_Jabatan, Gaji_Pokok, Tunjangan_Jabatan FROM tbl_jabatan";
$jabatans = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan</title>
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
                <h1 class="mb-4">Tambah Karyawan</h1>
                <form action="add_karyawan.php" method="post">
                    <div class="form-group mb-3">
                        <label for="NIK">NIK:</label>
                        <input type="text" name="NIK" class="form-control" value="<?= $NIK ?>" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Nama">Nama:</label>
                        <input type="text" name="Nama" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Id_Jabatan">Jabatan:</label>
                        <select name="Id_Jabatan" id="Id_Jabatan" class="form-control" required>
                            <option value="">Pilih Jabatan</option>
                            <?php while ($jabatan = $jabatans->fetch_assoc()): ?>
                                <option value="<?= $jabatan['id']; ?>"><?= $jabatan['Nama_Jabatan']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Gaji_Pokok">Gaji Pokok:</label>
                        <input type="text" name="Gaji_Pokok" id="Gaji_Pokok" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Tunjangan_Jabatan">Tunjangan Jabatan:</label>
                        <input type="text" name="Tunjangan_Jabatan" id="Tunjangan_Jabatan" class="form-control" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label for="Status">Status:</label>
                        <select name="Status" id="Status" class="form-control" required>
                            <option value="Kawin">Kawin</option>
                            <option value="Belum Kawin">Belum Kawin</option>
                        </select>
                    </div>
                    <div class="form-group mb-3" id="Jumlah_Anak_Group">
                        <label for="Jumlah_Anak">Jumlah Anak:</label>
                        <input type="number" name="Jumlah_Anak" id="Jumlah_Anak" class="form-control">
                    </div>
                    <div class="form-group mb-3" id="Tunjangan_Anak_Group">
                        <label for="Tunjangan_Anak">Tunjangan Anak:</label>
                        <input type="text" name="Tunjangan_Anak" id="Tunjangan_Anak" class="form-control" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save</button>
                    <a href="karyawan.php" class="btn btn-secondary"><i class="fas fa-times"></i> Close</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#Jumlah_Anak_Group, #Tunjangan_Anak_Group').hide();

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

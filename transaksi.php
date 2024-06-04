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

function generateTransactionID($conn) {
    $sql = "SELECT COUNT(*) AS count FROM tbl_gaji";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $count = $row['count'] + 1;
    $transaction_id = "TR-" . str_pad($count, 2, "0", STR_PAD_LEFT) . "-" . date("m") . "-" . date("Y");
    return $transaction_id;
}

$transaction_id = generateTransactionID($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi Gaji</title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#Nama_Karyawan').change(function() {
                var karyawan_id = $(this).val();
                if (karyawan_id) {
                    $.ajax({
                        type: 'POST',
                        url: 'get_karyawan_data.php',
                        data: 'karyawan_id=' + karyawan_id,
                        success: function(data) {
                            var karyawanData = JSON.parse(data);
                            $('#Jabatan').val(karyawanData.Nama_Jabatan);
                            $('#Gaji_Pokok').val(karyawanData.Gaji_Pokok);
                            $('#Tunjangan_Jabatan').val(karyawanData.Tunjangan_Jabatan);
                            $('#Status').val(karyawanData.Status);
                            $('#Jumlah_Anak').val(karyawanData.Jumlah_Anak);
                            $('#Tunjangan_Anak').val(karyawanData.Tunjangan_Anak);
                            $('#BPJS').val(karyawanData.BPJS);
                            $('#PPh21').val(karyawanData.PPh21);
                            calculateGajiBersih();
                        }
                    });
                }
            });

            function calculateGajiBersih() {
                var gaji_pokok = parseFloat($('#Gaji_Pokok').val()) || 0;
                var tunjangan_jabatan = parseFloat($('#Tunjangan_Jabatan').val()) || 0;
                var tunjangan_anak = parseFloat($('#Tunjangan_Anak').val()) || 0;
                var bpjs = gaji_pokok * 0.04;
                var pph21 = gaji_pokok * 0.02;
                var total_pendapatan = gaji_pokok + tunjangan_jabatan + tunjangan_anak;
                var total_potongan = bpjs + pph21;
                var gaji_bersih = total_pendapatan - total_potongan;

                $('#BPJS').val(bpjs.toFixed(2));
                $('#PPh21').val(pph21.toFixed(2));
                $('#Total_Pendapatan').val(total_pendapatan.toFixed(2));
                $('#Total_Potongan').val(total_potongan.toFixed(2));
                $('#Gaji_Bersih').val(gaji_bersih.toFixed(2));
            }

            $('#Jumlah_Anak').change(function() {
                var jumlah_anak = $(this).val();
                var gaji_pokok = parseFloat($('#Gaji_Pokok').val()) || 0;
                var tunjangan_anak = jumlah_anak * 0.05 * gaji_pokok;
                $('#Tunjangan_Anak').val(tunjangan_anak.toFixed(2));
                calculateGajiBersih();
            });

            $('#Status').change(function() {
                if ($(this).val() === "Kawin") {
                    $('#Jumlah_Anak').prop('readonly', false);
                } else {
                    $('#Jumlah_Anak').prop('readonly', true).val(0);
                    $('#Tunjangan_Anak').val(0);
                    calculateGajiBersih();
                }
            });
        });
    </script>
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
                    <a class="nav-link active" href="transaksi.php"><i class="fas fa-exchange-alt"></i> Transaksi</a>
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
                <h1 class="mb-4">Transaksi Gaji</h1>
                <form action="save_transaksi.php" method="post">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label for="ID_Gaji">ID Gaji:</label>
                                <input type="text" name="ID_Gaji" class="form-control" id="ID_Gaji" value="<?php echo $transaction_id; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="Nama_Karyawan">Nama Karyawan:</label>
                                <select name="Nama_Karyawan" id="Nama_Karyawan" class="form-control" required>
                                    <!-- Add PHP code to fetch and display karyawan names from the database -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="Jabatan">Jabatan:</label>
                                <input type="text" name="Jabatan" class="form-control" id="Jabatan" readonly>
                            </div>
                            <div class="form-group">
                                <label for="Gaji_Pokok">Gaji Pokok:</label>
                                <input type="text" name="Gaji_Pokok" class="form-control" id="Gaji_Pokok" readonly>
                            </div>
                            <div class="form-group">
                                <label for="Tunjangan_Jabatan">Tunjangan Jabatan:</label>
                                <input type="text" name="Tunjangan_Jabatan" class="form-control" id="Tunjangan_Jabatan" readonly>
                            </div>
                            <div class="form-group">
                                <label for="Status">Status:</label>
                                <select name="Status" id="Status" class="form-control" required>
                                    <option value="Kawin">Kawin</option>
                                    <option value="Belum Kawin">Belum Kawin</option>
                                </select>
                            </div>
                            <div class="form-group" id="Jumlah_Anak_Group">
                                <label for="Jumlah_Anak">Jumlah Anak:</label>
                                <input type="number" name="Jumlah_Anak" class="form-control" id="Jumlah_Anak" min="0">
                            </div>
                            <div class="form-group" id="Tunjangan_Anak_Group">
                                <label for="Tunjangan_Anak">Tunjangan Anak:</label>
                                <input type="text" name="Tunjangan_Anak" class="form-control" id="Tunjangan_Anak" readonly>
                            </div>
                            <div class="form-group">
                                <label for="BPJS">BPJS:</label>
                                <input type="text" name="BPJS" class="form-control" id="BPJS" readonly>
                            </div>
                            <div class="form-group">
                                <label for="PPh21">PPh21:</label>
                                <input type="text" name="PPh21" class="form-control" id="PPh21" readonly>
                            </div>
                            <div class="form-group">
                                <label for="Total_Pendapatan">Total Pendapatan:</label>
                                <input type="text" name="Total_Pendapatan" class="form-control" id="Total_Pendapatan" readonly>
                            </div>
                            <div class="form-group">
                                <label for="Total_Potongan">Total Potongan:</label>
                                <input type="text" name="Total_Potongan" class="form-control" id="Total_Potongan" readonly>
                            </div>
                            <div class="form-group">
                                <label for="Gaji_Bersih">Gaji Bersih:</label>
                                <input type="text" name="Gaji_Bersih" class="form-control" id="Gaji_Bersih" readonly>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="dashboard.php" class="btn btn-secondary">Close</a>
							<br>
							<br>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>

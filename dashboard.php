<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gaji";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Mengambil total karyawan
    $stmt = $conn->prepare("SELECT COUNT(*) as total_karyawan FROM tbl_karyawan");
    $stmt->execute();
    $total_karyawan = $stmt->fetch(PDO::FETCH_ASSOC)['total_karyawan'];

    // Mengambil total transaksi
    $stmt = $conn->prepare("SELECT COUNT(*) as total_transaksi FROM tbl_gaji");
    $stmt->execute();
    $total_transaksi = $stmt->fetch(PDO::FETCH_ASSOC)['total_transaksi'];

    // Mengambil pengguna aktif
    $stmt = $conn->prepare("SELECT COUNT(DISTINCT Id_User) as pengguna_aktif FROM tbl_gaji");
    $stmt->execute();
    $pengguna_aktif = $stmt->fetch(PDO::FETCH_ASSOC)['pengguna_aktif'];
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
                    <a class="nav-link active" href="dashboard.php"><i class="fas fa-home"></i> Home</a>
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
                <h3 class="mb-4">Selamat Datang di Dashboard Administrator Penggajian</h3>

                <div class="row">
                    <div class="col-md-4">
                        <div class="card text-white bg-primary mb-3">
                            <div class="card-header">Total Karyawan</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $total_karyawan; ?></h5>
                                <p class="card-text">Jumlah total karyawan</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-success mb-3">
                            <div class="card-header">Total Transaksi</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $total_transaksi; ?></h5>
                                <p class="card-text">Jumlah total transaksi</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card text-white bg-warning mb-3">
                            <div class="card-header">Pengguna Aktif</div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $pengguna_aktif; ?></h5>
                                <p class="card-text">Jumlah pengguna aktif</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

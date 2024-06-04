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

$sql = "SELECT k.id, k.NIK, k.Nama, j.Nama_Jabatan, k.Status FROM tbl_karyawan k 
        JOIN tbl_jabatan j ON k.Id_Jabatan = j.id ORDER BY k.Nama ASC";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
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
                    <a class="nav-link active" href="karyawan.php"><i class="fas fa-users"></i> Karyawan</a>
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
                <h1 class="mb-4">Data Karyawan</h1>
                <a href="add_karyawan.php" class="btn btn-primary mb-3"><i class="fas fa-plus"></i> Add</a>
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            $no = 1;
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>
                                        <td>{$no}</td>
                                        <td>{$row['NIK']}</td>
                                        <td>{$row['Nama']}</td>
                                        <td>{$row['Nama_Jabatan']}</td>
                                        <td>{$row['Status']}</td>
                                        <td>
                                            <a href='edit_karyawan.php?id={$row['id']}' class='btn btn-warning btn-sm'><i class='fas fa-edit'></i> Edit</a>
                                            <a href='delete_karyawan.php?id={$row['id']}' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this record?\");'><i class='fas fa-trash-alt'></i> Delete</a>
                                        </td>
                                    </tr>";
                                $no++;
                            }
                        } else {
                            echo "<tr><td colspan='6' class='text-center'>No data available</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

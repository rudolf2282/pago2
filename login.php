<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            text-align: center;
        }
        
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }
        
        .btn-primary:hover {
            background-color: #0056b3;
        }
        
        .form-label {
            font-weight: bold;
        }
        
        .form-control {
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-header">
                        <h3><i class="bi bi-box-arrow-in-right"></i> Login</h3>
                    </div>
                    <div class="card-body">
                        <form action="login.php" method="post" id="loginForm">
                            <div class="mb-3">
                                <label for="email" class="form-label"><i class="bi bi-envelope-fill"></i> Email:</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"><i class="bi bi-lock-fill"></i> Password:</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="d-flex justify-content-center">
                                <button type="submit" name="login" class="btn btn-primary"><i class="bi bi-box-arrow-in-right"></i> Login</button>
                            </div>
                        </form>
                        <?php
                        session_start();

                        if (isset($_POST['login'])) {
                            $email = $_POST['email'];
                            $password = $_POST['password'];

                            $conn = new mysqli("localhost", "root", "", "gaji");

                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $sql = "SELECT * FROM tbl_user WHERE email='$email'";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $user = $result->fetch_assoc();
                                if ($password === $user['password']) {
                                    $_SESSION['user_name'] = $user['user_name'];
                                    echo "<script>window.location.href = 'dashboard.php';</script>";
                                } else {
                                    echo "<script>alert('Email atau password salah');</script>";
                                }
                            } else {
                                echo "<script>alert('Email atau password salah');</script>";
                            }

                            $conn->close();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>

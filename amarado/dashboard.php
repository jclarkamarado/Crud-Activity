<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial;
            background:#000000;
        }
        .container {
            width: 420px;
            margin: 100px auto;
            padding: 50px;
            background: #ffffff48;
            border-radius: 6px;
            box-shadow: 0 0 8px #ccc;
            text-align: center;
        }
        a {
            display: block;
            margin: 10px 0;
            text-decoration: none;
            color: blue;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Dashboard</h2>

    <p>
        WELCOME,
        <b><?php echo htmlspecialchars($_SESSION['user']); ?></b> 🎉
    </p>

    <!-- CRUD LINK -->
    <a href="student.php">📘 Manage Student Records</a>

    <!-- LOGOUT -->
    <a href="logout.php">🚪 Logout</a>
</div>

</body>
</html>

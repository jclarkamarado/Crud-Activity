<?php
session_start();

// if (isset($_SESSION['user'])) {
//     header("Location: dashboard.php");
//     exit();
// }

if (isset($_POST['login'])) {
    $file = "users.txt";
    if (!file_exists($file)) file_put_contents($file, "");

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $error = "";

    $hash = hash("sha256", $password);
    $users = file($file, FILE_IGNORE_NEW_LINES);

    $valid = false;
    foreach ($users as $user) {
        list($u, $p) = explode("|", $user);
        if ($u === $username && $p === $hash) {
            $valid = true;
            break;
        }
    }

    if ($valid) {
        $_SESSION['user'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body { font-family:'Space Mono'; background:#000000; }
        .container {
            width: 300px; margin: 100px auto; padding: 80px; background: #7a797989;
            border-radius: 6px; box-shadow: 0 0 8px #8e8c8ea4; text-align: center;
        }
        input, button { width: 90%; padding: 8px; margin: 8px 0; }
        button { cursor: pointer; }
        .error { color: red; }
        a { color: blue; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required autofocus><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button name="login">Login</button>
    </form>
    <p>Don't have an account? <a href="signup.php">Sign up here</a></p>
</div>
</body>
</html>

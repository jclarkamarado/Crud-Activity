<?php
if (isset($_POST['signup'])) {
    $file = "users.txt";
    if (!file_exists($file)) file_put_contents($file, "");

    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $error = "";
    $success = "";

    if ($username === "" || $password === "") {
        $error = "Please fill all fields.";
    } else {
        $users = file($file, FILE_IGNORE_NEW_LINES);
        foreach ($users as $user) {
            list($u) = explode("|", $user);
            if ($u === $username) {
                $error = "Username already exists!";
                break;
            }
        }
        if (!$error) {
            $hash = hash("sha256", $password);
            file_put_contents($file, "$username|$hash\n", FILE_APPEND);
            $success = "Signup successful! You may now <a href='login.php'>login</a>.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <style>
        body { font-family: Arial; background:#000000; }
        .container {
            width: 320px; margin: 100px auto; padding: 20px; background: #79787863;
            border-radius: 6px; box-shadow: 0 0 8px #777778; text-align: center;
        }
        input, button { width: 90%; padding: 8px; margin: 8px 0; }
        button { cursor: pointer; }
        .error { color: red; }
        .success { color: green; }
        a { color: blue; text-decoration: none; }
    </style>
</head>
<body>
<div class="container">
    <h2>Sign Up</h2>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
    <?php if (!empty($success)) echo "<p class='success'>$success</p>"; ?>
    <form method="post">
        <input type="text" name="username" placeholder="Username" required autofocus><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button name="signup">Sign Up</button>
    </form>
    <p>Already have an account? <a href="login.php">Login here</a></p>
</div>
</body>
</html>

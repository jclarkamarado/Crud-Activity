<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$file = "students.txt";
if (!file_exists($file)) file_put_contents($file, "");

/* DELETE */
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $students = file($file, FILE_IGNORE_NEW_LINES);
    $new = [];

    foreach ($students as $s) {
        list($sid) = explode("|", $s);
        if ($sid != $id) $new[] = $s;
    }
    file_put_contents($file, implode("\n", $new));
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

/* ADD / UPDATE */
if (isset($_POST['save'])) {
    $name = trim($_POST['name']);
    $course = $_POST['course'];

    $students = file($file, FILE_IGNORE_NEW_LINES);
    $updated = false;

    // AUTO ID with 3-digit zero padding
    if (isset($_POST['edit_id'])) {
        $id = $_POST['edit_id'];
    } else {
        $id_num = 1;
        if (count($students) > 0) {
            $last = explode("|", end($students));
            $id_num = intval($last[0]) + 1;
        }
        $id = str_pad($id_num, 3, "0", STR_PAD_LEFT);
    }

    foreach ($students as $index => $s) {
        list($sid) = explode("|", $s);
        if ($sid == $id) {
            $students[$index] = "$id|$name|$course";
            $updated = true;
        }
    }

    if (!$updated) {
        $students[] = "$id|$name|$course";
    }

    file_put_contents($file, implode("\n", $students));
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

/* EDIT */
$edit = null;
if (isset($_GET['edit'])) {
    foreach (file($file, FILE_IGNORE_NEW_LINES) as $s) {
        list($id,$name,$course) = explode("|", $s);
        if ($id == $_GET['edit']) {
            $edit = [$id,$name,$course];
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Student Records - Simple Techy</title>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap" rel="stylesheet" />

<style>
  body {
    margin: 0;
    font-family: 'Roboto Mono', monospace;
    background-color: #121212;
    color: #e0e0e0;
    display: flex;
    justify-content: center;
    padding: 40px 20px;
  }

  .container {
    background-color: #1e1e1e;
    border-radius: 10px;
    padding: 30px 40px;
    width: 850px;
    max-width: 100%;
    box-shadow: 0 0 15px rgba(0, 123, 255, 0.3);
  }

  h1 {
    text-align: center;
    margin-bottom: 25px;
    font-weight: 700;
    color: #4fc3f7;
  }

  form {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
    margin-bottom: 30px;
    justify-content: center;
  }

  input[type="text"], select {
    background-color: #292929;
    border: 1px solid #4fc3f7;
    border-radius: 6px;
    padding: 12px 15px;
    color: #e0e0e0;
    font-size: 16px;
    width: 260px;
    transition: border-color 0.3s ease;
  }

  input[type="text"]::placeholder,
  select:invalid {
    color: #777;
  }

  input[type="text"]:focus,
  select:focus {
    outline: none;
    border-color: #82cfff;
    background-color: #323232;
  }

  button {
    background-color: #4fc3f7;
    border: none;
    border-radius: 6px;
    padding: 12px 40px;
    color: #121212;
    font-weight: 700;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s ease;
    align-self: center;
    flex-shrink: 0;
  }

  button:hover {
    background-color: #82cfff;
  }

  table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 10px;
    font-size: 16px;
  }

  thead tr {
    background-color: #2a2a2a;
    color: #4fc3f7;
  }

  th, td {
    padding: 14px 20px;
    text-align: center;
  }

  tbody tr {
    background-color: #292929;
    color: #d0d0d0;
    border-radius: 6px;
    transition: background-color 0.3s ease;
  }

  tbody tr:hover {
    background-color: #3a8ede;
    color: #121212;
    font-weight: 700;
  }

  tbody tr td:first-child {
    font-weight: 700;
    color: #4fc3f7;
  }

  .actions a {
    color: #82cfff;
    font-weight: 600;
    margin: 0 10px;
    text-decoration: none;
    transition: color 0.3s ease;
  }

  .actions a:hover {
    color: #b3e5fc;
  }

  .back-link {
    display: block;
    margin-top: 30px;
    font-weight: 600;
    color: #4fc3f7;
    text-decoration: none;
    text-align: center;
  }

  .back-link:hover {
    color: #82cfff;
  }

  @media (max-width: 700px) {
    form {
      flex-direction: column;
      gap: 15px;
      align-items: center;
    }
    input[type="text"], select, button {
      width: 100%;
    }
  }
</style>

</head>
<body>

<div class="container">
  <h1>Student Records</h1>

  <form method="post" autocomplete="off" spellcheck="false">
      <?php if ($edit): ?>
          <input type="hidden" name="edit_id" value="<?= htmlspecialchars($edit[0]) ?>">
      <?php endif; ?>

      <input type="text" name="name" placeholder="Student Name" required
        value="<?= htmlspecialchars($edit[1] ?? '') ?>">

      <select name="course" required>
          <option value="" disabled <?= empty($edit[2]) ? 'selected' : '' ?>>-- Select Course --</option>
          <?php
          $courses = ['BSIT', 'BEED', 'BSBA', 'BSHM'];
          foreach ($courses as $c) {
              $selected = ($edit[2] ?? '') === $c ? 'selected' : '';
              echo "<option value=\"$c\" $selected>$c</option>";
          }
          ?>
      </select>

      <button name="save"><?= $edit ? 'Update' : 'Add' ?></button>
  </form>

  <table>
    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Course</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php
    $students = file($file, FILE_IGNORE_NEW_LINES);
    if (count($students) === 0) {
        echo '<tr><td colspan="4" style="padding: 20px; color: #777;">No student records found.</td></tr>';
    } else {
        foreach ($students as $s) {
            list($id,$name,$course) = explode("|", $s);
            echo '<tr>';
            echo '<td>' . htmlspecialchars($id) . '</td>';
            echo '<td>' . htmlspecialchars($name) . '</td>';
            echo '<td>' . htmlspecialchars($course) . '</td>';
            echo '<td class="actions">';
            echo '<a href="?edit=' . urlencode($id) . '">Edit</a>';
            echo ' | ';
            echo '<a href="?delete=' . urlencode($id) . '" onclick="return confirm(\'Are you sure you want to delete this record?\')">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }
    }
    ?>
    </tbody>
  </table>

  <a href="dashboard.php" class="back-link">← Back to Dashboard</a>
</div>

</body>
</html>

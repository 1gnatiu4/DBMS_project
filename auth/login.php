<?php
session_start();
require_once '../config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = $user;
        $role = $user['role'];

       if ($user && password_verify($password, $user['password'])) {
    $_SESSION['user'] = $user;
    $role = $user['role'];

    // Redirect by role
    switch ($role) {
        case 'admin':
            header("Location: ../dashboard/index.php");
            break;
        case 'teacher':
            header("Location: ../dashboard/teacher.php");
            break;
        case 'parent':
            header("Location: ../dashboard/parent.php");
            break;
        case 'student':
            header("Location: ../dashboard/student.php");
            break;
        default:
            die("Unknown role.");
    }
    exit;
}

        
    } else {
        $error = "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
  <form method="POST" class="bg-white p-8 rounded shadow-md w-96">
    <h2 class="text-xl font-bold mb-6 text-blue-600">Login to School System</h2>
    <?php if ($error): ?>
      <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= $error ?></div>
    <?php endif; ?>
    <input type="text" name="username" placeholder="Username" class="w-full p-2 mb-4 border border-gray-300 rounded" required>
    <input type="password" name="password" placeholder="Password" class="w-full p-2 mb-4 border border-gray-300 rounded" required>
    <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Login</button>
  </form>
</body>
</html>

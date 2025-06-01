<?php
require_once '../config/db.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $role]);

        // ✅ Redirect to login page after successful registration
        header("Location: login.php?signup=success");
        exit;

    } catch (Exception $e) {
        $error = "Signup failed: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html>
<head>
  <title>User Signup</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
  <div class="bg-white p-6 rounded shadow-md w-96">
    <h2 class="text-xl font-bold mb-4 text-blue-600">User Signup</h2>

    <?php if ($success): ?>
      <div class="bg-green-100 text-green-700 p-2 mb-4 rounded"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <input type="text" name="username" placeholder="Username" class="w-full p-2 mb-3 border rounded" required>
      <input type="password" name="password" placeholder="Password" class="w-full p-2 mb-3 border rounded" required>

      <select name="role" class="w-full p-2 mb-4 border rounded" required>
        <option value="">Select Role</option>
        <option value="teacher">Teacher</option>
        <option value="parent">Parent</option>
        <option value="student">Student</option>
        <option value="admin">Admin</option>
      </select>

      <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Sign Up</button>
    </form>
  </div>
</body>
</html>

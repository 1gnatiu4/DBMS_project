<?php
require_once '../config/db.php';
require_once '../utils/auth_check.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first = $_POST['first_name'] ?? '';
    $last = $_POST['last_name'] ?? '';
    $class = $_POST['class'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $dob = $_POST['dob'] ?? '';
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $pdo->beginTransaction();

        // Create user account
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'student')");
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
        $user_id = $pdo->lastInsertId();

        // Add student details
        $stmt = $pdo->prepare("INSERT INTO students (user_id, first_name, last_name, class, gender, dob)
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $first, $last, $class, $gender, $dob]);

        $pdo->commit();
        $success = "Student registered successfully.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Add Student</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
  <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold mb-4 text-blue-600">Add New Student</h2>

    <?php if ($success): ?>
      <div class="bg-green-100 text-green-700 p-2 mb-4 rounded"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="grid grid-cols-2 gap-4">
        <input type="text" name="first_name" placeholder="First Name" class="p-2 border rounded" required>
        <input type="text" name="last_name" placeholder="Last Name" class="p-2 border rounded" required>
      </div>
      <div class="mt-4">
        <input type="text" name="class" placeholder="Class (e.g. Grade 10A)" class="w-full p-2 border rounded" required>
      </div>
      <div class="mt-4 flex space-x-4">
        <label class="flex items-center"><input type="radio" name="gender" value="male" required> <span class="ml-1">Male</span></label>
        <label class="flex items-center"><input type="radio" name="gender" value="female"> <span class="ml-1">Female</span></label>
      </div>
      <div class="mt-4">
        <label>Date of Birth</label>
        <input type="date" name="dob" class="w-full p-2 border rounded" required>
      </div>
      <hr class="my-4">
      <h3 class="text-lg font-semibold">Create Login for Student</h3>
      <input type="text" name="username" placeholder="Username" class="w-full p-2 border rounded mt-2" required>
      <input type="password" name="password" placeholder="Password" class="w-full p-2 border rounded mt-2" required>
      <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded mt-4 hover:bg-blue-700">Add Student</button>
    </form>
  </div>
</body>
</html>

<?php
require_once '../config/db.php';
require_once '../utils/auth_check.php';
if ($_SESSION['user']['role'] !== 'student') die("Access denied.");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

<div class="min-h-screen flex">
  <div class="w-64 bg-white shadow-lg">
    <div class="p-6 text-2xl font-bold text-blue-600">Student Panel</div>
    <nav class="mt-4">
      <a href="../students/profile.php" class="flex items-center p-3 hover:bg-blue-100"><i class="fas fa-id-badge mr-3"></i> My Profile</a>
      <a href="../grades/report.php" class="flex items-center p-3 hover:bg-blue-100"><i class="fas fa-chart-line mr-3"></i> My Results</a>
    </nav>
  </div>

  <div class="flex-1 p-8">
    <h1 class="text-2xl font-semibold mb-6">Hello, <?= $_SESSION['user']['username'] ?> 👋</h1>

    <div class="grid grid-cols-2 gap-6">
      <div class="bg-blue-500 text-white p-6 rounded-xl shadow text-center">
        <i class="fas fa-user text-3xl mb-2"></i>
        <h3 class="text-xl font-semibold">My Profile</h3>
        <a href="../students/profile.php" class="underline">View Details</a>
      </div>
      <div class="bg-green-500 text-white p-6 rounded-xl shadow text-center">
        <i class="fas fa-book-open text-3xl mb-2"></i>
        <h3 class="text-xl font-semibold">Results</h3>
        <a href="../grades/report.php" class="underline">View My Grades</a>
      </div>
    </div>

    <footer class="text-center mt-12 text-sm text-gray-500">
      &copy; 2025 Malata Ignatius - SIN: 2307365923
    </footer>
  </div>
</div>
</body>
</html>

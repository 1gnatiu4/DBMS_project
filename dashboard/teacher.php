<?php
require_once '../config/db.php';
require_once '../utils/auth_check.php';
if ($_SESSION['user']['role'] !== 'teacher') die("Access denied.");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Teacher Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

<div class="min-h-screen flex">
  <!-- Sidebar -->
  <div class="w-64 bg-white shadow-lg">
    <div class="p-6 text-2xl font-bold text-blue-600">Teacher Panel</div>
    <nav class="mt-4">
      <a href="../students/list.php" class="flex items-center p-3 hover:bg-blue-100"><i class="fas fa-users mr-3"></i> Student List</a>
      <a href="../grades/add.php" class="flex items-center p-3 hover:bg-blue-100"><i class="fas fa-pen mr-3"></i> Enter Grades</a>
      <a href="../grades/report.php" class="flex items-center p-3 hover:bg-blue-100"><i class="fas fa-chart-bar mr-3"></i> View Reports</a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="flex-1 p-8">
    <h1 class="text-2xl font-semibold mb-6">Welcome, <?= $_SESSION['user']['username'] ?></h1>

    <div class="grid grid-cols-3 gap-6">
      <div class="bg-blue-500 text-white p-6 rounded-xl shadow text-center">
        <i class="fas fa-user-graduate text-3xl mb-2"></i>
        <h3 class="text-xl font-semibold">View Students</h3>
        <p>Check your student list and profiles.</p>
      </div>
      <div class="bg-green-500 text-white p-6 rounded-xl shadow text-center">
        <i class="fas fa-pen-to-square text-3xl mb-2"></i>
        <h3 class="text-xl font-semibold">Enter Grades</h3>
        <p>Submit academic scores per subject.</p>
      </div>
      <div class="bg-purple-500 text-white p-6 rounded-xl shadow text-center">
        <i class="fas fa-chart-line text-3xl mb-2"></i>
        <h3 class="text-xl font-semibold">Analyze Performance</h3>
        <p>View student reports and trends.</p>
      </div>
    </div>

    <footer class="text-center mt-12 text-sm text-gray-500">
      &copy; 2025 Malata Ignatius - SIN: 2307365923
    </footer>
  </div>
</div>
</body>
</html>

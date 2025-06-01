<?php
require_once '../config/db.php';
require_once '../utils/auth_check.php';
if ($_SESSION['user']['role'] !== 'parent') die("Access denied.");
?>

<!DOCTYPE html>
<html>
<head>
  <title>Parent Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

<div class="min-h-screen flex">
  <div class="w-64 bg-white shadow-lg">
    <div class="p-6 text-2xl font-bold text-blue-600">Parent Panel</div>
    <nav class="mt-4">
      <a href="../grades/report.php" class="flex items-center p-3 hover:bg-blue-100"><i class="fas fa-file-alt mr-3"></i> View Child Reports</a>
    </nav>
  </div>

  <div class="flex-1 p-8">
    <h1 class="text-2xl font-semibold mb-6">Welcome, <?= $_SESSION['user']['username'] ?></h1>

    <div class="bg-white rounded-lg shadow p-6">
      <h2 class="text-lg font-semibold mb-4 text-blue-700"><i class="fas fa-info-circle mr-2"></i>Instructions</h2>
      <p>As a parent, you can view academic performance of your child and download or print reports.</p>

      <div class="mt-6">
        <a href="../grades/report.php" class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
          <i class="fas fa-file-download mr-2"></i> View Report Card
        </a>
      </div>
    </div>

    <footer class="text-center mt-12 text-sm text-gray-500">
      &copy; 2025 Malata Ignatius - SIN: 2307365923
    </footer>
  </div>
</div>
</body>
</html>

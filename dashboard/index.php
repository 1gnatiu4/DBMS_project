<?php
session_start();
require_once "../config/db.php";

// Optional: Restrict access only to logged-in users
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$student_count = $pdo->query("SELECT COUNT(*) FROM students")->fetchColumn();
$teacher_count = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'teacher'")->fetchColumn();
$class_count = $pdo->query("SELECT COUNT(DISTINCT class) FROM students")->fetchColumn();
$parent_count = $pdo->query("SELECT COUNT(*) FROM users WHERE role = 'parent'")->fetchColumn();

$subjects = ["Math", "Science", "English"];
$term_labels = ["Term 1", "Term 2", "Term 3"];
$performance_data = [
    "Math" => [40, 75, 95],
    "Science" => [20, 50, 60],
    "English" => [30, 40, 55]
];

// Prepare chart dataset
$datasets = [];
foreach ($performance_data as $label => $data) {
    $color = ($label === 'Math') ? 'blue' : (($label === 'Science') ? 'red' : 'orange');
    $datasets[] = [
        'label' => $label,
        'data' => $data,
        'borderColor' => $color,
        'fill' => false
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Learner Performance Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans">

<div class="flex h-screen">
  <!-- Sidebar -->
  <div class="w-64 bg-white shadow-md">
    <div class="p-6 text-2xl font-bold text-blue-600">School System</div>
    <nav class="mt-4">
      <a href="index.php" class="flex items-center p-3 text-blue-600 font-semibold hover:bg-blue-100">
        <i class="fas fa-tachometer-alt mr-3"></i> Dashboard
      </a>
      <a href="../students/add.php" class="flex items-center p-3 hover:bg-gray-100">
        <i class="fas fa-plus mr-3"></i> Register Students
      </a>
      <a href="../students/list.php" class="flex items-center p-3 hover:bg-gray-100">
        <i class="fas fa-user-graduate mr-3"></i> List Students
      </a>
      <a href="../grades/report.php" class="flex items-center p-3 hover:bg-gray-100">
        <i class="fas fa-book mr-3"></i> Grades
      </a>
      <a href="../grades/add.php" class="flex items-center p-3 hover:bg-gray-100">
        <i class="fas fa-pencil mr-3"></i> Enter Results
      </a>
      <a href="../grades/report.php" class="flex items-center p-3 hover:bg-gray-100">
        <i class="fas fa-chart-line mr-3"></i> Reports
      </a>
    </nav>
  </div>

  <!-- Main Content -->
  <div class="flex-1 p-6 overflow-y-auto">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold">Learner Performance Dashboard</h1>
      <div class="flex items-center space-x-4">
        <span><?= ucfirst($_SESSION['user']['role'] ?? 'Guest') ?></span>
        <i class="fas fa-user-circle text-2xl"></i>
      </div>
    </div>

    <div class="grid grid-cols-4 gap-4 mb-6">
      <div class="bg-blue-500 text-white p-4 rounded-xl shadow">
        <div class="text-lg">Students</div>
        <div class="text-2xl font-bold"><?= $student_count ?></div>
      </div>
      <div class="bg-yellow-400 text-white p-4 rounded-xl shadow">
        <div class="text-lg">Teachers</div>
        <div class="text-2xl font-bold"><?= $teacher_count ?></div>
      </div>
      <div class="bg-green-500 text-white p-4 rounded-xl shadow">
        <div class="text-lg">Classes</div>
        <div class="text-2xl font-bold"><?= $class_count ?></div>
      </div>
      <div class="bg-purple-500 text-white p-4 rounded-xl shadow">
        <div class="text-lg">Parents</div>
        <div class="text-2xl font-bold"><?= $parent_count ?></div>
      </div>
    </div>

    <div class="grid grid-cols-2 gap-6 mb-6">
      <div class="bg-white p-4 rounded-xl shadow">
        <h2 class="text-lg font-semibold mb-2">Overall Performance</h2>
        <canvas id="lineChart"></canvas>
      </div>
      <div class="bg-white p-4 rounded-xl shadow">
        <h2 class="text-lg font-semibold mb-2">Recent Grades</h2>
        <p>Dynamic grade list will go here.</p>
      </div>
    </div>

    <footer class="text-center mt-10 text-sm text-gray-500">
      &copy; 2025 Malata Ignatius - SIN: 2307365923
    </footer>
  </div>
</div>

<script>
const ctx = document.getElementById("lineChart").getContext("2d");
new Chart(ctx, {
  type: "line",
  data: {
    labels: <?= json_encode($term_labels) ?>,
    datasets: <?= json_encode($datasets) ?>
  }
});
</script>

</body>
</html>

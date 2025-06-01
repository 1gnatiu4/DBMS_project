<?php
require_once '../config/db.php';
require_once '../utils/auth_check.php';

// Only allow admin or teacher to view
if (!in_array($_SESSION['user']['role'], ['admin', 'teacher'])) {
    die("Access denied.");
}

// Fetch all students with user info
$stmt = $pdo->query("
    SELECT s.*, u.username 
    FROM students s
    JOIN users u ON s.user_id = u.id
    ORDER BY s.class, s.first_name
");
$students = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Student List</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-5xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold text-blue-600 mb-4">Registered Students</h2>

    <?php if (count($students) > 0): ?>
      <table class="w-full border text-sm">
        <thead class="bg-gray-100 text-left">
          <tr>
            <th class="border p-2">#</th>
            <th class="border p-2">Full Name</th>
            <th class="border p-2">ID Number</th>
            <th class="border p-2">Class</th>
            <th class="border p-2">Gender</th>
            <th class="border p-2">DOB</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($students as $index => $student): ?>
            <tr class="hover:bg-gray-50">
              <td class="border p-2"><?= $index + 1 ?></td>
              <td class="border p-2"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
              <td class="border p-2"><?= htmlspecialchars($student['username']) ?></td>
              <td class="border p-2"><?= htmlspecialchars($student['class']) ?></td>
              <td class="border p-2"><?= ucfirst($student['gender']) ?></td>
              <td class="border p-2"><?= $student['dob'] ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-red-500">No students registered yet.</p>
    <?php endif; ?>
  </div>
</body>
</html>

<?php
require_once '../config/db.php';
require_once '../utils/auth_check.php';

if ($_SESSION['user']['role'] !== 'student') {
    die("Access denied. Students only.");
}

$user_id = $_SESSION['user']['id'];
$student = $pdo->prepare("SELECT * FROM students WHERE user_id = ?");
$student->execute([$user_id]);
$student = $student->fetch();

$grades = $pdo->prepare("
    SELECT g.*, sub.subject_name
    FROM grades g
    JOIN subjects sub ON g.subject_id = sub.id
    WHERE g.student_id = ?
");
$grades->execute([$student['id']]);
$grades = $grades->fetchAll();

function get_grade($score) {
    if ($score >= 75) return "Distinction";
    if ($score >= 65) return "Merit";
    if ($score >= 55) return "Credit";
    if ($score >= 40) return "Pass";
    return "Fail";
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>My Profile</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold text-blue-600 mb-4">Student Profile</h2>
    <p><strong>Name:</strong> <?= $student['first_name'] . ' ' . $student['last_name'] ?></p>
    <p><strong>Class:</strong> <?= $student['class'] ?></p>
    <p><strong>DOB:</strong> <?= $student['dob'] ?></p>

    <hr class="my-4">

    <h3 class="text-xl font-semibold mb-2">My Grades</h3>

    <?php if (count($grades)): ?>
      <table class="w-full text-sm border">
        <thead class="bg-gray-100">
          <tr>
            <th class="border p-2 text-left">Term</th>
            <th class="border p-2 text-left">Subject</th>
            <th class="border p-2 text-left">Marks</th>
            <th class="border p-2 text-left">Grade</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($grades as $g): ?>
            <tr class="hover:bg-gray-50">
              <td class="border p-2"><?= $g['term'] ?> <?= $g['year'] ?></td>
              <td class="border p-2"><?= $g['subject_name'] ?></td>
              <td class="border p-2"><?= $g['score'] ?></td>
              <td class="border p-2"><?= get_grade($g['score']) ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p class="text-red-500">No grades recorded yet.</p>
    <?php endif; ?>
  </div>
</body>
</html>

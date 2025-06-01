
<?php
require_once '../config/db.php';
require_once '../utils/auth_check.php';
if (!in_array($_SESSION['user']['role'], ['admin', 'parent'])) {
    die("Access denied.");
}


function get_grade($score) {
    if ($score >= 75) return "Distinction";
    if ($score >= 65) return "Merit";
    if ($score >= 55) return "Credit";
    if ($score >= 40) return "Pass";
    return "Fail";
}

$students = $pdo->query("
    SELECT s.id, s.first_name, s.last_name, s.class, u.username
    FROM students s
    JOIN users u ON s.user_id = u.id
")->fetchAll();

$results = [];
foreach ($students as $student) {
    $grades = $pdo->prepare("
        SELECT g.*, sub.subject_name
        FROM grades g
        JOIN subjects sub ON g.subject_id = sub.id
        WHERE g.student_id = ?
    ");
    $grades->execute([$student['id']]);
    $results[$student['id']] = $grades->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Grades Report</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">
  <div class="max-w-7xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold text-blue-600 mb-6">Grades Report</h2>

    <?php foreach ($students as $student): ?>
      <div class="mb-10 border-b pb-6">
        <h3 class="text-xl font-bold mb-1"><?= $student['first_name'] . ' ' . $student['last_name'] ?></h3>
        <p class="text-sm text-gray-600 mb-2">ID Number: <?= $student['username'] ?> | Class: <?= $student['class'] ?></p>

        <?php if (count($results[$student['id']])): ?>
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
              <?php foreach ($results[$student['id']] as $record): ?>
                <tr class="hover:bg-gray-50">
                  <td class="border p-2"><?= $record['term'] ?> <?= $record['year'] ?></td>
                  <td class="border p-2"><?= $record['subject_name'] ?></td>
                  <td class="border p-2"><?= $record['score'] ?></td>
                  <td class="border p-2"><?= get_grade($record['score']) ?></td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else: ?>
          <p class="text-red-500">No grades available.</p>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>

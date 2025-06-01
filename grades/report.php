<?php
require_once '../config/db.php';
require_once '../utils/auth_check.php';


$user = $_SESSION['user'] ?? null;

if (!$user) {
    die("Access denied.");
}

$results = [];
$students = [];

function get_ecz_grade($score) {
    if ($score >= 75) return "1 - Distinction";
    if ($score >= 65) return "2 - Merit";
    if ($score >= 55) return "3 - Credit";
    if ($score >= 40) return "4 - Pass";
    return "8 - Fail";
}

if ($user['role'] === 'student') {
    // Get only the logged-in student's record
    $stmt = $pdo->prepare("SELECT s.id, s.first_name, s.last_name, s.class, u.username
                           FROM students s JOIN users u ON s.user_id = u.id
                           WHERE s.user_id = ?");
    $stmt->execute([$user['id']]);
    $student = $stmt->fetch();
    if ($student) {
        $students[] = $student;

        $grades = $pdo->prepare("
            SELECT g.*, sub.subject_name
            FROM grades g
            JOIN subjects sub ON g.subject_id = sub.id
            WHERE g.student_id = ?
        ");
        $grades->execute([$student['id']]);
        $results[$student['id']] = $grades->fetchAll();
    }
} elseif (in_array($user['role'], ['admin', 'parent', 'teacher'])) {
    // Get all students for admin, teacher, parent
    $students = $pdo->query("
        SELECT s.id, s.first_name, s.last_name, s.class, u.username
        FROM students s
        JOIN users u ON s.user_id = u.id
    ")->fetchAll();

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
} else {
    die("Access denied.");
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Report Card</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-6">

<?php if (count($students) > 0): ?>
  <?php foreach ($students as $student): ?>
    <div class="bg-white shadow p-6 mb-10">
      <h2 class="text-xl font-bold text-blue-600 mb-2">Report for <?= $student['first_name'] . ' ' . $student['last_name'] ?></h2>
      <p><strong>ID Number:</strong> <?= $student['username'] ?></p>
      <p><strong>Class:</strong> <?= $student['class'] ?></p>

      <?php if (!empty($results[$student['id']])): ?>
        <table class="w-full mt-4 border text-sm">
          <thead class="bg-gray-200">
            <tr>
              <th class="border p-2 text-left">Term</th>
              <th class="border p-2 text-left">Subject</th>
              <th class="border p-2 text-left">Score (%)</th>
              <th class="border p-2 text-left">ECZ Grade</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($results[$student['id']] as $record): ?>
              <tr class="hover:bg-gray-50">
                <td class="border p-2"><?= $record['term'] . ' ' . $record['year'] ?></td>
                <td class="border p-2"><?= $record['subject_name'] ?></td>
                <td class="border p-2"><?= $record['score'] ?></td>
                <td class="border p-2"><?= get_ecz_grade($record['score']) ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else: ?>
        <p class="text-red-500 mt-4">No grades recorded.</p>
      <?php endif; ?>
    </div>
  <?php endforeach; ?>
<?php else: ?>
  <div class="text-red-600 text-center mt-20 text-xl font-semibold">No student records found for this user.</div>
<?php endif; ?>

</body>
</html>

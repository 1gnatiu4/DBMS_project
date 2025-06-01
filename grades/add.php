<?php
require_once '../config/db.php';
require_once '../utils/auth_check.php';

if ($_SESSION['user']['role'] !== 'teacher') {
    die("Access denied. Only teachers can enter grades.");
}

$success = '';
$error = '';

// Fetch students and subjects
$students = $pdo->query("SELECT id, first_name, last_name FROM students")->fetchAll();
$subjects = $pdo->query("SELECT id, subject_name FROM subjects")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_POST['student_id'];
    $subject_id = $_POST['subject_id'];
    $term = $_POST['term'];
    $year = $_POST['year'];
    $score = $_POST['score'];
    $comments = $_POST['comments'];

    try {
        $stmt = $pdo->prepare("INSERT INTO grades (student_id, subject_id, term, year, score, comments) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$student_id, $subject_id, $term, $year, $score, $comments]);
        $success = "Grade added successfully.";
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Enter Grade</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 p-8">
  <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-2xl font-semibold text-blue-600 mb-4">Enter Student Grade</h2>

    <?php if ($success): ?>
      <div class="bg-green-100 text-green-700 p-2 mb-4 rounded"><?= $success ?></div>
    <?php elseif ($error): ?>
      <div class="bg-red-100 text-red-700 p-2 mb-4 rounded"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <div class="mb-4">
        <label>Student:</label>
        <select name="student_id" class="w-full p-2 border rounded" required>
          <option value="">Select Student</option>
          <?php foreach ($students as $student): ?>
            <option value="<?= $student['id'] ?>"><?= $student['first_name'] . ' ' . $student['last_name'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="mb-4">
        <label>Subject:</label>
        <select name="subject_id" class="w-full p-2 border rounded" required>
          <option value="">Select Subject</option>
          <?php foreach ($subjects as $subject): ?>
            <option value="<?= $subject['id'] ?>"><?= $subject['subject_name'] ?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <input type="text" name="term" placeholder="Term (e.g. Term 1)" class="p-2 border rounded" required>
        <input type="number" name="year" placeholder="Year (e.g. 2025)" class="p-2 border rounded" required>
      </div>
      <div class="mt-4">
        <input type="number" name="score" placeholder="Score (0-100)" class="w-full p-2 border rounded" required>
      </div>
      <div class="mt-4">
        <textarea name="comments" placeholder="Comments (optional)" class="w-full p-2 border rounded"></textarea>
      </div>
      <button type="submit" class="mt-4 w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700">Submit Grade</button>
    </form>
  </div>
</body>
</html>

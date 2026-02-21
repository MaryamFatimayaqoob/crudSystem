<?php

require_once "../config/Database.php";
require_once "../models/Student.php";

$database = new Database();
$db = $database->getConnection();

$student = new Student($db);

if(isset($_POST['add'])) {

    $data = [
        'roll_no' => $_POST['roll_no'],
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'department' => $_POST['department'],
        'semester' => $_POST['semester'],
        'cgpa' => $_POST['cgpa'],
        'status' => $_POST['status']
    ];

    $student->create($data);
}

if(isset($_GET['delete'])) {
    $student->delete($_GET['delete']);
}

$students = $student->readAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Student System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

<h2>Student Management System</h2>

<form method="POST" class="row g-3">
    <input type="text" name="roll_no" placeholder="Roll No" class="form-control" required>
    <input type="text" name="name" placeholder="Name" class="form-control" required>
    <input type="email" name="email" placeholder="Email" class="form-control" required>
    <input type="text" name="department" placeholder="Department" class="form-control" required>
    <input type="number" name="semester" placeholder="Semester" class="form-control" required>
    <input type="number" step="0.01" name="cgpa" placeholder="CGPA" class="form-control">
    <select name="status" class="form-control">
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
    </select>
    <button type="submit" name="add" class="btn btn-primary">Add Student</button>
</form>

<hr>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Roll</th>
<th>Name</th>
<th>Email</th>
<th>Dept</th>
<th>Sem</th>
<th>CGPA</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = $students->fetch(PDO::FETCH_ASSOC)): ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['roll_no'] ?></td>
<td><?= $row['name'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['department'] ?></td>
<td><?= $row['semester'] ?></td>
<td><?= $row['cgpa'] ?></td>
<td><?= $row['status'] ?></td>
<td>
<a href="?delete=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a>
</td>
</tr>
<?php endwhile; ?>

</table>

</body>
</html>
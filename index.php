<?php

require_once "../config/Database.php";
require_once "../models/Student.php";

$database = new Database();
$db = $database->getConnection();

$student = new Student($db);
$message = "";

if(isset($_POST['add'])) {

    if(
        !empty($_POST['roll_no']) &&
        !empty($_POST['name']) &&
        !empty($_POST['email'])
    ){

        $data = [
            'roll_no' => trim($_POST['roll_no']),
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'department' => trim($_POST['department']),
            'semester' => (int)$_POST['semester'],
            'cgpa' => (float)$_POST['cgpa'],
            'status' => $_POST['status']
        ];

        if($student->create($data)){
            header("Location: index.php?success=1");
            exit;
        } else {
            $message = "Error adding student.";
        }

    } else {
        $message = "Please fill required fields.";
    }
}

if(isset($_GET['delete'])) {

    if($student->delete($_GET['delete'])){
        header("Location: index.php?deleted=1");
        exit;
    }
}
$search = $_GET['search'] ?? "";
$page = $_GET['page'] ?? 1;

$limit = 5;
$offset = ($page - 1) * $limit;

if($search){
    $students = $student->search($search);
} else {
    $students = $student->readPaginated($limit,$offset);
}

$totalStudents = $student->readAll()->rowCount();
$totalPages = ceil($totalStudents / $limit);
$students = $student->readAll();

?>

<!DOCTYPE html>
<html>
<head>
<title>Student System</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

<h2 class="mb-4">Student Management System</h2>

<?php if(isset($_GET['success'])): ?>
<div class="alert alert-success">Student added successfully!</div>
<?php endif; ?>

<?php if(isset($_GET['deleted'])): ?>
<div class="alert alert-danger">Student deleted!</div>
<?php endif; ?>

<?php if($message): ?>
<div class="alert alert-warning"><?= $message ?></div>
<?php endif; ?>

<!-- FORM -->
<form method="POST" class="row g-3 mb-4">

<input type="text" name="roll_no" placeholder="Roll No" class="form-control" required>

<input type="text" name="name" placeholder="Name" class="form-control" required>

<input type="email" name="email" placeholder="Email" class="form-control" required>

<input type="text" name="department" placeholder="Department" class="form-control">

<input type="number" name="semester" placeholder="Semester" class="form-control">

<input type="number" step="0.01" name="cgpa" placeholder="CGPA" class="form-control">

<select name="status" class="form-control">
<option value="active">Active</option>
<option value="inactive">Inactive</option>
</select>

<button type="submit" name="add" class="btn btn-primary">Add Student</button>

</form>

<hr>
<form method="GET" class="mb-3">

<div class="input-group">
<input type="text" 
name="search" 
class="form-control"
placeholder="Search by name, roll or email"
value="<?= htmlspecialchars($search) ?>">

<button class="btn btn-dark">Search</button>
</div>

</form>

<!-- TABLE -->
<table class="table table-bordered table-hover">
<thead class="table-dark">
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
</thead>

<tbody>

<?php while($row = $students->fetch(PDO::FETCH_ASSOC)): ?>

<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['roll_no']) ?></td>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= htmlspecialchars($row['email']) ?></td>
<td><?= htmlspecialchars($row['department']) ?></td>
<td><?= $row['semester'] ?></td>
<td><?= $row['cgpa'] ?></td>
<td><?= ucfirst($row['status']) ?></td>

<td>
<a href="?delete=<?= $row['id'] ?>" 
class="btn btn-danger btn-sm"
onclick="return confirm('Delete this student?')">
Delete
</a>
</td>

</tr>

<?php endwhile; ?>

</tbody>
</table>
<nav>
<ul class="pagination">

<?php for($i=1;$i<=$totalPages;$i++): ?>

<li class="page-item <?= ($i==$page)?'active':'' ?>">

<a class="page-link" 
href="?page=<?= $i ?>&search=<?= $search ?>">
<?= $i ?>
</a>

</li>

<?php endfor; ?>

</ul>
</nav>
<script src="../assets/js/student.js"></script>
</body>
</html>

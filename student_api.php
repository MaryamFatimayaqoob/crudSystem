<?php

require_once "../config/Database.php";
require_once "../models/Student.php";

$db = (new Database())->getConnection();
$student = new Student($db);

if(isset($_POST['delete'])){

    $student->delete($_POST['delete']);
    exit;
}

$search = $_GET['search'] ?? "";

if($search){
    $stmt = $student->search($search);
} else {
    $stmt = $student->readAll();
}

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
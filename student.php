<?php

class Student {

    private $conn;
    private $table = "students";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($data) {

    try {

        $sql = "INSERT INTO students 
        (roll_no, name, email, department, semester, cgpa, status)
        VALUES (:roll_no, :name, :email, :department, :semester, :cgpa, :status)";

        $stmt = $this->conn->prepare($sql);
        foreach($data as $key => $value){
    $data[$key] = htmlspecialchars(strip_tags($value));
}
        return $stmt->execute($data);

    } catch(PDOException $e) {

        if($e->getCode() == 23000) {
            echo "Email or Roll Number already exists!";
        } else {
            echo "Something went wrong!";
        }
    }
}

    public function readAll() {

        $sql = "SELECT * FROM students ORDER BY id DESC";
        return $this->conn->query($sql);
    }

    public function delete($id) {

        $sql = "DELETE FROM students WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function getById($id) {

        $sql = "SELECT * FROM students WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function search($keyword){

    $sql = "SELECT * FROM students 
            WHERE name LIKE :keyword 
            OR roll_no LIKE :keyword 
            OR email LIKE :keyword
            ORDER BY id DESC";

    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
        'keyword' => "%".$keyword."%"
    ]);

    return $stmt;
}
    public function readPaginated($limit, $offset){

    $sql = "SELECT * FROM students 
            ORDER BY id DESC 
            LIMIT :limit OFFSET :offset";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt;
}
    public function update($data) {

        $sql = "UPDATE students SET
                roll_no = :roll_no,
                name = :name,
                email = :email,
                department = :department,
                semester = :semester,
                cgpa = :cgpa,
                status = :status
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute($data);
    }
}

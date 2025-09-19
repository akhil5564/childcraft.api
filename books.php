<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

$host = "localhost";
$db_name = "childcraft_db";
$username = "childuser";
$password = "password123";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo json_encode(["status" => "error", "message" => "Connection error: " . $e->getMessage()]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['title'], $data['Subject'], $data['class'])) {
    $stmt = $conn->prepare("INSERT INTO books (title, Subject, class) VALUES (:title, :Subject, :class)");
    $stmt->bindParam(':title', $data['title']);
    $stmt->bindParam(':Subject', $data['Subject']);
    $stmt->bindParam(':class', $data['class']);

    if($stmt->execute()) {
        $id = $conn->lastInsertId();
        echo json_encode([
            "id" => (int)$id,
            "title" => $data['title'],
            "Subject" => $data['Subject'],
            "class" => $data['class'],
            "createdAt" => date("c"),
            "message" => "Book created successfully"
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to create book"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}
?>

<?php
// Db.php
$host = "localhost";
$db_name = "childcraft_db";
$username = "childuser";
$password = "123";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Connection error: " . $e->getMessage();
    exit();
}

// Get POST data
$data = json_decode(file_get_contents("php://input"), true);

if(isset($data['username']) && isset($data['password'])) {
    $username = $data['username'];
    $password = password_hash($data['password'], PASSWORD_DEFAULT);
    $role = $data['role'] ?? 'user';
    $status = isset($data['status']) ? (int)$data['status'] : 1;

    $stmt = $conn->prepare("INSERT INTO users (username, password, role, status) VALUES (:username, :password, :role, :status)");
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':role', $role);
    $stmt->bindParam(':status', $status);

    if($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User created"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to create user"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid input"]);
}
?>

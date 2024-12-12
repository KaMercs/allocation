<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "room_assignment";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roomType = $_POST['roomType'];
    $roomNumber = $_POST['roomNumber'];

    // Check if the room already exists
    $checkSql = "SELECT * FROM rooms WHERE room_type = ? AND room_number = ?";
    $stmt = $conn->prepare($checkSql);
    $stmt->bind_param("ss", $roomType, $roomNumber);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Room already exists
        echo json_encode(["success" => false, "message" => "Room type and number already exist"]);
    } else {
        // Insert the new room
        $insertSql = "INSERT INTO rooms (room_type, room_number) VALUES (?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ss", $roomType, $roomNumber);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Room added successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Error: " . $conn->error]);
        }
    }
    $stmt->close();
}

$conn->close();
?>

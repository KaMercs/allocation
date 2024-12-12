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

// Fetch all rooms
$sql = "SELECT id, room_type, room_number FROM rooms";
$result = $conn->query($sql);

$rooms = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

echo json_encode(["rooms" => $rooms]);

$conn->close();
?>

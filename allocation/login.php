<?php
session_start();

// Database connection
$host = "localhost";
$dbname = "room_assignment";
$username = "root";
$password = ""; // Replace with your MySQL password if necessary

$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize inputs
function sanitize_input($input, $conn) {
    return htmlspecialchars(strip_tags($conn->real_escape_string($input)));
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputUsername = sanitize_input($_POST['username'], $conn);
    $inputPassword = $_POST['password']; // Do not sanitize passwords; hash verification needs raw input

    // Query the database
    $stmt = $conn->prepare("SELECT username, password FROM admin_login WHERE username = ?");
    $stmt->bind_param("s", $inputUsername);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($inputPassword, $user['password'])) {
            // Login successful
            $_SESSION['admin'] = $inputUsername;
            // Redirect to login.html (the same page)
            header("Location: login.html"); 
            exit();
        }
    }

    // If login fails, stay on the login page and show an error
    echo "<script>alert('Invalid username or password');</script>";
}

$conn->close();
?>

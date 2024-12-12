<?php
include 'db_connection.php';

$room_id = $_POST['room_id'];
$day_of_week = $_POST['day_of_week'];
$start_time = $_POST['start_time'];
$end_time = $_POST['end_time'];
$subject_code = $_POST['subject_code'];
$subject = $_POST['subject'];
$professor_name = $_POST['professor_name'];
$course = $_POST['course'];
$year_level = intval($_POST['year_level']);
$block = $_POST['block'];
$color = $_POST['color'];

// Insert into room_schedule
$insert_query = "
    INSERT INTO room_schedule (
        room_id, day_of_week, start_time, end_time, 
        subject_code, subject, professor_name, 
        course, year_level, block, color
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
";

$stmt = $conn->prepare($insert_query);
$stmt->bind_param("isssssssiis", $room_id, $day_of_week, $start_time, $end_time,
    $subject_code, $subject, $professor_name, $course, $year_level, $block, $color);

if ($stmt->execute()) {
    // Update availability
    $update_query = "
        UPDATE room_availability
        SET is_available = 0
        WHERE room_id = ? AND day_of_week = ? AND start_time >= ? AND end_time <= ?
    ";
    $update_stmt = $conn->prepare($update_query);
    $update_stmt->bind_param("isss", $room_id, $day_of_week, $start_time, $end_time);
    $update_stmt->execute();

    echo json_encode(['success' => true, 'message' => 'Schedule saved successfully!']);
} else {
    echo json_encode(['success' => false, 'message' => 'Error saving schedule.']);
}
?>

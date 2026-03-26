<?php
// Include configuration
require_once '../config.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Get and sanitize input data
$student_id = clean_input($_POST['student_id']);
$name = clean_input($_POST['name']);
$email = clean_input($_POST['email']);
$dob = clean_input($_POST['dob']);
$contact = clean_input($_POST['contact']);
$address = clean_input($_POST['address']);
$profile_picture = clean_input($_POST['profile_picture'] ?? '');

// Validate required fields
if (empty($student_id) || empty($name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Student ID, Name, and Email are required']);
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit();
}

// Check if student exists
$check_sql = "SELECT id FROM students WHERE student_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $student_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Student not found']);
    exit();
}

// Check if email is already used by another student
$email_check_sql = "SELECT id FROM students WHERE email = ? AND student_id != ?";
$email_check_stmt = $conn->prepare($email_check_sql);
$email_check_stmt->bind_param("ss", $email, $student_id);
$email_check_stmt->execute();
$email_result = $email_check_stmt->get_result();

if ($email_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email is already used by another student']);
    exit();
}

// Parse name to first_name and last_name
$name_parts = explode(' ', $name, 2);
$first_name = $name_parts[0];
$last_name = isset($name_parts[1]) ? $name_parts[1] : '';

// Update student profile
$update_sql = "UPDATE students SET 
    first_name = ?, 
    last_name = ?, 
    email = ?, 
    phone = ?, 
    address = ?,
    profile_picture = ?,
    updated_at = CURRENT_TIMESTAMP
    WHERE student_id = ?";

$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("sssssss", $first_name, $last_name, $email, $contact, $address, $profile_picture, $student_id);

if ($update_stmt->execute()) {
    // Get updated student data
    $select_sql = "SELECT student_id, first_name, last_name, email, phone, address, profile_picture, course, semester FROM students WHERE student_id = ?";
    $select_stmt = $conn->prepare($select_sql);
    $select_stmt->bind_param("s", $student_id);
    $select_stmt->execute();
    $updated_data = $select_stmt->get_result()->fetch_assoc();

    echo json_encode([
        'success' => true, 
        'message' => 'Profile updated successfully',
        'student_data' => [
            'id' => $updated_data['id'],
            'student_id' => $updated_data['student_id'],
            'name' => $updated_data['first_name'] . ' ' . $updated_data['last_name'],
            'email' => $updated_data['email'],
            'phone' => $updated_data['phone'],
            'address' => $updated_data['address'],
            'profile_picture' => $updated_data['profile_picture'],
            'course' => $updated_data['course'],
            'semester' => $updated_data['semester']
        ]
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update profile: ' . $conn->error]);
}

// Close statements
$check_stmt->close();
$email_check_stmt->close();
$update_stmt->close();
$select_stmt->close();
$conn->close();
?>

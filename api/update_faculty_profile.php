<?php
// Include configuration
require_once '../config.php';

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Get and sanitize input data
$faculty_id = clean_input($_POST['faculty_id']);
$name = clean_input($_POST['name']);
$email = clean_input($_POST['email']);
$contact = clean_input($_POST['contact']);
$address = clean_input($_POST['address']);
$profile_picture = clean_input($_POST['profile_picture'] ?? '');
$department = clean_input($_POST['department']);
$designation = clean_input($_POST['designation']);

// Validate required fields
if (empty($faculty_id) || empty($name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Faculty ID, Name, and Email are required']);
    exit();
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format']);
    exit();
}

// Check if faculty exists
$check_sql = "SELECT id FROM faculty WHERE faculty_id = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $faculty_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Faculty not found']);
    exit();
}

// Check if email is already used by another faculty
$email_check_sql = "SELECT id FROM faculty WHERE email = ? AND faculty_id != ?";
$email_check_stmt = $conn->prepare($email_check_sql);
$email_check_stmt->bind_param("ss", $email, $faculty_id);
$email_check_stmt->execute();
$email_result = $email_check_stmt->get_result();

if ($email_result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Email is already used by another faculty']);
    exit();
}

// Parse name to first_name and last_name
$name_parts = explode(' ', $name, 2);
$first_name = $name_parts[0];
$last_name = isset($name_parts[1]) ? $name_parts[1] : '';

// Update faculty profile
$update_sql = "UPDATE faculty SET 
    first_name = ?, 
    last_name = ?, 
    email = ?, 
    phone = ?, 
    address = ?,
    profile_picture = ?,
    department = ?,
    designation = ?,
    updated_at = CURRENT_TIMESTAMP
    WHERE faculty_id = ?";

$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param("sssssssss", $first_name, $last_name, $email, $contact, $address, $profile_picture, $department, $designation, $faculty_id);

if ($update_stmt->execute()) {
    // Get updated faculty data
    $select_sql = "SELECT faculty_id, first_name, last_name, email, phone, address, profile_picture, department, designation, specialization FROM faculty WHERE faculty_id = ?";
    $select_stmt = $conn->prepare($select_sql);
    $select_stmt->bind_param("s", $faculty_id);
    $select_stmt->execute();
    $updated_data = $select_stmt->get_result()->fetch_assoc();

    echo json_encode([
        'success' => true, 
        'message' => 'Profile updated successfully',
        'faculty_data' => [
            'id' => $updated_data['id'],
            'faculty_id' => $updated_data['faculty_id'],
            'name' => $updated_data['first_name'] . ' ' . $updated_data['last_name'],
            'email' => $updated_data['email'],
            'phone' => $updated_data['phone'],
            'address' => $updated_data['address'],
            'profile_picture' => $updated_data['profile_picture'],
            'department' => $updated_data['department'],
            'designation' => $updated_data['designation'],
            'specialization' => $updated_data['specialization']
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

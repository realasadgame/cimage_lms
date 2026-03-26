<?php
require_once '../config.php';

header('Content-Type: application/json');

// Handle POST request for registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faculty_id = clean_input($_POST['faculty_id']);
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $phone = clean_input($_POST['phone']);
    $department = clean_input($_POST['department']);
    $designation = clean_input($_POST['designation']);
    $specialization = clean_input($_POST['specialization']);
    
    // Validate input
    $errors = [];
    
    if (empty($faculty_id)) $errors[] = 'Faculty ID is required';
    if (empty($first_name)) $errors[] = 'First name is required';
    if (empty($last_name)) $errors[] = 'Last name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (empty($password)) $errors[] = 'Password is required';
    if ($password !== $confirm_password) $errors[] = 'Passwords do not match';
    if (strlen($password) < 6) $errors[] = 'Password must be at least 6 characters';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format';
    
    // Check if faculty ID already exists
    $stmt = $conn->prepare("SELECT id FROM faculty WHERE faculty_id = ?");
    $stmt->bind_param("s", $faculty_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = 'Faculty ID already exists';
    }
    $stmt->close();
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM faculty WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        $errors[] = 'Email already exists';
    }
    $stmt->close();
    
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'message' => implode(', ', $errors)
        ]);
        exit();
    }
    
    // Hash password
    $hashed_password = hash_password($password);
    $joining_date = date('Y-m-d');
    
    // Insert new faculty
    $stmt = $conn->prepare("INSERT INTO faculty (faculty_id, first_name, last_name, email, password, phone, department, designation, specialization, joining_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $faculty_id, $first_name, $last_name, $email, $hashed_password, $phone, $department, $designation, $specialization, $joining_date);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Registration successful! Your account is pending approval.',
            'redirect' => 'faculty-login.html'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Registration failed: ' . $conn->error
        ]);
    }
    $stmt->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
}
?>

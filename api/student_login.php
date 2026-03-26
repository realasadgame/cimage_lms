<?php
require_once '../config.php';

header('Content-Type: application/json');

// Handle POST request for login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = clean_input($_POST['student_id']);
    $password = $_POST['password'];
    
    // Validate input
    if (empty($student_id) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Please enter both Student ID and password'
        ]);
        exit();
    }
    
    // Check student credentials
    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ? AND status = 'active'");
    $stmt->bind_param("s", $student_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $student = $result->fetch_assoc();
        
        if (verify_password($password, $student['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $student['id'];
            $_SESSION['user_type'] = 'student';
            $_SESSION['user_data'] = $student;
            
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => 'student.html',
                'student_data' => [
                    'id' => $student['id'],
                    'student_id' => $student['student_id'],
                    'name' => $student['first_name'] . ' ' . $student['last_name'],
                    'email' => $student['email'],
                    'phone' => $student['phone'],
                    'address' => $student['address'],
                    'profile_picture' => $student['profile_picture'],
                    'course' => $student['course'],
                    'semester' => $student['semester']
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid Student ID or password'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid Student ID or password'
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

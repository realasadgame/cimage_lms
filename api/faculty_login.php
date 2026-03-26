<?php
require_once '../config.php';

header('Content-Type: application/json');

// Handle POST request for login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $faculty_id = clean_input($_POST['faculty_id']);
    $password = $_POST['password'];
    
    // Validate input
    if (empty($faculty_id) || empty($password)) {
        echo json_encode([
            'success' => false,
            'message' => 'Please enter both Faculty ID and password'
        ]);
        exit();
    }
    
    // Check faculty credentials
    $stmt = $conn->prepare("SELECT * FROM faculty WHERE faculty_id = ? AND status = 'active'");
    $stmt->bind_param("s", $faculty_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $faculty = $result->fetch_assoc();
        
        if (verify_password($password, $faculty['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $faculty['id'];
            $_SESSION['user_type'] = 'faculty';
            $_SESSION['user_data'] = $faculty;
            
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'redirect' => 'faculty.html',
                'faculty_data' => [
                    'id' => $faculty['id'],
                    'faculty_id' => $faculty['faculty_id'],
                    'name' => $faculty['first_name'] . ' ' . $faculty['last_name'],
                    'email' => $faculty['email'],
                    'phone' => $faculty['phone'],
                    'address' => $faculty['address'],
                    'profile_picture' => $faculty['profile_picture'],
                    'department' => $faculty['department'],
                    'designation' => $faculty['designation'],
                    'specialization' => $faculty['specialization']
                ]
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid Faculty ID or password'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Invalid Faculty ID or password'
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

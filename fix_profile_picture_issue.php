<?php
// Fix profile picture issue - Update database and test
require_once 'config.php';

echo "<h2>Profile Picture Database Fix</h2>";

// Check if profile_picture column exists in students table
$check_student = $conn->query("SHOW COLUMNS FROM students LIKE 'profile_picture'");
if ($check_student->num_rows == 0) {
    echo "<p style='color: red;'>❌ profile_picture column missing in students table</p>";
    // Add the column
    $conn->query("ALTER TABLE students ADD COLUMN profile_picture TEXT AFTER address");
    echo "<p style='color: green;'>✅ Added profile_picture column to students table</p>";
} else {
    echo "<p style='color: green;'>✅ profile_picture column exists in students table</p>";
}

// Check if profile_picture column exists in faculty table
$check_faculty = $conn->query("SHOW COLUMNS FROM faculty LIKE 'profile_picture'");
if ($check_faculty->num_rows == 0) {
    echo "<p style='color: red;'>❌ profile_picture column missing in faculty table</p>";
    // Add the column
    $conn->query("ALTER TABLE faculty ADD COLUMN profile_picture TEXT AFTER address");
    echo "<p style='color: green;'>✅ Added profile_picture column to faculty table</p>";
} else {
    echo "<p style='color: green;'>✅ profile_picture column exists in faculty table</p>";
}

// Test student login with profile picture
echo "<h3>Testing Student Login</h3>";
$test_student = $conn->query("SELECT student_id, first_name, last_name, email, phone, address, profile_picture FROM students WHERE student_id = '11942' LIMIT 1");
if ($test_student && $test_student->num_rows > 0) {
    $student = $test_student->fetch_assoc();
    echo "<p><strong>Student Data:</strong></p>";
    echo "<ul>";
    echo "<li>Name: " . $student['first_name'] . " " . $student['last_name'] . "</li>";
    echo "<li>Email: " . $student['email'] . "</li>";
    echo "<li>Profile Picture: " . ($student['profile_picture'] ?: 'NULL') . "</li>";
    echo "</ul>";
}

// Test faculty login with profile picture
echo "<h3>Testing Faculty Login</h3>";
$test_faculty = $conn->query("SELECT faculty_id, first_name, last_name, email, phone, address, profile_picture FROM faculty WHERE faculty_id = '444' LIMIT 1");
if ($test_faculty && $test_faculty->num_rows > 0) {
    $faculty = $test_faculty->fetch_assoc();
    echo "<p><strong>Faculty Data:</strong></p>";
    echo "<ul>";
    echo "<li>Name: " . $faculty['first_name'] . " " . $faculty['last_name'] . "</li>";
    echo "<li>Email: " . $faculty['email'] . "</li>";
    echo "<li>Profile Picture: " . ($faculty['profile_picture'] ?: 'NULL') . "</li>";
    echo "</ul>";
}

echo "<p><a href='index.html'>Back to Home</a></p>";
$conn->close();
?>

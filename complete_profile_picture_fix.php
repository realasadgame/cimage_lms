<?php
// Complete Profile Picture Fix - Database Update and Verification
require_once 'config.php';

echo "<h1>🔧 Complete Profile Picture Fix</h1>";

// Step 1: Update database structure
echo "<h2>Step 1: Updating Database Structure</h2>";

// Add profile_picture column to students table if not exists
$student_check = $conn->query("SHOW COLUMNS FROM students LIKE 'profile_picture'");
if ($student_check->num_rows == 0) {
    echo "<p style='color: orange;'>⚠️ Adding profile_picture column to students table...</p>";
    $conn->query("ALTER TABLE students ADD COLUMN profile_picture TEXT AFTER address");
    echo "<p style='color: green;'>✅ Added profile_picture column to students table</p>";
} else {
    echo "<p style='color: green;'>✅ profile_picture column already exists in students table</p>";
}

// Add profile_picture column to faculty table if not exists
$faculty_check = $conn->query("SHOW COLUMNS FROM faculty LIKE 'profile_picture'");
if ($faculty_check->num_rows == 0) {
    echo "<p style='color: orange;'>⚠️ Adding profile_picture column to faculty table...</p>";
    $conn->query("ALTER TABLE faculty ADD COLUMN profile_picture TEXT AFTER address");
    echo "<p style='color: green;'>✅ Added profile_picture column to faculty table</p>";
} else {
    echo "<p style='color: green;'>✅ profile_picture column already exists in faculty table</p>";
}

// Step 2: Update sample data with profile pictures
echo "<h2>Step 2: Updating Sample Data</h2>";

// Update student sample data
$student_pic = 'https://cdn1.iconfinder.com/data/icons/language-studies-thick-outline/33/language-10-1024.png';
$conn->query("UPDATE students SET profile_picture = '$student_pic' WHERE student_id = '11942'");
echo "<p style='color: green;'>✅ Updated student 11942 with profile picture</p>";

// Update faculty sample data
$faculty_pic = 'https://cdn1.iconfinder.com/data/icons/language-studies-thick-outline/33/language-10-1024.png';
$conn->query("UPDATE faculty SET profile_picture = '$faculty_pic' WHERE faculty_id = '444'");
echo "<p style='color: green;'>✅ Updated faculty 444 with profile picture</p>";

// Step 3: Verify database updates
echo "<h2>Step 3: Verification</h2>";

// Verify student data
$student_verify = $conn->query("SELECT student_id, first_name, last_name, profile_picture FROM students WHERE student_id = '11942'");
$student_result = $student_verify->fetch_assoc();
echo "<h3>Student Data Verification:</h3>";
echo "<p><strong>ID:</strong> {$student_result['student_id']}</p>";
echo "<p><strong>Name:</strong> {$student_result['first_name']} {$student_result['last_name']}</p>";
echo "<p><strong>Profile Picture:</strong> <span style='color: " . ($student_result['profile_picture'] ? 'green' : 'red') . ";'>" . ($student_result['profile_picture'] ?: 'NULL') . "</span></p>";

// Verify faculty data
$faculty_verify = $conn->query("SELECT faculty_id, first_name, last_name, profile_picture FROM faculty WHERE faculty_id = '444'");
$faculty_result = $faculty_verify->fetch_assoc();
echo "<h3>Faculty Data Verification:</h3>";
echo "<p><strong>ID:</strong> {$faculty_result['faculty_id']}</p>";
echo "<p><strong>Name:</strong> {$faculty_result['first_name']} {$faculty_result['last_name']}</p>";
echo "<p><strong>Profile Picture:</strong> <span style='color: " . ($faculty_result['profile_picture'] ? 'green' : 'red') . ";'>" . ($faculty_result['profile_picture'] ?: 'NULL') . "</span></p>";

// Step 4: Update login APIs to include profile_picture
echo "<h2>Step 4: API Updates Required</h2>";
echo "<p style='color: orange;'>⚠️ Now you need to update the login APIs to include profile_picture in SELECT queries</p>";
echo "<p style='color: blue;'>📝 Update api/student_login.php and api/faculty_login.php</p>";
echo "<p style='font-family: monospace; background: #f5f5f5; padding: 10px; border-radius: 5px;'>";
echo "// Current student_login.php query:<br>";
echo "// \$stmt = \$conn->prepare(\"SELECT * FROM students WHERE student_id = ? AND status = 'active'\");<br>";
echo "// Change to:<br>";
echo "// \$stmt = \$conn->prepare(\"SELECT * FROM students WHERE student_id = ? AND status = 'active'\");<br>";
echo "// Then in the response, include profile_picture in the student_data array<br>";
echo "</p>";

echo "<h2>✅ Fix Complete!</h2>";
echo "<p><strong>What to do next:</strong></p>";
echo "<ol>";
echo "<li>1. Run this script: <a href='http://localhost/cimage_lms/complete_profile_picture_fix.php'>http://localhost/cimage_lms/complete_profile_picture_fix.php</a></li>";
echo "<li>2. Test login with student ID '11942' and password 'cimagepatna'</li>";
echo "<li>3. Check if profile picture appears in dashboard</li>";
echo "<li>4. Update profile picture and verify persistence</li>";
echo "</ol>";

echo "<p><a href='index.html'>← Back to Home</a></p>";
$conn->close();
?>

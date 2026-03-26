<?php
// Debug profile picture issue
require_once 'config.php';

echo "<h2>🔍 Profile Picture Debug Report</h2>";

// 1. Check database structure
echo "<h3>1. Database Structure Check</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
echo "<tr style='background: #f0f0f0;'><th>Table</th><th>Column</th><th>Status</th></tr>";

// Check students table
$student_columns = $conn->query("SHOW COLUMNS FROM students LIKE 'profile_picture'");
if ($student_columns->num_rows > 0) {
    echo "<tr style='background: #d4edda;'><td>students</td><td>profile_picture</td><td style='color: green;'>✅ EXISTS</td></tr>";
} else {
    echo "<tr style='background: #f8d7da;'><td>students</td><td>profile_picture</td><td style='color: red;'>❌ MISSING</td></tr>";
}

// Check faculty table
$faculty_columns = $conn->query("SHOW COLUMNS FROM faculty LIKE 'profile_picture'");
if ($faculty_columns->num_rows > 0) {
    echo "<tr style='background: #d4edda;'><td>faculty</td><td>profile_picture</td><td style='color: green;'>✅ EXISTS</td></tr>";
} else {
    echo "<tr style='background: #f8d7da;'><td>faculty</td><td>profile_picture</td><td style='color: red;'>❌ MISSING</td></tr>";
}

echo "</table>";

// 2. Check sample data
echo "<h3>2. Sample Data Check</h3>";
echo "<table border='1' style='border-collapse: collapse; width: 100%; margin-top: 20px;'>";
echo "<tr style='background: #f0f0f0;'><th>Table</th><th>ID</th><th>Name</th><th>Profile Picture</th></tr>";

// Check student data
$student_data = $conn->query("SELECT student_id, first_name, last_name, profile_picture FROM students WHERE student_id IN ('11942', '11943') LIMIT 2");
while ($row = $student_data->fetch_assoc()) {
    $pic_status = $row['profile_picture'] ? '✅' : '❌';
    $pic_value = $row['profile_picture'] ?: 'NULL';
    echo "<tr><td>students</td><td>{$row['student_id']}</td><td>{$row['first_name']} {$row['last_name']}</td><td style='color: " . ($row['profile_picture'] ? 'green' : 'red') . ";'>{$pic_status} {$pic_value}</td></tr>";
}

// Check faculty data
$faculty_data = $conn->query("SELECT faculty_id, first_name, last_name, profile_picture FROM faculty WHERE faculty_id IN ('444', '445') LIMIT 2");
while ($row = $faculty_data->fetch_assoc()) {
    $pic_status = $row['profile_picture'] ? '✅' : '❌';
    $pic_value = $row['profile_picture'] ?: 'NULL';
    echo "<tr><td>faculty</td><td>{$row['faculty_id']}</td><td>{$row['first_name']} {$row['last_name']}</td><td style='color: " . ($row['profile_picture'] ? 'green' : 'red') . ";'>{$pic_status} {$pic_value}</td></tr>";
}

echo "</table>";

// 3. Test API responses
echo "<h3>3. API Response Test</h3>";

// Test student login API
echo "<h4>Student Login API Test:</h4>";
$student_test = [
    'student_id' => '11942',
    'password' => 'cimagepatna'
];

$ch = curl_init('http://localhost/cimage_lms/api/student_login.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($student_test));
$response = curl_exec($ch);
echo "<pre>";
echo "Request: " . json_encode($student_test, JSON_PRETTY_PRINT) . "\n";
echo "Response: " . $response . "\n";
echo "</pre>";

// Test faculty login API
echo "<h4>Faculty Login API Test:</h4>";
$faculty_test = [
    'faculty_id' => '444',
    'password' => 'cimagepatna'
];

$ch = curl_init('http://localhost/cimage_lms/api/faculty_login.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($faculty_test));
$response = curl_exec($ch);
echo "<pre>";
echo "Request: " . json_encode($faculty_test, JSON_PRETTY_PRINT) . "\n";
echo "Response: " . $response . "\n";
echo "</pre>";

echo "<p><strong>Next Steps:</strong></p>";
echo "<ol>";
echo "<li>1. Run this debug script to check database structure</li>";
echo "<li>2. If columns are missing, run the SQL commands manually</li>";
echo "<li>3. Test login with student ID '11942' and password 'cimagepatna'</li>";
echo "<li>4. Check if profile_picture is included in API response</li>";
echo "<li>5. Update profile picture and test again</li>";
echo "</ol>";

echo "<p><a href='index.html'>← Back to Home</a></p>";
$conn->close();
?>

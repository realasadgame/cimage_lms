<?php
// Include configuration
require_once 'config.php';

echo "<h2>Testing Profile Update APIs</h2>";

// Test 1: Check if address columns exist
echo "<h3>1. Checking Database Columns:</h3>";

// Check students table
$student_columns = $conn->query("DESCRIBE students");
$has_student_address = false;
while ($row = $student_columns->fetch_assoc()) {
    echo "Students Table - " . $row['Field'] . " (" . $row['Type'] . ")<br>";
    if ($row['Field'] === 'address') $has_student_address = true;
}

if (!$has_student_address) {
    echo "<p style='color: red;'>❌ Address column missing in students table</p>";
    // Add address column
    $conn->query("ALTER TABLE students ADD COLUMN address TEXT AFTER phone");
    echo "<p style='color: green;'>✓ Added address column to students table</p>";
} else {
    echo "<p style='color: green;'>✓ Address column exists in students table</p>";
}

// Check faculty table
$faculty_columns = $conn->query("DESCRIBE faculty");
$has_faculty_address = false;
while ($row = $faculty_columns->fetch_assoc()) {
    echo "Faculty Table - " . $row['Field'] . " (" . $row['Type'] . ")<br>";
    if ($row['Field'] === 'address') $has_faculty_address = true;
}

if (!$has_faculty_address) {
    echo "<p style='color: red;'>❌ Address column missing in faculty table</p>";
    // Add address column
    $conn->query("ALTER TABLE faculty ADD COLUMN address TEXT AFTER phone");
    echo "<p style='color: green;'>✓ Added address column to faculty table</p>";
} else {
    echo "<p style='color: green;'>✓ Address column exists in faculty table</p>";
}

// Test 2: Check if student data exists
echo "<h3>2. Checking Sample Data:</h3>";
$student_check = $conn->query("SELECT student_id, first_name, last_name, email, phone, address FROM students WHERE student_id = '11942'");
if ($student_check->num_rows > 0) {
    $student = $student_check->fetch_assoc();
    echo "<p style='color: green;'>✓ Found student: " . $student['first_name'] . " " . $student['last_name'] . "</p>";
    echo "<p>Address: " . ($student['address'] ?? 'Not set') . "</p>";
} else {
    echo "<p style='color: orange;'>! Sample student not found. Creating one...</p>";
    $conn->query("INSERT INTO students (student_id, first_name, last_name, email, password, phone, course, semester, enrollment_date) VALUES ('11942', 'Rahul', 'Kumar', 'rahul.kumar@cimage.in', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 'BCA', 3, '2022-07-01') ON DUPLICATE KEY UPDATE first_name='Rahul', last_name='Kumar'");
}

$faculty_check = $conn->query("SELECT faculty_id, first_name, last_name, email, phone, address FROM faculty WHERE faculty_id = '444'");
if ($faculty_check->num_rows > 0) {
    $faculty = $faculty_check->fetch_assoc();
    echo "<p style='color: green;'>✓ Found faculty: " . $faculty['first_name'] . " " . $faculty['last_name'] . "</p>";
    echo "<p>Address: " . ($faculty['address'] ?? 'Not set') . "</p>";
} else {
    echo "<p style='color: orange;'>! Sample faculty not found. Creating one...</p>";
    $conn->query("INSERT INTO faculty (faculty_id, first_name, last_name, email, password, phone, department, designation, specialization, joining_date) VALUES ('444', 'John', 'Doe', 'john.doe@cimage.in', '\$2y\$10\$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '9876543210', 'IT', 'Assistant Professor', 'Web Development', '2021-01-15') ON DUPLICATE KEY UPDATE first_name='John', last_name='Doe'");
}

// Test 3: Test API endpoints
echo "<h3>3. Testing API Endpoints:</h3>";
echo "<p>Try these test cases:</p>";
echo "<ul>";
echo "<li><a href='api/update_student_profile.php' target='_blank'>Test Student Update API</a></li>";
echo "<li><a href='api/update_faculty_profile.php' target='_blank'>Test Faculty Update API</a></li>";
echo "</ul>";

echo "<h3>4. Manual Test Form:</h3>";
?>
<form action="api/update_student_profile.php" method="POST" target="_blank">
    <h4>Test Student Profile Update</h4>
    <input type="hidden" name="student_id" value="11942">
    <input type="text" name="name" placeholder="Name" value="Rahul Kumar" required><br><br>
    <input type="email" name="email" placeholder="Email" value="rahul.kumar@cimage.in" required><br><br>
    <input type="text" name="dob" placeholder="DOB" value="2000-01-01"><br><br>
    <input type="text" name="contact" placeholder="Contact" value="9876543210"><br><br>
    <input type="text" name="address" placeholder="Address" value="Test Address, Patna"><br><br>
    <button type="submit">Test Student Update</button>
</form>

<form action="api/update_faculty_profile.php" method="POST" target="_blank">
    <h4>Test Faculty Profile Update</h4>
    <input type="hidden" name="faculty_id" value="444">
    <input type="text" name="name" placeholder="Name" value="John Doe" required><br><br>
    <input type="email" name="email" placeholder="Email" value="john.doe@cimage.in" required><br><br>
    <input type="text" name="contact" placeholder="Contact" value="9876543210"><br><br>
    <input type="text" name="department" placeholder="Department" value="IT"><br><br>
    <input type="text" name="designation" placeholder="Designation" value="Assistant Professor"><br><br>
    <button type="submit">Test Faculty Update</button>
</form>

<?php
$conn->close();
echo "<p><a href='index.html'>Back to Home</a></p>";
?>

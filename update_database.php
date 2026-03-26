<?php
// Include configuration
require_once 'config.php';

echo "<h2>Updating Database Structure...</h2>";

// Add address column to students table if not exists
$add_student_address = "ALTER TABLE students ADD COLUMN address TEXT AFTER phone";
if ($conn->query($add_student_address)) {
    echo "<p style='color: green;'>✓ Address column added to students table</p>";
} else {
    echo "<p style='color: orange;'>! Address column may already exist in students table: " . $conn->error . "</p>";
}

// Add address column to faculty table if not exists
$add_faculty_address = "ALTER TABLE faculty ADD COLUMN address TEXT AFTER phone";
if ($conn->query($add_faculty_address)) {
    echo "<p style='color: green;'>✓ Address column added to faculty table</p>";
} else {
    echo "<p style='color: orange;'>! Address column may already exist in faculty table: " . $conn->error . "</p>";
}

// Check if columns exist
echo "<h3>Checking Students Table Structure:</h3>";
$student_columns = $conn->query("DESCRIBE students");
while ($row = $student_columns->fetch_assoc()) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")<br>";
}

echo "<h3>Checking Faculty Table Structure:</h3>";
$faculty_columns = $conn->query("DESCRIBE faculty");
while ($row = $faculty_columns->fetch_assoc()) {
    echo "- " . $row['Field'] . " (" . $row['Type'] . ")<br>";
}

$conn->close();
echo "<p style='color: blue; font-weight: bold;'>Database update completed!</p>";
echo "<p><a href='index.html'>Go to Home</a></p>";
?>

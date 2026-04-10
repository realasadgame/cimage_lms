<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('../admin-login.html');
}

// Handle download requests
if (isset($_GET['download']) && isset($_GET['format'])) {
    $format = $_GET['download'];
    $type = $_GET['format']; // students, faculty, or all
    
    // Set headers for download
    if ($format == 'excel') {
        // For Excel, we'll create a simple HTML table that Excel can open
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="report_' . $type . '_' . date('Y-m-d') . '.xls"');
        
        echo '<table border="1">';
        
        if ($type == 'students') {
            echo '<tr><th>Student ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Course</th><th>Semester</th><th>Status</th><th>Enrollment Date</th></tr>';
            
            $stmt = $conn->query("SELECT * FROM students ORDER BY student_id");
            while ($row = $stmt->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['student_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                echo '<td>' . htmlspecialchars($row['address']) . '</td>';
                echo '<td>' . htmlspecialchars($row['course']) . '</td>';
                echo '<td>' . htmlspecialchars($row['semester']) . '</td>';
                echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                echo '<td>' . htmlspecialchars($row['enrollment_date']) . '</td>';
                echo '</tr>';
            }
        } elseif ($type == 'faculty') {
            echo '<tr><th>Faculty ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Department</th><th>Designation</th><th>Specialization</th><th>Status</th><th>Joining Date</th></tr>';
            
            $stmt = $conn->query("SELECT * FROM faculty ORDER BY faculty_id");
            while ($row = $stmt->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['faculty_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                echo '<td>' . htmlspecialchars($row['address']) . '</td>';
                echo '<td>' . htmlspecialchars($row['department']) . '</td>';
                echo '<td>' . htmlspecialchars($row['designation']) . '</td>';
                echo '<td>' . htmlspecialchars($row['specialization']) . '</td>';
                echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                echo '<td>' . htmlspecialchars($row['joining_date']) . '</td>';
                echo '</tr>';
            }
        } elseif ($type == 'all') {
            echo '<tr><th>Type</th><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Address</th><th>Department/Course</th><th>Designation/Semester</th><th>Specialization</th><th>Status</th><th>Date</th></tr>';
            
            // Students
            $stmt = $conn->query("SELECT * FROM students ORDER BY student_id");
            while ($row = $stmt->fetch_assoc()) {
                echo '<tr>';
                echo '<td>STUDENT</td>';
                echo '<td>' . htmlspecialchars($row['student_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                echo '<td>' . htmlspecialchars($row['address']) . '</td>';
                echo '<td>' . htmlspecialchars($row['course']) . '</td>';
                echo '<td>' . htmlspecialchars($row['semester']) . '</td>';
                echo '<td></td>';
                echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                echo '<td>' . htmlspecialchars($row['enrollment_date']) . '</td>';
                echo '</tr>';
            }
            
            // Faculty
            $stmt = $conn->query("SELECT * FROM faculty ORDER BY faculty_id");
            while ($row = $stmt->fetch_assoc()) {
                echo '<tr>';
                echo '<td>FACULTY</td>';
                echo '<td>' . htmlspecialchars($row['faculty_id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['email']) . '</td>';
                echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
                echo '<td>' . htmlspecialchars($row['address']) . '</td>';
                echo '<td>' . htmlspecialchars($row['department']) . '</td>';
                echo '<td>' . htmlspecialchars($row['designation']) . '</td>';
                echo '<td>' . htmlspecialchars($row['specialization']) . '</td>';
                echo '<td>' . htmlspecialchars($row['status']) . '</td>';
                echo '<td>' . htmlspecialchars($row['joining_date']) . '</td>';
                echo '</tr>';
            }
        }
        
        echo '</table>';
        exit();
    }
}

// Get statistics for dashboard
$total_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$total_faculty = $conn->query("SELECT COUNT(*) as count FROM faculty")->fetch_assoc()['count'];
$active_students = $conn->query("SELECT COUNT(*) as count FROM students WHERE status = 'active'")->fetch_assoc()['count'];
$active_faculty = $conn->query("SELECT COUNT(*) as count FROM faculty WHERE status = 'active'")->fetch_assoc()['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - CIMAGE LMS</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f6fa;
            color: #2c3e50;
        }

        .admin-container {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 2rem 0;
        }

        .sidebar-header {
            text-align: center;
            padding: 0 1rem 2rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-header h2 {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .menu-item {
            display: block;
            padding: 1rem 1.5rem;
            color: white;
            text-decoration: none;
            transition: background 0.3s ease;
        }

        .menu-item:hover, .menu-item.active {
            background: rgba(255,255,255,0.1);
        }

        .menu-item i {
            margin-right: 0.5rem;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .header h1 {
            font-size: 2rem;
            color: #2c3e50;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: #3498db;
            color: white;
        }

        .btn-primary:hover {
            background: #2980b9;
        }

        .btn-success {
            background: #27ae60;
            color: white;
        }

        .btn-success:hover {
            background: #229954;
        }

        .btn-warning {
            background: #f39c12;
            color: white;
        }

        .btn-warning:hover {
            background: #e67e22;
        }

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-card h3 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
            color: #3498db;
        }

        .stat-card p {
            color: #7f8c8d;
            font-weight: 500;
        }

        .reports-section {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }

        .reports-section h2 {
            margin-bottom: 1.5rem;
            color: #2c3e50;
        }

        .report-cards {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .report-card {
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }

        .report-card:hover {
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        .report-card h3 {
            margin-bottom: 1rem;
            color: #2c3e50;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .report-card p {
            color: #7f8c8d;
            margin-bottom: 1rem;
        }

        .download-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .download-buttons .btn {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
        }

        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .report-cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-university"></i> CIMAGE LMS</h2>
                <p>Admin Panel</p>
            </div>
            <nav class="sidebar-menu">
                <a href="index.php" class="menu-item">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="students.php" class="menu-item">
                    <i class="fas fa-user-graduate"></i> Students
                </a>
                <a href="faculty.php" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i> Faculty
                </a>
                <a href="reports.php" class="menu-item active">
                    <i class="fas fa-file-download"></i> Reports
                </a>
                <a href="logout.php" class="menu-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Reports & Downloads</h1>
                <div class="user-info">
                    <span>Welcome, <?php echo htmlspecialchars($_SESSION['user_data']['full_name']); ?></span>
                    <a href="logout.php" class="btn btn-danger">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?php echo $total_students; ?></h3>
                    <p>Total Students</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $total_faculty; ?></h3>
                    <p>Total Faculty</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $active_students; ?></h3>
                    <p>Active Students</p>
                </div>
                <div class="stat-card">
                    <h3><?php echo $active_faculty; ?></h3>
                    <p>Active Faculty</p>
                </div>
            </div>

            <div class="reports-section">
                <h2><i class="fas fa-download"></i> Download Reports</h2>
                
                <div class="report-cards">
                    <!-- Students Report -->
                    <div class="report-card">
                        <h3><i class="fas fa-user-graduate"></i> Students Report</h3>
                        <p>Download complete student information including personal details, course, and enrollment data.</p>
                        <div class="download-buttons">
                            <a href="?download=excel&format=students" class="btn btn-primary">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                        </div>
                    </div>

                    <!-- Faculty Report -->
                    <div class="report-card">
                        <h3><i class="fas fa-chalkboard-teacher"></i> Faculty Report</h3>
                        <p>Download complete faculty information including personal details, department, and employment data.</p>
                        <div class="download-buttons">
                            <a href="?download=excel&format=faculty" class="btn btn-primary">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                        </div>
                    </div>

                    <!-- Combined Report -->
                    <div class="report-card">
                        <h3><i class="fas fa-users"></i> Combined Report</h3>
                        <p>Download all students and faculty data in a single comprehensive report.</p>
                        <div class="download-buttons">
                            <a href="?download=excel&format=all" class="btn btn-primary">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

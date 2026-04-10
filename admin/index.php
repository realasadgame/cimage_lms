<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('../admin-login.html');
}

// Get statistics
$total_students = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$total_faculty = $conn->query("SELECT COUNT(*) as count FROM faculty")->fetch_assoc()['count'];
$active_students = $conn->query("SELECT COUNT(*) as count FROM students WHERE status = 'active'")->fetch_assoc()['count'];
$active_faculty = $conn->query("SELECT COUNT(*) as count FROM faculty WHERE status = 'active'")->fetch_assoc()['count'];

// Get recent students
$recent_students = $conn->query("SELECT * FROM students ORDER BY created_at DESC LIMIT 5");
// Get recent faculty
$recent_faculty = $conn->query("SELECT * FROM faculty ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - CIMAGE LMS</title>
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
            border-radius: 6px;
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

        .btn-danger {
            background: #e74c3c;
            color: white;
        }

        .btn-danger:hover {
            background: #c0392b;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .card-header {
            background: #3498db;
            color: white;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        .card-body {
            padding: 1.5rem;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
        }

        .table th {
            background: #f8f9fa;
            font-weight: 600;
        }

        .badge {
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .badge-success {
            background: #d4edda;
            color: #155724;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .content-grid {
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
                <a href="index.php" class="menu-item active">
                    <i class="fas fa-dashboard"></i> Dashboard
                </a>
                <a href="students.php" class="menu-item">
                    <i class="fas fa-user-graduate"></i> Students
                </a>
                <a href="faculty.php" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i> Faculty
                </a>
                <a href="reports.php" class="menu-item">
                    <i class="fas fa-file-download"></i> Reports
                </a>
                <a href="logout.php" class="menu-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Admin Dashboard</h1>
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

            <div class="content-grid">
                <div class="card">
                    <div class="card-header">
                        Recent Students
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Course</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($student = $recent_students->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                    <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['course']); ?></td>
                                    <td><span class="badge badge-success"><?php echo htmlspecialchars($student['status']); ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        Recent Faculty
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Faculty ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($faculty = $recent_faculty->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($faculty['faculty_id']); ?></td>
                                    <td><?php echo htmlspecialchars($faculty['first_name'] . ' ' . $faculty['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($faculty['department']); ?></td>
                                    <td><span class="badge badge-success"><?php echo htmlspecialchars($faculty['status']); ?></span></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

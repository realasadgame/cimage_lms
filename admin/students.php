<?php
require_once '../config.php';

// Check if admin is logged in
if (!is_admin()) {
    redirect('login.php');
}

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    
    switch ($action) {
        case 'add':
            $student_id = clean_input($_POST['student_id']);
            $first_name = clean_input($_POST['first_name']);
            $last_name = clean_input($_POST['last_name']);
            $email = clean_input($_POST['email']);
            $password = hash_password($_POST['password']);
            $phone = clean_input($_POST['phone']);
            $course = clean_input($_POST['course']);
            $semester = (int)$_POST['semester'];
            $enrollment_date = $_POST['enrollment_date'];
            $address = clean_input($_POST['address']);
            
            $stmt = $conn->prepare("INSERT INTO students (student_id, first_name, last_name, email, password, phone, address, course, semester, enrollment_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssssi", $student_id, $first_name, $last_name, $email, $password, $phone, $address, $course, $semester, $enrollment_date);
            
            if ($stmt->execute()) {
                $success = "Student added successfully!";
            } else {
                $error = "Error adding student: " . $conn->error;
            }
            $stmt->close();
            break;
            
        case 'edit':
            $id = (int)$_POST['id'];
            $first_name = clean_input($_POST['first_name']);
            $last_name = clean_input($_POST['last_name']);
            $email = clean_input($_POST['email']);
            $phone = clean_input($_POST['phone']);
            $address = clean_input($_POST['address']);
            $course = clean_input($_POST['course']);
            $semester = (int)$_POST['semester'];
            $status = clean_input($_POST['status']);
            
            $stmt = $conn->prepare("UPDATE students SET first_name = ?, last_name = ?, email = ?, phone = ?, address = ?, course = ?, semester = ?, status = ? WHERE id = ?");
            $stmt->bind_param("sssssssi", $first_name, $last_name, $email, $phone, $address, $course, $semester, $status, $id);
            
            if ($stmt->execute()) {
                $success = "Student updated successfully!";
            } else {
                $error = "Error updating student: " . $conn->error;
            }
            $stmt->close();
            break;
            
        case 'delete':
            $id = (int)$_POST['id'];
            $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
            $stmt->bind_param("i", $id);
            
            if ($stmt->execute()) {
                $success = "Student deleted successfully!";
            } else {
                $error = "Error deleting student: " . $conn->error;
            }
            $stmt->close();
            break;
    }
}

// Get all students
$students = $conn->query("SELECT * FROM students ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Students - CIMAGE LMS</title>
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

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }

        .alert {
            padding: 0.75rem;
            border-radius: 6px;
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .alert-success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background: #fee;
            border: 1px solid #fcc;
            color: #c33;
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .badge-warning {
            background: #ffc107;
            color: #212529;
            font-weight: 600;
        }

        .badge-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .actions {
            display: flex;
            gap: 0.5rem;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 500px;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-header h3 {
            font-size: 1.5rem;
            color: #2c3e50;
        }

        .close {
            font-size: 1.5rem;
            cursor: pointer;
            color: #7f8c8d;
        }

        .close:hover {
            color: #2c3e50;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }

        .form-group input, .form-group select {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.9rem;
        }

        .form-group input:focus, .form-group select:focus {
            outline: none;
            border-color: #3498db;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 768px) {
            .admin-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .form-row {
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
                    <i class="fas fa-dashboard"></i> Dashboard
                </a>
                <a href="students.php" class="menu-item active">
                    <i class="fas fa-user-graduate"></i> Students
                </a>
                <a href="faculty.php" class="menu-item">
                    <i class="fas fa-chalkboard-teacher"></i> Faculty
                </a>
                <a href="logout.php" class="menu-item">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>

        <div class="main-content">
            <div class="header">
                <h1>Manage Students</h1>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="fas fa-plus"></i> Add Student
                </button>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <span>All Students</span>
                    <span>Total: <?php echo $students->num_rows; ?></span>
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Student ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Course</th>
                                <th>Semester</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($student = $students->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                                <td><?php echo htmlspecialchars($student['first_name'] . ' ' . $student['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                <td><?php echo htmlspecialchars($student['course']); ?></td>
                                <td><?php echo htmlspecialchars($student['semester']); ?></td>
                                <td>
                                    <span class="badge <?php 
                                        echo $student['status'] == 'active' ? 'badge-success' : 
                                             ($student['status'] == 'inactive' ? 'badge-warning' : 'badge-danger'); 
                                    ?>">
                                        <?php echo htmlspecialchars($student['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <button class="btn btn-warning btn-sm" onclick="openEditModal(<?php echo $student['id']; ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm" onclick="deleteStudent(<?php echo $student['id']; ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Student Modal -->
    <div id="addModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Add New Student</h3>
                <span class="close" onclick="closeModal('addModal')">&times;</span>
            </div>
            <form method="POST" onsubmit="return validateStudentForm(this)">
                <input type="hidden" name="action" value="add">
                <div class="form-row">
                    <div class="form-group">
                        <label>Student ID</label>
                        <input type="text" name="student_id" required pattern="[0-9]+" title="Student ID must contain only numbers">
                        <small style="color: #666; font-size: 0.8rem;">Only numbers allowed</small>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required pattern="[a-zA-Z0-9._%+-]+@cimage\.in$" title="Email must end with @cimage.in">
                        <small style="color: #666; font-size: 0.8rem;">Must end with @cimage.in</small>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" required pattern="[A-Za-z\s]+" title="First name must contain only letters" placeholder="Enter first name">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" required pattern="[A-Za-z\s]+" title="Last name must contain only letters" placeholder="Enter last name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="phone" required pattern="[0-9]{10}" title="Phone number must be exactly 10 digits">
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required minlength="6">
                        <small style="color: #666; font-size: 0.8rem;">Minimum 6 characters</small>
                    </div>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" placeholder="Enter complete address" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Course</label>
                        <select name="course" required>
                            <option value="">Select Course</option>
                            <option value="BCA">BCA</option>
                            <option value="BBA">BBA</option>
                            <option value="B.Sc-IT">B.Sc-IT</option>
                            <option value="BBM">BBM</option>
                            <option value="B.Com">B.Com</option>
                            <option value="PGDM">PGDM</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <input type="number" name="semester" min="1" max="8" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Enrollment Date</label>
                    <input type="date" name="enrollment_date" required>
                </div>
                <button type="submit" class="btn btn-success">Add Student</button>
            </form>
        </div>
    </div>

    <!-- Edit Student Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Edit Student</h3>
                <span class="close" onclick="closeModal('editModal')">&times;</span>
            </div>
            <form method="POST" id="editForm" onsubmit="return validateStudentForm(this)">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" id="editId">
                <div class="form-row">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" id="editFirstName" required pattern="[A-Za-z\s]+" title="First name must contain only letters" placeholder="Enter first name">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" id="editLastName" required pattern="[A-Za-z\s]+" title="Last name must contain only letters" placeholder="Enter last name">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="editEmail" required pattern="[a-zA-Z0-9._%+-]+@cimage\.in$" title="Email must end with @cimage.in">
                        <small style="color: #666; font-size: 0.8rem;">Must end with @cimage.in</small>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="tel" name="phone" id="editPhone" pattern="[0-9]{10}" title="Phone number must be exactly 10 digits">
                    </div>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" id="editAddress" placeholder="Enter complete address">
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label>Course</label>
                        <select name="course" id="editCourse" required>
                            <option value="">Select Course</option>
                            <option value="BCA">BCA</option>
                            <option value="BBA">BBA</option>
                            <option value="B.Sc-IT">B.Sc-IT</option>
                            <option value="BBM">BBM</option>
                            <option value="B.Com">B.Com</option>
                            <option value="PGDM">PGDM</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Semester</label>
                        <input type="number" name="semester" id="editSemester" min="1" max="8" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" id="editStatus" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="graduated">Graduated</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Update Student</button>
            </form>
        </div>
    </div>

    <script>
        function validateStudentForm(form) {
            // Student ID validation (numbers only)
            const studentId = form.querySelector('[name="student_id"]');
            if (studentId && !/^[0-9]+$/.test(studentId.value)) {
                alert('Student ID must contain only numbers');
                studentId.focus();
                return false;
            }
            
            // Email validation (must end with @cimage.in)
            const email = form.querySelector('[name="email"]');
            if (email && !/@cimage\.in$/.test(email.value)) {
                alert('Email must end with @cimage.in');
                email.focus();
                return false;
            }
            
            // First Name validation (alphabets only)
            const firstName = form.querySelector('[name="first_name"]');
            if (firstName && !/^[A-Za-z\s]+$/.test(firstName.value)) {
                alert('First Name must contain only letters');
                firstName.focus();
                return false;
            }
            
            // Last Name validation (alphabets only)
            const lastName = form.querySelector('[name="last_name"]');
            if (lastName && !/^[A-Za-z\s]+$/.test(lastName.value)) {
                alert('Last Name must contain only letters');
                lastName.focus();
                return false;
            }
            
            // Phone validation (exactly 10 digits)
            const phone = form.querySelector('[name="phone"]');
            if (phone && !/^[0-9]{10}$/.test(phone.value)) {
                alert('Phone number must be exactly 10 digits');
                phone.focus();
                return false;
            }
            
            return true;
        }
        
        function openAddModal() {
            document.getElementById('addModal').style.display = 'block';
        }

        function openEditModal(id) {
            // Fetch student data and populate form
            fetch('get_student.php?id=' + id)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('editId').value = data.id;
                    document.getElementById('editFirstName').value = data.first_name;
                    document.getElementById('editLastName').value = data.last_name;
                    document.getElementById('editEmail').value = data.email;
                    document.getElementById('editPhone').value = data.phone;
                    document.getElementById('editCourse').value = data.course;
                    document.getElementById('editSemester').value = data.semester;
                    document.getElementById('editStatus').value = data.status;
                    document.getElementById('editModal').style.display = 'block';
                });
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function deleteStudent(id) {
            if (confirm('Are you sure you want to delete this student?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.innerHTML = `
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="${id}">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        }
    </script>
</body>
</html>

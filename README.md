# CIMAGE College LMS - PHP Backend System

A complete Learning Management System (LMS) for CIMAGE College with PHP/MySQL backend administration panel.

## Features

### 🔐 Authentication System
- **Student Login/Registration** - Secure student authentication with database storage
- **Faculty Login/Registration** - Faculty member authentication and management
- **Admin Panel** - Secure admin access at `/admin` route for system management

### 👥 Admin Panel Features
- **Dashboard** - Overview of students and faculty statistics
- **Student Management** - CRUD operations for student accounts
- **Faculty Management** - CRUD operations for faculty accounts
- **Real-time Updates** - Add, edit, delete users with instant database updates

### 🎨 Frontend Integration
- Modern, responsive design matching existing CIMAGE LMS theme
- Seamless integration with current HTML frontend
- AJAX-powered login forms for better user experience

## Database Structure

### Tables Created:
- `admins` - Administrator accounts
- `students` - Student information and credentials
- `faculty` - Faculty member information and credentials

### Default Credentials:
```
Admin Login:
Username: admin
Password: password (change after first login)

Student Login:
Student ID: 11942
Password: cimagepatna

Faculty Login:
Faculty ID: 444
Password: cimagepatna
```

## Installation Guide

### 1. Database Setup
1. Open XAMPP control panel and start Apache and MySQL
2. Open phpMyAdmin (http://localhost/phpmyadmin)
3. Import the `database.sql` file or run the SQL commands manually

### 2. File Structure
The system creates the following structure:
```
cimage_lms/
├── admin/                   # Admin panel (hidden from main site)
│   ├── index.php           # Admin dashboard
│   ├── login.php           # Admin login page
│   ├── students.php        # Student management
│   ├── faculty.php         # Faculty management
│   ├── get_student.php     # API for student data
│   ├── get_faculty.php     # API for faculty data
│   └── logout.php          # Admin logout
├── api/                    # Backend APIs
│   ├── student_login.php   # Student login API
│   ├── faculty_login.php   # Faculty login API
│   ├── student_register.php # Student registration API
│   └── faculty_register.php # Faculty registration API
├── config.php              # Database configuration and helper functions
├── database.sql            # Database structure and sample data
└── README.md               # This file
```

### 3. Configuration
1. Open `config.php` and update database settings if needed:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'cimage_lms');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```

### 4. Access Points
- **Main Site**: `http://localhost/cimage_lms/`
- **Admin Panel**: `http://localhost/cimage_lms/admin/`
- **Admin Login**: `http://localhost/cimage_lms/admin-login.html`

## API Endpoints

### Student Authentication
- **POST** `/api/student_login.php` - Student login
  ```json
  {
    "student_id": "11942",
    "password": "cimagepatna"
  }
  ```

- **POST** `/api/student_register.php` - Student registration

### Faculty Authentication
- **POST** `/api/faculty_login.php` - Faculty login
- **POST** `/api/faculty_register.php` - Faculty registration

### Admin APIs
- **GET** `/admin/get_student.php?id={id}` - Get student data
- **GET** `/admin/get_faculty.php?id={id}` - Get faculty data

## Security Features

### 🔒 Security Measures
- **Password Hashing** - Uses PHP's `password_hash()` with BCRYPT
- **SQL Injection Prevention** - Prepared statements for all database queries
- **XSS Protection** - Input sanitization and output escaping
- **Session Security** - Secure session configuration with HTTP-only cookies
- **CSRF Protection** - Form tokens for sensitive operations

### 🛡️ Input Validation
- Email validation for registration
- Password strength requirements (minimum 6 characters)
- Unique ID validation for student and faculty IDs
- Server-side validation for all form inputs

## Frontend Integration

The system seamlessly integrates with existing frontend:

### Updated Files:
- `studentlogin.html` - Now uses PHP backend API
- `faculty-login.html` - Now uses PHP backend API
- `admin-login.html` - Redirects to admin panel

### AJAX Integration:
- Modern fetch API for asynchronous requests
- Loading states and error handling
- LocalStorage for user session data
- Smooth transitions and user feedback

## Admin Panel Features

### 📊 Dashboard
- Total students and faculty counts
- Active users statistics
- Recent registrations overview
- Quick access to management tools

### 👥 User Management
- **Add Users** - Create new student/faculty accounts
- **Edit Users** - Update existing user information
- **Delete Users** - Remove accounts from system
- **Status Management** - Activate/deactivate user accounts
- **Search & Filter** - Find users quickly

### 🎨 User Interface
- Modern, responsive design
- Modal-based forms for better UX
- Real-time data updates
- Mobile-friendly interface

## Development Notes

### 📝 Code Structure
- **MVC Pattern** - Separation of concerns
- **Helper Functions** - Reusable utility functions in `config.php`
- **Error Handling** - Comprehensive error reporting
- **Logging** - Error logging for debugging

### 🔧 Customization
- Easy to modify database schema
- Configurable site URLs and settings
- Customizable CSS and styling
- Extensible API structure

## Troubleshooting

### Common Issues:

1. **Database Connection Error**
   - Check XAMPP services are running
   - Verify database credentials in `config.php`
   - Ensure database `cimage_lms` exists

2. **Login Not Working**
   - Check if sample data was imported
   - Verify passwords are hashed correctly
   - Check browser console for JavaScript errors

3. **Admin Panel Access Denied**
   - Ensure you're logging in with correct admin credentials
   - Check session configuration in `config.php`
   - Clear browser cookies and try again

4. **Registration Errors**
   - Check for duplicate student/faculty IDs
   - Verify email format is valid
   - Ensure all required fields are filled

### Debug Mode:
Set `error_reporting` and `display_errors` in `config.php` for development:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## Future Enhancements

### 🚀 Planned Features
- **Password Reset** - Email-based password recovery
- **Profile Management** - User self-service profile updates
- **Course Management** - Add course administration
- **Attendance System** - Track student attendance
- **Grade Management** - Academic performance tracking
- **File Upload** - Document and assignment management
- **Notification System** - Email and SMS notifications
- **Reporting** - Advanced analytics and reports

### 🔧 Technical Improvements
- **REST API** - Full RESTful API implementation
- **Caching** - Performance optimization
- **Backup System** - Automated database backups
- **Multi-language Support** - Internationalization
- **Mobile App API** - Native mobile app support

## Support

For technical support or questions:
- Check the troubleshooting section above
- Review the code comments for implementation details
- Test with the provided sample credentials

---

**Note**: This system is designed for educational purposes and should be further hardened for production use, including:
- HTTPS implementation
- Additional security headers
- Regular security audits
- Backup and recovery procedures

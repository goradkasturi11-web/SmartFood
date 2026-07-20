# Smart Food Redistribution Platform

A web-based application that connects food donors (restaurants, hotels, supermarkets, event organizers, and individuals) with NGOs, charitable organizations, and food banks to reduce food wastage and ensure efficient redistribution of surplus food.

## Features

### For Donors
- Register and manage donor account
- Post food donations with details (name, quantity, type, expiry, location, images)
- Edit and delete donations
- View and manage incoming NGO requests
- Accept or reject donation requests
- Track donation status

### For NGOs
- Register and manage NGO account (requires admin verification)
- Browse and search available food donations
- Filter by location, quantity, food type, and availability
- Request donations
- Cancel pending requests
- Mark food as collected
- Submit feedback on completed donations
- Receive notifications

### For Administrators
- Verify or reject NGO registrations
- View and manage all user accounts
- Suspend user accounts
- Generate reports on donations and requests
- View donation and request history
- Platform-wide statistics

## Technology Stack

- **Backend**: PHP (Core, MVC architecture)
- **Database**: MySQL (via XAMPP)
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Authentication**: PHP sessions with password hashing

## Requirements

- XAMPP (or equivalent PHP/MySQL server)
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Modern web browser

## Installation

### 1. Setup XAMPP

1. Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Start Apache and MySQL services from XAMPP Control Panel

### 2. Clone/Download Project

Place the project folder in `C:\xampp\htdocs\SmartFood\`

### 3. Database Setup

1. Open phpMyAdmin: http://localhost/phpmyadmin
2. Create a new database named `smartfood`
3. Import the SQL file:
   - Click on the `smartfood` database
   - Go to "Import" tab
   - Select `database/init.sql` from the project folder
   - Click "Go"

Alternatively, you can run the SQL commands manually from `database/init.sql`.

### 4. Configure Database Connection

The database connection is already configured in `config/database.php`:

```php
private $host = 'localhost';
private $db_name = 'smartfood';
private $username = 'root';
private $password = '';
```

If your MySQL credentials are different, update these values.

### 5. Set File Permissions

Ensure the `uploads` directory has write permissions for file uploads.

### 6. Access the Application

Open your browser and navigate to:
```
http://localhost/SmartFood/public/
```

## Default Admin Account

A default admin account is created during database initialization:

- **Email**: admin@smartfood.com
- **Password**: admin123

**Important**: Change the default admin password after first login.

## Project Structure

```
SmartFood/
├── assets/
│   ├── css/
│   │   └── style.css          # Custom styles
│   ├── js/
│   │   └── main.js            # JavaScript utilities
│   └── images/                # Static images
├── config/
│   ├── config.php             # Application configuration
│   └── database.php           # Database connection
├── controllers/
│   ├── AuthController.php     # Authentication logic
│   ├── DonorController.php    # Donor features
│   ├── NGOController.php      # NGO features
│   └── AdminController.php    # Admin features
├── database/
│   └── init.sql               # Database initialization script
├── models/
│   ├── User.php               # User model
│   ├── Donation.php           # Donation model
│   ├── Request.php            # Request model
│   ├── Notification.php       # Notification model
│   └── Feedback.php           # Feedback model
├── public/
│   └── index.php              # Main entry point / router
├── uploads/                   # Uploaded food images
├── views/
│   ├── layouts/
│   │   ├── header.php         # Common header
│   │   └── footer.php         # Common footer
│   ├── auth/
│   │   ├── login.php          # Login page
│   │   └── register.php       # Registration page
│   ├── donor/
│   │   ├── dashboard.php      # Donor dashboard
│   │   ├── add_donation.php   # Add donation form
│   │   ├── edit_donation.php  # Edit donation form
│   │   └── view_requests.php  # View donation requests
│   ├── ngo/
│   │   ├── dashboard.php      # NGO dashboard
│   │   ├── browse_food.php    # Browse available food
│   │   ├── view_donation.php  # View donation details
│   │   ├── feedback.php       # Submit feedback
│   │   └── notifications.php   # View notifications
│   ├── admin/
│   │   ├── dashboard.php      # Admin dashboard
│   │   ├── ngo_verification.php    # NGO verification queue
│   │   ├── user_management.php     # User management
│   │   ├── reports.php        # Generate reports
│   │   ├── donation_history.php    # Donation history
│   │   └── request_history.php     # Request history
│   └── home.php               # Home page
├── requirements.md            # Software requirements specification
└── README.md                  # This file
```

## Usage Guide

### Registration

1. Navigate to the registration page
2. Select your role (Donor or NGO)
3. Fill in the required information
4. For NGOs, provide organization name and registration number
5. Submit the form

**Note**: NGO accounts require admin verification before they can access the platform.

### Login

1. Navigate to the login page
2. Enter your email and password
3. Click "Login"
4. You will be redirected to your role-specific dashboard

### Donor Workflow

1. **Add Donation**: Click "Add Donation" from dashboard
2. **Fill Details**: Provide food name, quantity, type, expiry, location
3. **Upload Image**: Optionally add a food image
4. **Manage Requests**: View incoming NGO requests and accept/reject
5. **Track Status**: Monitor donation status (available, requested, completed)

### NGO Workflow

1. **Browse Food**: Search available donations using filters
2. **Request**: Click "Request Donation" on items you need
3. **Track Requests**: Monitor request status from dashboard
4. **Collect**: Mark food as collected when received
5. **Feedback**: Submit feedback on completed donations

### Admin Workflow

1. **Verify NGOs**: Review pending NGO registrations
2. **Manage Users**: View and suspend user accounts
3. **Generate Reports**: Create reports on platform activity
4. **View History**: Access donation and request history

## Security Features

- Password hashing using PHP `password_hash()`
- Role-based access control (RBAC)
- Session-based authentication
- Input validation and sanitization
- SQL injection prevention using PDO prepared statements
- File upload validation (type, size)

## Future Enhancements

- RESTful API for mobile app integration
- GPS-based location services
- AI-powered food recommendations
- Real-time notifications (WebSocket)
- Email notifications
- Multi-language support
- Advanced reporting and analytics

## Troubleshooting

### Database Connection Error

If you see "Database connection failed":
1. Ensure MySQL is running in XAMPP
2. Check database credentials in `config/database.php`
3. Verify the `smartfood` database exists

### File Upload Issues

If image uploads fail:
1. Ensure the `uploads` directory exists
2. Check directory permissions (write access)
3. Verify file size doesn't exceed 5MB limit
4. Ensure file type is allowed (JPG, PNG, GIF)

### Session Issues

If you're logged out unexpectedly:
1. Check PHP session configuration in `php.ini`
2. Ensure session.save_path is writable
3. Clear browser cookies

## Support

For issues or questions, please refer to the requirements document (`requirements.md`) or contact the development team.

## License

This project is developed by Food Bridge for the purpose of reducing food wastage and supporting communities in need.

## Acknowledgments

- Bootstrap for the UI framework
- Bootstrap Icons for iconography
- XAMPP for the development environment

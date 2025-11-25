# GAFCSC Staff Management System

A comprehensive Laravel-based staff management system designed specifically for military universities, featuring role-based access control and advanced staff tracking capabilities.

## Features

### 🎖️ Military-Focused Design
- **Military & Civilian Staff Classification**: Explicit categorization with visual indicators
- **Service Numbers**: Unique identifiers for both military and civilian personnel
- **Rank & Appointment System**: Separate fields for military ranks and institutional appointments
- **Location Tracking**: HQ, Junior Division, departments, and campus areas

### 🔐 Security & Access Control
- **Role-Based Authentication**: Admin and Viewer roles with granular permissions
- **Permission Middleware**: Route-level protection for sensitive operations
- **User Management**: Admin interface for role assignment

### 📊 Advanced Features
- **Comprehensive Search**: Filter by name, service number, rank, appointment, department, or location
- **Multi-Format Export**: CSV, Excel, and professionally formatted PDF exports
- **Statistics Dashboard**: Military/civilian breakdown, department statistics, recent activity
- **Responsive Design**: Works seamlessly on desktop and mobile devices

### 🏫 University-Specific
- **Department Management**: Academic and administrative departments
- **Appointment Tracking**: Commandant, Dean, Head of Department, etc.
- **Campus Locations**: HQ, divisions, buildings, and facilities

## Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Blade Templates with Tailwind CSS
- **Database**: SQLite (easily configurable for MySQL/PostgreSQL)
- **Export**: Laravel Excel, DomPDF
- **Authentication**: Laravel Breeze

## Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/YOUR_USERNAME/military-university-staff-management.git
   cd military-university-staff-management
   ```

2. **Install dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

3. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

5. **Install required packages**
   ```bash
   composer require maatwebsite/excel barryvdh/laravel-dompdf
   ```

6. **Start the application**
   ```bash
   php artisan serve
   ```

7. **Access the application**
   - Open your browser and navigate to `http://localhost:8000`
   - Register your first user account
   - Use the "Make Me Admin" button on the dashboard to gain administrative privileges

## Usage

### Initial Setup
1. **Register First User**: Create your account through the registration page
2. **Gain Admin Access**: Use the "Make Me Admin" button on the dashboard for initial setup
3. **Remove Setup Button**: After becoming admin, remove the setup button for security

### Managing Staff
- **Add Staff**: Click "Add New Staff" and fill in all required information:
  - Full Name
  - Service Number (e.g., MIL001, CIV001)
  - Staff Type (Military/Civilian)
  - Rank/Title (Colonel, Professor, Dr, etc.)
  - Appointment (Commandant, Dean, etc.)
  - Department
  - Location (HQ, Junior Division, etc.)

- **Search & Filter**: Use the search bar and filter dropdowns to find specific staff
- **Edit Staff**: Click "Edit" on any staff member (Admin only)
- **View Details**: Click "View" to see complete staff information

### Export Functionality
- **CSV Export**: Download spreadsheet-compatible format
- **Excel Export**: Professional .xlsx format with formatting
- **PDF Export**: Branded university documents with statistics

### User Management (Admin Only)
- Navigate to "User Management" in the main menu
- View all system users and their current roles
- Change user roles between Admin and Viewer
- Monitor user activity and access levels

## Database Schema

### Staff Table
```sql
- id: Primary key
- name: Full name of staff member
- service_number: Unique identifier (string)
- rank: Military rank or civilian title
- appointment: Institutional position
- staff_type: ENUM('military', 'civilian')
- department: Academic/administrative department
- location: Physical location or campus area
- created_at: Record creation timestamp
- updated_at: Last modification timestamp
```

### Users Table
```sql
- id: Primary key
- name: User's full name
- email: Login email (unique)
- password: Encrypted password
- role: ENUM('admin', 'viewer')
- created_at: Account creation timestamp
- updated_at: Last modification timestamp
```

## Permissions Matrix

| Action | Admin | Viewer |
|--------|-------|--------|
| View Staff | ✅ | ✅ |
| Add Staff | ✅ | ❌ |
| Edit Staff | ✅ | ❌ |
| Delete Staff | ✅ | ❌ |
| Export Data | ✅ | ✅ |
| Manage Users | ✅ | ❌ |
| View Dashboard | ✅ | ✅ |

## File Structure

```
military-university-staff-management/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── StaffController.php
│   │   │   ├── UserManagementController.php
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   │       └── CheckStaffPermissions.php
│   ├── Models/
│   │   ├── Staff.php
│   │   └── User.php
│   └── Exports/
│       └── StaffExport.php
├── database/
│   └── migrations/
├── resources/
│   └── views/
│       ├── staff/
│       ├── users/
│       └── dashboard.blade.php
└── routes/
    └── web.php
```

## Configuration

### Environment Variables
```env
# Database Configuration
DB_CONNECTION=sqlite
DB_DATABASE=/path/to/database.sqlite

# Mail Configuration (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

### Customization Options
- **University Logo**: Replace `public/images/logo.png` with your institution's logo
- **Color Scheme**: Modify Tailwind classes in blade templates
- **Default Roles**: Adjust in `UserRolesSeeder.php`
- **Export Styling**: Customize PDF templates in `resources/views/staff/export-pdf.blade.php`

## Security Features

- **Authentication Required**: All routes protected with Laravel's auth middleware
- **Role-Based Access**: Granular permissions prevent unauthorized access
- **CSRF Protection**: All forms include CSRF tokens
- **Input Validation**: Server-side validation on all user inputs
- **SQL Injection Prevention**: Eloquent ORM protects against SQL injection
- **XSS Protection**: Blade templating escapes output by default

## API Endpoints

| Method | URL | Description | Permission |
|--------|-----|-------------|------------|
| GET | `/staff` | List all staff | View |
| POST | `/staff` | Create new staff | Manage |
| GET | `/staff/{id}` | Show staff details | View |
| PUT | `/staff/{id}` | Update staff | Manage |
| DELETE | `/staff/{id}` | Delete staff | Manage |
| GET | `/staff/export/csv` | Export CSV | View |
| GET | `/staff/export/excel` | Export Excel | View |
| GET | `/staff/export/pdf` | Export PDF | View |

## Contributing

1. **Fork the repository**
2. **Create a feature branch**
   ```bash
   git checkout -b feature/AmazingFeature
   ```
3. **Make your changes**
4. **Add tests** (if applicable)
5. **Commit your changes**
   ```bash
   git commit -m 'Add some AmazingFeature'
   ```
6. **Push to the branch**
   ```bash
   git push origin feature/AmazingFeature
   ```
7. **Open a Pull Request**

### Development Guidelines
- Follow PSR-12 coding standards
- Write descriptive commit messages
- Add comments for complex logic
- Update documentation for new features
- Test on multiple browsers/devices

## Troubleshooting

### Common Issues

**1. Migration Errors**
```bash
# Reset and re-run migrations
php artisan migrate:fresh
```

**2. Permission Denied**
```bash
# Fix storage permissions
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
```

**3. Export Not Working**
```bash
# Install export packages
composer require maatwebsite/excel barryvdh/laravel-dompdf
```

**4. Logo Not Displaying**
```bash
# Create images directory and add logo
mkdir -p public/images
# Add your logo.png file to public/images/
```

## Performance Optimization

- **Database Indexing**: Service numbers and email fields are indexed
- **Lazy Loading**: Relationships loaded only when needed
- **Pagination**: Staff lists paginated to prevent memory issues
- **Caching**: Route and view caching enabled in production
- **Asset Compilation**: CSS/JS minified in production builds

## Deployment

### Production Checklist
- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure proper database credentials
- [ ] Set up mail server for notifications
- [ ] Configure web server (Apache/Nginx)
- [ ] Set up SSL certificate
- [ ] Enable Laravel optimizations:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```

## License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## Support

- **Issues**: Report bugs or request features via [GitHub Issues](https://github.com/YOUR_USERNAME/military-university-staff-management/issues)
- **Documentation**: This README and inline code comments
- **Community**: Feel free to fork and improve the project

## Acknowledgments

- Laravel Framework for the solid foundation
- Tailwind CSS for the responsive design system
- Laravel Excel for export functionality
- DomPDF for PDF generation
- The open-source community for inspiration and libraries

---

**Built with ❤️ for military universities worldwide**

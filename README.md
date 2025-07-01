# Military University Staff Management System

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
   git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY_NAME.git
   cd military-university-staff-management

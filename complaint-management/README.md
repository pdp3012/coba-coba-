# ComplaintHub - Complaint Management System

A clean, minimalist complaint management website built with Laravel (PHP) and MySQL, featuring a modern UI designed with Tailwind CSS.

## Features

### 🏠 Homepage
- Clean, modern landing page with complaint statistics
- Easy access to complaint submission
- Call-to-action for user registration and tracking

### 📝 Complaint Submission
- Simple, intuitive complaint form
- Support for both authenticated users and guests
- File attachment support (images, PDFs, documents)
- Categorization and priority levels
- Real-time form validation

### 👤 User Management & Titles
Users earn titles based on their participation:
- **Newcomer**: 1-3 complaints
- **Active Contributor**: 4-9 complaints  
- **Veteran Complainer**: 10+ complaints

### 📊 User Dashboard
- Personal complaint overview and statistics
- Recent complaint history
- Title progress tracking
- Complaint filtering and search
- Quick actions panel

### 🛠️ Admin Panel
- Comprehensive admin dashboard with system statistics
- Complaint management with status updates
- User management and title tracking
- Advanced filtering and search capabilities
- Assignment and notes system

### 📧 Notification System
- Email notifications for status changes
- High-priority complaint alerts for admins
- Queued email processing for performance

### 🎨 Design Features
- Minimalist, clean design using Tailwind CSS
- Fully responsive layout (mobile, tablet, desktop)
- Accessible color scheme with subtle accents
- Modern typography using Inter font
- Smooth transitions and hover effects

## Database Schema

### Users Table
- User information with admin privileges
- Title tracking based on complaint count
- Authentication and profile data

### Complaints Table
- Comprehensive complaint information
- Status tracking (Pending, In Progress, Resolved)
- Priority levels (Low, Medium, High)
- Category classification
- Support for both user and guest submissions

### Attachments Table
- File upload management
- Support for multiple file types
- Secure file storage

### Support Tables
- User titles with progression rules
- Complaint statuses for system configuration

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.4)
- **Database**: MySQL/SQLite
- **Frontend**: Blade Templates with Tailwind CSS
- **Build Tool**: Vite
- **Notifications**: Laravel Mail with Queue support
- **File Storage**: Laravel Storage with public disk

## Installation & Setup

1. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

4. **Storage Link**
   ```bash
   php artisan storage:link
   ```

5. **Build Assets**
   ```bash
   npm run build
   ```

6. **Start Development Server**
   ```bash
   php artisan serve
   ```

## Demo Data

To populate the application with sample data for demonstration:

```bash
php artisan db:seed --class=DemoDataSeeder
```

This creates:
- Admin user: admin@example.com / password
- Test users with various titles
- Sample complaints in different statuses
- Both user and guest complaints

## Default Admin Account

- **Email**: admin@example.com
- **Password**: password

## User Accounts for Testing

- **john@example.com** / password (Newcomer)
- **jane@example.com** / password (Active Contributor)  
- **bob@example.com** / password (Veteran Complainer)

## Security Features

- CSRF protection on all forms
- Input validation and sanitization
- Secure file upload handling
- Authentication middleware for protected routes
- Admin-only access controls

## Performance Considerations

- Database indexing for optimal query performance
- Queued email notifications
- Optimized asset building with Vite
- Pagination for large data sets
- Efficient eager loading of relationships

## File Upload Support

- **Image files**: JPG, JPEG, PNG
- **Documents**: PDF, DOC, DOCX
- **Size limit**: 10MB per file
- **Security**: File type validation and secure storage

## Email Configuration

The application supports email notifications. Configure your email settings in the `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@complanthub.com"
MAIL_FROM_NAME="ComplaintHub"
```

## Queue Configuration

For production environments, configure queue processing:

```bash
php artisan queue:work
```

## License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Contributing

This is a demonstration project showcasing modern web development practices with Laravel and Tailwind CSS.
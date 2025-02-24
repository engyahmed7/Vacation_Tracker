# Vacation Tracker

[![Laravel Version](https://img.shields.io/badge/Laravel-10.x-%23FF2D20?logo=laravel)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-8.1%2B-%23777BB4?logo=php)](https://php.net)

A comprehensive vacation management system with dual-approval workflow and Mattermost integration. The system supports three primary roles: **Admin, HR, and Supervisor**, ensuring a structured approval workflow. The application integrates with **Mattermost chat** to provide real-time notifications regarding vacation requests and approvals.
  
 ```mermaid
%% System Architecture Diagram for Vacation Tracker
graph TD
    subgraph User Roles
        A[Employee] -->|Submit Request| B(Vacation Request Service)
        HR[HR Manager] -->|Review/Approve| B
        SUP[Supervisor] -->|Review/Approve| B
        AD[Admin] -->|Manage Users| D[(Database)]
    end

    subgraph Core System
        B --> C{Mattermost Integration}
        B --> D[(Database)]
        D -->|Store| E[Users]
        D -->|Store| F[Vacation Requests]
        D -->|Store| G[Vacation Balances]
        D -->|Store| H[Vacation Types]
        B --> I[Vacation Balance Service]
    end

    subgraph Notifications
        C -->|New Request| J[Mattermost Channel]
        C -->|Approval Update| J
        C -->|Final Approval| J
    end

    subgraph Approval Workflow
        B -->|Pending| K[HR Approval]
        B -->|Pending| L[Supervisor Approval]
        K -->|Approved| M[Check Dual Approval]
        L -->|Approved| M
        M -->|Both Approved| N[Update Status]
        N --> I[Adjust Balances]
        N --> C[Send Notification]
    end

    style A fill:#4CAF50,color:white
    style HR fill:#2196F3,color:white
    style SUP fill:#FF9800,color:white
    style AD fill:#9C27B0,color:white
    style B fill:#607D8B,color:white
    style D fill:#795548,color:white
    style C fill:#009688,color:white
```

## Table of Contents
- [Key Features](#key-features)
- [Technology Stack](#technology-stack)
- [Installation Guide](#installation-guide)
- [Configuration](#configuration)
- [Workflow Overview](#workflow-overview)
- [Contributing](#contributing)

## Key Features

### Role-Based Access Control
| Role        | Permissions                                                                 |
|-------------|-----------------------------------------------------------------------------|
| Admin       | Full system control, user management, configuration                        |
| HR          | Approve/reject requests, view all employees, manage balances               |
| Supervisor  | Approve/reject team requests, view team members                            |
| Employee    | Submit requests, view personal history, check balances                     |

### Core Functionality
- Dual approval workflow (HR + Supervisor)
- Vacation type management (Annual/Casual)
- Future-date validation for requests
- Real-time balance tracking
- Mattermost notifications for:
  - New requests
  - Approvals/Rejections
  - System alerts

### Technical Highlights
- Filament PHP admin panel
- Automated vacation balance adjustment
- Audit logging for all transactions
- Policy-based authorization
- Responsive design

## Technology Stack

### Backend
- PHP 8.1+
- Laravel 10
- MySQL/PostgreSQL
- Laravel Sanctum (API Authentication)

### Frontend
- Filament PHP (Admin Dashboard)
- Blade Templates
- Alpine.js
- Tailwind CSS
- 
## Installation Guide
1. Clone the repository:
   ```sh
   git clone https://github.com/engyahmed7/Vacation_Tracker.git
   cd Vacation_Tracker
   ```
2. Install dependencies:
   ```sh
   composer install
   npm install && npm run build
   ```
3. Configure the `.env` file:
   ```sh
   cp .env.example .env
   ```
   - Set up **database credentials**
   - Configure **Mattermost API details**
   - Set **APP_URL** correctly
4. Run migrations(twice) and seeders:
   ```sh
   php artisan migrate
   php artisan migrate
   php artisan db:seed
   ```
5. Start the application:
   ```sh
   php artisan serve
   ```

## Configuration

### Environment Variables
```env
# Mattermost Integration
MATTERMOST_WEBHOOK_URL=https://your-mattermost-server/hooks/xyz123q

# Application Settings
APP_URL=http://localhost:8000
APP_ENV=production
APP_DEBUG=false

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vacation_tracker
DB_USERNAME=root
DB_PASSWORD=
```

## Workflow Overview

1. **Request Submission**
   ```mermaid
   sequenceDiagram
       User->>System: Submit Vacation Request
       System->>Database: Validate Dates
       System->>Mattermost: Send Notification
       System->>HR/Supervisor: Update Pending Approvals
   ```

2. **Approval Process**
   - Requires sequential approval from HR and Supervisor
   - Real-time status updates
   - Automatic balance deduction on full approval

3. **Cancellation Flow**
   - Admin can cancel pending/approved requests
   - Balance restoration for canceled requests
   - Mattermost notification for cancellations

4. **Managing Vacation Requests**
- HR and Supervisor can view pending requests from their profiles.
- Admin has full control over all requests.
- Approved requests **cannot be canceled** unless explicitly overridden by an Admin.

## Contributing
We welcome contributions from the community! To contribute, follow these steps:

1. **Fork** the repository.
2. **Create a new branch** following this naming convention: `feature/your-feature-name`.
3. **Make your changes** and ensure your code follows the project’s coding standards.
4. **Write tests** if applicable to ensure new functionality works as expected.
5. **Commit your changes** with a meaningful commit message.
6. **Push to your branch** on your forked repository.
7. **Open a Pull Request** with a clear description of your changes and link any relevant issues.
8. **Wait for a review** and address any feedback provided.

Your contributions help improve Vacation Tracker, and we appreciate your efforts!

# Software Requirements Specification (SRS)

## Smart Food Redistribution Platform

**Prepared By:** Food Bridge
**Date:** July 18, 2026
**Version:** 2.0 (Corrected & Finalized for Development)

---

## 1. Introduction

### 1.1 Purpose

The purpose of the Smart Food Redistribution Platform is to reduce food wastage by creating a centralized web application that connects food donors (restaurants, hotels, supermarkets, event organizers, and individuals) with NGOs, charitable organizations, and food banks. The platform enables quick and efficient redistribution of surplus food while ensuring transparency, accountability, and food safety.

### 1.2 Scope

The Smart Food Redistribution Platform is a web-based application that provides the following functionalities:

- User registration and secure login
- Food donation posting
- Food search and request management
- NGO verification
- Real-time donation status tracking
- Notification system
- Donation history and reporting
- Platform administration (user management, NGO verification, reporting)

The platform aims to minimize food waste and maximize food accessibility for communities in need.

### 1.3 Objectives

- Reduce food wastage.
- Connect donors with nearby NGOs.
- Simplify food donation management.
- Increase transparency in food distribution.
- Generate reports for monitoring donations.
- Provide a secure and user-friendly platform.

### 1.4 Intended Audience

- Restaurants, Hotels, and Supermarkets
- Individual Donors
- NGOs and Food Banks
- Platform Administrators
- Developers and Project Evaluators

### 1.5 Definitions

| Term | Description |
|---|---|
| Donor | A user who donates surplus food. |
| NGO | Organization that receives donated food. |
| Admin | Platform operator who verifies NGOs, manages users, and oversees reporting. |
| Donation | Food item uploaded by a donor. |
| Request | Application submitted by an NGO to receive food. |
| Notification | System-generated alert sent to a user regarding donation or request activity. |

---

## 2. Overall Description

### 2.1 Product Perspective

The Smart Food Redistribution Platform is an independent web application that serves as an intermediary between food donors and NGOs. It provides a centralized environment where food donations can be efficiently managed, utilizing a PHP backend and a MySQL database (via XAMPP).

### 2.2 Product Functions

The system will:

- Register and authenticate users securely.
- Allow donors to upload food details and manage their postings.
- Allow NGOs to search available food and request donations.
- Notify donors regarding requests and NGOs regarding approvals.
- Allow admins to verify NGOs, manage users, generate reports, and maintain history.

### 2.3 User Classes

**Donor**
Responsibilities: Register/Login, add food donations, edit/delete donations, accept/reject NGO requests, view incoming request details.

**NGO**
Responsibilities: Register/Login, browse available food, filter/search donations, request donations, cancel pending requests, track request status, confirm food receipt, submit feedback.

**Admin**
Responsibilities: Verify or reject NGO registrations, manage (view/suspend) donor and NGO accounts, view platform-wide donation and request history, generate reports.

### 2.4 Assumptions

- Users have internet connectivity and a modern web browser.
- NGOs are legally registered entities, subject to admin verification before their requests are approved.
- Donors provide accurate food information and ensure the food is safe for consumption.

### 2.5 Constraints

- Authentication is mandatory for all core platform features.
- Requires a server running PHP and a MySQL database (XAMPP environment for development).
- Development and testing occur on localhost (XAMPP); HTTPS enforcement applies only at production deployment (see Section 4, Security).

---

## 3. Functional Requirements

**FR1 — User Registration**
- Users shall be able to register as a Donor or an NGO.
- The system shall validate: email format, phone number validity, and password strength.
- NGO accounts shall default to a `pending` verification status until reviewed by an Admin.

**FR2 — Login**
- The system shall allow users to log in using their Email and Password.
- The system shall authenticate users, maintain secure PHP sessions, and redirect them to role-specific dashboards (Donor, NGO, or Admin).

**FR3 — Food Donation Management**
- The donor shall be able to add, update, delete, and view a history of their donations.
- Donation data includes: Food Name, Quantity (numeric value + unit), Food Type, Preparation Date, Expiry Time, Pickup Address, Contact Number, and Image.
- Donations shall automatically be marked `expired` once their Expiry Time has passed without being collected.

**FR4 — Search Food**
- NGOs shall be able to search available food.
- NGOs shall be able to filter by: Location (including proximity), Quantity, Food Type, and Availability Status.

**FR5 — Request Donation**
- NGOs shall be able to request specific food donations.
- NGOs shall be able to view request statuses and cancel requests before they are approved by the donor.

**FR6 — Donation Approval**
- Donors shall be able to accept or reject incoming requests from NGOs.
- Donors shall be able to view details of the requesting NGO.

**FR7 — Notifications**
- The system shall log and display a notification record when: a new donation is posted (to local/nearby NGOs), a request is submitted, a request is approved/rejected, or food is collected.
- Notifications shall be viewable on a per-user dashboard and marked as read/unread.

**FR8 — Feedback**
- Users shall be able to rate their experience and submit feedback upon the completion of a donation cycle.
- Each feedback entry shall be linked to the specific donation transaction it refers to.

**FR9 — Admin Management**
- Admins shall be able to review pending NGO registrations and approve or reject them, updating the NGO's verification status.
- Admins shall be able to view, search, and suspend Donor or NGO accounts.
- Admins shall be able to generate reports on total donations, completed transactions, and platform activity over a selected date range.

---

## 4. Non-Functional Requirements

**Architecture Note (resolving session vs. API design):** This platform is implemented as a traditional server-rendered PHP application using PHP sessions for authentication (per FR2), not a token/API-only architecture. The codebase shall follow a modular MVC structure so that a RESTful API layer can be added later for mobile app integration without requiring a redesign of the core application.

**Security**
- Passwords must be securely hashed (using PHP `password_hash()` and verified with `password_verify()`).
- HTTPS communication must be enforced **at production deployment**. Local development and testing via XAMPP occurs over plain HTTP and does not require SSL configuration.
- Role-based access control (RBAC) to protect Donor, NGO, and Admin-specific endpoints.
- PHP session timeouts for idle users.

**Reliability**
- System availability target: 99%.
- Daily MySQL database backups.

**Scalability**
- The system architecture must support the addition of more users and cities.
- Modular PHP/MVC design to allow a future RESTful API layer for mobile application integration.

**Usability**
- Responsive UI design (mobile, tablet, desktop).
- Simple, intuitive dashboards for Donors, NGOs, and Admins.

**Maintainability**
- Modular PHP architecture (MVC pattern).
- Clear documentation and commenting for easy code updates.

---

## 5. External Interface Requirements

**User Interface**

The application shall contain the following primary views:

- Home Page
- Registration & Login Pages
- Donor Dashboard
- NGO Dashboard
- Admin Dashboard (NGO verification queue, user management, reports)
- Food Listing & Search Page
- Donation Details Page
- Notifications Page
- Reports/History Page

**Hardware Interface**

Accessible via Laptop, Desktop, and Smartphone.

**Software Interface**

- Frontend: HTML5, CSS3, JavaScript, Bootstrap (or Tailwind CSS).
- Backend: PHP (Core, MVC structure).
- Database: MySQL (Hosted via XAMPP).

---

## 6. Database Design (Relational Schema for MySQL)

**Note for AI Web Generators:** The tables below define the exact, final MySQL relational schema required for the database initialization script. This schema is corrected and complete — build directly against it.

```sql
-- =========================================
-- Table: users
-- =========================================
CREATE TABLE users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('donor', 'ngo', 'admin') NOT NULL,
    address TEXT NOT NULL,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- =========================================
-- Table: ngos
-- =========================================
CREATE TABLE ngos (
    ngo_id INT PRIMARY KEY,
    organization_name VARCHAR(150) NOT NULL,
    registration_number VARCHAR(100) UNIQUE NOT NULL,
    verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending',
    verified_by INT NULL,
    verified_at TIMESTAMP NULL,
    FOREIGN KEY (ngo_id) REFERENCES users(user_id),
    FOREIGN KEY (verified_by) REFERENCES users(user_id)
);

-- =========================================
-- Table: donations
-- =========================================
CREATE TABLE donations (
    donation_id INT PRIMARY KEY AUTO_INCREMENT,
    donor_id INT,
    food_name VARCHAR(100) NOT NULL,
    quantity_value DECIMAL(10,2) NOT NULL,
    quantity_unit VARCHAR(20) NOT NULL,
    food_type VARCHAR(50) NOT NULL,
    preparation_date DATETIME NOT NULL,
    expiry_time DATETIME NOT NULL,
    pickup_location TEXT NOT NULL,
    latitude DECIMAL(10, 8) NULL,
    longitude DECIMAL(11, 8) NULL,
    status ENUM('available', 'requested', 'completed', 'expired') DEFAULT 'available',
    image_path VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (donor_id) REFERENCES users(user_id)
);

-- =========================================
-- Table: requests
-- =========================================
CREATE TABLE requests (
    request_id INT PRIMARY KEY AUTO_INCREMENT,
    donation_id INT,
    ngo_id INT,
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending',
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (donation_id) REFERENCES donations(donation_id),
    FOREIGN KEY (ngo_id) REFERENCES users(user_id)
);

-- =========================================
-- Table: feedback
-- =========================================
CREATE TABLE feedback (
    feedback_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    donation_id INT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
    comments TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (donation_id) REFERENCES donations(donation_id)
);

-- =========================================
-- Table: notifications
-- =========================================
CREATE TABLE notifications (
    notification_id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type ENUM('new_donation', 'request_submitted', 'request_approved', 'request_rejected', 'food_collected') NOT NULL,
    message TEXT NOT NULL,
    related_donation_id INT NULL,
    related_request_id INT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id),
    FOREIGN KEY (related_donation_id) REFERENCES donations(donation_id),
    FOREIGN KEY (related_request_id) REFERENCES requests(request_id)
);
```

---

## 7. Use Case Summary

| Use Case | Actor(s) | Description |
|---|---|---|
| Register / Login | Donor, NGO | Users create accounts and authenticate via PHP sessions. |
| Add Donation | Donor | Donor inserts food records into the MySQL database. |
| Search Donation | NGO | NGO queries available food from the database using filters. |
| Request Donation | NGO | NGO submits a claim for a specific food item. |
| Cancel Request | NGO | NGO withdraws a pending request before donor approval. |
| Accept/Reject Request | Donor | Donor approves or rejects the NGO's request, updating the donation status. |
| Collect Food | NGO | NGO marks the food as successfully received. |
| Provide Feedback | All Users | Users submit ratings regarding the transaction. |
| Verify NGO | Admin | Admin reviews and approves/rejects a pending NGO registration. |
| Manage Users | Admin | Admin views or suspends Donor/NGO accounts. |
| Generate Reports | Admin | Admin produces donation and activity reports over a date range. |

---

## 8. Conclusion

The Smart Food Redistribution Platform aims to provide an efficient, secure, and transparent solution for redistributing surplus food to those in need. By leveraging a PHP and MySQL (XAMPP) stack, the system will offer a robust relational data structure to connect donors with verified NGOs. This helps reduce food waste while improving access to food for underserved communities. Its modular design allows for future enhancements such as AI-powered recommendations, GPS tracking, and mobile applications, making it scalable and sustainable for long-term use.

---

## Appendix A: Corrections Applied to the Original Draft

The following gaps in the original draft were identified and resolved in this version:

| # | Area | Original Issue | Resolution |
|---|---|---|---|
| 1 | User Classes (2.3) | Admin responsibilities mentioned in 2.2 but no Admin user class was defined | Added Admin as a formal user class |
| 2 | Functional Requirements | No FR covering Admin actions despite being required | Added FR9 (Admin Management) |
| 3 | `users` table | No `admin` role in schema | Added `'admin'` to role ENUM |
| 4 | Database | No table for the FR7 notification system | Added `notifications` table |
| 5 | `ngos` table | No verification tracking despite scope requiring NGO verification | Added `verification_status`, `verified_by`, `verified_at` |
| 6 | `feedback` table | Not linked to a specific donation | Added `donation_id` foreign key |
| 7 | `donations` table | `quantity` was free-text, but FR4 requires filtering by quantity | Split into `quantity_value` (decimal) + `quantity_unit` |
| 8 | Multiple tables | No timestamps for history/reporting | Added `created_at`/`updated_at` |
| 9 | `users`, `donations` | No coordinates, despite "nearby NGO" being a stated objective | Added `latitude`/`longitude` |
| 10 | `donations.status` | Missing `expired` state despite tracking expiry time | Added `'expired'` |
| 11 | `requests.status` | Missing `cancelled` state despite FR5 requiring cancellation | Added `'cancelled'` |
| 12 | Architecture | SRS asked for both PHP sessions and a RESTful API without specifying which is primary | Resolved: session-based auth is primary; modular MVC structure supports a future API layer |
| 13 | Security (HTTPS) | HTTPS enforcement stated without scope, impractical for local XAMPP dev | Resolved: HTTPS required at production only; local development uses HTTP |

---

## Appendix B: Suggested Prompt for Windsurf

```
Build a complete PHP + MySQL web application called "Smart Food Redistribution
Platform" based on this SRS document in full — including the database schema
in Section 6, all functional requirements in Section 3, non-functional
requirements in Section 4, and the UI views listed in Section 5.

Requirements:
- Core PHP (no framework) with MVC folder structure
- MySQL via XAMPP, connect using mysqli or PDO
- User roles: donor, ngo, admin — with role-based dashboard redirects after login
- Passwords hashed with PHP password_hash() / verified with password_verify()
- Sessions for authentication (traditional server-rendered app, not API-only)
- Donor: add/edit/delete donations, accept/reject NGO requests, view request details
- NGO: search/filter donations by location, quantity, food type, and availability;
  submit/cancel requests, mark food as collected, submit feedback
- Admin: verify/reject NGOs, view and suspend user accounts, generate reports
- Insert a row into `notifications` whenever: a donation is posted, a request is
  submitted, a request is approved/rejected, or food is marked collected
- Frontend: HTML5, Bootstrap, responsive for mobile/tablet/desktop
- Do not configure HTTPS/SSL — this is local XAMPP development only

Start by generating the database initialization SQL file exactly as given in
Section 6, then the folder structure, then the registration/login flow,
then each dashboard (Donor, NGO, Admin) in order.
```

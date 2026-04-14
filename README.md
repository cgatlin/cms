Case Management System
Portfolio Project – Backend Development

A web-based Case Management System designed to help organizations track, manage, and update cases efficiently. This application supports role-based access, case lifecycle tracking, and structured data management for real-world workflows.

Overview

This project simulates a production-style internal tool used by organizations such as legal offices, social services, or support teams to manage cases from creation to resolution.

It focuses on:
    Clean backend architecture
    Practical CRUD operations
    Role-based permissions
    Real-world data relationships
    Maintainable and scalable design

## Features

**Core Functionality**
    - [] Create, view, update, and delete cases
    - [] Assign cases to users (case workers/admins)
    - [] Track case status (Open, In Progress, Closed)
    - [] Add notes or updates to cases (case history)
    - [] Filter and search cases

**User Roles**
    Admin
        Full access to all cases and users
    Case Worker
        Manage assigned cases
        Update case status and notes

**Additional Features (Planned / In Progress)**
    Dashboard with case statistics (charts)
    Pagination for large datasets
    Validation and error handling
    Activity log (audit trail)

**Tech Stack**
    Backend: Laravel (PHP)
    Frontend: Blade templates (Laravel)
    Database: MySQL
    Styling: Bootstrap / Tailwind (depending on implementation)
    Charts: Chart.js (for dashboard analytics)

**Database Structure**
    users
        id, name, email, role, etc.
    cases
        id, title, description, status, assigned_to, created_by
    case_notes
        id, case_id, user_id, note, created_at

**Usage**
    Register or log in as a user
    Admins can create and assign cases
    Case workers can update assigned cases
    Use filters/search to manage large case lists

**Project Goals**
This project is part of a structured career restart plan and demonstrates:
    Ability to design relational databases
    Understanding of MVC architecture (Laravel)
    Implementation of role-based access control
    Building real-world CRUD applications
    Writing clean, maintainable code

**Future Improvements**
    API integration (RESTful endpoints)
    File attachments for cases
    Notifications (email or in-app)
    Advanced reporting and exports (CSV/PDF)
    Unit and feature testing

## **Screenshots (Coming Soon)**
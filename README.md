# Hospital-Patient Management System

This project is a comprehensive **Hospital-Patient Management System** developed as part of the CSE311: Database Management Systems course at North South University. It is designed to streamline hospital operations by organizing patient data, managing staff responsibilities, handling appointments, diagnostics, and billing in a secure, role-based interface.

## 🚀 Features

### 🔐 User Roles & Access
- **Admin**: Manages departments and staff
- **Doctor**: Manages appointments, prescribes tests/treatments
- **Nurse**: Views patient data and performs tests
- **Patient**: Books appointments, views records, pays bills

### 📅 Appointment Management
- Patients can book, view, or cancel appointments
- Doctors can update status and fees of appointments

### 🧪 Test Management
- Doctors can prescribe multiple tests (deduplicated)
- Nurses perform tests and update results
- Linked directly to billing

### 💊 Treatment Plans
- Doctors prescribe dosage and suggestions
- Timestamped and accessible to both doctors and patients

### 💰 Billing System
- Auto-updated billing with appointment/test charges
- Online payment system with downloadable invoices

## 🧱 Entity-Relationship Highlights
- Composite Attributes: Name, Address
- Multivalued Attributes: Patient mobile numbers
- Derived Attributes: Patient age
- Ternary Relationships: Doctor-Patient-Test, Doctor-Patient-Appointment, etc.
- Specialization/Generalization: `User → Patient/Staff → Doctor/Nurse`

## 🛠️ Technologies Used
- **Frontend**: HTML, CSS, JS
- **Backend**: PHP (Raw)
- **Database**: MySQL (via XAMPP)
- **Tools**: ER modeling, SQL DDL/DML, Session management

## 📸 Interface Highlights

- Secure login and password reset
- Patient dashboard, appointment booking, and billing
- Doctor interfaces for managing treatments and viewing patient history
- Nurse dashboards for test updates
- Admin panels for staff and department management

## ⚙️ Setup Instructions

1. Install **XAMPP** and start Apache & MySQL
2. Clone this repository:
   ```bash
   git clone https://github.com/yourusername/hospital-management-system.git
Import the SQL schema from hospital_db.sql into your MySQL database

Move the project files to htdocs in XAMPP directory

Open http://localhost/hospital-management-system in your browser

👥 Team Members
S M Samiul Islam (2231971042)

System implementation, frontend/backend for patients and login, ERD modeling

Xaima Zaman (2232780042)

Frontend/backend for doctor, nurse, and admin, SQL execution, ERD modeling

📌 Acknowledgment
Submitted to: Dr. Abu Sayed Mohammad Latiful Hoque (Slf)
Lab Instructor: Sadia Afrin
North South University, Spring 2025

📄 License
This project is for academic use only. All rights reserved by the original authors.

# SIAKAD SMP Muhammadiyah - Academic Information System

## Project Overview

SIAKAD SMP Muhammadiyah is a web-based Academic Information System designed to digitize and streamline academic and administrative processes at Muhammadiyah Junior High School. This system covers a wide range of functionalities, from new student enrollment (PPDB), student and teacher management, class scheduling, attendance tracking, and grade processing, to online school fee (SPP) payments.

The primary goal is to create an efficient, accessible, and integrated platform for students, teachers, administrative staff, and school management.

## Key Features

1.  📝 **PPDB (New Student Admission / *Penerimaan Peserta Didik Baru*)**
    * Online registration form for prospective students.
    * Document upload: passport-sized photo, Family Card (Kartu Keluarga), and Birth Certificate (Akta Kelahiran).
    * Data verification by admin/operator.
    * Registration status tracking: Pending → Verified → Accepted/Rejected.
    * Printable registration proof.
    * (Optional) Dedicated login for applicants to check their admission status.

2.  👨‍🎓 **Student Management**
    * Add, edit, and delete student records.
    * Assign students to classes.
    * Manage student status: Active / Graduated / Transferred.

3.  👨‍🏫 **Teacher Management**
    * Add, edit, and delete teacher records.
    * Assign teachers to subjects and classes.

4.  📅 **Lesson Scheduling / Timetable Management**
    * Input and manage lesson schedules for each class.
    * Organized by day and time slots.

5.  ✅ **Attendance Tracking**
    * Teachers can record daily student attendance.
    * Attendance categories: Present / Permit (Izin) / Sick (Sakit) / Absent without leave (Alfa).

6.  📊 **Grading & Report Cards**
    * Input scores for daily assignments, mid-term exams (UTS), and final exams (UAS).
    * Automatic calculation of final grades and predicates.
    * Generate and print semesterly report cards.

7.  💳 **Financial Management (SPP Payments)**
    * Online payment fasilitas for SPP (School Fees) via Midtrans Payment Gateway.
    * Securely stored and accessible payment history for each student.

## 👥 User Roles & Access Levels

The system defines several user roles with specific access permissions:

1.  **Admin:**
    * Full access to all system modules and data management.
    * Responsible for core system configuration and security.
2.  **Operator:**
    * Manages student data, schedules, and validates new student registrations.
3.  **Teacher (Guru):**
    * Inputs student grades and attendance.
    * Views their teaching schedule and assigned classes.
4.  **Student (Siswa):**
    * Views their personal schedule and grades.
    * Prints their academic results/report cards.
    * Makes online SPP payments.

## 🧩 Detailed Features by Role

#### 🔹 Admin
* **Academic Year Management:** Define and manage academic years and semesters.
* **Class Management & Student Placement:** Create classes, assign homeroom teachers, and place students into classes.
* **User & Role Management (CRUD):** Manage accounts for Teachers, Students, and Operators, including their roles and permissions.
* **Subject Management:** Add, edit, or delete subjects offered by the school.
* **Schedule Management:** Oversee and finalize class schedules.
* **SPP (School Fee) Configuration:** Set up SPP amounts, payment deadlines, and payment gateway settings.
* System-wide settings and backups.

#### 🔹 Operator
* **PPDB Data Validation:** Verify documents and data submitted by new student applicants.
* **Student Data Management (CRUD):** Manage the database of active students.
* **Schedule & Subject Data Input:** Assist in inputting and updating lesson schedules and subject details.
* **SPP Payment Monitoring:** Track and verify SPP payments.
* Generate operational reports.

#### 🔹 Teacher
* **View Teaching Schedule:** Access personal timetable and list of classes/subjects taught.
* **Grade Input:** Enter student scores for assignments, quizzes, UTS, and UAS for assigned subjects/classes.
* **Attendance Input:** Record daily student attendance for their classes.
* Access student lists for their classes.

#### 🔹 Student
* **View Personal Dashboard:** Access own grades, attendance records, and lesson schedule.
* **Print Academic Records:** Download or print personal report cards/transcripts.
* **Online SPP Payment:** Make SPP payments securely through the Midtrans payment gateway integration.
* View payment history.

## 🛠️ Technology Stack (Example - Please Fill In Your Actual Stack)

* **Backend:** [e.g., Laravel (PHP)]
* **Frontend:** [e.g., React.js, Vue.js, Blade with Livewire/Alpine.js]
* **Database:** [e.g., MySQL, PostgreSQL]
* **Payment Gateway Integration:** Midtrans
* **Web Server:** [e.g., Nginx, Apache]
* **Other Tools:** [e.g., Composer, NPM/Yarn, Git]

---

*This README provides a general outline. Feel free to add more specific details about your project's architecture, setup instructions, contribution guidelines, etc.*

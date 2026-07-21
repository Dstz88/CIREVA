# CIREVA
# Product Requirements Document (PRD) v5.0

**Version:** 5.0  
**Status:** Final  
**Project:** CIREVA (Cirebon Event & Virtual Application)

---

# 1. Document Information

## Purpose
Dokumen ini menjadi **single source of truth** untuk analisis, desain, implementasi, pengujian, dan pemeliharaan CIREVA.

## Stakeholders
- Product Owner
- Administrator
- Organizer
- User
- Developer
- UI/UX Designer
- QA Tester

---

# 2. Product Vision

Menyediakan platform terpadu untuk pengelolaan event budaya Cirebon mulai dari onboarding organizer hingga penyelenggaraan event dan pelaporan.

---

# 3. Product Goals

- Digitalisasi pengelolaan event budaya.
- Standarisasi proses SPK.
- Transparansi penjualan tiket.
- Penyediaan kalender budaya.
- Pelaporan terintegrasi.

---

# 4. User Roles

## Guest
- Home
- Search Event
- View Event
- View Calendar
- Register
- Login

## User
- Dashboard
- Booking
- Payment
- E-Ticket
- Booking History
- Profile

## Organizer
- Dashboard
- Organizer Profile
- Upload Documents
- SPK Management
- Event Management
- Calendar Management
- Ticket Management
- Booking Monitoring
- Reporting

## Administrator
- Dashboard
- Organizer Verification
- SPK Approval
- Event Approval
- Calendar Management
- Ticket Management
- Payment Verification
- Reports
- User Management
- Master Data

---

# 5. Permission Matrix

| Module | Guest | User | Organizer | Admin |
|--------|:----:|:----:|:---------:|:----:|
| Event | R | R | CRUD | CRUD |
| Calendar | R | R | CRUD | CRUD |
| Ticket | - | Buy | CRUD | CRUD |
| Booking | - | CRUD | Monitor | Full |
| SPK | - | - | Read/Sign | Full |
| Report | - | - | View | Full |

---

# 6. Core Modules

- Authentication
- Organizer Onboarding
- SPK Management
- Organizer Verification
- Event Management
- Calendar Management
- Ticket Management
- Booking Management
- Payment Monitoring
- Notification
- Reporting
- Master Data

---

# 7. Organizer Onboarding

Register
→ Complete Profile
→ Upload Supporting Documents
→ Generate SPK
→ Read Agreement
→ Accept Agreement
→ Digital Signature
→ SPK Signed
→ Waiting Verification
→ Admin Review
→ Approved
→ Dashboard Active

Business Rules:
- Dashboard Organizer hanya aktif setelah Organizer dan SPK berstatus Approved.
- Organizer tidak dapat membuat Event sebelum lolos verifikasi.

---

# 8. SPK Management

## Features
- Generate SPK
- View SPK
- Accept Agreement
- Digital Signature
- SPK History
- Download SPK

## SPK Status

Draft
→ Generated
→ Pending Signature
→ Signed
→ Under Review
→ Revision Required
→ Approved
→ Rejected
→ Expired

---

# 9. Event Management

Features:
- Create Event
- Update Event
- Delete Event
- Submit Event
- Publish Event
- Archive Event

Event Status:

Draft
→ Submitted
→ Under Review
→ Revision Required
→ Approved
→ Published
→ Ongoing
→ Finished
→ Archived

---

# 10. Calendar Management

## Features

- View Calendar
- Month View
- Week View
- Day View
- Timeline View
- Input Jadwal
- Update Jadwal
- Delete Jadwal
- Conflict Detection
- Venue Availability
- Calendar Approval
- Recurring Event

Calendar Status

- Draft
- Scheduled
- Published
- Ongoing
- Finished
- Cancelled

Business Rules

- Jadwal tidak boleh bentrok.
- Jadwal Published memerlukan approval untuk perubahan.
- Jadwal dengan booking tidak dapat dihapus.

---

# 11. Ticket Management

Features

- Create Ticket
- Update Ticket
- Delete Ticket
- Activate Ticket
- Deactivate Ticket

Ticket dapat dijual jika:

- Organizer Approved
- SPK Approved
- Event Published
- Calendar Scheduled/Published

---

# 12. Booking Workflow

Search Event
→ Select Ticket
→ Booking
→ Payment
→ Ticket Issued
→ Download E-Ticket

Booking Status

Pending
Paid
Cancelled
Completed
Expired

---

# 13. Notification

- Booking Created
- Payment Success
- Event Approved
- Event Rejected
- SPK Approved
- Reminder Event

---

# 14. Reporting

Organizer:
- Booking Report
- Revenue Report
- Event Report

Admin:
- Organizer Report
- Event Report
- Ticket Report
- Transaction Report

---

# 15. Database Overview

Core Tables

- users
- organizer_profiles
- cooperation_agreements
- calendars
- events
- event_categories
- event_locations
- tickets
- bookings
- transactions
- reports
- notifications
- activity_logs

---

# 16. Architecture Standard

Controller
→ Service
→ Repository
→ Model

Gunakan:
- Service Pattern
- Repository Pattern
- Form Request
- Middleware
- Policies
- Events
- Listeners
- Observer

---

# 17. Acceptance Criteria

- Organizer tidak dapat membuat Event sebelum SPK Approved.
- Event tidak dapat Published tanpa Approval.
- Jadwal tidak boleh bentrok.
- Tiket hanya dijual pada Event Published.
- Semua aktivitas penting dicatat pada Activity Log.

---

# 18. Future Scope

- Payment Gateway
- QR Ticket Validation
- Mobile Apps
- REST API
- WhatsApp Notification
- Dashboard Analytics
- Multi Region Support

---
Dokumen ini menjadi referensi utama untuk AI_CONTEXT.md, ARCHITECTURE.md, DATABASE.md, WORKFLOW.md, BACKLOG.md, dan implementasi Laravel CIREVA.

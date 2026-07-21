# CIREVA

# 13_MODULE_SPECIFICATION.md

**Version:** 1.1  
**Status:** Ready for Development

---

# Purpose

Dokumen ini menjelaskan spesifikasi implementasi setiap modul pada CIREVA.

Dokumen ini menjadi acuan implementasi:

- Controller
- Service
- Repository
- Model
- Policy
- Middleware
- Event & Listener
- Business Rule
- State Transition

---

# 1. Authentication Module

## Purpose

Mengelola autentikasi dan otorisasi seluruh pengguna.

### Features

- Register User
- Register Organizer
- Login
- Logout
- Email Verification
- Forgot Password
- Reset Password

### Components

Controller

- AuthController

Service

- AuthenticationService

Repository

- UserRepository

Models

- User
- Role

Middleware

- guest
- auth
- verified

Policy

- UserPolicy

Events

- Registered
- EmailVerified
- PasswordReset

Business Rules

- Email harus unik.
- Password harus terenkripsi.
- Role ditentukan saat registrasi.
- Organizer wajib melengkapi profil setelah registrasi.

---

# 2. Organizer Module

## Purpose

Mengelola seluruh data organizer.

### Features

- Dashboard
- Organizer Profile
- Upload Document
- Verification Status
- Edit Profile

### Components

Controller

- OrganizerController

Service

- OrganizerService

Repository

- OrganizerRepository

Model

- OrganizerProfile

Policy

- OrganizerPolicy

Middleware

- auth

Events

- OrganizerRegistered
- OrganizerVerified
- OrganizerRejected

### State Transition

Pending

↓

Under Review

↓

Approved

↓

Rejected

↓

Suspended

### Business Rules

- Profil wajib lengkap.
- Dokumen wajib diunggah.
- Organizer hanya dapat membuat Event jika status Approved.

---

# 3. SPK Module

## Purpose

Mengelola kerja sama antara Organizer dan Administrator.

### Features

- Generate SPK
- View SPK
- Accept Agreement
- Digital Signature
- Download SPK
- SPK History

### Components

Controller

- SpkController

Service

- SpkService

Repository

- SpkRepository

Model

- CooperationAgreement

Policy

- SpkPolicy

Events

- SpkGenerated
- SpkSigned
- SpkApproved
- SpkRejected

### State Transition

Draft

↓

Generated

↓

Pending Signature

↓

Signed

↓

Under Review

↓

Approved

↓

Rejected

↓

Expired

### Business Rules

- Hanya Organizer Approved yang dapat memiliki SPK.
- SPK wajib Approved sebelum Event diajukan.

---

# 4. Event Module

## Purpose

Mengelola seluruh Event Budaya.

### Features

- Create Event
- Update Event
- Delete Event
- Submit Event
- Approval
- Publish
- Archive

### Components

Controller

- EventController

Service

- EventService

Repository

- EventRepository

Models

- Event
- EventCategory
- EventLocation

Policy

- EventPolicy

Middleware

- auth

Events

- EventSubmitted
- EventApproved
- EventPublished
- EventArchived

### State Transition

Draft

↓

Submitted

↓

Under Review

↓

Revision Required

↓

Approved

↓

Published

↓

Ongoing

↓

Finished

↓

Archived

### Business Rules

- Organizer harus Approved.
- SPK harus Approved.
- Event hanya dapat dipublikasikan setelah disetujui Admin.
- Event wajib memiliki Schedule.

---

# 5. Calendar Module

## Purpose

Mengelola jadwal Event.

### Features

- Month View
- Week View
- Day View
- Timeline View
- Create Schedule
- Update Schedule
- Delete Schedule
- Conflict Detection
- Venue Availability

### Components

Controller

- CalendarController

Service

- CalendarService

Repository

- CalendarRepository

Model

- EventSchedule

Policy

- CalendarPolicy

### State Transition

Draft

↓

Scheduled

↓

Published

↓

Ongoing

↓

Finished

↓

Cancelled

### Business Rules

- Tidak boleh ada benturan jadwal.
- Jadwal tidak dapat dihapus jika sudah memiliki Booking.

---

# 6. Ticket Module

## Purpose

Mengelola tiket Event.

### Features

- Create Ticket
- Update Ticket
- Delete Ticket
- Activate Ticket
- Ticket Availability
- Ticket Quota

### Components

Controller

- TicketController

Service

- TicketService

Repository

- TicketRepository

Model

- Ticket

Policy

- TicketPolicy

### Business Rules

- Ticket hanya dapat dibuat pada Event Published.
- Kuota tidak boleh negatif.

---

# 7. Booking Module

## Purpose

Mengelola pemesanan tiket.

### Features

- Booking Ticket
- Cancel Booking
- Booking History
- Download E-Ticket

### Components

Controller

- BookingController

Service

- BookingService

Repository

- BookingRepository

Models

- Booking
- BookingItem

Policy

- BookingPolicy

Events

- BookingCreated
- BookingPaid
- BookingCancelled
- BookingCompleted

### State Transition

Pending

↓

Paid

↓

Completed

↓

Cancelled

↓

Expired

### Business Rules

- Booking menghasilkan Transaction.
- Booking Success menghasilkan Notification.
- Booking Paid menghasilkan E-Ticket.

---

# 8. Transaction Module

## Purpose

Mengelola pembayaran.

### Features

- Upload Payment Proof
- Verify Payment
- Refund (Future)

### Components

Controller

- TransactionController

Service

- TransactionService

Repository

- TransactionRepository

Models

- Transaction
- PaymentProof

Policy

- TransactionPolicy

Events

- PaymentUploaded
- PaymentVerified
- PaymentSucceeded
- PaymentFailed

### State Transition

Pending

↓

Success

↓

Failed

↓

Refunded

### Business Rules

- Bukti pembayaran wajib diverifikasi Admin.
- Payment Success mengubah Booking menjadi Paid.

---

# 9. Notification Module

## Purpose

Mengelola seluruh notifikasi sistem.

### Features

- Booking Notification
- Event Notification
- SPK Notification
- Reminder

### Components

Service

- NotificationService

Repository

- NotificationRepository

Model

- Notification

Business Rules

- Notification dibuat secara otomatis oleh Event System.
- User dapat menandai notifikasi telah dibaca.

---

# 10. Reporting Module

## Purpose

Menghasilkan laporan sistem.

### Features

- Event Report
- Booking Report
- Revenue Report
- Organizer Report

### Components

Controller

- ReportController

Service

- ReportService

Repository

- ReportRepository

Model

- Report

Business Rules

- Laporan hanya dapat diakses Admin.

---

# 11. Master Data Module

## Purpose

Mengelola data referensi sistem.

### Features

- Event Categories
- Event Locations
- User Management

### Components

Controller

- MasterDataController

Service

- MasterDataService

Repository

- MasterDataRepository

---

# Cross Module Rules

- Organizer harus Approved sebelum membuat Event.
- SPK harus Approved sebelum Event diajukan.
- Event harus memiliki Schedule.
- Event harus Published sebelum Ticket dijual.
- Ticket harus Active sebelum dapat dipesan.
- Booking menghasilkan Transaction.
- Transaction Success menghasilkan Notification.
- Transaction Success menerbitkan E-Ticket.
- Activity Log dicatat untuk seluruh perubahan status utama.

---

# Module Dependency

Authentication

↓

Organizer

↓

SPK

↓

Event

↓

Calendar

↓

Ticket

↓

Booking

↓

Transaction

↓

Notification

↓

Reporting

---

# References

- 01_PRD.md
- 03_ARCHITECTURE.md
- 04_DATABASE.md
- 05_WORKFLOW.md
- 10_ROUTES.md
- 11_ERD.md
- 12_DATABASE_SCHEMA.md
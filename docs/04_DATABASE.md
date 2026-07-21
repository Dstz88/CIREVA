# CIREVA

# DATABASE DESIGN

Version : 1.0

Status : Draft

---

# 1. Purpose

Dokumen ini menjelaskan arsitektur database CIREVA yang akan menjadi acuan implementasi Laravel Migration, Eloquent Model, Repository, dan Service.

---

# 2. Database Technology

Database Engine

- MySQL 8+

Storage Engine

- InnoDB

Charset

- utf8mb4

Collation

- utf8mb4_unicode_ci

Timezone

- Asia/Jakarta

---

# 3. Database Principles

Seluruh tabel wajib memiliki

- id
- created_at
- updated_at

Soft Delete digunakan pada tabel:

- users
- organizer_profiles
- events
- tickets

Audit Log digunakan untuk seluruh aktivitas penting.

---

# 4. Database Modules

Authentication

- users
- roles

Organizer

- organizer_profiles
- organizer_documents
- cooperation_agreements

Master Data

- event_categories
- event_locations

Event

- events
- event_schedules

Ticket

- tickets

Booking

- bookings
- booking_items

Payment

- transactions
- payment_proofs

Reporting

- reports
- activity_logs

Notification

- notifications

---

# 5. Entity Relationships

User

↓

Organizer Profile

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

---

# 6. Naming Convention

Primary Key

id

Foreign Key

*_id

Boolean

is_*

Timestamp

*_at

Status

status

Slug

slug

---

# 7. Status Convention

Organizer

- Pending
- Approved
- Rejected
- Suspended

SPK

- Draft
- Generated
- Pending Signature
- Signed
- Approved
- Rejected
- Expired

Event

- Draft
- Submitted
- Approved
- Published
- Finished
- Archived

Calendar

- Draft
- Scheduled
- Published
- Ongoing
- Finished
- Cancelled

Booking

- Pending
- Paid
- Cancelled
- Completed
- Expired

Transaction

- Pending
- Success
- Failed
- Refunded

---

# 8. Database Rules

- Email wajib unik.
- Slug event wajib unik.
- Agreement Number wajib unik.
- Jadwal tidak boleh bentrok.
- Event harus memiliki Organizer.
- Ticket harus memiliki Event.
- Booking harus memiliki Ticket.
- Transaction harus memiliki Booking.

---

# 9. Index Strategy

Unique Index

- email
- slug
- agreement_number

Composite Index

- event_id + start_datetime
- booking_id + ticket_id

---

# 10. Migration Strategy

01 Roles

02 Users

03 Organizer Profiles

04 Organizer Documents

05 Cooperation Agreements

06 Categories

07 Locations

08 Events

09 Event Schedules

10 Tickets

11 Bookings

12 Booking Items

13 Transactions

14 Payment Proofs

15 Notifications

16 Reports

17 Activity Logs

---

# 11. Laravel Standards

- UUID tidak digunakan.
- Big Integer Primary Key.
- Foreign Key Cascade.
- Soft Delete digunakan sesuai kebutuhan.
- Seluruh relasi menggunakan Eloquent Relationship.

---

# 12. References

- 01_PRD.md
- 03_ARCHITECTURE.md
- 05_WORKFLOW.md
- 11_ERD.md
- 12_DATABASE_SCHEMA.md
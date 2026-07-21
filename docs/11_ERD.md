# CIREVA

# 11_ERD.md

## Entity Relationship Diagram (ERD)

**Version:** 1.1  
**Status:** Ready for Development  
**Project:** CIREVA (Cirebon Event & Virtual Application)

---

# 1. Purpose

Dokumen ini mendefinisikan struktur Entity Relationship Diagram (ERD) sebagai acuan implementasi:

- Laravel Migration
- Eloquent Model
- Foreign Key
- Repository
- Service Layer
- Business Rule Validation

---

# 2. Core Entities

## Authentication

- roles
- users

---

## Organizer

- organizer_profiles
- organizer_documents
- cooperation_agreements (SPK)

---

## Event

- event_categories
- event_locations
- events
- event_schedules

---

## Ticket

- tickets

---

## Booking

- bookings
- booking_items

---

## Transaction

- transactions
- payment_proofs

---

## System

- notifications
- reports
- activity_logs

---

# 3. Entity Relationship

```text
roles
└── 1 ─────── N users

users
├── 1 ─────── 1 organizer_profiles
├── 1 ─────── N bookings
├── 1 ─────── N notifications
└── 1 ─────── N activity_logs

organizer_profiles
├── 1 ─────── N organizer_documents
├── 1 ─────── N cooperation_agreements
└── 1 ─────── N events

event_categories
└── 1 ─────── N events

event_locations
└── 1 ─────── N events

events
├── 1 ─────── N event_schedules
├── 1 ─────── N tickets
└── 1 ─────── N reports

tickets
└── 1 ─────── N booking_items

bookings
├── 1 ─────── N booking_items
└── 1 ─────── 1 transactions

transactions
└── 1 ─────── N payment_proofs
```

---

# 4. Laravel Relationships

## User

- belongsTo(Role)
- hasOne(OrganizerProfile)
- hasMany(Booking)
- hasMany(Notification)
- hasMany(ActivityLog)

---

## OrganizerProfile

- belongsTo(User)
- hasMany(OrganizerDocument)
- hasMany(CooperationAgreement)
- hasMany(Event)

---

## OrganizerDocument

- belongsTo(OrganizerProfile)

---

## CooperationAgreement

- belongsTo(OrganizerProfile)

---

## Event

- belongsTo(OrganizerProfile)
- belongsTo(EventCategory)
- belongsTo(EventLocation)
- hasMany(EventSchedule)
- hasMany(Ticket)

---

## EventSchedule

- belongsTo(Event)

---

## Ticket

- belongsTo(Event)
- hasMany(BookingItem)

---

## Booking

- belongsTo(User)
- hasMany(BookingItem)
- hasOne(Transaction)

---

## BookingItem

- belongsTo(Booking)
- belongsTo(Ticket)

---

## Transaction

- belongsTo(Booking)
- hasMany(PaymentProof)

---

## PaymentProof

- belongsTo(Transaction)

---

## Notification

- belongsTo(User)

---

## ActivityLog

- belongsTo(User)

---

# 5. Business Dependency

Workflow bisnis utama sistem:

```text
User

↓

Organizer

↓

Upload Document

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

Report
```

Dependency ini merupakan aturan bisnis dan tidak seluruhnya direpresentasikan oleh Foreign Key.

---

# 6. Business Rules

## Organizer

- Setiap Organizer berasal dari satu User.
- Organizer wajib melengkapi profil.
- Organizer wajib mengunggah dokumen.
- Organizer harus berstatus **Approved** sebelum mengajukan Event.

---

## SPK

- Satu Organizer dapat memiliki beberapa SPK.
- Hanya SPK dengan status **Approved** yang dapat digunakan untuk mengajukan Event.

---

## Event

- Event dimiliki oleh satu Organizer.
- Event memiliki satu kategori.
- Event memiliki satu lokasi.
- Event dapat memiliki banyak jadwal.
- Event dapat memiliki banyak tiket.

---

## Calendar

- Jadwal tidak boleh bentrok pada lokasi dan waktu yang sama.
- Jadwal dengan Booking aktif tidak boleh dihapus.

---

## Ticket

- Tiket hanya dapat dibuat pada Event yang memenuhi aturan bisnis.
- Tiket hanya dapat dijual ketika:
  - Organizer Approved
  - SPK Approved
  - Event Published
  - Schedule Valid

---

## Booking

- Satu Booking dapat memiliki beberapa Ticket.
- Booking menghasilkan satu Transaction.

---

## Transaction

- Transaction hanya berasal dari Booking.
- Payment Success mengubah Booking menjadi Paid.
- Payment Success menerbitkan E-Ticket.

---

# 7. Referential Integrity Rules

Seluruh relasi wajib menggunakan Foreign Key.

Gunakan:

- ON UPDATE CASCADE

Gunakan salah satu:

- ON DELETE RESTRICT
- ON DELETE CASCADE
- ON DELETE SET NULL

sesuai kebutuhan modul.

---

# 8. Soft Delete Rules

Gunakan Soft Delete pada entitas berikut:

- users
- organizer_profiles
- events

Gunakan Hard Delete untuk tabel referensi jika memang diperlukan dan tidak memiliki histori bisnis.

---

# 9. Migration Order

1. roles
2. users
3. organizer_profiles
4. organizer_documents
5. cooperation_agreements
6. event_categories
7. event_locations
8. events
9. event_schedules
10. tickets
11. bookings
12. booking_items
13. transactions
14. payment_proofs
15. notifications
16. reports
17. activity_logs

---

# 10. Entity Responsibility

| Entity | Responsibility |
|---------|----------------|
| User | Autentikasi dan identitas pengguna |
| Organizer Profile | Data organisasi penyelenggara |
| Organizer Document | Dokumen persyaratan organizer |
| Cooperation Agreement | Perjanjian kerja sama (SPK) |
| Event | Informasi event budaya |
| Event Schedule | Jadwal pelaksanaan event |
| Ticket | Informasi tiket dan kuota |
| Booking | Data pemesanan tiket |
| Booking Item | Detail tiket pada booking |
| Transaction | Data pembayaran |
| Payment Proof | Bukti pembayaran |
| Notification | Notifikasi sistem |
| Report | Data laporan |
| Activity Log | Audit aktivitas pengguna |

---

# 11. Next Step

Dokumen ini menjadi acuan implementasi:

- 04_DATABASE.md
- 12_DATABASE_SCHEMA.md
- Laravel Migration
- Eloquent Model
- Foreign Key
- Repository Pattern
- Service Pattern
- Policy
- Business Rule Validation

---

# References

- 03_ARCHITECTURE.md
- 04_DATABASE.md
- 05_WORKFLOW.md
- 07_CODING_STANDARDS.md
- 09_DEVELOPMENT_RULES.md
- 10_ROUTES.md
- 12_DATABASE_SCHEMA.md
- 13_MODULE_SPECIFICATION.md
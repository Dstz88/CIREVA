# CIREVA

# 12_DATABASE_SCHEMA.md

**Version:** 1.1  
**Status:** Ready for Development  
**Project:** CIREVA (Cirebon Event & Virtual Application)

---

# 1. Purpose

Dokumen ini menjadi spesifikasi teknis seluruh struktur database CIREVA.

Dokumen ini digunakan sebagai acuan untuk:

- Laravel Migration
- Eloquent Model
- Repository
- Service Layer
- Form Request
- Policy
- Database Validation
- ERD Validation

---

# 2. Database Convention

## Database

- MySQL 8+
- InnoDB
- utf8mb4
- utf8mb4_unicode_ci

---

## General Rules

Primary Key

- id (BIGINT UNSIGNED AUTO_INCREMENT)

Foreign Key

- *_id

Timestamp

- created_at
- updated_at

Soft Delete

- deleted_at (jika diperlukan)

Audit Field

- created_by
- updated_by
- approved_by
- verified_by

---

# 3. Tables

---

## roles

| Field | Type | Constraint |
|--------|------|------------|
| id | bigint | PK |
| name | varchar(50) | Unique |
| description | varchar(255) | Nullable |
| created_at | timestamp | |
| updated_at | timestamp | |

Relationship

- hasMany(User)

---

## users

| Field | Type |
|------|------|
| id | bigint |
| role_id | bigint |
| name | varchar(150) |
| email | varchar(150) |
| password | varchar(255) |
| email_verified_at | timestamp |
| remember_token | varchar(100) |
| created_at | timestamp |
| updated_at | timestamp |
| deleted_at | timestamp |

Relationship

- belongsTo(Role)
- hasOne(OrganizerProfile)
- hasMany(Booking)
- hasMany(Notification)
- hasMany(ActivityLog)

---

## organizer_profiles

| Field | Type |
|------|------|
| id | bigint |
| user_id | bigint |
| organization_name | varchar(200) |
| owner_name | varchar(150) |
| phone | varchar(30) |
| address | text |
| description | text |
| logo | varchar(255) |
| status | enum(pending,under_review,approved,rejected,suspended) |
| verified_by | bigint nullable |
| verified_at | datetime nullable |
| rejection_reason | text nullable |
| created_at | timestamp |
| updated_at | timestamp |
| deleted_at | timestamp |

Relationship

- belongsTo(User)
- hasMany(OrganizerDocument)
- hasMany(CooperationAgreement)
- hasMany(Event)

---

## organizer_documents

| Field | Type |
|------|------|
| id | bigint |
| organizer_profile_id | bigint |
| document_type | varchar(100) |
| file_path | varchar(255) |
| verification_status | enum(pending,approved,rejected) |
| verified_by | bigint nullable |
| verified_at | datetime nullable |
| rejection_reason | text nullable |
| created_at | timestamp |
| updated_at | timestamp |

Relationship

- belongsTo(OrganizerProfile)

---

## cooperation_agreements

| Field | Type |
|------|------|
| id | bigint |
| organizer_profile_id | bigint |
| agreement_number | varchar(100) |
| version | varchar(20) |
| file_path | varchar(255) |
| signed_at | datetime nullable |
| approved_by | bigint nullable |
| approved_at | datetime nullable |
| rejected_reason | text nullable |
| expired_at | datetime nullable |
| status | enum(draft,generated,pending_signature,signed,under_review,revision_required,approved,rejected,expired) |
| created_at | timestamp |
| updated_at | timestamp |

Relationship

- belongsTo(OrganizerProfile)

---

## event_categories

| Field | Type |
|------|------|
| id | bigint |
| name | varchar(100) |
| slug | varchar(120) |
| description | text nullable |
| created_at | timestamp |
| updated_at | timestamp |

Relationship

- hasMany(Event)

---

## event_locations

| Field | Type |
|------|------|
| id | bigint |
| name | varchar(150) |
| address | text |
| latitude | decimal(10,7) |
| longitude | decimal(10,7) |
| capacity | integer nullable |
| created_at | timestamp |
| updated_at | timestamp |

Relationship

- hasMany(Event)

---

## events

| Field | Type |
|------|------|
| id | bigint |
| organizer_profile_id | bigint |
| category_id | bigint |
| location_id | bigint |
| title | varchar(200) |
| slug | varchar(220) |
| description | longtext |
| banner | varchar(255) |
| status | enum(draft,submitted,under_review,revision_required,approved,published,ongoing,finished,archived) |
| approved_by | bigint nullable |
| approved_at | datetime nullable |
| published_by | bigint nullable |
| published_at | datetime nullable |
| created_at | timestamp |
| updated_at | timestamp |
| deleted_at | timestamp |

Relationship

- belongsTo(OrganizerProfile)
- belongsTo(Category)
- belongsTo(Location)
- hasMany(EventSchedule)
- hasMany(Ticket)

Business Rule

- Organizer harus Approved.
- SPK harus Approved.
- Event baru dapat Published setelah disetujui Admin.

---

## event_schedules

| Field | Type |
|------|------|
| id | bigint |
| event_id | bigint |
| start_datetime | datetime |
| end_datetime | datetime |
| timezone | varchar(50) |
| status | enum(draft,scheduled,published,ongoing,finished,cancelled) |
| created_at | timestamp |
| updated_at | timestamp |

Relationship

- belongsTo(Event)

Business Rule

- Tidak boleh terdapat jadwal pada lokasi dan waktu yang sama.

---

## tickets

| Field | Type |
|------|------|
| id | bigint |
| event_id | bigint |
| name | varchar(150) |
| description | text nullable |
| price | decimal(12,2) |
| quota | integer |
| sold | integer default 0 |
| status | enum(active,inactive,sold_out) |
| sale_start | datetime nullable |
| sale_end | datetime nullable |
| created_at | timestamp |
| updated_at | timestamp |

Relationship

- belongsTo(Event)

---

## bookings

| Field | Type |
|------|------|
| id | bigint |
| user_id | bigint |
| booking_code | varchar(50) |
| total_amount | decimal(12,2) |
| status | enum(pending,paid,cancelled,completed,expired) |
| expired_at | datetime nullable |
| created_at | timestamp |
| updated_at | timestamp |

Relationship

- belongsTo(User)
- hasMany(BookingItem)
- hasOne(Transaction)

---

## booking_items

| Field | Type |
|------|------|
| id | bigint |
| booking_id | bigint |
| ticket_id | bigint |
| quantity | integer |
| price | decimal(12,2) |
| subtotal | decimal(12,2) |
| created_at | timestamp |
| updated_at | timestamp |

Relationship

- belongsTo(Booking)
- belongsTo(Ticket)

---

## transactions

| Field | Type |
|------|------|
| id | bigint |
| booking_id | bigint |
| transaction_number | varchar(100) |
| payment_method | varchar(50) |
| amount | decimal(12,2) |
| status | enum(pending,success,failed,refunded) |
| paid_at | datetime nullable |
| created_at | timestamp |
| updated_at | timestamp |

Relationship

- belongsTo(Booking)
- hasOne(PaymentProof)

---

## payment_proofs

| Field | Type |
|------|------|
| id | bigint |
| transaction_id | bigint |
| file_path | varchar(255) |
| verified_by | bigint nullable |
| verified_at | datetime nullable |
| status | enum(pending,approved,rejected) |
| created_at | timestamp |
| updated_at | timestamp |

Relationship

- belongsTo(Transaction)

---

## notifications

| Field | Type |
|------|------|
| id | bigint |
| user_id | bigint |
| title | varchar(200) |
| message | text |
| is_read | boolean |
| read_at | datetime nullable |
| created_at | timestamp |

Relationship

- belongsTo(User)

---

## reports

| Field | Type |
|------|------|
| id | bigint |
| report_type | varchar(100) |
| generated_by | bigint |
| generated_at | datetime |
| created_at | timestamp |

Relationship

- belongsTo(User)

---

## activity_logs

| Field | Type |
|------|------|
| id | bigint |
| user_id | bigint |
| module | varchar(100) |
| action | varchar(100) |
| description | text |
| ip_address | varchar(45) |
| user_agent | text nullable |
| created_at | timestamp |

Relationship

- belongsTo(User)

---

# 4. Migration Order

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

# 5. References

- 01_PRD.md
- 03_ARCHITECTURE.md
- 04_DATABASE.md
- 05_WORKFLOW.md
- 10_ROUTES.md
- 11_ERD.md
- 13_MODULE_SPECIFICATION.md
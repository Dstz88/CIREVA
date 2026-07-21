# CIREVA

# 09_DEVELOPMENT_RULES.md

**Version:** 1.1  
**Status:** Ready for Development

---

# Purpose

Dokumen ini mendefinisikan aturan pengembangan proyek CIREVA.

Seluruh Developer dan AI Assistant wajib mengikuti aturan ini agar implementasi tetap:

- Konsisten
- Aman
- Mudah dipelihara
- Mudah dikembangkan
- Selaras dengan seluruh dokumentasi proyek

---

# 1. General Rules

Seluruh implementasi wajib mengacu pada dokumentasi resmi proyek.

Urutan prioritas referensi:

1. 01_PRD.md
2. 05_WORKFLOW.md
3. 12_DATABASE_SCHEMA.md
4. 13_MODULE_SPECIFICATION.md
5. 03_ARCHITECTURE.md
6. 04_DATABASE.md
7. 10_ROUTES.md
8. 11_ERD.md
9. 07_CODING_STANDARDS.md

Jika terjadi konflik, gunakan dokumen dengan prioritas lebih tinggi.

---

# 2. Development Principles

Seluruh pengembangan harus memenuhi prinsip:

- Modular
- Maintainable
- Reusable
- Secure
- Scalable
- Testable
- Consistent

---

# 3. Documentation First

Sebelum mengembangkan fitur baru, pastikan:

- kebutuhan tercantum di PRD
- workflow tersedia
- database telah dirancang
- module specification tersedia
- backlog tersedia

Jangan membuat fitur berdasarkan asumsi.

---

# 4. Workflow Compliance

Seluruh perubahan status wajib mengikuti workflow resmi.

Dilarang:

- melewati status
- mengubah status secara langsung
- membuat status baru tanpa memperbarui dokumentasi

Contoh:

Organizer

```
Pending
↓

Under Review
↓

Approved
↓

Suspended
```

Event

```
Draft
↓

Submitted
↓

Under Review
↓

Approved
↓

Published
↓

Finished
```

Booking

```
Pending
↓

Paid
↓

Completed
```

---

# 5. Status Management Rules

Seluruh perubahan status wajib dilakukan melalui Service Layer.

Contoh:

```
OrganizerService::approve()

SpkService::approve()

EventService::publish()

BookingService::complete()
```

Controller tidak boleh mengubah status secara langsung.

Seluruh status wajib menggunakan Enum.

---

# 6. Feature Development Process

Urutan implementasi:

1. Analisis PRD
2. Validasi Workflow
3. Validasi Database
4. Migration
5. Model
6. Repository
7. Service
8. Form Request
9. Policy
10. Controller
11. View
12. Route
13. Feature Test
14. Dokumentasi

---

# 7. Database Rules

Seluruh perubahan database:

- menggunakan Migration
- menggunakan Foreign Key
- menggunakan Index
- menggunakan Timestamp
- menggunakan Transaction jika melibatkan banyak tabel

Database tidak boleh diubah secara manual.

---

# 8. Business Logic Rules

Business Logic hanya berada pada Service.

Dilarang berada pada:

- Controller
- Repository
- Blade
- Route
- Model (kecuali Accessor, Mutator, Scope)

---

# 9. Authorization Rules

Seluruh fitur harus menggunakan:

- Middleware
- Policy
- Gate (jika diperlukan)

Role minimum:

- Guest
- User
- Organizer
- Administrator

---

# 10. Validation Rules

Seluruh input menggunakan Form Request.

Validasi minimal mencakup:

- Required
- Nullable
- Type
- Format
- Unique
- Exists
- Relationship

---

# 11. Transaction Rules

Operasi berikut wajib menggunakan:

```php
DB::transaction()
```

- Approval Organizer
- Approval SPK
- Publish Event
- Booking
- Payment

---

# 12. Error Handling

Gunakan:

- Exception
- Logging
- User Friendly Message

Jangan tampilkan:

- Stack Trace
- SQL Error
- Internal Exception

di production.

---

# 13. Security Rules

Selalu gunakan:

- CSRF
- Authorization
- Validation
- Hash Password
- Policy
- Mass Assignment Protection

Upload file wajib divalidasi:

- tipe file
- ukuran file
- mime type

---

# 14. UI Development Rules

Seluruh tampilan mengikuti:

06_UI_GUIDELINE.md

Gunakan:

- Blade Component
- Layout
- Reusable Partial

Hindari duplikasi.

---

# 15. Performance Rules

Selalu:

- eager loading
- pagination
- indexing
- query optimization

Hindari:

- query dalam loop
- N+1 Query

---

# 16. File Organization

Setiap fitur memiliki struktur:

```
Controller

↓

Request

↓

Service

↓

Repository

↓

Model

↓

Policy

↓

View
```

Jangan mencampur tanggung jawab antar layer.

---

# 17. Activity Log Rules

Seluruh aktivitas penting wajib dicatat.

Minimal:

- Login
- Logout
- Organizer Approval
- SPK Approval
- Event Approval
- Publish Event
- Booking
- Payment

Activity Log digunakan sebagai audit trail.

---

# 18. Refactoring Rules

Refactoring diperbolehkan apabila:

- meningkatkan readability
- mengurangi duplikasi
- meningkatkan performa
- mempermudah testing

Refactoring tidak boleh mengubah Business Rule.

---

# 19. Testing Rules

Minimal dilakukan:

Feature Test

- Authentication
- Organizer
- SPK
- Event
- Ticket
- Booking
- Transaction

Unit Test

- Service Layer
- Business Rule kompleks

Bug harus diperbaiki sebelum Merge.

---

# 20. Change Management

Perubahan berikut wajib memperbarui dokumentasi:

- Workflow
- Database Schema
- ERD
- Route
- Module Specification
- Backlog
- Business Rule

Dokumentasi harus selalu sinkron dengan implementasi.

---

# 21. AI Development Rules

AI Assistant wajib:

- membaca seluruh dokumentasi terkait sebelum menghasilkan kode
- tidak membuat asumsi baru
- mengikuti Architecture
- mengikuti Coding Standards
- mengikuti Workflow
- menjelaskan dampak perubahan lintas modul

AI tidak boleh:

- mengubah struktur proyek tanpa persetujuan
- mengubah Business Rule tanpa memperbarui dokumentasi

---

# 22. Definition of Done

Sebuah fitur dianggap selesai apabila:

- sesuai PRD
- sesuai Workflow
- sesuai Database Schema
- sesuai Module Specification
- mengikuti Coding Standards
- mengikuti Development Rules
- lolos Feature Test
- lolos Code Review
- dokumentasi diperbarui

---

# 23. References

- 01_PRD.md
- 03_ARCHITECTURE.md
- 04_DATABASE.md
- 05_WORKFLOW.md
- 06_UI_GUIDELINE.md
- 07_CODING_STANDARDS.md
- 08_BACKLOG.md
- 10_ROUTES.md
- 11_ERD.md
- 12_DATABASE_SCHEMA.md
- 13_MODULE_SPECIFICATION.md
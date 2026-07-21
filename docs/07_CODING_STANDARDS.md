# CIREVA

# 07_CODING_STANDARDS.md

**Version:** 1.1  
**Status:** Ready for Development

---

# Purpose

Dokumen ini mendefinisikan standar penulisan kode untuk seluruh proyek CIREVA.

Semua developer dan AI Assistant wajib mengikuti aturan ini agar implementasi tetap:

- Konsisten
- Mudah dipelihara
- Aman
- Mudah dikembangkan
- Sesuai Best Practice Laravel

---

# 1. General Principles

Seluruh kode harus memenuhi prinsip berikut:

- Readability
- Maintainability
- Scalability
- Security
- Reusability
- Simplicity
- Consistency

Utamakan kode yang mudah dipahami daripada kode yang terlalu kompleks.

---

# 2. PHP Standard

Gunakan standar:

- PSR-1
- PSR-12
- PSR-4

Gunakan fitur PHP 8.3+ apabila sesuai, seperti:

- Constructor Property Promotion
- Readonly Property
- Enum
- Match Expression
- Named Arguments

---

# 3. Laravel Convention

Gunakan konvensi Laravel sebisa mungkin.

Prioritaskan penggunaan:

- Resource Controller
- Route Model Binding
- Form Request
- Eloquent Relationship
- Policy
- Notification
- Event & Listener
- Queue
- Database Transaction

Jangan membuat solusi khusus apabila Laravel telah menyediakan mekanisme yang sesuai.

---

# 4. Project Structure

```text
app/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   └── Requests/
├── Models/
├── Services/
├── Repositories/
├── Policies/
├── Events/
├── Listeners/
├── Notifications/
├── Enums/
├── Traits/
└── Support/

resources/
├── views/
├── css/
└── js/

routes/
database/
tests/
```

---

# 5. Naming Convention

## Class

PascalCase

```
EventService
BookingRepository
OrganizerController
```

---

## Method

camelCase

```
approveEvent()

createBooking()

generateAgreement()
```

---

## Variable

camelCase

```
$event

$ticketQuota
```

---

## Constant

UPPER_SNAKE_CASE

```
MAX_UPLOAD_SIZE

DEFAULT_STATUS
```

---

## Enum

Gunakan PascalCase.

```
EventStatus

BookingStatus

OrganizerStatus
```

---

## Database

Table

```
event_categories
```

Column

```
organizer_profile_id
```

Migration

```
create_events_table
```

---

# 6. Controller Rules

Controller hanya bertanggung jawab untuk:

- menerima Request
- memanggil Service
- mengembalikan Response

Controller tidak boleh:

- menjalankan query kompleks
- mengubah status data
- menjalankan business rule
- mengakses Repository secara langsung

Seluruh business process dilakukan melalui Service.

---

# 7. Form Request Rules

Semua validasi input wajib menggunakan Form Request.

Hindari:

```php
$request->validate(...)
```

Gunakan:

```
StoreEventRequest

UpdateBookingRequest
```

---

# 8. Service Rules

Seluruh business logic berada di Service.

Service harus:

- reusable
- stateless
- mudah diuji
- tidak bergantung pada HTTP Request

Semua perubahan status dilakukan melalui Service.

Contoh:

```
EventService::approve()

OrganizerService::verify()

BookingService::complete()
```

---

# 9. Repository Rules

Repository hanya bertanggung jawab terhadap akses data.

Repository tidak boleh:

- redirect
- validasi
- business rule
- mengakses Request

---

# 10. Model Rules

Model hanya digunakan untuk:

- Relationship
- Scope
- Accessor
- Mutator
- Cast

Business Logic tidak ditempatkan pada Model.

---

# 11. Route Rules

Gunakan Resource Route apabila sesuai.

```
Route::resource('events', EventController::class);
```

Gunakan Route Name yang konsisten.

```
events.index

events.store

events.update
```

---

# 12. Validation Rules

Gunakan:

- Form Request
- Rule Object
- Custom Rule (jika diperlukan)

Validation harus berada di satu tempat.

---

# 13. Database Rules

Gunakan:

- Migration
- Foreign Key
- Index
- Timestamp
- Soft Delete (bila diperlukan)

Database tidak boleh diubah secara manual.

---

# 14. Query Rules

Gunakan:

- Eloquent
- Relationship
- Scope

Gunakan Query Builder apabila benar-benar diperlukan.

Hindari:

- N+1 Query

Gunakan:

```php
with()
```

untuk eager loading.

---

# 15. Transaction Rules

Operasi berikut wajib menggunakan:

```php
DB::transaction()
```

- Booking
- Payment
- Approval Organizer
- Approval SPK
- Approval Event

---

# 16. Status Management Rules

Seluruh status sistem menggunakan Enum.

Contoh:

```
OrganizerStatus

SpkStatus

EventStatus

BookingStatus

TransactionStatus
```

Dilarang menggunakan string literal.

Jangan:

```php
if ($event->status == 'approved')
```

Gunakan:

```php
if ($event->status === EventStatus::APPROVED)
```

---

# 17. Exception Handling

Gunakan Exception apabila:

- proses gagal
- resource tidak ditemukan
- validasi bisnis gagal

Jangan menggunakan:

```
die()

exit()
```

---

# 18. Logging Rules

Gunakan Log untuk:

- Error
- Warning
- Approval
- Payment
- Booking

Jangan menggunakan:

```
dd()

dump()

ray()
```

di production.

---

# 19. Security Rules

Selalu gunakan:

- CSRF
- Authorization
- Policy
- Gate
- Validation
- Hash Password
- Mass Assignment Protection

Jangan pernah menyimpan password dalam bentuk plain text.

---

# 20. Authorization Rules

Semua fitur yang mengubah data wajib menggunakan Policy.

Contoh:

```
EventPolicy

BookingPolicy

OrganizerPolicy

SpkPolicy
```

---

# 21. Blade Guidelines

Gunakan Blade Component untuk elemen yang berulang.

Misalnya:

- Button
- Card
- Modal
- Badge
- Alert
- Input

Hindari duplikasi markup.

---

# 22. Tailwind Guidelines

Gunakan Utility Class Tailwind.

Hindari:

- Inline Style
- CSS yang duplikat

---

# 23. Comments

Komentar hanya digunakan untuk:

- algoritma kompleks
- business rule penting

Jangan menjelaskan kode yang sudah jelas.

---

# 24. Testing Guidelines

Minimal pengujian dilakukan pada:

- Authentication
- Organizer
- SPK
- Event
- Booking
- Transaction

Business Logic wajib memiliki Unit Test apabila kompleks.

---

# 25. Git Commit Convention

Gunakan Conventional Commit.

```
feat:

fix:

refactor:

docs:

style:

test:

chore:
```

---

# 26. Code Review Checklist

Sebelum Merge:

- Mengikuti PRD
- Mengikuti Workflow
- Mengikuti Database Schema
- Mengikuti Module Specification
- Tidak ada Business Logic di Controller
- Menggunakan Form Request
- Menggunakan Service Layer
- Menggunakan Policy
- Tidak ada duplikasi kode
- Tidak ada N+1 Query
- Tidak ada `dd()`
- Lolos Testing

---

# 27. References

- 02_AI_CONTEXT.md
- 03_ARCHITECTURE.md
- 04_DATABASE.md
- 05_WORKFLOW.md
- 09_DEVELOPMENT_RULES.md
- 12_DATABASE_SCHEMA.md
- 13_MODULE_SPECIFICATION.md
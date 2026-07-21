# CIREVA

# 02_AI_CONTEXT.md

> **Purpose**
>
> Dokumen ini menjadi sumber konteks utama bagi AI Assistant selama proses pengembangan CIREVA. AI harus menggunakan dokumen ini sebagai acuan sebelum membuat kode, melakukan refactor, atau memberikan rekomendasi.

---

# 1. Project Identity

## Project Name

CIREVA (Cirebon Event & Virtual Application)

## Project Type

Web-based Cultural Event Management System

## Development Method

Agile Scrum

## Framework

Laravel 13

## Frontend

Blade

Tailwind CSS

Alpine.js

## Database

MySQL

---

# 2. AI Role

AI bertindak sebagai **Senior Laravel Software Engineer** yang membantu proses pengembangan proyek.

AI bertanggung jawab untuk:

- memahami arsitektur sistem
- menjaga konsistensi kode
- menghasilkan kode yang bersih
- mengikuti seluruh workflow proyek
- tidak membuat asumsi di luar dokumentasi

AI **bukan** pengambil keputusan bisnis.

Apabila terdapat konflik antara prompt pengguna dan dokumentasi proyek, AI harus menjelaskan konflik tersebut dan meminta konfirmasi sebelum mengubah aturan bisnis.

---

# 3. Primary References

Urutan prioritas dokumen adalah:

1. 01_PRD.md
2. 05_WORKFLOW.md
3. 12_DATABASE_SCHEMA.md
4. 13_MODULE_SPECIFICATION.md
5. 03_ARCHITECTURE.md
6. 04_DATABASE.md
7. 10_ROUTES.md
8. 11_ERD.md
9. 07_CODING_STANDARDS.md
10. 09_DEVELOPMENT_RULES.md

Jika terjadi konflik, gunakan dokumen dengan prioritas lebih tinggi sebagai acuan.

---

# 4. Project Goals

AI harus memahami bahwa tujuan utama CIREVA adalah:

- mengelola organizer
- mengelola SPK
- mengelola event budaya
- mengelola jadwal event
- mengelola tiket
- mengelola booking
- mengelola transaksi
- menghasilkan laporan

---

# 5. Core Business Rules

AI harus selalu mengikuti aturan berikut:

- Organizer wajib disetujui Admin sebelum dapat membuat Event.
- SPK wajib disetujui sebelum Event dapat diajukan.
- Event harus melalui proses approval sebelum dipublikasikan.
- Tiket hanya dapat dijual jika Event telah Published.
- Booking hanya dapat dilakukan pada tiket yang aktif.
- Pembayaran yang berhasil akan menghasilkan E-Ticket.
- Semua perubahan status harus mengikuti workflow yang telah ditentukan.

AI tidak boleh melewati workflow hanya demi mempermudah implementasi.

---

# 6. Development Principles

Selalu utamakan:

- Readability
- Maintainability
- Scalability
- Security
- Reusability
- Simplicity

---

# 7. Laravel Architecture

AI harus mengikuti struktur berikut:

```text
Route
    ↓
Middleware
    ↓
Controller
    ↓
Form Request
    ↓
Service
    ↓
Repository
    ↓
Model
    ↓
Database
```

Business logic tidak boleh ditempatkan di Controller.

---

# 8. Folder Responsibilities

## Controller

- menerima request
- memanggil service
- mengembalikan response

Tidak boleh berisi business logic.

---

## Service

Berisi seluruh business logic.

---

## Repository

Berisi akses database.

---

## Model

Berisi relasi Eloquent dan scope.

---

## Policy

Berisi authorization.

---

## Middleware

Berisi validasi akses.

---

# 9. Coding Expectations

AI harus menghasilkan kode yang:

- modular
- mudah dibaca
- konsisten
- menggunakan dependency injection
- memanfaatkan Eloquent Relationship
- menghindari duplikasi

---

# 10. UI Principles

AI harus mengikuti UI Guideline.

Prioritas:

- sederhana
- konsisten
- responsif
- mudah digunakan

Framework UI:

- Blade
- Tailwind CSS
- Alpine.js

---

# 11. Naming Convention

Gunakan:

Class

```
OrganizerService
```

Controller

```
EventController
```

Model

```
Booking
```

Migration

```
create_events_table
```

Variable

```
$eventSchedule
```

Method

```
approveEvent()
```

---

# 12. Response Rules

Saat menghasilkan kode:

- jelaskan tujuan perubahan
- jangan mengubah file di luar ruang lingkup permintaan tanpa alasan
- gunakan praktik Laravel modern
- pertahankan kompatibilitas dengan Laravel 13

---

# 13. Refactoring Rules

Saat melakukan refactor:

- jangan mengubah perilaku bisnis
- jangan mengubah struktur database tanpa alasan
- jangan menghapus fitur tanpa konfirmasi
- utamakan peningkatan keterbacaan dan maintainability

---

# 14. Things AI Must Avoid

AI tidak boleh:

- membuat business rule baru
- menambah kolom database tanpa alasan
- mengubah workflow bisnis
- mengubah status workflow
- mengubah route publik tanpa kebutuhan
- mengabaikan validasi
- melakukan query berulang (N+1) jika dapat dihindari

---

# 15. Preferred Laravel Features

Gunakan jika sesuai:

- Form Request
- Policy
- Resource Controller
- Route Model Binding
- Eloquent Relationship
- Service Layer
- Repository Pattern
- Notification
- Event & Listener
- Database Transaction
- Queue (jika diperlukan)

---

# 16. Development Priority

Prioritas implementasi:

1. Authentication
2. Organizer
3. SPK
4. Event
5. Calendar
6. Ticket
7. Booking
8. Transaction
9. Report
10. Notification

---

# 17. Communication Style

AI harus:

- memberikan jawaban teknis yang jelas
- menggunakan istilah Laravel yang benar
- menghindari asumsi
- meminta klarifikasi jika informasi kurang
- menjaga konsistensi dengan dokumentasi proyek

---

# 18. Success Criteria

Implementasi dianggap berhasil apabila:

- sesuai PRD
- sesuai Workflow
- sesuai Database Schema
- sesuai Architecture
- lolos validasi Laravel
- mudah dipelihara
- konsisten dengan seluruh modul

---

# 19. References

- 00_PROJECT.md
- 01_PRD.md
- 03_ARCHITECTURE.md
- 04_DATABASE.md
- 05_WORKFLOW.md
- 07_CODING_STANDARDS.md
- 09_DEVELOPMENT_RULES.md
- 10_ROUTES.md
- 11_ERD.md
- 12_DATABASE_SCHEMA.md
- 13_MODULE_SPECIFICATION.md
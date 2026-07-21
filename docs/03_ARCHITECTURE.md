# CIREVA

# 03_ARCHITECTURE.md

**Version:** 1.0  
**Status:** Final Draft

---

# 1. Purpose

Dokumen ini mendefinisikan arsitektur aplikasi CIREVA sebagai acuan implementasi Laravel 13. Seluruh pengembangan harus mengikuti arsitektur ini agar konsisten, mudah dipelihara, dan mudah dikembangkan.

---

# 2. Technology Stack

## Backend
- Laravel 13
- PHP 8.3+
- MySQL 8

## Frontend
- Blade
- Tailwind CSS
- Alpine.js
- Vite

## Development
- Composer
- Git

---

# 3. Architectural Principles

- Modular
- SOLID
- PSR-12
- Clean Code
- Separation of Concerns
- Thin Controller
- Business Logic di Service Layer
- Data Access di Repository Layer

---

# 4. Layer Architecture

```text
Browser
    │
Blade View
    │
Routes
    │
Middleware
    │
Controller
    │
Service
    │
Repository
    │
Eloquent Model
    │
MySQL
```

---

# 5. Project Structure

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
├── Observers/
├── Notifications/
└── Support/

resources/
├── views/
├── css/
└── js/

routes/
├── web.php
├── auth.php
└── organizer.php
```

---

# 6. Module Architecture

- Authentication
- Organizer
- SPK
- Event
- Calendar
- Ticket
- Booking
- Transaction
- Report
- Notification
- Master Data

Setiap modul memiliki:
- Controller
- Form Request
- Service
- Repository
- Model
- Policy

---

# 7. Request Flow

```text
HTTP Request
    │
Route
    │
Middleware
    │
Policy
    │
Controller
    │
Form Request Validation
    │
Service
    │
Repository
    │
Model
    │
Database
```

---

# 8. Middleware

- auth
- verified
- role:admin
- role:organizer
- EnsureProfileCompleted
- EnsureSpkApproved
- EnsureOrganizerVerified

---

# 9. Services

Contoh service:

- AuthenticationService
- OrganizerService
- SpkService
- EventService
- CalendarService
- TicketService
- BookingService
- TransactionService
- ReportService
- NotificationService

---

# 10. Repository

Contoh repository:

- UserRepository
- OrganizerRepository
- SpkRepository
- EventRepository
- CalendarRepository
- TicketRepository
- BookingRepository
- TransactionRepository

Repository hanya bertanggung jawab terhadap akses data.

---

# 11. Events & Listeners

Events:
- OrganizerApproved
- SpkApproved
- EventPublished
- BookingCreated
- PaymentCompleted

Listeners:
- SendNotification
- GenerateETicket
- WriteActivityLog
- UpdateReport

---

# 12. Policies

- EventPolicy
- TicketPolicy
- BookingPolicy
- CalendarPolicy
- OrganizerPolicy
- SpkPolicy

---

# 13. Error Handling

- Form Request Validation
- Exception Handler
- Database Transaction
- Logging

---

# 14. Security

- Authentication
- Authorization
- CSRF Protection
- Mass Assignment Protection
- Rate Limiting
- Activity Logging

---

# 15. Coding Standards

- Satu controller menangani satu resource.
- Controller tidak berisi business logic.
- Service tidak mengakses Request secara langsung.
- Repository tidak berisi business rule.
- Gunakan Dependency Injection.
- Gunakan Eloquent Relationship.

---

# 16. References

- 01_PRD.md
- 04_DATABASE.md
- 05_WORKFLOW.md
- 11_ERD.md
- 12_DATABASE_SCHEMA.md

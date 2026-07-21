# CIREVA

# 10_ROUTES.md

**Version:** 1.0  
**Status:** Final Draft

---

# Purpose

Dokumen ini mendefinisikan struktur route aplikasi CIREVA sebagai acuan implementasi `routes/web.php`, middleware, controller, dan authorization.

---

# Route Groups

## Public Routes

| Method | URI | Route Name | Controller | Middleware |
|---|---|---|---|---|
| GET | / | home | HomeController@index | web |
| GET | /events | events.index | EventController@index | web |
| GET | /events/{slug} | events.show | EventController@show | web |
| GET | /calendar | calendar.index | CalendarController@index | web |
| GET | /about | about | PageController@about | web |

---

## Authentication

| Method | URI | Route Name |
|---|---|---|
| GET | /login | login |
| POST | /login | login.store |
| GET | /register | register |
| POST | /register | register.store |
| POST | /logout | logout |
| GET | /forgot-password | password.request |
| POST | /forgot-password | password.email |
| GET | /reset-password/{token} | password.reset |

Middleware: guest / auth / verified

---

## User Routes

Prefix: `/user`

Middleware:
- auth
- verified
- role:user

| URI |
|---|
| /dashboard |
| /profile |
| /bookings |
| /bookings/{booking} |
| /payments |
| /tickets |
| /notifications |

---

## Organizer Routes

Prefix: `/organizer`

Middleware:
- auth
- verified
- role:organizer
- EnsureProfileCompleted
- EnsureSpkApproved
- EnsureOrganizerVerified

### Dashboard

- GET /dashboard

### Profile

- GET /profile
- PUT /profile

### Documents

- GET /documents
- POST /documents

### SPK

- GET /spk
- POST /spk/sign
- GET /spk/download

### Event

- GET /events
- GET /events/create
- POST /events
- GET /events/{event}
- GET /events/{event}/edit
- PUT /events/{event}
- DELETE /events/{event}

### Calendar Management

- GET /calendar
- GET /calendar/create
- POST /calendar
- GET /calendar/{schedule}/edit
- PUT /calendar/{schedule}
- DELETE /calendar/{schedule}

### Ticket

- GET /tickets
- GET /tickets/create
- POST /tickets
- PUT /tickets/{ticket}
- DELETE /tickets/{ticket}

### Booking Monitoring

- GET /bookings

### Reports

- GET /reports

---

## Administrator Routes

Prefix: `/admin`

Middleware:
- auth
- verified
- role:admin

### Dashboard
- GET /dashboard

### Organizer
- Resource organizer-verifications

### SPK
- GET /spk
- PUT /spk/{agreement}/approve
- PUT /spk/{agreement}/reject

### Event Approval
- GET /events
- PUT /events/{event}/approve
- PUT /events/{event}/reject

### Calendar
- Resource calendars

### Ticket
- Resource tickets

### Payment
- GET /transactions
- PUT /transactions/{transaction}/verify

### Master Data
- Resource event-categories
- Resource event-locations
- Resource users

### Reports
- GET /reports

---

# Route Naming Convention

- resource.index
- resource.create
- resource.store
- resource.show
- resource.edit
- resource.update
- resource.destroy

---

# Controller Mapping

- HomeController
- EventController
- CalendarController
- ProfileController
- OrganizerController
- SpkController
- TicketController
- BookingController
- TransactionController
- ReportController
- NotificationController
- AdminController

---

# References

- 01_PRD.md
- 03_ARCHITECTURE.md
- 04_DATABASE.md
- 05_WORKFLOW.md
- 11_ERD.md

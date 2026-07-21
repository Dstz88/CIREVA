# Administrator Module

---

# Role

You are responsible for implementing the Administrator Module of the CIREVA project.

Administrator is responsible for managing the entire system.

---

# Scope

Implement

- Dashboard
- Organizer Verification
- SPK Verification
- Event Verification
- Calendar Monitoring
- Ticket Monitoring
- Booking Monitoring
- Transaction Monitoring
- User Management
- Role Management
- Reports
- System Configuration

---

# Required References

00_PROJECT.md

01_PRD.md

05_WORKFLOW.md

10_ROUTES.md

13_MODULE_SPECIFICATION.md

---

# Required Components

Generate when needed

- Controller
- Service
- Repository
- Policy
- Form Request
- Blade View
- Feature Test

---

# Business Rules

Administrator can

- approve organizer
- reject organizer
- approve SPK
- reject SPK
- approve event
- reject event
- suspend organizer
- restore organizer

Every approval must be logged.

Every rejection must store notes.

---

# Dashboard

Dashboard should display

- Total Users
- Total Organizers
- Pending Organizer
- Pending SPK
- Pending Event
- Published Events
- Active Tickets
- Total Bookings
- Revenue Summary

---

# Development Rules

Always

Use Policy

Use Service Layer

Use Repository Pattern

Use Enum Status

Use DB Transaction when changing approval status.

---

# Do Not

Do not bypass approval workflow.

Do not delete historical data.
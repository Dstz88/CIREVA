# Ticket Module

---

# Role

You are responsible for implementing the Ticket Management Module.

---

# Scope

Implement

- Create Ticket Type
- Update Ticket
- Delete Ticket
- Ticket Quota
- Ticket Price
- Ticket Sales Period
- Ticket Availability

---

# Required References

05_WORKFLOW.md

12_DATABASE_SCHEMA.md

13_MODULE_SPECIFICATION.md

---

# Required Components

- Controller
- Service
- Repository
- Form Request
- Policy
- Blade View
- Feature Test

---

# Business Rules

Ticket can only be created if

- Organizer is Approved
- SPK is Approved
- Event is Published
- Event Schedule is Valid

Ticket cannot be sold when

- Event Finished
- Event Cancelled
- Quota is Full
- Sales Period Ended

Quota must never become negative.

---

# Development Rules

Use

- TicketStatus Enum
- DB Transaction
- Repository Pattern
- Service Layer

Always lock quota updates inside transaction.

---

# Expected Output

Generate complete Ticket Module.

---

# Do Not

Do not implement Booking.

Do not implement Payment.
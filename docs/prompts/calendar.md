# Calendar Module

---

# Role

Implement Calendar Module.

---

# Scope

Implement

- Month View
- Week View
- Day View
- Timeline View
- Create Schedule
- Update Schedule
- Delete Schedule
- Conflict Detection
- Venue Availability

---

# References

05_WORKFLOW.md

11_ERD.md

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

---

# Business Rules

Schedule must

- belong to one Event
- not overlap another schedule at the same location
- respect Event status

Booked schedule cannot be deleted.

---

# Development Rules

Conflict detection belongs inside Service Layer.

Always validate datetime.

Always use transaction when updating schedule.

---

# Do Not

Do not implement Ticket.

Do not implement Booking.
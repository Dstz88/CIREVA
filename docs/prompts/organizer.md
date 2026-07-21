# Organizer Module

---

# Role

Implement Organizer Module.

---

# Scope

Implement

- Organizer Dashboard
- Organizer Profile
- Edit Profile
- Upload Documents
- Verification Status

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

---

# Business Rules

Organizer must

- complete profile
- upload required documents

Organizer status controls dashboard access.

Status transition

Pending

↓

Under Review

↓

Approved

↓

Rejected

↓

Suspended

---

# Development Rules

Always

- use Service
- use Repository
- use Policy

Status changes only inside Service.

---

# Do Not

Do not implement SPK.

Do not implement Event.
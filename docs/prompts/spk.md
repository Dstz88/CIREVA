# SPK Module

---

# Role

Implement Cooperation Agreement (SPK) Module.

---

# Scope

Implement

- Generate SPK
- View SPK
- Download SPK
- Accept Agreement
- Digital Signature
- SPK History

---

# References

05_WORKFLOW.md

12_DATABASE_SCHEMA.md

13_MODULE_SPECIFICATION.md

---

# Status Workflow

Draft

↓

Generated

↓

Pending Signature

↓

Signed

↓

Under Review

↓

Approved

↓

Rejected

↓

Expired

---

# Required Components

- Controller
- Service
- Repository
- Policy
- Blade View

---

# Business Rules

Only Approved Organizer can continue SPK process.

Only Approved SPK allows Event submission.

---

# Development Rules

Always use

- Enum
- DB Transaction
- Service Layer

---

# Do Not

Do not implement Event Approval.

Do not modify Organizer module.
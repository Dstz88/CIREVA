# Authentication Module

---

# Role

You are responsible for implementing the Authentication Module of the CIREVA project.

---

# Scope

Implement only:

- Register User
- Register Organizer
- Login
- Logout
- Forgot Password
- Reset Password
- Email Verification

---

# Required References

01_PRD.md

05_WORKFLOW.md

10_ROUTES.md

12_DATABASE_SCHEMA.md

13_MODULE_SPECIFICATION.md

---

# Required Components

Generate when needed:

- Controller
- Service
- Repository
- Form Request
- Policy
- Routes
- Blade View
- Feature Test

---

# Business Rules

- Email must be unique.
- Password must be hashed.
- Default role must follow documentation.
- Organizer registration only creates account.
- Organizer Profile is completed after registration.

---

# Development Rules

Always

- Use Form Request
- Use Service Layer
- Use Repository
- Use Laravel Authentication
- Use Middleware
- Use Policy

---

# Expected Output

Generate production-ready Laravel code.

---

# Do Not

Do not implement Organizer Verification.

Do not implement Event.

Do not modify unrelated modules.
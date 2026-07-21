# State Machine

Version: 1.0

Project: CIREVA (Cirebon Event & Virtual Application)

Last Updated: YYYY-MM-DD

---

# Purpose

This document defines every state transition within the CIREVA system.

Every status change must follow this document.

Status transitions are enforced only through the Service Layer.

Direct status updates from Controllers or Repositories are prohibited.

---

# General Rules

- Every module has its own state machine.
- Status transitions must be validated.
- Invalid transitions must throw a Business Exception.
- Every transition should be logged when required.
- All status values should use Laravel Enum.

---

# Organizer State Machine

## States

Pending

↓

Profile Completed

↓

Documents Uploaded

↓

SPK Generated

↓

Agreement Accepted

↓

Signed

↓

Under Review

↓

Approved

↓

Suspended

↓

Rejected

---

## Allowed Transition

| From | To |
|------|----|
| Pending | Profile Completed |
| Profile Completed | Documents Uploaded |
| Documents Uploaded | SPK Generated |
| SPK Generated | Agreement Accepted |
| Agreement Accepted | Signed |
| Signed | Under Review |
| Under Review | Approved |
| Under Review | Rejected |
| Approved | Suspended |
| Suspended | Approved |

---

## Business Rules

Organizer can create Event only after:

- Organizer Status = Approved
- SPK Status = Approved

---

# SPK State Machine

## States

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

## Allowed Transition

| From | To |
|------|----|
| Draft | Generated |
| Generated | Pending Signature |
| Pending Signature | Signed |
| Signed | Under Review |
| Under Review | Approved |
| Under Review | Rejected |
| Approved | Expired |

---

## Business Rules

Approved SPK is required before Event Submission.

---

# Event State Machine

## States

Draft

↓

Submitted

↓

Under Review

↓

Revision Required

↓

Approved

↓

Published

↓

Ongoing

↓

Finished

↓

Archived

↓

Cancelled

---

## Allowed Transition

| From | To |
|------|----|
| Draft | Submitted |
| Submitted | Under Review |
| Under Review | Approved |
| Under Review | Revision Required |
| Revision Required | Submitted |
| Approved | Published |
| Published | Ongoing |
| Ongoing | Finished |
| Finished | Archived |
| Published | Cancelled |
| Ongoing | Cancelled |

---

## Business Rules

Publish requires:

- Organizer Approved
- SPK Approved
- Calendar Scheduled

---

# Calendar State Machine

## States

Draft

↓

Scheduled

↓

Published

↓

Ongoing

↓

Finished

↓

Cancelled

---

## Allowed Transition

| From | To |
|------|----|
| Draft | Scheduled |
| Scheduled | Published |
| Published | Ongoing |
| Ongoing | Finished |
| Scheduled | Cancelled |
| Published | Cancelled |

---

## Business Rules

Cannot publish if schedule conflicts with another approved event.

---

# Ticket State Machine

## States

Draft

↓

Active

↓

Sold Out

↓

Closed

↓

Archived

---

## Allowed Transition

| From | To |
|------|----|
| Draft | Active |
| Active | Sold Out |
| Active | Closed |
| Sold Out | Closed |
| Closed | Archived |

---

## Business Rules

Ticket becomes Active only when:

- Event Published
- Sales Period Started

---

# Booking State Machine

## States

Pending

↓

Confirmed

↓

Issued

↓

Used

↓

Cancelled

↓

Expired

---

## Allowed Transition

| From | To |
|------|----|
| Pending | Confirmed |
| Confirmed | Issued |
| Issued | Used |
| Pending | Cancelled |
| Confirmed | Cancelled |
| Pending | Expired |

---

## Business Rules

Quota decreases when booking is confirmed.

Quota returns if booking is cancelled according to policy.

---

# Transaction State Machine

## States

Pending

↓

Paid

↓

Cancelled

↓

Refund Requested

↓

Refunded

↓

Expired

---

## Allowed Transition

| From | To |
|------|----|
| Pending | Paid |
| Pending | Cancelled |
| Pending | Expired |
| Paid | Refund Requested |
| Refund Requested | Refunded |

---

## Business Rules

Each Transaction belongs to one Booking.

---

# Notification State Machine

## States

Queued

↓

Sending

↓

Sent

↓

Failed

---

## Allowed Transition

| From | To |
|------|----|
| Queued | Sending |
| Sending | Sent |
| Sending | Failed |

---

# State Ownership

| Module | Enum |
|---------|------|
| Organizer | OrganizerStatus |
| SPK | SpkStatus |
| Event | EventStatus |
| Calendar | CalendarStatus |
| Ticket | TicketStatus |
| Booking | BookingStatus |
| Transaction | TransactionStatus |
| Notification | NotificationStatus |

---

# Transition Rules

All transitions must:

- Be validated in Service Layer.
- Use Laravel Enum.
- Be logged if required.
- Follow documented workflow.
- Reject invalid transitions.

---

# Definition of Done

State Machine implementation is complete when:

- Every module has a defined state machine.
- Every status uses Enum.
- All transitions are validated.
- Invalid transitions throw exceptions.
- Services enforce state transitions.
- Policies respect current state.
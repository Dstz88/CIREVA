# CIREVA

# 05_WORKFLOW.md

**Version:** 1.0  
**Status:** Final Draft

---

# Purpose

Dokumen ini mendefinisikan alur bisnis (business workflow) dan state transition seluruh modul utama CIREVA. Workflow ini menjadi acuan implementasi Service, Repository, Controller, Middleware, Policies, dan Business Rules.

---

# 1. Organizer Onboarding Workflow

```text
Guest
    │
    ▼
Register Organizer
    │
    ▼
Email Verification
    │
    ▼
Complete Organizer Profile
    │
    ▼
Upload Supporting Documents
    │
    ▼
Generate SPK
    │
    ▼
Read Agreement
    │
    ▼
Accept Agreement
    │
    ▼
Digital Signature
    │
    ▼
Waiting Verification
    │
    ▼
Admin Review
    │
 ┌──┴──────────────┐
 ▼                 ▼
Approved       Revision / Rejected
 │
 ▼
Organizer Dashboard Active
```

Business Rules:
- Organizer tidak dapat membuat Event sebelum status Organizer = Approved.
- SPK harus ditandatangani sebelum proses verifikasi Admin.

---

# 2. SPK Workflow

Status:

Draft → Generated → Pending Signature → Signed → Under Review → Revision Required → Approved / Rejected / Expired

Rules:
- SPK dibuat otomatis setelah profil dan dokumen lengkap.
- SPK Approved menjadi syarat utama aktivasi Organizer.

---

# 3. Event Workflow

```text
Draft
  │
  ▼
Submitted
  │
  ▼
Under Review
 ┌──┴───────────┐
 ▼              ▼
Approved   Revision Required
 │
 ▼
Published
 │
 ▼
Ongoing
 │
 ▼
Finished
 │
 ▼
Archived
```

Rules:
- Event hanya dapat dipublikasikan setelah Organizer dan SPK Approved.
- Event harus memiliki minimal satu jadwal aktif.

---

# 4. Calendar Management Workflow

```text
Create Schedule
      │
      ▼
Conflict Validation
      │
 ┌────┴─────┐
 ▼          ▼
Valid    Conflict
 │          │
 ▼          ▼
Scheduled  Revision
 │
 ▼
Published
 │
 ▼
Ongoing
 │
 ▼
Finished
```

Rules:
- Jadwal pada lokasi dan waktu yang sama tidak boleh bentrok.
- Jadwal dengan booking tidak dapat dihapus.
- Perubahan jadwal Published memerlukan persetujuan Admin.

---

# 5. Ticket Workflow

Create Ticket
→ Validation
→ Active
→ On Sale
→ Sold Out / Closed

Rules:
- Tiket hanya aktif jika Event Published.
- Kuota tidak boleh bernilai negatif.

---

# 6. Booking Workflow

Search Event
→ Select Ticket
→ Checkout
→ Booking Created
→ Payment
→ Payment Verification
→ Ticket Issued
→ Download E-Ticket
→ Event Completed

Booking Status:
- Pending
- Paid
- Cancelled
- Completed
- Expired

---

# 7. Payment Workflow

Pending Payment
→ Upload Proof (jika diperlukan)
→ Admin Verification
→ Success / Failed
→ Booking Updated

Rules:
- Booking menjadi Paid hanya jika transaksi Success.

---

# 8. Notification Workflow

Trigger:
- Organizer Approved
- SPK Approved
- Event Approved
- Booking Created
- Payment Success
- Event Reminder
- Event Cancelled

Semua notifikasi disimpan pada tabel `notifications`.

---

# 9. Reporting Workflow

Organizer:
Booking Data
→ Revenue Calculation
→ Report Generation

Administrator:
System Data
→ Aggregation
→ Report Generation
→ Export

---

# 10. State Summary

| Module | Main States |
|--------|-------------|
| Organizer | Registered, Verified, Active, Suspended |
| SPK | Draft, Signed, Approved, Rejected |
| Event | Draft, Submitted, Published, Finished |
| Calendar | Draft, Scheduled, Published, Finished |
| Ticket | Active, Sold Out, Closed |
| Booking | Pending, Paid, Completed |
| Transaction | Pending, Success, Failed |

---

# References

- 01_PRD.md
- 04_DATABASE.md
- 11_ERD.md
- 12_DATABASE_SCHEMA.md

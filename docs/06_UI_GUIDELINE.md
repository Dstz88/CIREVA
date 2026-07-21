# CIREVA

# 06_UI_GUIDELINE.md

> **Purpose**
>
> Dokumen ini mendefinisikan standar antarmuka pengguna (UI) CIREVA agar seluruh halaman memiliki tampilan yang konsisten, mudah digunakan, responsif, dan sesuai identitas visual proyek.

---

# 1. Design Philosophy

CIREVA menggunakan pendekatan desain yang:

- Clean
- Modern
- Professional
- Simple
- Responsive
- Accessible

Fokus utama adalah menyajikan informasi event budaya dengan jelas tanpa mengurangi kenyamanan pengguna.

---

# 2. Design Principles

Setiap halaman harus memenuhi prinsip berikut:

- Konsisten
- Mudah dipelajari
- Mudah dinavigasi
- Responsif
- Memiliki hierarki visual yang jelas
- Mengutamakan keterbacaan

---

# 3. Brand Identity

## Primary Color

```
#0E4A7B
```

Digunakan untuk:

- Button utama
- Link aktif
- Navbar
- Sidebar aktif

---

## Secondary Color

```
#D4A017
```

Digunakan untuk:

- Badge
- Highlight
- Ikon penting
- Aksen visual

---

## Success

```
#22C55E
```

---

## Warning

```
#F59E0B
```

---

## Danger

```
#EF4444
```

---

## Info

```
#3B82F6
```

---

## Background

```
#F8FAFC
```

---

## Surface

```
#FFFFFF
```

---

## Border

```
#E5E7EB
```

---

## Text

Primary

```
#111827
```

Secondary

```
#6B7280
```

---

# 4. Typography

Font Family

```
Plus Jakarta Sans
```

Fallback

```
sans-serif
```

---

## Font Weight

Light

```
300
```

Regular

```
400
```

Medium

```
500
```

Semi Bold

```
600
```

Bold

```
700
```

---

## Heading Scale

| Element | Size |
|----------|------|
| H1 | 36px |
| H2 | 30px |
| H3 | 24px |
| H4 | 20px |
| H5 | 18px |
| Body | 16px |
| Small | 14px |

---

# 5. Spacing System

Menggunakan sistem kelipatan **8px**.

```
4
8
16
24
32
40
48
64
80
96
```

---

# 6. Border Radius

Small

```
6px
```

Medium

```
10px
```

Large

```
16px
```

Extra Large

```
24px
```

---

# 7. Shadow

Card

```
shadow-sm
```

Dropdown

```
shadow-md
```

Modal

```
shadow-xl
```

---

# 8. Layout

Container

```
max-w-7xl
mx-auto
px-4
```

Content menggunakan grid Tailwind.

---

# 9. Responsive Breakpoint

| Device | Width |
|----------|-------|
| Mobile | <640px |
| Small | ≥640px |
| Medium | ≥768px |
| Large | ≥1024px |
| XL | ≥1280px |
| 2XL | ≥1536px |

---

# 10. Navigation

Guest:

- Home
- Event
- Kalender
- Tentang
- Login
- Register

Organizer:

- Dashboard
- Event
- Calendar
- Ticket
- Booking
- Report
- Profile

Administrator:

- Dashboard
- Organizer
- SPK
- Event
- Calendar
- Ticket
- Master Data
- Report

---

# 11. Button Guidelines

## Primary Button

Digunakan untuk aksi utama.

Contoh:

- Simpan
- Submit
- Login

---

## Secondary Button

Digunakan untuk aksi pendukung.

---

## Danger Button

Digunakan untuk:

- Delete
- Reject
- Cancel

---

## Outline Button

Digunakan untuk aksi ringan.

---

# 12. Form Guidelines

Semua form harus memiliki:

- Label
- Placeholder yang jelas
- Helper Text (jika diperlukan)
- Validasi
- Error Message
- Required Indicator

---

# 13. Card Guidelines

Setiap card memiliki:

- Judul
- Konten
- Padding konsisten
- Border halus
- Shadow ringan

---

# 14. Table Guidelines

Gunakan tabel untuk:

- Data organizer
- Event
- Booking
- Laporan

Ketentuan:

- Header jelas
- Sorting jika diperlukan
- Pagination
- Empty State
- Loading State

---

# 15. Badge Guidelines

Gunakan badge untuk status.

Contoh:

Approved

Hijau

Pending

Kuning

Rejected

Merah

Published

Biru

Finished

Abu-abu

---

# 16. Modal Guidelines

Modal digunakan untuk:

- Konfirmasi
- Form singkat
- Preview

Modal tidak digunakan untuk proses yang panjang.

---

# 17. Alert Guidelines

Jenis alert:

Success

Warning

Danger

Info

Setiap alert harus memiliki ikon dan pesan yang jelas.

---

# 18. Empty State

Jika data kosong:

- tampilkan ilustrasi sederhana (opsional)
- tampilkan pesan yang informatif
- sediakan tombol aksi jika relevan

Contoh:

"Belum ada event yang tersedia."

---

# 19. Loading State

Gunakan:

- Skeleton Loader
- Spinner
- Progress Indicator

Hindari halaman kosong saat proses berlangsung.

---

# 20. Accessibility

Minimal memenuhi:

- Kontras warna yang memadai
- Navigasi keyboard
- Focus state yang terlihat
- Label pada seluruh input
- Alt text untuk gambar
- Struktur heading yang benar

---

# 21. Icons

Gunakan satu pustaka ikon secara konsisten.

Rekomendasi:

- Heroicons
- Lucide Icons

Jangan mencampur beberapa pustaka ikon dalam satu halaman.

---

# 22. UI Consistency Rules

- Maksimal satu aksi utama (Primary Button) dalam satu area.
- Gunakan warna hanya untuk memberikan makna.
- Hindari penggunaan warna berlebihan.
- Semua halaman menggunakan layout yang konsisten.
- Gunakan komponen yang dapat digunakan kembali (reusable).
- Hindari hardcode style jika dapat menggunakan utility Tailwind.

---

# 23. References

- 00_PROJECT.md
- 01_PRD.md
- 03_ARCHITECTURE.md
- 05_WORKFLOW.md
- 07_CODING_STANDARDS.md
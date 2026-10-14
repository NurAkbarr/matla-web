# design.md: EduSphere Student Dashboard

Dokumen panduan desain untuk halaman **Dashboard Utama** platform belajar EduSphere. Dokumen ini menjadi acuan untuk designer dan developer agar tampilan konsisten.

- **Produk:** EduSphere (tagline: *Learn · Grow · Succeed*)
- **Halaman:** Dashboard siswa
- **Gaya:** modern, bersih, ramah, kartu-kartu membulat dengan bayangan lembut
- **Warna utama:** `#006837` (hijau)
- **Warna pendukung:** `#FCA900` (amber)

---

## 1. Prinsip Desain

1. **Ramah dan memotivasi.** Sapaan personal, ilustrasi, dan pesan semangat ("Keep going!", "Small steps make big progress!").
2. **Hierarki jelas.** Hal terpenting (lanjut belajar, progres) ada di bagian atas dan memakai warna utama.
3. **Hijau untuk aksi, amber untuk sorotan.** Hijau dipakai untuk tombol, navigasi aktif, dan progres. Amber dipakai hemat untuk pencapaian, badge, dan penanda penting.
4. **Ruang napas.** Banyak whitespace, kartu terpisah jelas, tidak ramai.
5. **Mudah dibaca.** Kontras teks memenuhi WCAG AA.

---

## 2. Warna

### 2.1 Palet Utama (Primary Green)

| Token | HEX | Penggunaan |
|---|---|---|
| `--primary-900` | `#002B16` | Bayangan gelap, teks di atas amber |
| `--primary-800` | `#003D20` | Latar sidebar |
| `--primary-700` | `#004D28` | Hover item sidebar |
| `--primary-600` | `#005A2F` | Hover tombol utama |
| **`--primary-500`** | **`#006837`** | **Warna dasar: tombol, item aktif, progres, link** |
| `--primary-400` | `#2E8556` | Ikon aktif, donut progres |
| `--primary-300` | `#5FA680` | Border fokus lembut |
| `--primary-200` | `#A3CDB6` | Track progres terisi muda |
| `--primary-100` | `#D3E8DC` | Latar ikon, badge lembut |
| `--primary-50` | `#EEF7F2` | Latar banner sapaan, hover baris |

### 2.2 Palet Pendukung (Secondary Amber)

| Token | HEX | Penggunaan |
|---|---|---|
| `--secondary-700` | `#B87A00` | Teks amber di atas latar terang |
| `--secondary-600` | `#E09600` | Hover elemen amber |
| **`--secondary-500`** | **`#FCA900`** | **Warna pendukung: badge, ikon sertifikat, notifikasi, highlight** |
| `--secondary-400` | `#FDBC33` | Ikon pencapaian |
| `--secondary-300` | `#FED066` | Garis aksen |
| `--secondary-200` | `#FEE199` | Border kartu "You're doing great!" |
| `--secondary-100` | `#FFF0CC` | Latar ikon amber |
| `--secondary-50` | `#FFF8E6` | Latar kartu apresiasi |

### 2.3 Netral

| Token | HEX | Penggunaan |
|---|---|---|
| `--bg-page` | `#F4F8F6` | Latar halaman (hijau sangat pucat) |
| `--surface` | `#FFFFFF` | Latar kartu |
| `--border` | `#E3EBE7` | Garis tipis kartu dan pemisah |
| `--text-heading` | `#14231B` | Judul |
| `--text-body` | `#3C4A43` | Teks isi |
| `--text-muted` | `#74827B` | Teks sekunder, label, waktu |
| `--text-on-primary` | `#FFFFFF` | Teks di atas hijau |
| `--text-on-secondary` | `#2B1D00` | Teks di atas amber |

### 2.4 Warna Status

| Token | HEX | Penggunaan |
|---|---|---|
| `--success` | `#006837` | Selesai (sama dengan primary) |
| `--warning` | `#FCA900` | Sedang berjalan, perlu perhatian |
| `--danger` | `#D93025` | Error, badge notifikasi mendesak |
| `--info` | `#2F6FED` | Info netral (jarang dipakai) |
| `--neutral-state` | `#CBD5D0` | Belum dimulai |

### 2.5 Aturan Penggunaan Warna

- **Rasio 60 / 30 / 10:** 60% netral (putih dan abu-hijau muda), 30% hijau, 10% amber.
- **Jangan menulis teks amber `#FCA900` di atas putih.** Kontrasnya rendah (sekitar 1.8:1). Untuk teks amber di latar terang pakai `--secondary-700`. Untuk teks di atas blok amber pakai `--text-on-secondary`.
- Teks putih di atas `#006837` kontrasnya sekitar 7.5:1 dan aman untuk semua ukuran.
- Thumbnail kursus boleh memakai warna kategori sendiri (biru Python, ungu UI/UX, dan sebagainya) sebagai ilustrasi. Elemen UI di sekitarnya (progres, tombol) tetap memakai hijau.

---

## 3. Tipografi

- **Font utama:** `Poppins` (alternatif: `Inter`, `Plus Jakarta Sans`)
- **Fallback:** `system-ui, -apple-system, "Segoe UI", Roboto, sans-serif`

| Gaya | Ukuran / Line-height | Weight | Dipakai untuk |
|---|---|---|---|
| Display | 28px / 36px | 600 | Sapaan "Good Morning, Ayesha!" |
| H2 (judul section) | 20px / 28px | 600 | "My Current Courses" |
| H3 (judul kartu) | 16px / 24px | 600 | "Learning Progress", nama kursus |
| Body | 14px / 22px | 400 | Teks isi |
| Body Medium | 14px / 22px | 500 | Item menu, label |
| Small | 12px / 18px | 400 | Subjudul, waktu, "8 / 12 Lessons" |
| Stat Number | 32px / 40px | 700 | Angka di kartu statistik |
| Link | 12px / 18px | 500 | "View All →" |

---

## 4. Spacing, Radius, dan Bayangan

### Spacing (basis 4px)

`4 · 8 · 12 · 16 · 20 · 24 · 32 · 40`

- Jarak antar kartu: **20px**
- Padding dalam kartu: **20px** (kartu kecil 16px)
- Padding halaman: **24px**

### Border Radius

| Token | Nilai | Dipakai untuk |
|---|---|---|
| `--radius-sm` | 8px | Input kecil, badge |
| `--radius-md` | 12px | Tombol, item menu, thumbnail |
| `--radius-lg` | 16px | Kartu |
| `--radius-xl` | 24px | Sidebar, container utama |
| `--radius-full` | 999px | Avatar, progress bar, pill |

### Bayangan

```css
--shadow-card: 0 2px 12px rgba(0, 104, 55, 0.06);
--shadow-hover: 0 8px 24px rgba(0, 104, 55, 0.12);
--shadow-sidebar: 0 8px 32px rgba(0, 43, 22, 0.25);
```

---

## 5. Layout

Layout 3 area: **Sidebar kiri**, **konten utama tengah**, dan **panel kanan**.

```
┌────────┬───────────────────────────────────────┬──────────────┐
│        │  Topbar (search · notif · profil)     │              │
│        ├───────────────────────────┬───────────┤              │
│Sidebar │  Hero "Good Morning"      │  3 Stat   │  Calendar    │
│ 240px  ├───────────────────────────┴───────────┤  Upcoming    │
│        │  My Current Courses (4 kartu)         │  Classes     │
│        ├──────────────────┬────────────────────┤  Quick Links │
│        │ Learning Progress│ Recent Activity    │              │
│        ├──────────────────┴────────────────────┤              │
│        │  Banner "Achieve Your Goals"          │              │
└────────┴───────────────────────────────────────┴──────────────┘
```

| Area | Lebar | Catatan |
|---|---|---|
| Sidebar | 240px (fixed) | Tinggi penuh, radius 24px, margin 16px |
| Konten utama | fluid (min 640px) | Grid 12 kolom, gap 20px |
| Panel kanan | 320px | Kalender, kelas mendatang, quick links |

### Breakpoint

| Nama | Lebar | Perilaku |
|---|---|---|
| Desktop XL | ≥ 1280px | Layout 3 area penuh |
| Desktop | 1024–1279px | Panel kanan turun ke bawah konten utama |
| Tablet | 768–1023px | Sidebar menjadi ikon saja (72px), kartu kursus 2 kolom |
| Mobile | < 768px | Sidebar jadi bottom navigation / drawer, semua kartu 1 kolom |

---

## 6. Komponen

### 6.1 Sidebar

- **Latar:** `--primary-800` `#003D20`, radius 24px, `--shadow-sidebar`
- **Logo:** ikon topi wisuda dalam kotak `--primary-500`, teks "EduSphere" putih (Poppins 600, 20px), tagline 11px putih 70%
- **Item menu:** tinggi 44px, radius 12px, ikon 20px + label 14px/500, warna teks `rgba(255,255,255,0.75)`
  - **Hover:** latar `--primary-700`, teks putih
  - **Aktif:** latar `--primary-500` `#006837` dengan garis aksen kiri `--secondary-500` selebar 4px, teks putih
- **Menu:** Dashboard, My Courses, Live Classes, Assignments, Tests & Quizzes, Resources, Calendar, Messages, Settings
- **Kartu motivasi bawah:** latar `--primary-700`, teks "Small steps make big progress!" putih, ilustrasi tumpukan buku. Garis bawah teks memakai `--secondary-500`

### 6.2 Topbar

- **Search:** lebar fleksibel, tinggi 44px, latar putih, border `--border`, radius 12px, ikon kaca pembesar `--text-muted`. Fokus: border `--primary-500` + ring `0 0 0 3px rgba(0,104,55,0.15)`
- **Notifikasi:** ikon lonceng 24px, badge bulat 16px `--danger` angka putih
- **Profil:** avatar 40px bulat, nama (14px/600), peran "Student" (12px muted), chevron dropdown

### 6.3 Hero Banner Sapaan

- **Latar:** gradien `--primary-50` → `#DDF0E5`, radius 16px, padding 24px
- **Judul:** "Good Morning, Ayesha!" Display 28px `--text-heading`, ikon matahari `--secondary-500`
- **Subteks:** 14px `--text-body`
- **CTA "Continue Learning →":** tombol primary
- **Ilustrasi:** siswi dengan laptop di kanan, tulisan tangan "Learn Anytime Anywhere" memakai `--primary-500`

### 6.4 Tombol

| Varian | Latar | Teks | Hover | Dipakai untuk |
|---|---|---|---|---|
| **Primary** | `#006837` | putih | `#005A2F` | CTA utama (Continue Learning, Explore Courses) |
| **Secondary (amber)** | `#FCA900` | `#2B1D00` | `#E09600` | Aksi sorotan (Claim Certificate, Join Now) |
| **Ghost / Link** | transparan | `#006837` | latar `--primary-50` | "View All →" |

- Tinggi 40px (kecil 32px), padding horizontal 20px, radius 12px, font 14px/500
- Fokus keyboard: outline 2px `--secondary-500` offset 2px
- Disabled: latar `#E3EBE7`, teks `--text-muted`

### 6.5 Kartu Statistik (Enrolled Courses, Completed Lessons, Certificates)

- Tiga kartu sejajar, latar putih, radius 16px, `--shadow-card`, padding 16px
- **Ikon:** kotak 40px radius 12px
  - Enrolled Courses → latar `--primary-100`, ikon `--primary-500`
  - Completed Lessons → latar `--primary-100`, ikon `--primary-400`
  - Certificates → latar `--secondary-100`, ikon `--secondary-500`
- **Label:** 12px muted. **Angka:** Stat Number 32px/700 `--text-heading`
- **Link "View all →":** 12px/500 `--primary-500`

### 6.6 Kartu Kursus (My Current Courses)

- Grid 4 kolom, gap 16px. Kartu putih, radius 16px, border `--border`
- **Thumbnail:** tinggi 100px, radius 12px, ilustrasi kategori
- **Judul:** 14px/600. **Subjudul:** 12px muted
- **Progress bar:** tinggi 6px, radius full, track `--primary-100`, isi `--primary-500`
- **Persen:** 12px/600 di kanan bar. Jika ≥ 80% isi bar menjadi `--secondary-500` sebagai penanda hampir selesai
- **Meta:** "8 / 12 Lessons" 12px muted
- **Hover:** naik 2px, `--shadow-hover`

### 6.7 Learning Progress (Donut)

- Donut 120px, ketebalan 14px. Track `--primary-100`, isi `--primary-500`, ujung membulat
- Angka tengah "72%" 24px/700, label "Overall Progress" 11px muted
- **Legenda:** titik 8px + label + angka
  - Completed → `--primary-500`
  - In Progress → `--secondary-500`
  - Not Started → `--neutral-state`
- **Kartu apresiasi:** latar `--secondary-50`, border `--secondary-200`, ikon piala `--secondary-500`, teks "You're doing great! Keep it up!"

### 6.8 Recent Activity

- Daftar vertikal, tiap baris: ikon 36px bulat + judul (14px/600) + deskripsi (12px muted) + waktu (12px muted, rata kanan)
- Pemisah garis tipis `--border`
- Warna ikon per jenis:
  - Lesson selesai → `--primary-100` / `--primary-500`
  - Assignment → `--primary-50` / `--primary-400`
  - Quiz lulus → `--secondary-100` / `--secondary-500`
  - Sertifikat → `--secondary-100` / `--secondary-600`

### 6.9 Kalender (Panel Kanan)

- Header: "September 2025" 16px/600 + tombol panah kiri/kanan
- Grid 7 kolom, label hari 12px muted, tanggal 13px
- **Hari ini:** lingkaran 32px `--primary-500`, teks putih
- **Ada kelas:** titik 4px `--secondary-500` di bawah angka
- Tanggal bulan lain: `#B5C0BA`
- Hover tanggal: latar `--primary-50`

### 6.10 Upcoming Classes

- Daftar 3 item: ikon kursus 40px, judul 14px/600, "Live Class" 12px muted, waktu 12px, chevron kanan
- Waktu hari ini → teks `--primary-500`
- Hover baris: latar `--primary-50`
- Link "View All →" di header

### 6.11 Quick Links

- 4 tombol ikon (My Assignments, Tests & Quizzes, Study Resources, Join Live Class)
- Kotak ikon 40px radius 12px latar `--primary-50`, ikon `--primary-500`; "Join Live Class" memakai latar `--secondary-100` dan ikon `--secondary-500`
- Label 11px/500 di bawah ikon, rata tengah

### 6.12 Banner "Achieve Your Goals"

- Latar gradien `--primary-500` → `--primary-700`, radius 16px, teks putih
- Judul 16px/600, deskripsi 12px putih 80%
- Tombol "Explore Courses" varian **Secondary (amber)** agar kontras terhadap latar hijau
- Ilustrasi topi wisuda dan buku di kanan

---

## 7. Ikonografi dan Ilustrasi

- **Ikon:** Lucide atau Phosphor, gaya outline, stroke 1.75px, ukuran 20px (navigasi) dan 24px (kartu)
- **Ilustrasi:** flat dengan outline halus, palet menyesuaikan hijau dan amber (kulit, rambut, dan pakaian bebas, aksen utama hijau dan amber)
- Semua ikon dekoratif diberi `aria-hidden="true"`; ikon fungsional diberi `aria-label`

---

## 8. Interaksi dan Motion

| Elemen | Efek | Durasi |
|---|---|---|
| Tombol | Ganti warna latar | 150ms ease-out |
| Kartu kursus | translateY(-2px) + shadow | 200ms ease-out |
| Progress bar | Animasi isi dari 0 saat load | 600ms ease-out |
| Donut | Animasi stroke-dashoffset saat load | 800ms ease-out |
| Item sidebar | Latar dan warna teks | 150ms |
| Dropdown profil | Fade + slide 4px | 150ms |

Hormati `prefers-reduced-motion`: matikan animasi non-esensial.

---

## 9. Aksesibilitas

- Kontras teks minimal **4.5:1** (teks besar 3:1)
- Semua elemen interaktif punya state fokus yang terlihat
- Target sentuh minimal **44 × 44px** di mobile
- Informasi tidak hanya dibedakan lewat warna: progres selalu disertai angka persen, status disertai label teks
- Gunakan elemen semantik (`nav`, `main`, `aside`, `section`, `button`)
- Progress bar: `role="progressbar"` dengan `aria-valuenow`, `aria-valuemin`, `aria-valuemax`
- Alt text untuk ilustrasi bermakna, kosong untuk dekoratif

---

## 10. Design Tokens (CSS)

```css
:root {
  /* Primary */
  --primary-900: #002B16;
  --primary-800: #003D20;
  --primary-700: #004D28;
  --primary-600: #005A2F;
  --primary-500: #006837;
  --primary-400: #2E8556;
  --primary-300: #5FA680;
  --primary-200: #A3CDB6;
  --primary-100: #D3E8DC;
  --primary-50:  #EEF7F2;

  /* Secondary */
  --secondary-700: #B87A00;
  --secondary-600: #E09600;
  --secondary-500: #FCA900;
  --secondary-400: #FDBC33;
  --secondary-300: #FED066;
  --secondary-200: #FEE199;
  --secondary-100: #FFF0CC;
  --secondary-50:  #FFF8E6;

  /* Neutral */
  --bg-page: #F4F8F6;
  --surface: #FFFFFF;
  --border: #E3EBE7;
  --text-heading: #14231B;
  --text-body: #3C4A43;
  --text-muted: #74827B;
  --text-on-primary: #FFFFFF;
  --text-on-secondary: #2B1D00;

  /* Status */
  --success: #006837;
  --warning: #FCA900;
  --danger: #D93025;
  --info: #2F6FED;
  --neutral-state: #CBD5D0;

  /* Typography */
  --font-sans: "Poppins", system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;

  /* Radius */
  --radius-sm: 8px;
  --radius-md: 12px;
  --radius-lg: 16px;
  --radius-xl: 24px;
  --radius-full: 999px;

  /* Shadow */
  --shadow-card: 0 2px 12px rgba(0, 104, 55, 0.06);
  --shadow-hover: 0 8px 24px rgba(0, 104, 55, 0.12);
  --shadow-sidebar: 0 8px 32px rgba(0, 43, 22, 0.25);

  /* Layout */
  --sidebar-width: 240px;
  --right-panel-width: 320px;
  --gap: 20px;
}
```

### Contoh Pemakaian

```css
body {
  font-family: var(--font-sans);
  background: var(--bg-page);
  color: var(--text-body);
}

.card {
  background: var(--surface);
  border: 1px solid var(--border);
  border-radius: var(--radius-lg);
  box-shadow: var(--shadow-card);
  padding: 20px;
}

.btn-primary {
  background: var(--primary-500);
  color: var(--text-on-primary);
  border-radius: var(--radius-md);
  padding: 10px 20px;
  font-weight: 500;
  transition: background 150ms ease-out;
}
.btn-primary:hover { background: var(--primary-600); }

.btn-secondary {
  background: var(--secondary-500);
  color: var(--text-on-secondary);
  border-radius: var(--radius-md);
  padding: 10px 20px;
  font-weight: 500;
}
.btn-secondary:hover { background: var(--secondary-600); }

.sidebar { background: var(--primary-800); border-radius: var(--radius-xl); }
.sidebar .item.active {
  background: var(--primary-500);
  box-shadow: inset 4px 0 0 var(--secondary-500);
  color: #fff;
}

.progress { height: 6px; border-radius: var(--radius-full); background: var(--primary-100); }
.progress > span { display: block; height: 100%; border-radius: inherit; background: var(--primary-500); }
.progress.almost > span { background: var(--secondary-500); }
```

---

## 11. Checklist Implementasi

- [ ] Token warna, font, radius, dan bayangan sudah masuk ke CSS global
- [ ] Sidebar memakai `--primary-800` dan item aktif memakai `--primary-500`
- [ ] Semua tombol utama hijau, tombol sorotan amber
- [ ] Teks di atas amber memakai warna gelap
- [ ] Progress bar kursus dan donut memakai hijau
- [ ] Kalender memberi titik amber pada hari yang ada kelas
- [ ] Responsif diuji di 1280, 1024, 768, dan 375px
- [ ] Kontras dan fokus keyboard sudah diuji

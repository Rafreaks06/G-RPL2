# G-RPL Design System Documentation

## 1. Konsep Desain Utama

Global RPL (G-RPL) adalah sistem digital terintegrasi untuk program Rekognisi Pembelajaran Lampau yang dirancang dengan prinsip:

- **Modern**: Menggunakan animasi halus, gradient modern, dan interaksi responsif
- **Profesional**: Desain yang clean dan tidak berlebihan untuk kredibilitas akademik
- **Bersih**: Layout yang simple dengan whitespace yang cukup
- **Mudah dipahami**: Visual hierarchy jelas dan navigasi intuitif
- **Digital-first**: Semua proses dari pendaftaran hingga penilaian dilakukan online

### Color Palette Philosophy
- **Primary Blue (#1565C0)**: Kredibilitas, kepercayaan, profesionalisme (warna utama)
- **Accent Orange (#F9A825)**: Energi, kecerahan, optimisme (warna aksen utama)
- **Secondary Red (#E53935)**: Dinamisme, aksi, perhatian (warna tambahan)
- **Background Light (#F8FAFC, #F5F6FA)**: Clean, terang, mudah dibaca
- **Text Dark (#172033, #1A1A2E)**: Kontras tinggi untuk keterbacaan
- **Text Muted (#64748B)**: Informasi sekunder

---

## 2. Warna Utama dan Warna Pendukung

### Primary Colors
| Nama | Hex | Penggunaan |
|------|-----|------------|
| Primary Blue | `#1565C0` | Logo, CTA utama, header navbar, accent line, status aktif |
| Primary Dark | `#0D47A1` | Hover state tombol primary |
| Accent Orange | `#F9A825` | Highlight, status badge, progress bar, icon accent |
| Secondary Red | `#E53935` | Danger state, attention marker |

### Background Colors
| Nama | Hex | Penggunaan |
|------|-----|------------|
| Background Light | `#F8FAFC` | Hero, sections dengan gradient subtle |
| Background Muted | `#F5F6FA` | Background body, sections netral |
| Card Background | `#FFFFFF` | Card components, form elements |

### Text Colors
| Nama | Hex | Penggunaan |
|------|-----|------------|
| Text Primary | `#172033`, `#1A1A2E` | Headings, main content |
| Text Muted | `#64748B` | Body text, captions, secondary info |
| Text Muted Light | `#5F6472` | Requirements/FAQ pages |

### Status Colors
| Nama | Hex | Penggunaan |
|------|-----|------------|
| Success | `#16794F` | Success states, active status |
| Warning | `#B7791F` | Warning states, draft status |

---

## 3. Logo dan Identitas Visual Global RPL

### Logo Structure
Logo G-RPL terdiri dari 3 potongan berwarna yang membentuk satu kesatuan:

1. **Potongan Biru (#1565C0)**: Mewakili kepercayaan dan struktur akademik
2. **Potongan Orange (#F9A825)**: Mewakili energi dan inovasi
3. **Potongan Merah (#E53935)**: Mewakili aksi dan dedikasi

### Logo Animations (Desktop)
- **3D Floating**: Logo bergerak halus dengan rotasi 3D
- **Piece Assembly**: Potongan logo terbang dari luar layar dan bergabung
- **Glow Rotation**: Effect cahaya berputar di sekeliling logo
- **Text Fade-in**: Teks "G-RPL" muncul setelah logo lengkap

### Logo Usage
- **Header**: Posisi kiri navbar, ukuran 46px x 46px
- **Preloader**: Posisi tengah dengan animasi naik-turun
- **Footer**: Posisi kiri, ukuran kecil (h-7)
- **Cards**: Icon kecil dengan gradient background

---

## 4. Struktur Layout Setiap Section

### 4.1 Beranda (Homepage)

**Layout:**
- Navbar fixed dengan gradient accent line
- Hero section dengan 2 kolom (kiri: teks, kanan: preview system)
- Stats section dengan 4 kartu metrik
- Grid sections untuk Tentang, Fitur, Keunggulan, Alur, Pengumuman, Persyaratan, FAQ
- CTA section dengan gradient background

**Visual Elements:**
- Soft orb effect (background blur circles)
- Glassmorphism cards (backdrop-filter blur)
- Floating animations untuk konten hero
- Responsive layout: mobile stack, desktop 2-column

**Hero Components:**
1. Badge status "Portal Resmi G-RPL"
2. Headline besar dengan highlight gradient
3. Deskripsi singkat tentang RPL
4. CTA buttons (Daftar Sekarang, Pelajari RPL)
5. Stats grid (45 Prodi, 144 Maks SKS, 100% Digital)
6. Preview panel (simulasi dashboard)

### 4.2 Tentang RPL

**Layout:**
- Section dengan background white
- Grid 3 kolom (1:2 rasio)
- Kiri: Deskripsi text
- Kanan: 3 kartu (Tipe A1, Tipe A2, dan Hybrid)
- Banner bottom dengan gradient background

**Components:**
- Badge "Tentang Program"
- Heading besar dengan text-balance
- Glass cards dengan border subtle
- Gradient icon backgrounds
- Call-to-action banner

### 4.3 Fitur/Sistem Banner

**Layout:**
- Background #0D47A1 (dark blue)
- Grid 2 kolom (text | system cards)
- Soft orb backgrounds dengan gradient
- 4 kartu sistem dengan gradient background

**Components:**
- Badge "Sistem Terintegrasi"
- Headline putih besar
- Text text-white/75
- Cards dengan backdrop blur
- Number prefix (01, 02, 03, 04)

### 4.4 Keunggulan

**Layout:**
- Background #F8FAFC
- Centered heading dengan badge
- Grid 3 kolom untuk 3 keunggulan
- Icon dengan gradient background

**Components:**
- Badge "Keunggulan G-RPL"
- Icon circles dengan gradient (Blue, Orange, Red)
- Heading medium, text body
- Hover effects untuk cards

### 4.5 Alur Pendaftaran

**Layout:**
- Background white
- Centered heading
- Grid 5 kolom untuk 5 tahapan
- Connector line (desktop only)
- Cards dengan number badge

**Components:**
- Badge "Tahapan Pendaftaran"
- Number badges (#1-#5) dengan gradient
- Connector line (gradient from blue to transparent)
- Cards dengan hover effect
- Mobile: 2 kolom, last item span 2 kolom

### 4.6 Pengumuman

**Layout:**
- Background #F8FAFC dengan border-y
- Centered heading
- Search bar di atas
- Grid 3 kolom untuk 3 cards

**Components:**
- Badge "Pengumuman Terbaru"
- Input search dengan shadow
- Cards dengan gradient backgrounds
- Image thumbnails
- Status badges

### 4.7 Persyaratan

**Layout:**
- Grid 2 kolom (text | form)
- Kiri: Text deskripsi dengan gradient background
- Kanan: Form checklist dengan cards

**Components:**
- Badge "Persyaratan"
- Gradient background section
- Checklist dengan icon checks
- CTA button

### 4.8 FAQ

**Layout:**
- Centered max-w-4xl
- Grid 2 kolom untuk FAQ items
- Accordion/detailed elements
- Bottom CTA section

**Components:**
- Badge "Pertanyaan Umum"
- FAQ cards dengan gradient
- Details/summary untuk expand/collapse
- Icon circles
- Bottom CTA section

---

## 5. Tipografi

### Font Families
- **Headings**: 'Sora' (Google Fonts)
- **Body**: 'Plus Jakarta Sans' (Google Fonts)
- **UI Elements**: System sans-serif

### Font Weights
| Level | Weight | Size (Desktop) | Size (Mobile) | Usage |
|-------|--------|----------------|---------------|-------|
| H1 | 800 (ExtraBold) | 4.9rem (up to 7xl) | clamp(1.75rem, 8.5vw, 2.55rem) | Hero headline |
| H2 | 800 (ExtraBold) | 3xl-4xl | 1.65rem-1.75rem | Section titles |
| H3 | 800 (ExtraBold) | xl-2xl | 0.82rem-1.15rem | Card titles |
| H4 | 800 (ExtraBold) | base | 0.78rem-0.82rem | Subtitles |
| P (body) | 400 (Regular) | base-lg | 0.68rem-0.9rem | Content text |
| Small | 600 (SemiBold) | xs-sm | 0.52rem-0.78rem | Meta info, badges |

### Text Styling
- **Headings**: Letter-spacing -0.035em to -0.055em (desktop), -0.025em to -0.04em (mobile)
- **Body**: Line-height 1.35-1.62
- **Buttons**: Uppercase tracking 0.12em-0.18em
- **Badges**: Uppercase tracking 0.11em-0.16em

---

## 6. Gaya Tombol, Card, Icon, dan Komponen UI

### Buttons

**Primary Button**
- Background: #1565C0
- Hover: #0D47A1
- Color: White
- Padding: 0 18px, min-height 44px
- Border-radius: 12px
- Shadow: 0 10px 22px rgba(21, 101, 192, 0.18)
- Font: 800, uppercase tracking
- Hover effects: translateY(-1px), increased shadow

**Secondary Button**
- Background: White
- Border: #1565C0/15
- Color: #1565C0
- Hover: #EAF3FF
- Padding: 0 18px, min-height 44px
- Border-radius: 12px
- Shadow: none atau subtle

**Button Small**
- Min-height: 38px
- Padding: 0 14px
- Font-size: 0.92rem

**Button Muted**
- Background: White
- Border: border-color
- Color: text-color
- Hover: surface-muted

### Cards

**Clean Card (Default)**
- Background: White atau surface-raised (#fbfcff)
- Border: 1px solid #1565C0/10 atau rgba(26, 26, 46, 0.10)
- Border-radius: 2rem (sections), 1.2rem-1.45rem (components), 1rem (mobile)
- Shadow: 0 18px 45px rgba(26, 26, 46, 0.08)
- Hover: translateY(-3px to -5px), increased shadow, border-color accent
- Glass effect: rgba(255,255,255,0.82) backdrop-filter blur(14px)

**Gradient Card**
- Background: linear-gradient(to-br, #F8FAFC, #FFFFFF)
- Border-radius: 1rem-2.5rem
- Shadow: 0 22px 55px rgba(21, 101, 192, 0.22)
- Gradient accents: radial gradients dari corner

### Icons

**Icon Sizes**
- Small: w-4 h-4 (16px)
- Medium: w-7 h-7 (28px)
- Large: w-14 h-14 (56px)
- Extra Large: w-16 h-16 (64px)

**Icon Styles**
- Gradient background dengan text center
- Border-radius: 2rem (large), 1.5rem (xl), 0.85rem (small)
- Shadow: 0 16px 32px rgba(21, 101, 192, 0.22) atau 0 8px 18px rgba(249, 168, 37, 0.22)
- Circle: w-10-11 h-10-11 (40-44px)

**Icon Gradients**
- Blue: #EAF3FF ke #1565C0
- Orange: #FFF8E1 ke #F9A825
- Red: #FEECEC ke #E53935

### Badges

**Status Badge**
- Min-height: 28px-32px
- Padding: 0 10px
- Border-radius: 999px
- Font-size: 0.82rem-0.9rem
- Uppercase tracking
- Background: rgba(22, 121, 79, 0.10) atau rgba(194, 65, 50, 0.10)

**Info Badge**
- Background: #EAF3FF atau #FFF8E1
- Border: rgba(21, 101, 192, 0.18)
- Color: #1565C0 atau #B7791F
- Padding: 0 10px
- Border-radius: 999px
- Font-size: 0.76rem-0.84rem

### Inputs
- Min-height: 44px
- Padding: 10px 12px
- Border: 1px solid border-color
- Border-radius: 12px
- Background: White
- Focus: border-color #1565C0, outline 3px rgba(21, 101, 192, 0.16)

### Preloader
- Fixed inset-0 z-[9999]
- Background: White
- Centered content
- Logo dengan border-radius-3xl
- Progress bar dengan gradient from #1565C0 via #1E88E5 to #F9A825
- Fade-out transition: opacity 0.45s

---

## 7. Posisi Gambar, Teks, dan Elemen Visual

### Hero Section
- **Image/Visual**: Posisi kanan (desktop), bawah (mobile)
- **Text**: Posisi kiri (desktop), atas (mobile)
- **Badge**: Absolute top-left
- **Stats cards**: Bawah teks, grid 3 kolom
- **Gradient accents**: Soft orbs di background (top-right, bottom-left)

### Section Headers
- **Badge**: absolute top-0, left-0 dengan margin
- **Heading**: left-aligned, max-width: 690px
- **Description**: max-w-2xl atau max-w-3xl
- **Buttons**: flex-row gap-4

### Cards
- **Icon**: Top, center, w-11-16 h-11-16
- **Title**: center, margin-bottom: 0.5-1rem
- **Body text**: left-aligned, line-height 1.35-1.62
- **CTA**: Bottom, flex items-center

### Stats Grid
- **Desktop**: 4 kolom (grid-cols-4)
- **Mobile**: 2 kolom (grid-cols-2)
- **Cards**: center-aligned content

### Alur/Steps
- **Desktop**: 5 kolom, connector line di antara
- **Mobile**: 2 kolom, last step span 2 kolom
- **Numbers**: center, gradient background
- **Connectors**: absolute, top-8, left-[58%] (desktop)

### Form Elements
- **Desktop**: 2 kolom (grid-cols-2)
- **Mobile**: 1 kolom (grid-cols-1)
- **Labels**: Above input, 0.88rem font-weight 800
- **Input**: Full width, min-height 44px

---

## 8. Nuansa Desain yang Ditampilkan

### Modern
- Animasi smooth dengan CSS transitions
- Gradient modern (conic, radial, linear)
- Glassmorphism effect (backdrop-filter blur)
- 3D transforms (rotateX, rotateY)
- Floating animations

### Profesional
- Color scheme yang konservatif (Blue-based)
- Typography yang readable
- Spacing yang consistent
- Border subtle (rgba dengan low opacity)
- Shadow yang tidak berlebihan

### Bersih
- Background light (#F8FAFC, #F5F6FA)
- White cards dengan shadow subtle
- White space yang cukup
- Text dengan contrast ratio tinggi
- No clutter components

### Mudah Dipahami
- Visual hierarchy jelas
- Icon dengan label
- Progress indicators
- Status badges
- Responsive layout dengan mobile-first approach

---

## 9. Catatan Responsive Design

### Breakpoints
- **Desktop**: min-width 1024px (landscape tablet ke atas)
- **Tablet**: 768px - 1023px
- **Mobile**: max-width 767px
- **Small Mobile**: max-width 420px
- **Large Desktop**: min-width 1280px

### Mobile Optimization

**Layout Changes:**
- Hero: 1 kolom (stack)
- Stats: 2 kolom (grid-cols-2)
- Sections: 1 kolom (full width)
- Forms: 1 kolom (grid-cols-1)
- Grid sections: 2 kolom (grid-cols-2)

**Typography Adjustments:**
- H1: clamp(1.75rem, 8.5vw, 2.55rem)
- H2: 1.65rem (mobile) vs 3xl-4xl (desktop)
- Body: 0.68rem-0.9rem (mobile) vs base-lg (desktop)

**Component Adjustments:**
- Cards: smaller padding (0.75rem-1rem)
- Icons: smaller size (w-10-14 h-10-14)
- Buttons: smaller padding (0.72rem-0.85rem)
- Spacing: reduced (gap-0.5-1rem)

**Touch Targets:**
- Min-height: 44px untuk semua buttons
- Min-height: 38px untuk small buttons
- Padding: cukup untuk finger tap

### Desktop Enhancements

**Hover Effects:**
- Cards: translateY(-5px), shadow increase
- Links: color change, underline
- Icons: rotation, scale

**Advanced Effects:**
- Soft orbs (background blur circles)
- Connector lines (steps)
- Glassmorphism layers
- 3D transforms
- Gradients dengan multiple color stops

**Layout:**
- Max-width: 82rem (1280px+)
- 2-3 columns untuk grids
- Sticky sidebar untuk dashboard

### Accessibility

**Reduced Motion:**
```css
@media (prefers-reduced-motion: reduce) {
    .section-reveal,
    .section-reveal-right,
    .hero-fade-left,
    .hero-fade-right,
    .soft-orb,
    .preloader-logo {
        animation: none;
        opacity: 1;
        transform: none;
        transition: none;
    }
}
```

**High Contrast:**
- Text dengan background cukup kontras
- Focus states dengan outline
- Clickable areas cukup besar

**Keyboard Navigation:**
- All interactive elements focusable
- Skip links (future)
- Logical tab order

### Performance Considerations
- CSS animations dengan will-change minimal
- Backdrop-filter limited to 1-2 elements
- SVG icons inline
- Preloader untuk perceived performance
- Smooth scroll behavior

---

## 10. Komponen UI Spesifik

### Topbar (Dashboard)
- Sticky top 0
- Min-height 72px
- Background: rgba(244, 240, 232, 0.88)
- Backdrop-filter: blur(18px)
- Brand: 42px x 42px rounded-12
- Navigation: gap-8px

### Sidebar (Dashboard)
- Width: 280px
- Background: #111827 (dark)
- Color: #f9fafb
- Sticky top: 94px
- Shadow: 0 22px 55px rgba(17, 24, 39, 0.18)

### Workspace (Dashboard)
- Background: rgba(255, 255, 255, 0.88)
- Backdrop-filter: blur(10px)
- Padding: 24px
- Gap: 22px

### Form Grid
- Desktop: 2 kolom (grid-cols-2)
- Mobile: 1 kolom (grid-cols-1)
- Gap: 16px
- Padding: 18px
- Background: surface-raised
- Border-radius: 8px

### Data Table
- Min-width: 760px
- Header: #f8fafc background
- Row hover: #f8fbff background
- Padding: 13px 14px
- Font-size: 0.78rem uppercase

### Modal
- Background: rgba(17, 24, 39, 0.56)
- Backdrop-filter: blur(6px)
- Max-width: 560px
- Max-height: calc(100vh - 80px)
- Border-radius: 12px

---

## 11. Animation & Transitions

### Hero Animations
- Fade-in left/right (0.85s)
- Delay 0.1s - 0.4s untuk setiap elemen
- Ease-in-out untuk smooth entry

### Soft Orb Animations
- Soft float (8s, 10s cycles)
- translateY(-16px) dengan translateX(14px)
- Infinite loop, ease-in-out

### Logo Animations (Navbar)
- 3D float (5s, infinite)
- Piece assembly (0.85s cubic-bezier)
- Glow rotation (8s, linear infinite)

### Scroll Animations
- Section reveal (0.75s)
- Fade-in dari kiri/kanan
- Opacity dan transform simultaneously

### Button Transitions
- Transform: 0.18s ease
- Box-shadow: 0.18s ease
- Background: 0.18s ease
- Hover: translateY(-1px)

### Form Focus
- Border-color: 0.18s ease
- Outline: 3px rgba(21, 101, 192, 0.16)
- Transition: 0.18s ease

---

## 12. File Structure & Assets

### CSS
- `resources/css/app.css` - TailwindCSS + custom styles
- Components: navbar, sidebar, dashboard, profile
- Animations: hero, soft-orb, logo, section-reveal

### Blade Components
- `resources/views/components/navbar.blade.php`
- `resources/views/components/footer.blade.php`

### Page Templates
- `resources/views/pages/home.blade.php`
- `resources/views/pages/about.blade.php`
- `resources/views/pages/requirements.blade.php`
- `resources/views/pages/faq.blade.php`
- `resources/views/pages/dashboard.blade.php`
- `resources/views/pages/applicant/profile.blade.php`
- `resources/views/pages/admin/*.blade.php`

### Images
- `public/images/logo.png` - Logo G-RPL
- Status: Existing (digunakan di navbar, footer, preloader)

---

## 13. Implementasi Notes

### Tech Stack
- **Backend Framework**: Laravel 13.x (PHP 8.5+)
- **Frontend**: React (App.jsx) terintegrasi via Laravel Vite
- **Styling**: Tailwind CSS

### Desktop-Only Styling
- Media query: `@media (min-width: 1024px)`
- Sidebar sticky position
- Advanced hover effects
- 3D transforms
- Connector lines

### Mobile-Only Styling
- Media query: `@media (max-width: 767px)`
- Single column layout
- Compact spacing
- Touch-friendly targets
- Reduced animations

### Common Patterns

**Card Component:**
```html
<div class="clean-card glass-card border border-[#1565C0]/10 rounded-3xl p-6 shadow-sm hover:bg-white">
    <div class="w-14 h-14 rounded-2xl bg-[#EAF3FF] text-[#1565C0] flex items-center justify-center mb-6">
        <span class="font-heading text-2xl font-extrabold">A</span>
    </div>
    <h3 class="font-heading font-extrabold text-[#172033] text-xl mb-3">Title</h3>
    <p class="text-sm text-[#64748B] leading-relaxed">Description</p>
</div>
```

**Badge Component:**
```html
<span class="inline-flex px-4 py-2 rounded-full bg-[#EAF3FF] text-[#1565C0] text-xs font-bold uppercase tracking-[0.16em]">
    Label Text
</span>
```

**Button Component:**
```html
<a class="group inline-flex items-center justify-center px-7 py-3.5 bg-[#1565C0] text-white text-sm font-bold rounded-2xl hover:bg-[#0D47A1] transition-all shadow-xl shadow-blue-500/20 hover:-translate-y-1">
    Button Text
    <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform">...</svg>
</a>
```

---

## 14. Future Enhancements

### Potential Improvements
1. Dark mode toggle
2. Animated background particles
3. SVG illustrations untuk empty states
4. Loading skeletons untuk data fetching
5. Micro-interactions untuk form inputs
6. Progress indicators untuk multi-step forms
7. Toast notifications untuk feedback
8. Skeleton screens untuk performance

### Accessibility Upgrades
1. Skip navigation links
2. ARIA labels untuk icons
3. Keyboard navigation enhancements
4. Screen reader optimizations
5. Color contrast verification
6. Focus visible styles

### Performance Optimizations
1. Image lazy loading
2. Critical CSS extraction
3. Font loading optimizations
4. Animation optimizations
5. Bundle size reductions

---

**Last Updated:** 2026-06-01
**Version:** 1.0.0
**Maintained by:** G-RPL Development Team
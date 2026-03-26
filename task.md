# Task: KanmoCRM View Refactoring

## Phase 1 – Foundation & Shared Assets
- [x] **1.1** Pindahkan CSS global (custom-scrollbar, entries-select) dari [_scrollbar.blade.php](file:///c:/laragon/www/kanmo_crm/resources/views/pages/setup-channel-email/partials/_scrollbar.blade.php) & [sidebar.blade.php](file:///c:/laragon/www/kanmo_crm/resources/views/layouts/sidebar.blade.php) ke [resources/css/app.css](file:///c:/laragon/www/kanmo_crm/resources/css/app.css)
- [x] **1.2** Upgrade [layouts/app.blade.php](file:///c:/laragon/www/kanmo_crm/resources/views/layouts/app.blade.php) — tambah `@stack('scripts')` & `@stack('styles')` stack placeholder
- [x] **1.3** Buat shared JS module [resources/js/modules/crud-table.js](file:///c:/laragon/www/kanmo_crm/resources/js/modules/crud-table.js) — fungsi reusable: [loadTable](file:///c:/laragon/www/kanmo_crm/resources/js/modules/crud-table.js#96-112), [renderPagination](file:///c:/laragon/www/kanmo_crm/resources/js/modules/crud-table.js#63-95), [showToast](file:///c:/laragon/www/kanmo_crm/resources/js/modules/crud-table.js#2-32), [escHtml](file:///c:/laragon/www/kanmo_crm/resources/js/modules/crud-table.js#33-38), [escJs](file:///c:/laragon/www/kanmo_crm/resources/js/modules/crud-table.js#39-44), [openModal](file:///c:/laragon/www/kanmo_crm/resources/js/modules/crud-table.js#191-205), [closeModal](file:///c:/laragon/www/kanmo_crm/resources/js/modules/crud-table.js#206-222), `showLoading`
- [x] **1.4** Daftarkan [crud-table.js](file:///c:/laragon/www/kanmo_crm/resources/js/modules/crud-table.js) di [vite.config.js](file:///c:/laragon/www/kanmo_crm/vite.config.js) dan import di [app.js](file:///c:/laragon/www/kanmo_crm/resources/js/app.js)

## Phase 2 – Blade Components
- [x] **2.1** Buat [resources/views/components/page-header.blade.php](file:///c:/laragon/www/kanmo_crm/resources/views/components/page-header.blade.php) — komponen breadcrumb+judul+tombol aksi
- [x] **2.2** Buat [resources/views/components/data-table.blade.php](file:///c:/laragon/www/kanmo_crm/resources/views/components/data-table.blade.php) — tabel container dengan controls, thead slot, tbody loading state, dan pagination
- [x] **2.3** Buat [resources/views/components/modal.blade.php](file:///c:/laragon/www/kanmo_crm/resources/views/components/modal.blade.php) — modal container reusable (judul, konten slot, tombol aksi)
- [x] **2.4** Buat [resources/views/components/status-badge.blade.php](file:///c:/laragon/www/kanmo_crm/resources/views/components/status-badge.blade.php) — badge Aktif/Non Aktif
- [x] **2.5** Buat [resources/views/components/loading-overlay.blade.php](file:///c:/laragon/www/kanmo_crm/resources/views/components/loading-overlay.blade.php) — komponen loading overlay animasi spin

## Phase 3 – Migrasi Layout Lama
- [x] **3.1** Identifikasi semua file yang masih memakai `x-dashonic-horizontal-layout` (ada ~10 file)
- [x] **3.2** Migrasi seluruhnya ke `@extends('layouts.app')` + `@section('content')`

## Phase 4 – Refactor Halaman Master Data (prioritas tinggi, pola repetitif)
- [x] **4.1** `master-data/data-category` — hapus inline `<script>`, gunakan komponen + `@push('scripts')`
- [x] **4.2** `master-data/data-brand-name` — idem
- [x] **4.3** `master-data/data-brand-category` — idem
- [x] **4.4** `master-data/data-type` — idem
- [x] **4.5** `master-data/data-sub-category` — idem
- [x] **4.6** `master-data/data-source` — idem
- [x] **4.7** `master-data/data-activity` — idem
- [x] **4.8** `master-data/data-aux-reason` — idem
- [x] **4.9** `master-data/data-status-ticket` — idem
- [x] **4.10** `master-data/data-group-agent` — idem
- [x] **4.11** `master-data/data-group-name` — idem
- [x] **4.12** `master-data/data-site` — idem
- [x] **4.13** `master-data/data-meta` — idem
- [x] **4.14** `master-data/data-fulfillment` — idem
- [x] **4.15** `master-data/data-fulfillment-location` — idem
- [x] **4.16** `master-data/data-holiday` — idem
- [x] **4.17** `master-data/data-max-handle` — idem
- [x] **4.18** `master-data/channel-ticket` — idem
- [x] **4.19** `master-data/department-escalation-unit` — idem

## Phase 5 – Refactor Halaman Management User
- [x] **5.1** `management-user/data-user-application` — ekistrak AlpineJS component ke file JS terpisah
- [x] **5.2** `management-user/data-access-application` — idem
- [x] **5.3** `management-user/level-user-application` — idem
- [x] **5.4** `management-user/export-user-application` — idem

## Phase 6 – Refactor Halaman Apps
- [x] **6.1** `apps/thread-transaction` — hapus inline `<script>`, gunakan komponen
- [x] **6.2** `apps/ticketing-system` — idem
- [x] **6.3** `apps/ticketing-department` — idem
- [x] **6.4** `apps/journey` — idem
- [x] **6.5** `apps/history-ticketing` — idem
- [x] **6.6** `apps/taskboard` — idem

## Phase 7 – Refactor Halaman Lainnya
- [x] **7.1** `channel/email/inbox` & `channel/email/history` — ekstrak script ke `@push('scripts')`
- [ ] **7.2** `setup-channel-email/*` (13 file) — migrasi layout + ekstrak script
- [ ] **7.3** `setting-application/*` — idem
- [ ] **7.4** `master-customer/*` — idem
- [ ] **7.5** `report/*` — idem
- [ ] **7.6** `data-login/*`, `recording/*` — idem

## Phase 8 – Sidebar Cleanup
- [x] **8.1** Ekstrak `<style>` sidebar ke [app.css](file:///c:/laragon/www/kanmo_crm/resources/css/app.css)
- [x] **8.2** Ekstrak fungsi JS sidebar (`toggleSubmenu`, `toggleProfileMenu`, `openAuxModal`) ke [resources/js/sidebar.js](file:///c:/laragon/www/kanmo_crm/resources/js/sidebar.js)

## Phase 9 – Cleanup & Dokumentasi
- [ ] **9.1** Hapus `layouts_backup/` directory
- [ ] **9.2** Hapus `@include('pages.setup-channel-email.partials._scrollbar')` yang sudah tidak dipakai
- [ ] **9.3** Tulis `README-VIEWS.md` — panduan arsitektur view + cara menambah halaman baru
- [ ] **9.4** Verifikasi: pastikan tidak ada `<script>` inline di luar `@push('scripts')` pada seluruh file

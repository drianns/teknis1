# KanmoCRM Blade View Architecture

This document outlines the standardized architecture for Blade views in KanmoCRM, following the refactoring from legacy layouts to a modern, maintainable system.

## 🏗️ Core Layout
All pages must extend the base layout:
```blade
@extends('layouts.app')

@section('content')
    <!-- Main content here -->
@endsection
```

## 🧩 Shared Components
We use a set of standardized components to ensure UI consistency:

### 1. Page Header
`x-page-header`: Displays breadcrumbs, title, and optional action buttons.
```blade
<x-page-header title="Page Title" :breadcrumbs="['Module', 'Submodule']">
    <button>Action Button</button>
</x-page-header>
```

### 2. Data Table
`x-data-table`: A standardized container for AJAX-powered tables.
```blade
<x-data-table>
    <x-slot name="thead">
        <th>ID</th>
        <th>Name</th>
        <th>Action</th>
    </x-slot>
    <!-- tbody is populated via JS loadTable -->
</x-data-table>
```

### 3. Modal
`x-modal`: A reusable modal container with standardized styling.
```blade
<x-modal id="myModal" title="Modal Title" maxWidth="max-w-2xl">
    <!-- Form content -->
    <x-slot name="footer">
        <button onclick="saveData()">Save</button>
    </x-slot>
</x-modal>
```

## 📜 JavaScript Strategy
1. **No Inline Scripts**: Avoid `<script>` tags inside the main `@section`.
2. **Push to Scripts**: All page-specific logic must be wrapped in `@push('scripts')`.
3. **Use CrudTable**: For standard CRUD operations, use the global `window.CrudTable` module.
   - `window.CrudTable.init(config, renderRowCallback)`
   - `window.CrudTable.loadTable()`
   - `window.CrudTable.openModal(id)`

## 🎨 CSS & Styling
1. **Global Styles**: Defined in `resources/css/app.css`.
2. **Tailwind v4**: The project uses TailwindCSS v4 with modern utilities.
3. **Page-specific Styles**: Use `@push('styles')` for unique page requirements.

## 🚀 Adding a New Page
1. Create a directory in `resources/views/pages/`.
2. Create `index.blade.php` extending `layouts.app`.
3. Use `x-page-header`, `x-data-table`, and `x-modal` components.
4. Define `TABLE_CONFIG` and `renderRow` in `@push('scripts')`.
5. Initialize with `CrudTable.init()`.

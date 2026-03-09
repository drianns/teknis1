import './bootstrap';
// sidebar logic is now inline in sidebar.blade.php for instant loading

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;
Alpine.plugin(collapse);
Alpine.start();

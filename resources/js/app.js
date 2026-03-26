import './bootstrap';
import './sidebar';

// Shared utility modules — available globally on all pages
import './modules/loading-overlay';
import './modules/modal-helper';

import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';

window.Alpine = Alpine;
Alpine.plugin(collapse);
Alpine.start();

import { CrudTable } from './modules/crud-table';
window.CrudTable = CrudTable;


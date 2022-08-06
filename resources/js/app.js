import Alpine from 'alpinejs';
import application from './edit.js';

window.alpine = Alpine;
Alpine.data('application', application);
Alpine.start();

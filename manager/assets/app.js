import './bootstrap.js';

import $ from 'jquery';
import 'bootstrap';
import '@coreui/coreui';
import 'lodash-es';
import 'simplebar';

window.$ = $;

import { Sidebar } from '@coreui/coreui';

const header = $('header.header');
document.addEventListener('scroll', () => {
    if (header) {
        header.classList.toggle('shadow-sm', document.documentElement.scrollTop > 0);
    }
});

$('.header-toggle-click').on('click', function () {
    Sidebar.getInstance(document.querySelector('#sidebar')).toggle();
});

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/_variables.css';
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

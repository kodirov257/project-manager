import './bootstrap.js';

import $ from 'jquery';
import 'bootstrap';
import '@coreui/coreui';

window.$ = $;

/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import 'bootstrap/dist/css/bootstrap.min.css';
import '@coreui/coreui/dist/css/coreui.min.css';
import '@coreui/icons/css/all.min.css';
import './styles/app.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

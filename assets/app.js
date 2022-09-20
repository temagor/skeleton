/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
// import './bootstrap';

import { createPinia } from 'pinia';
const pinia = createPinia();
import { createApp } from 'vue';
import ProfileScopeComponent from './components/ProfileScope.vue';
import CheckStateComponent from './components/CheckStateComponent.vue';

const profileScope = createApp()
profileScope
    .component('ProfileScope', ProfileScopeComponent)
    .use(pinia)
    .mount('#profileScope')

const checkState = createApp()
checkState
    .component('CheckState', CheckStateComponent)
    .use(pinia)
    .mount('#checkState')
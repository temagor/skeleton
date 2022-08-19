/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import { createApp } from 'vue';
import SingIn from './components/signIn.vue';
import SingUp from './components/signUp.vue';

const sign = createApp()
sign
    .component('signin', SingIn)
    .component('signup', SingUp)
    .mount('#sign')

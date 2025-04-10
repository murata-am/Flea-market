import Vue from 'https://cdn.jsdelivr.net/npm/vue@2.7.11/dist/vue.esm.browser.js';
import LikeButton from './components/LikeButton.vue';

Vue.component('like-button', LikeButton);

const app = new Vue({
    el: '#app',
});

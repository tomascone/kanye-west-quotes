/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';

import { createApp } from "vue/dist/vue.esm-bundler"
import Toast from "vue-toastification";
// Import the CSS or use your own!
import "vue-toastification/dist/index.css";
import Router from '@/router'
import store from '@/store'
 
const app = createApp({})

const options = {
    // You can set your default options here
};

app.use(Router)
app.use(store)
app.use(Toast, options);
app.mount('#app')
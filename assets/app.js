/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
const $ = require('jquery');
require('bootstrap');

$(document).ready(function () {
    $('[data-toggle="popover"]').popover();
});

// any CSS you import will output into a single css file (app.css in this case)
import './styles/global.scss';
import 'bootstrap';
import bsCustomFileInput from 'bs-custom-file-input';

// start the Stimulus application
import './bootstrap';

bsCustomFileInput.init();

window.darkMode = function () {
    var theme;

    const currentTheme = localStorage.getItem("theme");
    if(typeof currentTheme === "undefined" || currentTheme == "light"){
        theme = "dark";
    }
    else{
        theme = "light";
    }
    document.documentElement.classList.toggle('dark-mode');
    localStorage.setItem("theme", theme);
}

$(function() {
    const currentTheme = localStorage.getItem("theme");
    if(currentTheme == "dark"){
        document.documentElement.classList.toggle('dark-mode');
    }
});
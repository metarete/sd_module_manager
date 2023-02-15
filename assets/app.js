/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */
const $ = require('jquery');
require('bootstrap');



// any CSS you import will output into a single css file (app.css in this case)
import './styles/global.scss';
import 'bootstrap';
import bsCustomFileInput from 'bs-custom-file-input';

// start the Stimulus application
import './bootstrap';

bsCustomFileInput.init();
//icona tema dark

window.darkMode = function () {
    var theme;
    var el = document.getElementById("dark-mode");
    const currentTheme = localStorage.getItem("theme");

    if(typeof currentTheme === "undefined" || currentTheme == "light"){
        theme = "dark";
        $(el).find('i').toggleClass('bi-brightness-high-fill bi-moon-fill');
    }
    else{
        theme = "light";
        $(el).find('i').toggleClass('bi-moon-fill bi-brightness-high-fill ');
    }
    //$(el).find('i').toggleClass('bi-brightness-high-fill bi-moon-fill');
    document.documentElement.classList.toggle('dark-mode');
    localStorage.setItem("theme", theme);
}

$(function() {
    const currentTheme = localStorage.getItem("theme");
    var el = document.getElementById("dark-mode");
    if(currentTheme == "dark"){
        document.documentElement.classList.toggle('dark-mode');
        $(el).find('i').toggleClass('bi-moon-fill bi-brightness-high-fill ');
    }
});
//grafico schede
var percentualeNuove = $("#percentualeNuove").text();
var percentualeApprovate = $("#percentualeApprovate").text();
var percentualeAttive = $("#percentualeAttive").text();
var percentualeInAttesa = $("#percentualeInAttesa").text();
var percentualeChiuse = $("#percentualeChiuse").text();
var percentualeChiuseConRinnovo = $("#percentualeChiuseConRinnovo").text();

var yValues = [percentualeNuove, percentualeApprovate, percentualeAttive, percentualeInAttesa, percentualeChiuse, percentualeChiuseConRinnovo];
var barColors = [
  "#6f42c1",
  "#d63384",
  "#198754",
  "#ffc107",
  "#dc3545",
  "#fd7e14",
];

new Chart("myChart", {
  type: "doughnut",
  data: {
    
    legend: [{
        position: 'top',
        display: false
      }],
    datasets: [{
      backgroundColor: barColors,
      data: yValues
    }]
  },
});
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

// import HTMX library
//import 'htmx.org';
//window.htmx = require('htmx.org');
//import './htmx_functions.js';

bsCustomFileInput.init();
//icona tema dark

window.darkMode = function (elemento) {
  var theme;
  var test = $(elemento);
  const currentTheme = localStorage.getItem("theme");
  
  if(typeof currentTheme === "undefined" || currentTheme == "light"){
      theme = "dark";
      test.find('i').toggleClass('bi-brightness-high-fill bi-moon-fill');
  }
  else{
      theme = "light";
      test.find('i').toggleClass('bi-moon-fill bi-brightness-high-fill ');
  }
  //$(el).find('i').toggleClass('bi-brightness-high-fill bi-moon-fill');
  document.documentElement.classList.toggle('dark-mode');
  localStorage.setItem("theme", theme);
}
$(function() {
  //tema dark
  const currentTheme = localStorage.getItem("theme");
  var el = document.getElementById("dark-mode-mobile");
  var el2 = document.getElementById("dark-mode");
  if(currentTheme == "dark"){
      document.documentElement.classList.toggle('dark-mode');
      $(el).find('i').toggleClass('bi-moon-fill bi-brightness-high-fill ');
      $(el2).find('i').toggleClass('bi-moon-fill bi-brightness-high-fill ');
  }
  //scheda barthel
  $('#barthel_form_deambulazioneValida').on('change', function(e){
    var valoreDeambulazione = $(this).val();
    var selettore = $('#barthel_form_usoCarrozzina');
    if(valoreDeambulazione != 0 || valoreDeambulazione == ''){
      selettore.prop('disabled', 'disabled');
      selettore.prop('required', false);
    }
    else {
      selettore.prop('disabled', false);
    }
  });
  if($('#barthel_form_deambulazioneValida')
  && ($('#barthel_form_deambulazioneValida').val() != 0
  || $('#barthel_form_deambulazioneValida').val() == '')){
    var selettore = $('#barthel_form_usoCarrozzina');
    if(selettore){
      selettore.prop('disabled', 'disabled');
    }

  }
  
  //valutazione generale
  $('#valutazione_generale_form_panf').on('change', function(e){
    var valorePresenzaAssistenteNonFamigiare = $(this).val();
    var selettore = $('#valutazione_generale_form_fanf');
    var labelSelettore = $('.fanf_attr');
    if(valorePresenzaAssistenteNonFamigiare == 'non presente'){
      if(labelSelettore)
      labelSelettore.hide();
      selettore.val('nessuna');
      //selettore.prop('disabled', 'disabled');
      //selettore.prop('required', false);
      selettore.hide();
    }
    else {
      //selettore.prop('disabled', false);
      selettore.show();
      if(labelSelettore)
      labelSelettore.show();
    }
  });
  if($('#valutazione_generale_form_panf')
  && $('#valutazione_generale_form_panf').val() == 'non presente'){
    var selettore = $('#valutazione_generale_form_fanf');
    var labelSelettore = $('.fanf_attr');
    if(selettore){
      //selettore.prop('disabled', 'disabled');
      if(labelSelettore)
        labelSelettore.hide();
      selettore.val('nessuna');
      selettore.hide();
    }

  }
  $("#spinner").click(function() {
    var modale = $('#modal-spinner');
    if(modale){
      modale.show();
    }
    else{
      modale.hide();
    }
  });
  
});

 

//grafico schede
var percentualeNuove = $("#percentualeNuove").text();
var percentualeApprovate = $("#percentualeApprovate").text();
var percentualeAttive = $("#percentualeAttive").text();
var percentualeInAttesa = $("#percentualeInAttesa").text();
var percentualeInAttesaConRinnovo = $("#percentualeInAttesaConRinnovo").text();
var percentualeVerifica = $("#percentualeVerifica").text();
var percentualeChiuse = $("#percentualeChiuse").text();
var percentualeChiuseConRinnovo = $("#percentualeChiuseConRinnovo").text();

var yValues = [percentualeNuove, percentualeApprovate, percentualeAttive, percentualeInAttesa, percentualeInAttesaConRinnovo, percentualeVerifica, percentualeChiuse, percentualeChiuseConRinnovo];
var barColors = [
  "#6f42c1",
  "#d63384",
  "#198754",
  "#ffc107",
  "#1E90FF",
  "#8B4513",
  "#dc3545",
  "#fd7e14",
];
if(typeof Chart !== 'undefined'){ 
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
}



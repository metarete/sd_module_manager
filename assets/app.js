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
  //scheda braden

  $('#braden_form_presenzaPresidiAntidecubito').on('change', function(e){
    var valorePresenzaPresidiAntidecubito = $(this).val();
    var selettore = $('#braden_form_presidiAntidecubito');
    var labelSelettore = $('.presidiAntidecubito_attr');
    var labelSelettore2 = $('.prova');
    if(valorePresenzaPresidiAntidecubito == 'No'){
      if(labelSelettore)
      labelSelettore.hide();
      labelSelettore2.hide();
      selettore.hide();
    }
    else {
      selettore.show();
      if(labelSelettore)
      labelSelettore.show();
      labelSelettore2.show();
    }
    
  });
  if($('#braden_form_presenzaPresidiAntidecubito')
  && ($('#braden_form_presenzaPresidiAntidecubito').val() != 'Si' )
  || ($('#braden_form_presenzaPresidiAntidecubito').val() != '')){
    var selettore = $('#braden_form_presidiAntidecubito');
    var labelSelettore = $('.presidiAntidecubito_attr');
    var labelSelettore2 = $('.prova');
    if(selettore){
      if(labelSelettore)
        labelSelettore.hide();
        labelSelettore2.hide();
        selettore.hide();
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

//audio

var record = document.querySelector('#record');
var stop = document.querySelector('#stop');
var soundClips = document.querySelector('.sound-clips');
var soundClipsAudioGiaPresenti = document.querySelector('.sound-clips-audio-gia-presenti');
var canvas = document.querySelector('.visualizer');
var mainSection = document.querySelector('.main-controls');
var save = document.querySelector('#salva');
var edit = document.querySelector('#modifica');
var idPaziente = document.querySelector('.idPaziente')
var xhr = new XMLHttpRequest();
let arrayBlob = [];
let arrayElementiDaEliminare = [];
let indiceElementoArray = 0;
var newURL = window.location.protocol + "//" + window.location.host


let audioCtx;
var canvasCtx = canvas.getContext("2d");

//blocco per la creazione degli audio

if (navigator.mediaDevices.getUserMedia) {
  console.log('getUserMedia supported.');

  const constraints = { audio: true };
  let chunks = [];

  let onSuccess = function(stream) {
    const mediaRecorder = new MediaRecorder(stream);

    visualize(stream);

    //evento al click del tasto di registrazione
    record.onclick = function() {
      mediaRecorder.start();
      console.log(mediaRecorder.state);
      console.log("recorder started");
      record.style.background = "grey";

      stop.disabled = false;
      record.disabled = true;
    }

    //evento al click del tasto di stop registrazione
    stop.onclick = function() {
      mediaRecorder.stop();
      console.log(mediaRecorder.state);
      console.log("recorder stopped");
      record.style.background = "";
      record.style.color = "";

      stop.disabled = true;
      record.disabled = false;
    }

    //evento al click del tasto salva
    if(save != null){
      save.onclick = function() {
        
        xhr.open("POST", newURL + '/audio/privacy/salva', true);
        xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8');
        xhr.send(
          JSON.stringify({
              testArray : arrayBlob,
              idPaziente : idPaziente.id
          })
        )
        xhr.onload = function(e) {
          // Check if the request was a success
          if (this.readyState === XMLHttpRequest.DONE && this.status === 201) {
            window.location.replace(newURL + '/admin/paziente');
          }
          else{
            window.alert("Errore");
          }
        }
        

      }
    }

    //evento al click del tasto salva modifiche
    if(edit != null){
      edit.onclick = function () {
        
        xhr.open("POST", newURL + '/audio/privacy/modifica', true);
        xhr.setRequestHeader('Content-type', 'application/json; charset=UTF-8');
        xhr.send(
          JSON.stringify({
              testArray : arrayBlob,
              arrayElementiDaEliminare : arrayElementiDaEliminare,
              idAudioPrivacy: soundClips.id,
          })
        )
        xhr.onload = function(e) {
          // Check if the request was a success
          if (this.readyState === XMLHttpRequest.DONE && this.status === 201) {
            window.location.replace(newURL + '/admin/paziente');
          }
          else{
            window.alert("Errore");
          }
        }
        

      }
    }

    //evento in attesa di modifiche nella edit
    if(soundClipsAudioGiaPresenti != null ){
      console.log('ascolto edit');
      const deleteButtons = document.getElementsByName('delete');
      console.log(deleteButtons);
      
      for (let i = 0; i < deleteButtons.length; i++) {
        deleteButtons[i].onclick = function(e) {          
          console.log(i);
          arrayElementiDaEliminare.push(i);
          e.target.closest(".clip").remove();
        }

      }
        
    }

    //metodo chiamato al termine di ogni registrazione
    mediaRecorder.onstop = function(e) {

      //creazione componenti HTML
      console.log("inizio creazione html");
      const clipContainer = document.createElement('article');
      const audio = document.createElement('audio');
      const deleteButton = document.createElement('button');
    
      clipContainer.classList.add('clip');
      audio.setAttribute('controls', '');
      deleteButton.textContent = 'Elimina';
      deleteButton.className = 'btn btn-danger';
      deleteButton.id = 'delete'

      clipContainer.appendChild(audio);
      soundClips.appendChild(clipContainer);

      audio.controls = true;
      const blob = new Blob(chunks, { 'type' : 'audio/mp3' });
      chunks = [];
      //creo url per il blob
      const audioURL = window.URL.createObjectURL(blob);
      audio.src = audioURL;
      //converto il blob in base64
      var reader = new window.FileReader();
      reader.readAsDataURL(blob); 
      var base64 = null;
      console.log("creazione componenti completata");

      //aspetto che l'elemento blob venga creato
      reader.onloadend = function() {
        base64 = reader.result;
        base64 = base64.split(',')[1];
        console.log("onloadend partito");
        //inserisco il blob convertito nell'array da spedire
        console.log(arrayBlob);
        arrayBlob.push(base64);
        console.log(arrayBlob);
      
      }

      deleteButton.id = indiceElementoArray;
      clipContainer.appendChild(deleteButton);
      indiceElementoArray = indiceElementoArray+1;

      //evento al click del tasto delete per ogni audio
      deleteButton.onclick = function(e) {
        console.log('inizio delete');newURL
        delete arrayBlob[e.target.id];
        //arrayBlob.splice(e.target.id, 1); 
        console.log(e.target.id);
        e.target.closest(".clip").remove();
        console.log(arrayBlob);
      }
      
    }

    mediaRecorder.ondataavailable = function(e) {
      chunks.push(e.data);
    }
 
  }

  let onError = function(err) {
    console.log('The following error occured: ' + err);
  }

  navigator.mediaDevices.getUserMedia(constraints).then(onSuccess, onError);

} else {
   console.log('getUserMedia not supported on your browser!');
}

//metodo per la visualizzazione degli audio
function visualize(stream) {
  if(!audioCtx) {
    audioCtx = new AudioContext();
  }

  const source = audioCtx.createMediaStreamSource(stream);

  const analyser = audioCtx.createAnalyser();
  analyser.fftSize = 2048;
  const bufferLength = analyser.frequencyBinCount;
  const dataArray = new Uint8Array(bufferLength);

  source.connect(analyser);
  //analyser.connect(audioCtx.destination);

  draw()

  function draw() {
    const WIDTH = canvas.width
    const HEIGHT = canvas.height;

    requestAnimationFrame(draw);

    analyser.getByteTimeDomainData(dataArray);

    canvasCtx.fillStyle = 'rgb(200, 200, 200)';
    canvasCtx.fillRect(0, 0, WIDTH, HEIGHT);

    canvasCtx.lineWidth = 2;
    canvasCtx.strokeStyle = 'rgb(0, 0, 0)';

    canvasCtx.beginPath();

    let sliceWidth = WIDTH * 1.0 / bufferLength;
    let x = 0;


    for(let i = 0; i < bufferLength; i++) {

      let v = dataArray[i] / 128.0;
      let y = v * HEIGHT/2;

      if(i === 0) {
        canvasCtx.moveTo(x, y);
      } else {
        canvasCtx.lineTo(x, y);
      }

      x += sliceWidth;
    }

    canvasCtx.lineTo(canvas.width, canvas.height/2);
    canvasCtx.stroke();

  }
}

window.onresize = function() {
  canvas.width = mainSection.offsetWidth;
}

window.onresize();

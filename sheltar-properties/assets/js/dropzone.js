// npm package: dropify
// github link: https://github.com/dropzone/dropzone

$(function () {
  'use strict';

  $("exampleDropzone").dropzone({
    url: 'nobleui.com'
  });


});


Dropzone.options.myDropzone = {
  url: 'upload.php',
  autoProcessQueue: false,
  uploadMultiple: true,
  parallelUploads: 5,
  maxFiles: 5,
  maxFilesize: 1,
  acceptedFiles: 'image/*',
  addRemoveLinks: true,
  init: function () {
    dzClosure = this; // Makes sure that 'this' is understood inside the functions below.

    // for Dropzone to process the queue (instead of default form behavior):
    document.getElementById("submit-all").addEventListener("click", function (e) {
      // Make sure that the form isn't actually being sent.
      e.preventDefault();
      e.stopPropagation();
      dzClosure.processQueue();
    });

    //send all the form data along with the files:
    this.on("sendingmultiple", function (data, xhr, formData) {
      formData.append("firstname", jQuery("#firstname").val());
      formData.append("lastname", jQuery("#lastname").val());
    });
  }
}

// Dropzone.autoDiscover = false;
// var dropzone = new Dropzone("#property-images", {
//   maxFiles: 10,
//   acceptedFiles: 'image/*',
//   addRemoveLinks: true,
//   dictDefaultMessage: 'Drop files here or click to upload',
//   sending: function (file, xhr, formData) {
//     formData.append("property-images[]", file);
//   }
// });


$(document).ready(function () {
  Dropzone.autoDiscover = false;
  $("#dZUpload").dropzone({
    url: "hn_SimpeFileUploader.ashx",
    addRemoveLinks: true,
    success: function (file, response) {
      var imgName = response;
      file.previewElement.classList.add("dz-success");
      console.log("Successfully uploaded :" + imgName);
    },
    error: function (file, response) {
      file.previewElement.classList.add("dz-error");
    }
  });
});
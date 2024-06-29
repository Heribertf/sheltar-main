// $(document).ready(function () {
//     // Initialize Dropzone
//     Dropzone.autoDiscover = false;
//     var dropzone = new Dropzone("#property-images", {
//         maxFiles: 10,
//         acceptedFiles: 'image/*',
//         addRemoveLinks: true,
//         dictDefaultMessage: 'Drop files here or click to upload',
//         sending: function (file, xhr, formData) {
//             formData.append("property-images[]", file);
//         }
//     });

//     $("#property-listing").submit(function (e) {
//         e.preventDefault(); // Prevent the default form submission

//         var formData = new FormData(this);

//         $.ajax({
//             type: "POST",
//             url: "./script.php",
//             data: formData,
//             contentType: false,
//             processData: false,
//             success: function (response) {
//                 // Handle the success response
//                 console.log(response);
//             },
//             error: function (xhr, status, error) {
//                 // Handle the error response
//                 console.error(error);
//             }
//         });
//     });
// });

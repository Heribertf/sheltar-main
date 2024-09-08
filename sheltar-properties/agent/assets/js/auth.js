$(document).ready(function () {
    $('#register-form').submit(function (e) {
        e.preventDefault();

        var password = $('#password').val();
        if (!isPasswordStrong(password)) {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Password should be at least 8 characters long and contain a combination of uppercase, lowercase, and numbers.'
            });
            return;
        }

        var loader = document.getElementById("loader");
        loader.style.display = "block";

        var formData = new FormData($(this)[0]);
        $.ajax({
            type: 'POST',
            url: './register-check',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                loader.style.display = "none";
                if (response.success) {
                    Swal.fire({
                        title: "Account Created",
                        text: response.message,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: () => {
                            Swal.showLoading()
                        }
                    }).then((result) => {
                        if (response.email) {
                            window.location.href = "./verification?email=" +
                                response.email;
                        } else {
                            window.location.href = "./sign-in";
                        }
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function (xhr, status, error) {
                loader.style.display = "none";
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An unexpected error occurred'
                });
            }
        });
    });

    function isPasswordStrong(password) {
        // Password should be at least 8 characters long and contain a combination of uppercase, lowercase, and numbers.
        var strongRegex = new RegExp("^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.{8,})");
        return strongRegex.test(password);
    }


    $('#login-form').submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        $.ajax({
            type: 'POST',
            url: './login-check',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {

                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        window.location.href = "./dashboard";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    }).then((result) => {
                        if (response.redirect && response.email) {
                            window.location.href = "./verification?email=" + response.email;
                        }
                    });
                }

            },
            error: function (xhr, status, error) {
                // console.log(xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.'
                });
            }
        });
    });

    $('#verify-form').submit(function (e) {
        e.preventDefault();

        var loader = document.getElementById("loader");
        loader.style.display = "block";

        var formData = new FormData($(this)[0]);
        $.ajax({
            type: 'POST',
            url: './code-check',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                loader.style.display = "none";

                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then((result) => {
                        window.location.href = "./sign-in";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    }).then((result) => {
                        if (response.redirect) {
                            window.location.href = "./sign-in";
                        }
                    });
                }
            },
            error: function (xhr, status, error) {
                loader.style.display = "none";
                // console.log(xhr);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An unexpected error occurred'
                })
            }
        });
    });

    $('.resend').on('click', function (e) {
        e.preventDefault();

        var loader = document.getElementById("loader");
        loader.style.display = "block";

        const userEmail = this.getAttribute('dataEmailId');
        $.ajax({
            type: 'POST',
            url: './resend-code',
            data: {
                userEmail: userEmail
            },
            dataType: 'json',
            success: function (response) {
                loader.style.display = "none";
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then((result) => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function (xhr, status, error) {
                loader.style.display = "none";
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An unexpected error occurred'
                });
            }
        });
    });

    $('#reset-form').submit(function (e) {
        e.preventDefault();

        var loader = document.getElementById("loader");
        loader.style.display = "block";

        var formData = new FormData($(this)[0]);
        $.ajax({
            type: 'POST',
            url: './password-reset',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                loader.style.display = "none";
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then((result) => {
                        window.location.href = "./sign-in";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function (xhr, status, error) {
                loader.style.display = "none";
                TSwal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An unexpected error occurred'
                });
            }
        });
    });

    $('#pass-reset-form').submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        $.ajax({
            type: 'POST',
            url: './update-fgt-password',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    Swal.fire({
                        position: 'top-end',
                        icon: 'success',
                        title: 'Success',
                        text: response.message
                    }).then((result) => {
                        window.location.href = "./sign-in";
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message
                    });
                }
            },
            error: function (xhr, status, error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'An unexpected error occurred'
                });
            }
        });
    });
});



// 'use strict';

// // Display success alert
// function showSuccessAlert(message, callback = null) {
//     Swal.fire({
//         position: 'top-end',
//         icon: 'success',
//         title: 'Success!',
//         text: message,
//         confirmButtonColor: '#3085d6',
//     }).then((result) => {
//         if (result.isConfirmed && callback) {
//             callback();
//         }
//     });
// }

// // Display error alert
// function showErrorAlert(message, callback = null) {
//     Swal.fire({
//         icon: 'error',
//         title: 'Oops...',
//         text: message,
//         confirmButtonColor: '#d33',
//     }).then((result) => {
//         if (result.isConfirmed && callback) {
//             callback();
//         }
//     });
// }

// // AJAX helper function
// function sendAjaxRequest(url, method, data, successCallback, errorCallback) {
//     const xhr = new XMLHttpRequest();
//     xhr.open(method, url, true);
//     xhr.onreadystatechange = function () {
//         if (xhr.readyState === XMLHttpRequest.DONE) {
//             if (xhr.status === 200) {
//                 successCallback(JSON.parse(xhr.responseText));
//             } else {
//                 errorCallback(xhr);
//             }
//         }
//     };
//     xhr.send(data);
// }

// // Event delegation for resend code buttons
// document.addEventListener('click', function (event) {
//     if (event.target.matches('.resend')) {
//         const userEmail = event.target.getAttribute('dataEmailId');
//         sendAjaxRequest('./resend-code', 'POST', `userEmail=${userEmail}`, function (response) {
//             if (response.success) {
//                 showSuccessAlert(response.message, () => location.reload());
//             } else {
//                 showErrorAlert(response.message);
//             }
//         }, function (xhr) {
//             showErrorAlert("An unexpected error occurred");
//         });
//     }
// });

// // Form submissions
// const forms = document.querySelectorAll('form');
// forms.forEach(function (form) {
//     form.addEventListener('submit', function (event) {
//         event.preventDefault();

//         const formData = new FormData(form);
//         const url = form.getAttribute('action');
//         const method = form.getAttribute('method') || 'POST';

//         sendAjaxRequest(url, method, formData, function (response) {
//             if (response.success) {
//                 showSuccessAlert(response.message, () => {
//                     if (response.redirect) {
//                         if (response.email) {
//                             window.location.href = `./verification.php?email=${response.email}`;
//                         } else {
//                             window.location.href = response.redirect;
//                         }
//                     }
//                 });
//             } else {
//                 showErrorAlert(response.message, () => {
//                     if (response.redirect && response.email) {
//                         window.location.href = `./verification.php?email=${response.email}`;
//                     }
//                 });
//             }
//         }, function (xhr) {
//             showErrorAlert("An unexpected error occurred");
//         });
//     });
// });

// // Password strength validation
// function isPasswordStrong(password) {
//     const strongRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.{8,})/;
//     return strongRegex.test(password);
// }

// document.addEventListener('DOMContentLoaded', function () {
//     const registerForm = document.getElementById('register-form');
//     if (registerForm) {
//         registerForm.addEventListener('submit', function (event) {
//             event.preventDefault();

//             const password = document.getElementById('password').value;
//             if (!isPasswordStrong(password)) {
//                 showErrorAlert('Password should be at least 8 characters long and contain a combination of uppercase, lowercase, and numbers.');
//                 return;
//             }

//             registerForm.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
//         });
//     }
// });
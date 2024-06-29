$(document).ready(function () {

    $('#contact-agent').submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        console.log(formData);

        var loader = document.getElementById("loader");
        loader.style.display = "block";

        $.ajax({
            type: 'POST',
            url: './contact-agent.php?request=message',
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
                        text: response.message,
                        // showConfirmButton: false,
                        // timer: 2000
                    }).then((result) => {
                        $('#contact-agent')[0].reset();
                        location.reload();
                    });

                } else {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        stopOnFocus: true,
                        backgroundColor: "linear-gradient(to right, #FF3E4D, #FFA34F)",
                    }).showToast();
                }

            },
            error: function (xhr, status, error) {
                loader.style.display = "none";
                Toastify({
                    text: "An unexpected error occurred.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    stopOnFocus: true,
                    backgroundColor: "linear-gradient(to right, #FF3E4D, #FFA34F)",
                }).showToast();
            }
        });

    });

    $('#request-callback').submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);

        var loader = document.getElementById("loader");
        loader.style.display = "block";

        $.ajax({
            type: 'POST',
            url: './contact-agent.php?request=callback',
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
                        text: response.message,
                        // showConfirmButton: false,
                        // timer: 2000
                    }).then((result) => {
                        $('#request-callback')[0].reset();
                        location.reload();
                    });

                } else {
                    Toastify({
                        text: response.message,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "center",
                        stopOnFocus: true,
                        backgroundColor: "linear-gradient(to right, #FF3E4D, #FFA34F)",
                    }).showToast();
                }

            },
            error: function (xhr, status, error) {
                loader.style.display = "none";
                Toastify({
                    text: "An unexpected error occurred.",
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "center",
                    stopOnFocus: true,
                    backgroundColor: "linear-gradient(to right, #FF3E4D, #FFA34F)",
                }).showToast();
            }
        });

    });
});

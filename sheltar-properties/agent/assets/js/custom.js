document.addEventListener('DOMContentLoaded', function () {
    const paymentBtns = document.querySelectorAll('.payment-btn');

    paymentBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            payWithPaystack(e, btn);
        }, false);
    });
});

function payWithPaystack(e, btn) {
    e.preventDefault();
    const planId = btn.getAttribute('data-plan-id');
    const publicKey = btn.getAttribute('data-key');
    const customerEmail = btn.getAttribute('data-email');
    const planAmount = btn.getAttribute('data-amount');

    let handler = PaystackPop.setup({
        key: publicKey,
        email: customerEmail,
        amount: planAmount * 100,
        currency: 'KES',
        ref: '' + Math.floor((Math.random() * 1000000000) + 1),
        onClose: function () {
            Swal.fire({
                icon: 'warning',
                title: 'Terminated!',
                text: 'Transaction was not completed, window closed.'
            });
        },
        callback: function (response) {
            fetch('http://localhost/sheltar-properties/agent/verify-transaction', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    reference: response.reference,
                    plan_id: planId
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            position: 'top-end',
                            icon: 'success',
                            title: 'Success',
                            text: data.message,
                            // showConfirmButton: false,
                            // timer: 2000
                        }).then((result) => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: data.message
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An error occurred during transaction verification.'
                    });
                });
        }
    });

    handler.openIframe();
}

document.addEventListener('DOMContentLoaded', function () {
    var agentRadio = document.getElementById("agent");
    var landloardRadio = document.getElementById("landloard");
    var agentDetails = document.getElementById("agent-details");
    var landloardDetails = document.getElementById("landloard-details");

    function toggleDetails() {
        if (agentRadio.checked) {
            agentDetails.style.display = "block";
            landloardDetails.style.display = "none";
        } else if (landloardRadio.checked) {
            agentDetails.style.display = "none";
            landloardDetails.style.display = "block";
        }
    }

    agentRadio.addEventListener("change", toggleDetails);
    landloardRadio.addEventListener("change", toggleDetails);

    toggleDetails();

    $('#agent-form').submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        console.log(formData);
        $.ajax({
            type: 'POST',
            url: './request-verification?type=agent',
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
                        // showConfirmButton: false,
                        // timer: 2000
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.'
                });
            }
        });
    });

    $('#landloard-form').submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        console.log(formData);
        $.ajax({
            type: 'POST',
            url: './request-verification?type=landloard',
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
                        // showConfirmButton: false,
                        // timer: 2000
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
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'An unexpected error occurred.'
                })
            }
        });
    });

});

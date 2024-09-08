document.addEventListener('DOMContentLoaded', function () {
    const paymentBtns = document.querySelectorAll('.payment-btn');

    paymentBtns.forEach(btn => {
        btn.addEventListener('click', function (e) {
            payWithMpesa(e, btn);
        }, false);
    });

    function payWithMpesa(e, btn) {
        e.preventDefault();
        const planId = btn.getAttribute('data-plan-id');
        const publicKey = btn.getAttribute('data-key');
        const customerEmail = btn.getAttribute('data-email');
        const planAmount = btn.getAttribute('data-amount');
        const confirmPayment = document.getElementById('confirm-payment');
        const alertSection = document.getElementById('alert-section');
        const paymentStatus = document.getElementById('payment-status');
        const paymentMessage = document.getElementById('payment-message');
        const currency = 'KES';
        const selectedAmount = 10;

        const modalOverlay = document.querySelector('.modal-overlay');
        const goBackBtn = document.querySelector('.go-back');
        const price = document.getElementById('plan-price');
        price.textContent = "KSh " + planAmount;
        modalOverlay.classList.add('active');

        // Remove previous event listeners if any
        goBackBtn.removeEventListener('click', closeModal);
        modalOverlay.removeEventListener('click', handleModalClick);
        confirmPayment.removeEventListener('click', handleConfirmPayment);

        // Add event listeners
        goBackBtn.addEventListener('click', closeModal);
        modalOverlay.addEventListener('click', handleModalClick);
        confirmPayment.addEventListener('click', handleConfirmPayment);

        function closeModal() {
            modalOverlay.classList.remove('active');
            Swal.fire({
                icon: 'warning',
                title: 'Terminated!',
                text: 'Transaction was not completed.'
            });
        }

        function handleModalClick(e) {
            if (e.target === modalOverlay) {
                closeModal();
            }
        }

        function handleConfirmPayment() {
            const phoneNumberPart = document.getElementById('phoneNumber').value;
            const phoneNumber = '254' + phoneNumberPart;
            if (validatePhoneNumber(phoneNumber)) {
                confirmPayment.disabled = true; // Disable the button to prevent multiple clicks
                initiatePayment(planId, selectedAmount, currency, phoneNumber, customerEmail);
            } else {
                alert('Please enter a valid phone number in the format 2547xxxxxxxx');
            }
        }

        function validatePhoneNumber(phoneNumber) {
            const regex = /^254\d{9}$/;
            return regex.test(phoneNumber);
        }

        function initiatePayment(plan, amount, currency, phoneNumber, email) {
            const name = 'Test User';

            alertSection.style.display = 'block';

            fetch('process-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=initiate&plan=${plan}&amount=${amount}&currency=${currency}&phone_number=${phoneNumber}&email=${email}&name=${name}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        paymentMessage.textContent = 'Payment initiated. Please check your phone to complete the transaction.';
                        setTimeout(() => verifyPayment(data.data.invoice.invoice_id), 10000); // Check payment status after 10 seconds
                    } else {
                        paymentStatus.textContent = 'ERROR';
                        paymentMessage.textContent = `Error: ${data.message}`;
                        confirmPayment.disabled = false; // Re-enable the button if there's an error
                    }
                })
                .catch(error => {
                    paymentStatus.textContent = 'ERROR';
                    paymentMessage.textContent = `Error: ${error.message}`;
                    confirmPayment.disabled = false; // Re-enable the button if there's an error
                });
        }

        let retryCount = 0;
        const maxRetries = 6;

        function verifyPayment(transactionId) {
            fetch('process-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `action=verify&transactionId=${transactionId}`
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        handlePaymentState(data.data.invoice);
                    } else {
                        showError(data.message);
                    }
                })
                .catch(error => {
                    showError(error.message);
                });
        }

        function handlePaymentState(invoice) {
            if (invoice.state === 'COMPLETE') {
                alertSection.classList.remove('alert-secondary');
                alertSection.classList.add('alert-success');
                paymentStatus.textContent = 'COMPLETED';
                paymentMessage.textContent = 'Payment successful! Your subscription is now active.';
            } else if (['PENDING', 'PROCESSING'].includes(invoice.state)) {
                paymentStatus.textContent = `${invoice.state.toLowerCase()}...`;
                paymentMessage.textContent = `Payment is still ${invoice.state.toLowerCase()}. Please wait...`;

                if (retryCount < maxRetries) {
                    retryCount++;
                    setTimeout(() => verifyPayment(invoice.invoice_id), 10000);
                } else {
                    alertSection.classList.remove('alert-secondary');
                    alertSection.classList.add('alert-warning');
                    paymentStatus.textContent = `${invoice.state.toLowerCase()}...`;
                    paymentMessage.textContent = `Payment is still ${invoice.state.toLowerCase()} after multiple attempts. If the transaction is successful on your end, please contact support for assistance.`;
                }
            } else {
                showError(`Payment failed. Status: ${invoice.state}`);
            }
        }

        function showError(message) {
            alertSection.classList.remove('alert-secondary');
            alertSection.classList.add('alert-danger');
            paymentStatus.textContent = 'ERROR';
            paymentMessage.textContent = `Error: ${message}`;
            confirmPayment.disabled = false; // Re-enable the button if there's an error
        }
    }


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

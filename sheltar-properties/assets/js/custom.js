// document.getElementById('admin_password').addEventListener('click', function () {
//     document.getElementById('form_error_message').style.display = 'none';
// });


// Get all input elements with the 'hide-error' class
var inputElements = document.querySelectorAll('.hide-error');

// Add click event listeners to each input element
inputElements.forEach(function (inputElement) {
    inputElement.addEventListener('click', function () {
        // Get the corresponding error message element by ID
        var errorMessageId = inputElement.getAttribute('id') + '_error_message';
        var errorMessageElement = document.getElementById(errorMessageId);

        if (errorMessageElement) {
            // Hide the error message element
            errorMessageElement.style.display = 'none';
        }
    });
});


var inputLoginElements = document.querySelectorAll('.login-errors');

inputLoginElements.forEach(function (inputLoginElement) {
    inputLoginElement.addEventListener('click', function () {
        var errorMessageId = inputLoginElement.getAttribute('id') + '_error_message';
        var errorMessageElement = document.getElementById(errorMessageId);

        if (errorMessageElement) {
            errorMessageElement.style.display = 'none';
        }
    });
});

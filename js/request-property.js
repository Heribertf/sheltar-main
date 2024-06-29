$(document).ready(function () {
    var base_color = "rgb(230,230,230)";
    var active_color = "rgb(23, 199, 136)";



    var child = 1;
    var length = $("section").length - 1;
    $("#prev").addClass("disabled");
    $("#submit").addClass("disabled");

    $("section").not("section:nth-of-type(1)").hide();
    $("section").not("section:nth-of-type(1)").css('transform', 'translateX(100px)');

    var svgWidth = length * 200 + 24;
    $("#svg_wrap").html(
        '<svg version="1.1" id="svg_form_time" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 ' +
        svgWidth +
        ' 24" xml:space="preserve"></svg>'
    );

    function makeSVG(tag, attrs) {
        var el = document.createElementNS("http://www.w3.org/2000/svg", tag);
        for (var k in attrs) el.setAttribute(k, attrs[k]);
        return el;
    }

    for (i = 0; i < length; i++) {
        var positionX = 12 + i * 200;
        var rect = makeSVG("rect", { x: positionX, y: 9, width: 200, height: 6 });
        document.getElementById("svg_form_time").appendChild(rect);
        // <g><rect x="12" y="9" width="200" height="6"></rect></g>'
        var circle = makeSVG("circle", {
            cx: positionX,
            cy: 12,
            r: 12,
            width: positionX,
            height: 6
        });
        document.getElementById("svg_form_time").appendChild(circle);
    }

    var circle = makeSVG("circle", {
        cx: positionX + 200,
        cy: 12,
        r: 12,
        width: positionX,
        height: 6
    });
    document.getElementById("svg_form_time").appendChild(circle);

    $('#svg_form_time rect').css('fill', base_color);
    $('#svg_form_time circle').css('fill', base_color);
    $("circle:nth-of-type(1)").css("fill", active_color);


    $(".button").click(function () {
        $("#svg_form_time rect").css("fill", active_color);
        $("#svg_form_time circle").css("fill", active_color);
        var id = $(this).attr("id");
        if (id == "next") {

            const inputs = $("section:nth-of-type(" + child + ")").find('input[type="text"], input[type="number"], input[type="tel"], select');
            let isValid = true;
            inputs.each(function () {
                if ($(this).val().trim() === '') {
                    isValid = false;
                    return false;
                }
            });

            if (isValid) {
                $('#warning-message').hide();
                $("#prev").removeClass("disabled");
                if (child >= length) {
                    $(this).addClass("disabled");
                    $('#submit').removeClass("disabled");
                }
                if (child <= length) {
                    child++;
                }
            } else {
                $('#warning-message').text('Please fill in all the required fields before proceeding.').show();
            }


        } else if (id == "prev") {
            $("#next").removeClass("disabled");
            $('#submit').addClass("disabled");
            if (child <= 2) {
                $(this).addClass("disabled");
            }
            if (child > 1) {
                child--;
            }
        }
        var circle_child = child + 1;
        $("#svg_form_time rect:nth-of-type(n + " + child + ")").css(
            "fill",
            base_color
        );
        $("#svg_form_time circle:nth-of-type(n + " + circle_child + ")").css(
            "fill",
            base_color
        );
        var currentSection = $("section:nth-of-type(" + child + ")");
        currentSection.fadeIn();
        currentSection.css('transform', 'translateX(0)');
        currentSection.prevAll('section').css('transform', 'translateX(-100px)');
        currentSection.nextAll('section').css('transform', 'translateX(100px)');
        $('section').not(currentSection).hide();
    });
    $('#request-property-form').submit(function (e) {
        e.preventDefault();

        var formData = new FormData($(this)[0]);
        console.log(formData);

        var loader = document.getElementById("loader");
        loader.style.display = "block";

        $.ajax({
            type: 'POST',
            url: './process-property-request.php',
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
                        $('#request-property-form')[0].reset();
                        location.reload();
                    });

                } else {
                    Toastify({
                        text: response.message,
                        duration: 3000, // Duration in milliseconds
                        close: true,
                        gravity: "top",
                        position: "center",
                        stopOnFocus: true,
                        backgroundColor: "linear-gradient(to right, #FF3E4D, #FFA34F)",
                    }).showToast();

                    setTimeout(function () {
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
                    }, 3000);

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

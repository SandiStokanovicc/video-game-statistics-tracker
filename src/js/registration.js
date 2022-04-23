$(document).ready(function () {
    $("#buttonSignUp").click(function () {

        var requestData = {
            email: $("#emailSignUp").val(),
            username: $("#usernameSignUp").val(),
            password: $("#passwordSignUp").val(),
            confirm_password: $("#passwordSignUpConfirm").val(),
        };

        // Variable to hold request
        $.post("/rest/registration.php", requestData)
            .done(function () {
                // you will get response from your php page (what you echo or print)
                // show successfully for submit message
                $("#result").html(response);
                console.log("hljeb1");
            })
            .fail(function (response) {
                // Log the error to the console
                // show error
                $("#result").html('There is some error while submit');
                console.error("The following error occurred: " + response.responseText);
            });

        return false;
    });
});
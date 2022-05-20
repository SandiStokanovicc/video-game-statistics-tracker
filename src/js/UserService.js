var UserService = {
    init: function () {
        var token = localStorage.getItem("token");
        if (token) {
        window.location.replace("index.html");
    }


        $('#login-form').validate({
            submitHandler: function (form) {
                var user = Object.fromEntries((new FormData(form)).entries());
                UserService.login(user);
            }
        });
        $('#signup-form').validate({
            submitHandler: function (form) {
                var user = {};
                user.username = $('#usernameSignUp').val();
                user.password = $('#passwordSignUp').val();
                user.email = $('#emailSignUp').val();
                UserService.register(user);
            }
        });


    },
    login: function (user) {
        console.log(JSON.stringify(user));
        
        $.ajax({
            type: "POST",
            url: 'rest/login',
            data: JSON.stringify(user),
            contentType: "application/json",
            dataType: "json",

            success: function (data) {
                console.log(data);
                localStorage.setItem("token", data.token);
                window.location.replace("index.html");

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //console.log(data);
                toastr.error("error");
                console.log(errorThrown);
                console.log(textStatus);
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON.message));
            }
        });
    },

    logout: function () {
        localStorage.clear();
        window.location.replace("index.html");
    },

    register: function (user) {
        
        $.ajax({
            type: "POST",
            url: ' /rest/authentication/register',
            data: JSON.stringify(user),
            contentType: "application/json",
            dataType: "json",

            success: function (data) {
                $('#SignUpModal').modal('toggle');
                localStorage.setItem("token", data.token);
                toastr.success('You have been succesfully registered.');
                localStorage.clear();
                console.log("data")

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error("error");
                console.log(errorThrown);
                console.log(textStatus);
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON.message));
                
            }
        });
    }
}


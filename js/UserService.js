var UserService = {
    parsedUser: "",
    init: function () {
        var token = localStorage.getItem("token");
        if (token) { // if user is logged in
            try {
                var base64Url = token.split('.')[1];      //function for getting data from JWT key
                var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
                var jsonPayload = decodeURIComponent(atob(base64).split('').map(function (c) {
                    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
                }).join(''));
                parsedUser = JSON.parse(jsonPayload);
            }
            catch (err) {
                toastr.error("Invalid token. Reloading in 3 seconds.");
                setTimeout(() => { this.logout(); }, 3000);

            }
            $("#sign-in").addClass('d-none');
            $("#sign-up").addClass('d-none');
            $("#or").addClass('d-none');
            $("#sign-out").removeClass('d-none');

            $("#your-profile").html(parsedUser.username);


        } else { //if user isn't logged in
            $("#sign-in").removeClass('d-none');
            $("#sign-up").removeClass('d-none');
            $("#sign-out").addClass('d-none');
            $("#your-profile").addClass('d-none');
            $("#or").removeClass('d-none');
        }


        // login and signup forms validations and function calls
        $('#login-form').validate({
            rules: {
                emailLogIn: {
                    required: true,
                    email: true
                },
                passwordLogIn: {
                    required: true,
                    minlength: 6
                }
            },
            messages: {
                emailLogIn: {
                    required: "Please enter an email",
                    email: "Please enter a valid email"

                },
                passwordLogIn: {
                    required: "specify password",
                    minlength: "Password must be at least 6 characters long"
                }
            },
            submitHandler: function (form) {
                var user = Object.fromEntries((new FormData(form)).entries());
                UserService.login(user);
            }
        });
        $('#signup-form').validate({
            rules: {
                emailSignUp: {
                    required: true,
                    email: true
                },
                usernameSignUp: {
                    required: true,
                    minlength: 3
                },
                passwordSignUp: {
                    required: true,
                    minlength: 6
                },
                passwordSignUpConfirm: {
                    required: true,
                    minlength: 6,
                    equalTo: "#passwordSignUp" //for checking both passwords are same or not
                },
            },
            messages: {
                usernameSignUp: {
                    required: "Please enter a username",
                    minlength: "Your username must consist of at least 3 characters"
                },
                passwordSignUp: {
                    required: "Please enter a password",
                    minlength: "Your password must be consist of at least 6 characters"
                },
                passwordSignUpConfirm: {
                    required: "Please confirm your password",
                    minlength: "Your password must be consist of at least 6 characters",
                    equalTo: "Please enter the same password as above"
                },
            },
            submitHandler: function (form) {
                var user = {};
                user.username = $('#usernameSignUp').val();
                user.password = $('#passwordSignUp').val();
                user.email = $('#emailSignUp').val();

                UserService.register(user);

            }
        });


    },

    //login
    login: function (user) {

        $.ajax({
            type: "POST",
            url: ' rest/login',
            data: JSON.stringify(user),
            contentType: "application/json",
            dataType: "json",

            success: function (data) {
                localStorage.setItem("token", data.token); //sets jwt token from data
                window.location.replace("index.html");
            },


            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
                console.log(textStatus);
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
            }
        });
    },


    //log out user
    logout: function () {
        localStorage.clear(); //remove token from local storage
        window.location.replace("index.html");
    },


    //register user
    register: function (user) {
        console.log(JSON.stringify(user));
        $.ajax({
            type: "POST",
            url: ' rest/register',
            data: JSON.stringify(user),
            contentType: "application/json",
            dataType: "json",

            success: function (data) {
                $('#SignUpModal').modal('toggle');
                localStorage.setItem("token", data.token);
                toastr.success('You have been succesfully registered.');
                localStorage.clear();
                window.location.replace("index.html");

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
                console.log(textStatus);
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON.message));

            }
        });
    },
}
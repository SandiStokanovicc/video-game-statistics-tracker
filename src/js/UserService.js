var UserService = {
    init: function () {
        var token = localStorage.getItem("token");
        if (token) {
         document.getElementById("sign-in").classList.add('d-none');
         document.getElementById("sign-up").classList.add('d-none');
         document.getElementById("or").classList.add('d-none');
         document.getElementById("sign-out").classList.remove('d-none');
         
    } else {
        document.getElementById("sign-in").classList.remove('d-none');
        document.getElementById("sign-up").classList.remove('d-none');
        document.getElementById("sign-out").classList.add('d-none');
        document.getElementById("your-profile").classList.add('d-none');
        document.getElementById("or").classList.remove('d-none');
    }



        $('#login-form').validate({
            rules : {
                emailLogIn :{
                    required: true,
                    email: true
                },
                passwordLogIn : {
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
            rules : {
                emailSignUp :{
                    required: true,
                    email: true
                },
                usernameSignUp :{
                    required : true,
                    minlength : 3
                },
                passwordSignUp : {
                    required: true,
                    minlength: 6
                },
                passwordSignUpConfirm : {
                    required : true,
                    minlength : 6,
                    equalTo : "#passwordSignUp" //for checking both passwords are same or not
                    },
            },
            messages: {
                usernameSignUp :{
                    required : "Please enter a username",
                    minlength : "Your username must consist of at least 3 characters"
                    },
                    passwordSignUp : {
                    required : "Please enter a password",
                    minlength : "Your password must be consist of at least 6 characters"
                    },
                    passwordSignUpConfirm : {
                    required : "Please confirm your password",
                    minlength : "Your password must be consist of at least 6 characters",
                    equalTo : "Please enter the same password as above"
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
    login: function (user) {
        console.log(JSON.stringify(user));
        
        $.ajax({
            type: "POST",
            url: '/rest/login',
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
        console.log(JSON.stringify(user));
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





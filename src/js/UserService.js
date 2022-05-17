// $(document).ready(function () {
//     $("#buttonSignUp").click(function () {
//         $('#modalFormSign').validate({
//             submitHandler: function(form) {
//               var entity = Object.fromEntries((new FormData(form)).entries());
//               UserService.login(entity);
//             }
//           });
//         var requestData = {
//             email: $("#emailSignUp").val(),
//             username: $("#usernameSignUp").val(),
//             password: $("#passwordSignUp").val(),
           
//         };

//         // Variable to hold request
//         $.post("", requestData)
//             .done(function () {
//                 // you will get response from your php page (what you echo or print)
//                 // show successfully for submit message
//                 $("#result").html();
//                 console.log("hljeb1");
//             })
//             .fail(function (response) {
//                 // Log the error to the console
//                 // show error
//                 $("#result").html('There is some error while submit');
//                 console.error("The following error occurred: " + response.responseText);
//             });

//         return false;
//     });
// });


// var UserService = {
//     init: function () {
//         var token = localStorage.getItem("token");
//         if (token) {
//             window.location.replace("homepage.html");
//         }


//         $('#modalFormLog').validate({
//             submitHandler: function (form) {
//                 var user = Object.fromEntries((new FormData(form)).entries());
//                 UserService.login(user);
//             }
//         });

//         $('#modalFormSign').validate({
//             submitHandler: function (form) {
//                 var user = {};
//                 user.username = $('#usernameSignUp').val();
//                 user.password = $('#passwordSignUp').val();
//                 user.email = $('#emailSignUp').val();
//                 UserService.register(user);
//             }
//         });
//     },

//     login: function (user) {
        
//         $.ajax({
//             type: "POST",
//             url: '/video-game-statistics-tracker/src/rest/authentication/login',
//             data: JSON.stringify(user),
//             contentType: "application/json",
//             dataType: "json",

//             success: function (data) {
//                 console.log(data);
//                 localStorage.setItem("token", data.token);
//                 window.location.replace("index.html");

//             },
//             error: function (XMLHttpRequest, textStatus, errorThrown) {
//                 toastr.error(XMLHttpRequest.responseJSON.message);
//             }
//         });
//     },
//     logout: function(){
//         localStorage.clear();
//         window.location.replace("index.html");
//       },


//     register: function (user) {


//         $.ajax({
//             type: "POST",
//             url: '/rest/authentication/register',
//             data: JSON.stringify(user),
//             contentType: "application/json",
//             dataType: "json",

//             success: function (data) {
//                // $('#modalFormSign').modal('toggle');
//                // localStorage.setItem("token", data.token);
//                // toastr.success('You have been succesfully registered.');
//                 //localStorage.clear();
//                 console.log("knai1");
//             },
//             error: function (XMLHttpRequest, textStatus, errorThrown) {
//                 console.log("knai2");
//                 //toastr.error(XMLHttpRequest.responseJSON.message);
//             }
//         });
//     }
// }
var UserService = {
    init: function () {
       // var token = localStorage.getItem("token");
      //  if (token) {
       //     window.location.replace("index.html");
       // }


        $('#modalFormLog').validate({
            submitHandler: function (form) {
                var user = Object.fromEntries((new FormData(form)).entries());
                UserService.login(user);
            }
        });

        $('#modalFormSign').validate({
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
        $.ajax({
            type: "POST",
            url: ' rest/authentication/login',
            data: JSON.stringify(user),
            contentType: "application/json",
            dataType: "json",

            success: function (data) {
                console.log(data);
               // localStorage.setItem("token", data.token);
               // window.location.replace("index.html");

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest.responseJSON.message);
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
            url: '/video-game-statistics-tracker/src/rest/authentication/register',
            data: JSON.stringify(user),
            contentType: "application/json",
            dataType: "json",

            success: function (data) {
                // $('#SignUpModal').modal('toggle');
                // localStorage.setItem("token", data.token);
                // toastr.success('You have been succesfully registered.');
                // localStorage.clear();
                console.log("data")

            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error("test");
                console.log(errorThrown);
                console.log(textStatus);
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON.message));
                
            }
        });
    }
}


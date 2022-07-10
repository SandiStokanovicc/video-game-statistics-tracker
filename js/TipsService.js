
var TipsService = {
    init: function () {
    $.ajax({
        type: "get",
        url: ' rest/tip',
        contentType: "application/json",

        success: function (data) {
            $('#tip').html(data[0].tipText);
        },


        error: function (XMLHttpRequest, textStatus, errorThrown) {
            //console.log(data);
            toastr.error(XMLHttpRequest.responseJSON.message);
            //toastr.error("error");
            console.log(errorThrown);
            console.log(textStatus);
            console.log(JSON.stringify(XMLHttpRequest));
            console.log(JSON.stringify(XMLHttpRequest.responseJSON));
            console.log(JSON.stringify(XMLHttpRequest.responseJSON.message));
        }
    });
}}
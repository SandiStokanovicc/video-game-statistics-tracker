
var TipsService = {
    //get tip from backend
    init: function () {
    $.ajax({
        type: "get",
        url: ' rest/tip',
        contentType: "application/json",

        success: function (data) {
            //display tip under searchbar
            $('#tip').html(data[0].tipText);
        },
        error: function (XMLHttpRequest, textStatus, errorThrown) {

            console.log(errorThrown);
            console.log(textStatus);
            console.log(JSON.stringify(XMLHttpRequest));
            console.log(JSON.stringify(XMLHttpRequest.responseJSON));
            console.log(JSON.stringify(XMLHttpRequest.responseJSON.message));
        }
    });
}}
var FavouriteService = {
    init: function(summonerName, server){

        var user = new Object();
        user.summonerName = summonerName;
        user.server = server;
        user.userId = parsedUser.iduser;
        console.log(user);
    },

    addFavourite: function (user) {
        console.log(JSON.stringify(user));
        
        $.ajax({
            type: "POST",
            url: ' rest/addFavourite',
            data: JSON.stringify(user),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
            },

            success: function (data) {
                console.log("added");
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
    },
}
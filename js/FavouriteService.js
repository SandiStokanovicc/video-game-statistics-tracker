var FavouriteService = {

    addFavourite: function () {
        console.log(JSON.stringify(user));
        var user = new Object();
        user.favouriteId = 0;
        user.summonerName = searchPlayerInput;
        user.serverId = regionButton;
        user.userId = parsedUser.iduser;
        console.log(user);
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

    init: function () {
        console.log(parsedUser);
        console.log(parsedUser.iduser)
        $.ajax({
            type: "GET",
            url: ' rest/favourites',
            data: parsedUser.iduser,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
            },

            success: function (data) {
                console.log(data);
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
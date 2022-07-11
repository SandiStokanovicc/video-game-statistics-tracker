var FavouriteMatchService = {

    init: function(){
        if(typeof(parsedUser) == 'undefined') $('#showFavouriteMatchesButton').hide(); 
    },

    addFavourite: function (matchIndex) {
        var match = new Object();
        if($('#RegionButton').html() === "na1") match.continent = "americas";
        else match.continent = "europe";
        match.userId = parsedUser.iduser;
        match.mainPlayerPUUID = globalResults.puuid;
        match.APImatchID = globalResults.matchIDs[matchIndex];
        console.log(match);
        $.ajax({
            type: "POST",
            url: ' rest/addFavouriteMatch',
            data: JSON.stringify(match),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
            },

            success: function (data) {
                console.log("added");
            },

            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error("Match is already a favourite.");
                console.log(errorThrown);
                console.log(textStatus);
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
            }
        });
    },

    listFavouriteMatches: function () {
        $.ajax({
            type: "POST",
            url: ' rest/favouriteMatches',
            data: JSON.stringify(parsedUser),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
            },

            success: function (data) {
                //CHECK IF DATA IS EMPTY, IF YES, SHOW MESSAGE, RELOAD MAIN PAGE
                if(data["matches"].length == 0) console.log("empty");
                else{
                    console.log(data);
                    RiotService.displayFavouriteMatches(data);
                }
            },


            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //console.log(data);
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
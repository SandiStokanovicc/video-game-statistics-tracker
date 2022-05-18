var RiotService = {
    getSummonerInfo: function () {
        var requestData = {};

        $.ajax({
            url: '/video-game-statistics-tracker/src/rest/summoners/' + $('#SearchPlayerInput').val() + "/" + $('#RegionButton').html(),

            type: 'GET',
            contentType: "application/json",
            //data nije potrebno jer se svi proslijedjeni podaci koriste u URL-u
            dataType: "json",
            success: function (results) {
                console.log(JSON.stringify(results));
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON.message));
                console.log("Error! RiotService.getSummonerInfo() [script]");
            }
        });
    }

}
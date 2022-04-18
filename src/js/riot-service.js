var RiotService = {
    getSummonerInfo: function () {
        var requestData = {};

        $.ajax({
            url: '../Backend/code/rest/summoners/' + $('#SearchPlayerInput').val() + "/" + $('#RegionButton').html(),
         
            type: 'GET',
            contentType: "application/json",
            //data nije potrebno jer se svi proslijedjeni podaci koriste u URL-u
            dataType: "json",
            success: function (results) {
                console.log(JSON.stringify(results));
            },
            error: function() {
                console.log("Error! RiotService.getSummonerInfo() [script]");
            }
        });
    }

}
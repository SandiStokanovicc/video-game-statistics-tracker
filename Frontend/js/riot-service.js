var RiotService = {
    getSummonerInfo: function () {
        var requestData = {};

        $.ajax({
            url: 'Backend/code/rest/summoners/' + document.getElementById("SearchPlayerInput").value + '/' + document.getElementById('RegionButton').innerText,
            //$('summonerName') je search bar, $('region') bi trebalo biti onaj dropdown
            type: 'GET',
            contentType: "application/json",
            //data nije potrebno jer se svi proslijedjeni podaci koriste u URL-u
            dataType: "json",
            success: function (results) {
                console.log("Printing returned json: " + results);
            },
            error: console.log("Error!")
        });
    }

}
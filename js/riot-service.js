// array.forEach(function(currentValue, index, arr), thisValue)

var RiotService = {
    displaySpinner: function(){
        document.getElementById("main").classList.add('d-none');
        document.getElementById("main-container").classList.remove('d-none');
    },

    displayShowMatches: function() {
        //document.getElementById("main").classList.add('d-none');
        document.getElementById("main-container").classList.add('d-none');
        document.getElementById("matches").classList.remove('d-none');
    },

    getSummonerInfo: function () {
        this.displaySpinner();
        //setTimeout(5000);
        //this.displayShowMatches();

        var requestData = {};

        $.ajax({
            url: 'rest/summoners/' + $('#SearchPlayerInput').val() + "/" + $('#RegionButton').html(),

            type: 'GET',
            contentType: "application/json",
            //data nije potrebno jer se svi proslijedjeni podaci koriste u URL-u
            dataType: "json",
            beforeSend: function(xhr){
                xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
            },
            success: function (results) {
                
                console.log(JSON.stringify(results));
                var html = "";
                //$("#matchContainer").html();
                
                html += `
                <div class="container-sm mb-5 mt-5">
                <div class="row">
                    <div class="col-sm p-4">
                    summonerLevel: `
                        + results.summonerLevel +
                    `
                    <br>profileIconId: ` 
                    + results.profileIconId +
                    `
                    <br>Name: ` 
                    + results.name +
                    //+ results.matches[0].items[0][0] +
                    `
                    <br>Rank Flex: `
                    + results.ranks.RANKED_FLEX_SR.tier + ' ' + results.ranks.RANKED_FLEX_SR.rank + 
                    `
                    <br>Wins: `
                    + results.ranks.RANKED_FLEX_SR.wins + 
                    `
                    <br>Losses: ` + results.ranks.RANKED_FLEX_SR.losses + 
                    `
                    <br>Rank Solo-Duo: `
                    + results.ranks.RANKED_SOLO_5x5.tier + ' ' + results.ranks.RANKED_SOLO_5x5.rank + 
                    `
                    <br>Wins: `
                    + results.ranks.RANKED_SOLO_5x5.wins + 
                    `
                    <br>Losses: ` + results.ranks.RANKED_SOLO_5x5.losses + 
                    `
                    </div>
                </div>
                </div>`;
                
                var i, itemCount;

                for(i = 0; i < 3; i++){
                    html += `
                    <div class="container-sm">
                        <div class="row mb-3">
                            <div class="col-sm p-2" id="match` + (i+1) + `">
                            <br>matchLength: ` + results.matches[i].info.matchLength; 

                            if(results.matches[i].info.win == "true") html += `<br>victory`;
                            else html += `<br>defeat`;

                            html+=`<br><br>Participants:`; 
                            for(var j = 0; j < 10; j++){
                                itemCount = 1;
                                html += `<br><br>Participant ` + j + `<br>Basic info:<br><br>name: ` + results.matches[i].info.participants[j].summonerName +  
                                `<br>champLevel: ` + results.matches[i].info.participants[j].champLevel +
                                `<br>kills: ` + results.matches[i].info.participants[j].kills +
                                `<br>deaths: ` + results.matches[i].info.participants[j].deaths +
                                `<br>assists: ` + results.matches[i].info.participants[j].assists + 
                                `<br>controlWardsPlaced: `  + results.matches[i].info.participants[j].controlWardsPlaced + 
                                `<br>totalDamageDealt: ` + results.matches[i].info.participants[j].totalDamageDealtToChampions +
                                `<br>totalDamageTaken: ` + results.matches[i].info.participants[j].totalDamageTaken +
                                `<br>totalMinionsKilled: ` + results.matches[i].info.participants[j].totalMinionsKilled +
                                `<br>wardsKilled: ` + results.matches[i].info.participants[j].wardsKilled + 
                                `<br>wardsPlaced: ` + results.matches[i].info.participants[j].wardsPlaced + 

                                `<br><br>Participant ` + j + `<br>Items:<br>`;
                                results.matches[i].items[j].forEach(item => {
                                    html +=`<br>Item ` + itemCount + `: ` + item;
                                    itemCount++;
                                });
                                }
                            html+=`</div>
                        </div>
                    </div>`;
                    }
/*
                for(i = 0; i < 2; i++){
                    html += `
                    <div class="container-sm">
                        <div class="row mb-3">
                            <div class="col-sm p-2" id="match` + (i+1) + `">
                                 <br>name: ` + results.matches[0].participants[0].summonerName + `
                            </div>
                        </div>
                    </div>`;
                    }*/
                        $("#matchContainer").html(html);
                        
            },
            complete: function (data) {
                RiotService.displayShowMatches();
                //this.displayShowMatches(); 
               },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest.responseJSON.message);
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
            }
        })
    }
}
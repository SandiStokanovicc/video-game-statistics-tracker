// array.forEach(function(currentValue, index, arr), thisValue)

var RiotService = {
    displaySpinner: function () {
        document.getElementById("background").style.backgroundImage = "url('Pictures/background-blur.png')";
        document.getElementById("main").classList.add('d-none');
        document.getElementById("main-container").classList.remove('d-none');
    },

    displayShowMatches: function () {
        //document.getElementById("main").classList.add('d-none');
        document.getElementById("background").style.backgroundImage = "url('Pictures/background-blur.png')";
        document.getElementById("main-container").classList.add('d-none');
        document.getElementById("matches").classList.remove('d-none');
    },

    unhideMainPageOnFail: function () {
        document.getElementById("background").style.backgroundImage = "url('Pictures/background1.png')";
        document.getElementById("main-container").classList.add('d-none');
        document.getElementById("matches").classList.add('d-none');
        document.getElementById("main").classList.remove('d-none');
    },

    getSummonerInfo: function () {
        this.displaySpinner();
        let searchPlayerInput = $('#SearchPlayerInput').val();
        let regionButton = $('#RegionButton').html().trim();
        if (searchPlayerInput.length == 0) searchPlayerInput = "";
        //console.log(regionButton);
        //setTimeout(5000);
        //this.displayShowMatches();

        var requestData = {};

        $.ajax({
            url: 'rest/summoners/' + searchPlayerInput + "/" + regionButton,

            type: 'GET',
            contentType: "application/json",
            //data nije potrebno jer se svi proslijedjeni podaci koriste u URL-u
            dataType: "json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
            },
            success: function (results) {

                console.log(JSON.stringify(results));
                var html = "";
                //$("#matchContainer").html();

                html += `
                <div class="container mb-5 mt-5">
                <div class="row">
                    <div class="col-sm p-4">
                    profileIconId: `
                    + results.profileIconId +
                    `
                    <br>Name: `
                    + results.name +
                    //+ results.matches[0].items[0][0] +
                    `
                    <br>Summoner Level: `
                    + results.summonerLevel +
                    `
                    </div>
                    <div class="col-sm p-4"">
                    Ranked SoloDuo: `
                    + results.ranks.RANKED_SOLO_5x5.tier + ' ' + results.ranks.RANKED_SOLO_5x5.rank +
                    `
                    <br>Wins: `
                    + results.ranks.RANKED_SOLO_5x5.wins +
                    `
                    <br>Losses: ` + results.ranks.RANKED_SOLO_5x5.losses +
                    `
                    </div>
                    <div class="col-sm p-4"">
                    Ranked Flex: `
                    + results.ranks.RANKED_FLEX_SR.tier + ' ' + results.ranks.RANKED_FLEX_SR.rank +
                    `
                    <br>Wins: `
                    + results.ranks.RANKED_FLEX_SR.wins +
                    `
                    <br>Losses: ` + results.ranks.RANKED_FLEX_SR.losses +
                    `
                    </div>
                </div>
                </div>`;

                var i, itemCount;

                for (i = 0; i < 5; i++) {

                    if (results.matches[i].info.win == "true") {
                        html += `
                        <div id="listallmatches">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item" id="match` + (i + 1) + `">
                                    <h2 class="accordion-header bg-primary p-2" id="flush-heading` + (i + 1) + `">
                                    <button class="accordion-button collapsed bg-primary text-white" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapse` + (i + 1) + `" aria-expanded="false"
                                        aria-controls="flush-collapse` + (i + 1) + `">
                                    Match Length: ` + results.matches[i].info.matchLength + ` minutes
                        <br>Victory`;
                    }
                    else {
                        html += `
                        <div id="listallmatches">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item" id="match` + (i + 1) + `">
                                    <h2 class="accordion-header bg-danger p-2" id="flush-heading` + (i + 1) + `">
                                    <button class="accordion-button collapsed bg-danger text-white" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapse` + (i + 1) + `" aria-expanded="false"
                                        aria-controls="flush-collapse` + (i + 1) + `">
                                    Match Length: ` + results.matches[i].info.matchLength + ` minutes
                        <br>Defeat`;
                    }
                    if (results.matches[i].info.playedBefore > 86399) {
                        html += `<br>Played before: ` + parseInt(results.matches[i].info.playedBefore / 86400) + ` days`
                    }
                    else if (results.matches[i].info.playedBefore > 3599) {
                        html += `<br>Played before: ` + parseInt(results.matches[i].info.playedBefore / 3600) + ` hours`
                    }
                    else {
                        html += `<br>Played before: ` + parseInt(results.matches[i].info.playedBefore / 60) + ` minutes`

                    }
                    //`<br>KDA: ` + results.matches[i].info 
                    html += `<img class="shadow" src="Pictures/champion/` + results.matches[i].info.searchedPlayerInfo.championName + `.png" alt="ChampName" width="100" height="100"></img>
                        <br>Champion: ` + results.matches[i].info.searchedPlayerInfo.championName +
                        `<br>K/ ` + results.matches[i].info.searchedPlayerInfo.kills + ` D/ ` +
                        results.matches[i].info.searchedPlayerInfo.deaths + ` A/ ` + results.matches[i].info.searchedPlayerInfo.assists +
                        ` </button>
                    </h2>` +
                        `<div id="flush-collapse` + (i + 1) + `" class="accordion-collapse collapse" aria-labelledby="flush-heading` + (i + 1) + `"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body text-white">`;
                    for (var j = 0; j < 10; j++) {
                        itemCount = 1;
                        html += `
                            <div class="container">
                            <div class="row">
                            <div class="col-sm" id="playerName">Participant ` + j + `<br>Name: ` + results.matches[i].info.participants[j].summonerName +
                            `<br>Champion Level: ` + results.matches[i].info.participants[j].champLevel +
                            `<br>Champion Name: ` + results.matches[i].info.participants[j].championName + `</div>` +
                            `<div class="col-sm" id="Kills">Kills: ` + results.matches[i].info.participants[j].kills +
                            `<br>Deaths: ` + results.matches[i].info.participants[j].deaths +
                            `<br>Assists: ` + results.matches[i].info.participants[j].assists +
                            `<br>KDA: ` + results.matches[i].info.participants[j].kda + `</div>` +
                            `<div class="col-sm" id="controlWardsPlaced">Control Wards Placed: ` + results.matches[i].info.participants[j].controlWardsPlaced +
                            `<br>Wards Killed: ` + results.matches[i].info.participants[j].wardsKilled +
                            `<br>Wards Placed: ` + results.matches[i].info.participants[j].wardsPlaced + `</div>` +
                            `<div class="col-sm">
                            <div class="col-sm"> Total Damage Dealt: ` + results.matches[i].info.participants[j].totalDamageDealtToChampions + ` 
                            <div class="progress mt-3 mb-3">` +
                            `<div class="progress-bar progress-bar-striped progress-bar-animated bg-info text-dark p-2" role="progressbar" aria-valuenow="` + (results.matches[i].info.participants[j].totalDamageDealtToChampions / 1000) + `"
                            aria-valuemin="0" aria-valuemax="100" style="width:` + (results.matches[i].info.participants[j].totalDamageDealtToChampions / 1000) + `%` + `;" id="totalDamageDealt">` + results.matches[i].info.participants[j].totalDamageDealtToChampions +
                            `</div>
                            </div>
                            </div>` +
                            `<div class="col-sm"> Total Damage Taken: ` + results.matches[i].info.participants[j].totalDamageTaken + `
                            <div class="progress mt-3 mb-3">` +
                            `<div class="progress-bar progress-bar-striped progress-bar-animated bg-danger text-dark p-2" role="progressbar" aria-valuenow="` + (results.matches[i].info.participants[j].totalDamageTaken / 1000) + `"
                            aria-valuemin="0" aria-valuemax="100" style="width:` + (results.matches[i].info.participants[j].totalDamageTaken / 1000) + `%` + `;" id="totalDamageDealt">` + results.matches[i].info.participants[j].totalDamageTaken +
                            `</div>
                            </div>
                            </div>
                            </div>` +
                            `<div class="col-sm">  
                            <div id="minionsKilled"> CS: ` + results.matches[i].info.participants[j].totalMinionsKilled + ` </div> ` +
                            `<div> CS per Minute: ` + (results.matches[i].info.participants[j].totalMinionsKilled / results.matches[i].info.matchLength).toFixed(2) + `</div>
                            </div>
    
                            <div class="col-2">
                            <div class="row">`;

                        results.matches[i].items[j].some(function (item) {
                            if (itemCount == 4) html += `</div><div class="row">`;
                            html += `<div class="col-sm mb-2 p-2"><img class="shadow" src="Pictures/item/` + item + `.png" alt="Item" width="50" height="50"></div>`;
                            itemCount++;
                            return itemCount === 7;
                        });
                        html += `
                        </div>
                        </div>
                            </div>
                            </div>
                            <hr>
                            `;
                        //</hr>results.matches[i].items[j].forEach(item => {
                        //    html += `<div id="itemBought"><br>Item ` + itemCount + `: ` + item + `</div>`;
                        //    itemCount++;
                        //});
                    }
                    html += `       </div>
                                 </div>
                            </div>
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
                RiotService.displayShowMatches();
            },
            //complete: function (data) {
            //RiotService.displayShowMatches();
            //this.displayShowMatches(); 
            //},
            error: function (errorMessage, XMLHttpRequest, textStatus, errorThrown) {
                RiotService.unhideMainPageOnFail();
                $fullErrorMessage = errorMessage.status + ": " + errorMessage.statusText;
                toastr.error($fullErrorMessage);
                console.log(errorMessage);
                console.log($fullErrorMessage);
                //toastr.error(XMLHttpRequest.responseJSON.message);
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
            }
        })
    }
}//if($playedBefore > 86400) $playedBefore /= 86400;
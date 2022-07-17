var RiotService = {
    displaySpinner: function () {
        document.getElementById("background").style.backgroundImage = "url('Pictures/background-blur.png')";
        document.getElementById("main").classList.add('d-none');
        document.getElementById("main-container").classList.remove('d-none');
        document.getElementById("matches").classList.add('d-none');
        document.getElementById("favourites").classList.add('d-none');
    },

    displayShowMatches: function () {
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

    showLiveMatch: function () {
        if ($("#liveMatchButton").html() === "Check Live Game") {
            $("#liveMatchFull").show();
            $("#liveMatchButton").html("Hide Live Game");
            $("#liveMatchButton").removeClass("btn btn-success mb-5;");
            $("#liveMatchButton").addClass("btn btn-danger mb-5;");
        }
        else {
            $("#liveMatchFull").hide();
            $("#liveMatchButton").html("Check Live Game");
            $("#liveMatchButton").removeClass("btn btn-danger mb-5;");
            $("#liveMatchButton").addClass("btn btn-success mb-5;");
        }
    },

    globalResults: "",
    globalPlayerInput: "",
    globalRegion: "",
    getSearch: function () {
        searchPlayerInput = $('#SearchPlayerInput').val();
        regionButton = $('#RegionButton').html().trim();
        RiotService.getSummonerInfo(searchPlayerInput, regionButton)
    },
    getSummonerInfo: function (searchPlayerInput = "", regionButton = "") {
        this.displaySpinner();
        if (regionButton != "") {
            globalRegion = regionButton;
            globalPlayerInput = searchPlayerInput;
        }
        globalRegion = globalRegion.trim();
        console.log(globalRegion);

        $.ajax({
            url: 'rest/summoners/' + globalPlayerInput + "/" + globalRegion,

            type: 'GET',
            contentType: "application/json",
            //data nije potrebno jer se svi proslijedjeni podaci koriste u URL-u
            dataType: "json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
            },
            success: function (results) {
                globalResults = results;
                console.log(JSON.stringify(results));
                var html = "";
                //$("#matchContainer").html();

                html += `
                    <div class="container profile-container mb-5 mt-5">
                    <div class="row profilebox">
                        <div class="col p-4">
                        <img class="shadow profileicons" src="Pictures/profileIcons/` + results.profileIconId + `.png" alt="profileicon"></img>`
                    +
                    `
                        <br>Name:<br> `
                    + results.name +
                    `
                        <br>Summoner Level: `
                    + results.summonerLevel +
                    `
                        </div>
                        <div class="col p-4">
                        <img  class="profileicons" src="Pictures/rank/`
                    + results.ranks[0].tier + '_' + results.ranks[0].rank +
                    `.png" alt="profileicon"></img>
                        <br>`;
                html += results.ranks[0].queueType + `
                        <br>Wins: `
                    + results.ranks[0].wins +
                    `
                        <br>Losses: ` + results.ranks[0].losses +
                    `
                        </div>
                        <div class="col p-4">
                        <img  class="profileicons" src="Pictures/rank/`
                    + results.ranks[1].tier + '_' + results.ranks[1].rank +
                    `.png" alt="profileicon"></img>
                        <br>`;
                html += results.ranks[1].queueType + `
                        <br>Wins: `
                    + results.ranks[1].wins +
                    `
                        <br>Losses: ` + results.ranks[1].losses +
                    `
                        </div>
                        <button type="button" onclick="FavouriteService.addFavourite()" class="btn btn-danger mb-5;">Add Favourite</button>
                    </div>
                    </div>`;
                if (results.liveMatch.IsInMatch == true) {
                    html += `<button id="liveMatchButton" type="button" onclick="RiotService.showLiveMatch()" class="btn btn-success mb-5;">
                        Check Live Game
                    </button>
                    <div class="container mt-4 mb-5" style="display: none" id="liveMatchFull">`;
                    for (i = 0; i < 10; i++) {
                        html += `<div class="container" id="livematch"><div class="row"><div class="col text-center"><p class="text-break mt-3 livematchtext"><br>` + results.liveMatch.participants[i].summonerName + `</p></div>
                            <div class="col "><img class="shadow championicons mt-2 mb-2" src="Pictures/champion/` + results.liveMatch.participants[i].championId + `.png" alt="ChampName"</img></div>
                            <div class="col d-flex justify-content-start"><img class="shadow summspell mt-4 me-2" src="Pictures/summonerSpells/` + results.liveMatch.participants[i].summonerSpell1Id + `.png" alt="ChampName"></img>
                            <img class="shadow summspell mt-4" src="Pictures/summonerSpells/` + results.liveMatch.participants[i].summonerSpell2Id + `.png" alt="ChampName"></img></div>
                            <div class="col"><p class="text-break livematchtext"><br>Banned Champion:</p></div>`;
                        if (results.liveMatch.bannedChampions[i] == undefined) continue;
                        html += `
                        <div class="col"><img class="shadow championicons mt-2 mb-2" src="Pictures/champion/` + results.liveMatch.bannedChampions[i] + `.png" alt="ChampName" width="30" height="30"></img></div></div></div>`;
                    }
                    html += `</div>`;
                }
                if (results.matches.length === 0) {
                    $("#matchContainer").html(html);
                    RiotService.displayShowMatches();
                }

                else {
                    var i;

                    for (i = 0; i < 5; i++) {

                        if (results.matches[i].info.win == "true") {
                            html += `
                        <div id="listallmatches">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item" id="match` + (i + 1) + `">
                                    <h2 class="accordion-header bg-primary p-2" id="flush-heading` + (i + 1) + `">
                                    <button type="button" onclick="FavouriteMatchService.addFavourite(` + i + `)" class="btn btn-primary mb-5;">Add Favourite</button>
                                    <button class="accordion-button collapsed bg-primary text-white" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapse` + (i + 1) + `" aria-expanded="false"
                                        aria-controls="flush-collapse` + (i + 1) + `">
                                        <div class="match-text">
                                    Match Length: ` + results.matches[i].info.matchLength + ` minutes
                        <br>Victory`;
                        }
                        else {
                            html += `
                        <div  id="listallmatches">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item" id="match` + (i + 1) + `">
                                    <h2 class="accordion-header bg-danger p-2" id="flush-heading` + (i + 1) + `">
                                    <button type="button" onclick="FavouriteMatchService.addFavourite(` + i + `)" class="btn btn-danger mb-5;">Add Favourite</button>
                                    <button class="accordion-button collapsed bg-danger text-white" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapse` + (i + 1) + `" aria-expanded="false"
                                        aria-controls="flush-collapse` + (i + 1) + `">
                                        <div class="match-text">
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
                        html += `</div><div class="match-icon"><img class="shadow championicons" src="Pictures/champion/` + results.matches[i].info.searchedPlayerInfo.championId + `.png" alt="ChampName"></img></div>
                        <div class="match-text">Champion: ` + results.matches[i].info.searchedPlayerInfo.championName +
                            `<br>K/ ` + results.matches[i].info.searchedPlayerInfo.kills + ` D/ ` +
                            results.matches[i].info.searchedPlayerInfo.deaths + ` A/ ` + results.matches[i].info.searchedPlayerInfo.assists +
                            `</div> </button>
                    </h2>` +
                            `<div id="flush-collapse` + (i + 1) + `" class="accordion-collapse collapse" aria-labelledby="flush-heading` + (i + 1) + `"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body text-white">`;
                        for (var j = 0; j < 10; j++) {
                            html += `
                            <div class="container">
                            <div class="row">
                            <div class="col-4 col-sm mb-2 mt-2 match-open-text"><p id="playerName" class="mb-md-2">` + results.matches[i].info.participants[j].summonerName +
                                `</p><img class="shadow championicons" src="Pictures/champion/` + results.matches[i].info.participants[j].championId + `.png" alt="ChampName" width="100" height="100"></img>` +
                                `<br>Level: ` + results.matches[i].info.participants[j].champLevel +
                                `</div> <div class="col-4 col-sm mb-2 mt-2 match-open-text" id="Kills">Kills: ` + results.matches[i].info.participants[j].kills +
                                `<br>Deaths: ` + results.matches[i].info.participants[j].deaths +
                                `<br>Assists: ` + results.matches[i].info.participants[j].assists +
                                `<br>KDA: ` + results.matches[i].info.participants[j].kda + `</div>` +
                                `<div class="col-4 col-sm mb-2 mt-2 match-open-text" id="controlWardsPlaced">Control Wards Placed: ` + results.matches[i].info.participants[j].controlWardsPlaced +
                                `<br>Wards Killed: ` + results.matches[i].info.participants[j].wardsKilled +
                                `<br>Wards Placed: ` + results.matches[i].info.participants[j].wardsPlaced + `</div>` +
                                `<div class="col-6 col-sm mt-2 mb-2 match-open-text">
                            <div class="col match-open-text">Damage Dealt: ` + results.matches[i].info.participants[j].totalDamageDealtToChampions + ` 
                            <div class="progress mt-2 mb-2">` +
                                `<div class="progress-bar match-open-text progress-bar-striped progress-bar-animated bg-info text-dark p-2" role="progressbar" aria-valuenow="` + (results.matches[i].info.participants[j].totalDamageDealtToChampions / 1000) + `"
                            aria-valuemin="0" aria-valuemax="100" style="width:` + ((results.matches[i].info.participants[j].totalDamageDealtToChampions / 1000) * 2) + `%` + `;" id="totalDamageDealt">` + " " +
                                `</div>
                            </div>
                            </div>` +
                                `<div class="col match-open-text">Damage Taken: ` + results.matches[i].info.participants[j].totalDamageTaken + `
                            <div class="progress mt-2 mb-2">` +
                                `<div class="progress-bar match-open-text progress-bar-striped progress-bar-animated bg-danger text-dark p-2" role="progressbar" aria-valuenow="` + (results.matches[i].info.participants[j].totalDamageTaken / 1000) + `"
                            aria-valuemin="0" aria-valuemax="100" style="width:` + ((results.matches[i].info.participants[j].totalDamageTaken / 1000) * 2) + `%` + `;" id="totalDamageDealt">` + " " +
                                `</div>
                            </div>
                            </div>
                            </div>` +
                                `<div class="col-6 col-sm mt-2 mb-2 match-open-text">  
                            <div id="minionsKilled"> CS: ` + results.matches[i].info.participants[j].totalMinionsKilled + ` </div> ` +
                                `<div> CS per Minute: ` + (results.matches[i].info.participants[j].totalMinionsKilled / results.matches[i].info.matchLength).toFixed(2) + `</div>
                            </div>
    
                            <div class="col-12 col-md-3 mt-2">`;
                            html += `<div class="row">`
                            html += `<div class="col mb-sm-2 p-2"><img class="item" src="Pictures/item/` +
                                results.matches[i].info.participants[j].item0 + `.png" alt="Item"></div>`;

                            html += `<div class="col mb-sm-2 p-2"><img class="item" src="Pictures/item/` +
                                results.matches[i].info.participants[j].item1 + `.png" alt="Item"></div>`;

                            html += `<div class="col mb-sm-2 p-2"><img class="item" src="Pictures/item/` +
                                results.matches[i].info.participants[j].item2 + `.png" alt="Item"></div>`;
                            html += `</div><div class="row">`;
                            html += `<div class="col mb-sm-2 p-2"><img class="item" src="Pictures/item/` +
                                results.matches[i].info.participants[j].item3 + `.png" alt="Item"></div>`;
                            html += `<div class="col mb-sm-2 p-2"><img class="item" src="Pictures/item/` +
                                results.matches[i].info.participants[j].item4 + `.png" alt="Item"></div>`;

                            html += `<div class="col mb-sm-2 p-2"><img class="item" src="Pictures/item/` +
                                results.matches[i].info.participants[j].item5 + `.png" alt="Item"></div>`;
                            html += `</div><div class="row">`;
                            html += `<div class="col mb-sm-2 p-2"><img class="item" src="Pictures/item/` +
                                results.matches[i].info.participants[j].item6 + `.png" alt="Item"></div>`;
                            html += `
                        </div>
                        </div>
                            </div>
                            </div>
                            <hr>
                            `;
                        }
                        html += `       </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                    }
                    $("#matchContainer").html(html);
                    RiotService.displayShowMatches();
                }
            },
            error: function (errorMessage, XMLHttpRequest, textStatus, errorThrown) {
                RiotService.unhideMainPageOnFail();
                $fullErrorMessage = errorMessage.status + ": " + errorMessage.statusText;
                toastr.error($fullErrorMessage);
                console.log(errorMessage);
            }
        })
    }
}
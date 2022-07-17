var FavouriteMatchService = {
    globalFavouriteMatches: "",

    displaySpinner: function () {
        document.getElementById("background").style.backgroundImage = "url('Pictures/background-blur.png')";
        document.getElementById("main").classList.add('d-none');
        document.getElementById("matches").classList.add('d-none');
        document.getElementById("favourites").classList.add('d-none');
        document.getElementById("main-container").classList.remove('d-none');
    },

    displayShowFavouriteMatches: function () {
        document.getElementById("background").style.backgroundImage = "url('Pictures/background-blur.png')";
        document.getElementById("main-container").classList.add('d-none');
        document.getElementById("main").classList.add('d-none');
        document.getElementById("faq").classList.add('d-none');
        document.getElementById("copyright").classList.add('d-none');
        document.getElementById("about-us").classList.add('d-none');
        document.getElementById("favourites").classList.add('d-none');
        document.getElementById("matches").classList.remove('d-none');
    },

    init: function () {
        if (typeof (parsedUser) == 'undefined') $('#showFavouriteMatchesButton').hide();
    },

    addFavourite: function (matchIndex) {
        var match = new Object();
        if ($('#RegionButton').html() === "na1") match.continent = "americas";
        else match.continent = "europe";
        if (typeof (parsedUser) != 'undefined') {
            match.userId = parsedUser.iduser;
        };
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
                toastr.success("Added to favourites");
            },
            error: function (errorMessage) {
                console.log(errorMessage);
                toastr.error(errorMessage.responseJSON.message);
            }
        });
    },

    removeFavourite: function (matchIndex) {
        var old_html = $("#matchContainer").html();
        $('#match' + (matchIndex + 1)).remove();
        toastr.info("Removing in the background...");
        var match = new Object();
        if ($('#RegionButton').html() === "na1") match.continent = "americas";
        else match.continent = "europe";
        if (typeof (parsedUser) != 'undefined') {
            match.userId = parsedUser.iduser;
        };
        match.APImatchID = globalFavouriteMatches.matchIDs[matchIndex];
        console.log(match);
        $.ajax({
            type: "DELETE",
            url: ' rest/removeFavouriteMatch',
            data: JSON.stringify(match),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
            },

            success: function (data) {
                toastr.success("Removed from favourites");
                var matchContainer = $('#matchContainer')[0];
                var matchClass = $('.matchClass')[0];

                //const matchContainer = 
                if (!matchContainer.contains(matchClass)) {
                    toastr.info("Empty favourites, redirecting...");
                    setTimeout(() => { window.location.replace("index.html"); }, 3000);
                }
            },
            error: function (errorMessage) {
                console.log(errorMessage);
                toastr.error(errorMessage.responseJSON.message);
                $("#matchContainer").html(old_html);
            }
        });
    },

    listFavouriteMatches: function () {
        FavouriteMatchService.displaySpinner();
        var userIdObject = new Object();
        if (typeof (parsedUser) != 'undefined') {
            userIdObject.userId = parsedUser.iduser;
        };
        $.ajax({
            type: "POST",
            url: ' rest/favouriteMatches',
            data: JSON.stringify(userIdObject),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
            },

            success: function (results) {
                globalFavouriteMatches = results;
                console.log(results);
                if (results["matches"].length == 0) console.log("empty");
                else {
                    var i, html = "";
                    html += `
                    <div class="container text-center">
                        <div class="row">
                            <h1 class="mt-5 mb-5">
                                FAVOURITE MATCHES
                            </h1>
                        </div>
                    </div>
                    `;
                    for (i = 0; i < results['matches'].length; i++) {
                        if (results.matches[i].info.win == "true") {
                            html += `
                        <div id="match` + (i + 1) + `", class="matchClass">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item" id="matchDiv2` + (i + 1) + `">
                                    <h2 class="accordion-header bg-primary p-2" id="flush-heading` + (i + 1) + `">
                                    <button type="button" onclick="FavouriteMatchService.removeFavourite(` + i + `)" class="btn btn-primary mb-5;">Remove Favourite</button>
                                    <button class="accordion-button collapsed bg-primary text-white" type="button"
                                        data-bs-toggle="collapse" data-bs-target="#flush-collapse` + (i + 1) + `" aria-expanded="false"
                                        aria-controls="flush-collapse` + (i + 1) + `">
                                        <div class="match-text">
                                    Match Length: ` + results.matches[i].info.matchLength + ` minutes
                        <br>Victory`;
                        }
                        else {
                            html += `
                        <div  id="match` + (i + 1) + `", class="matchClass">
                            <div class="accordion accordion-flush" id="accordionFlushExample">
                                    <div class="accordion-item" id="matchDiv2` + (i + 1) + `">
                                    <h2 class="accordion-header bg-danger p-2" id="flush-heading` + (i + 1) + `">
                                    <button type="button" onclick="FavouriteMatchService.removeFavourite(` + i + `)" class="btn btn-danger mb-5;">Remove Favourite</button>
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
                        html += `</div><div class="match-icon"><img class="shadow championicons" src="Pictures/champion/` + results.matches[i].info.searchedPlayerInfo.championId + `.png" alt="ChampName"></img></div>
                        <div class="match-text">Champion: ` + results.matches[i].info.searchedPlayerInfo.championName +
                            `<br>K/ ` + results.matches[i].info.searchedPlayerInfo.kills + ` D/ ` +
                            results.matches[i].info.searchedPlayerInfo.deaths + ` A/ ` + results.matches[i].info.searchedPlayerInfo.assists +
                            `</div> </button>
                    </h2>` +
                            `<div id="flush-collapse` + (i + 1) + `" class="accordion-collapse collapse" aria-labelledby="flush-heading` + (i + 1) + `"
                    data-bs-parent="#accordionFlushExample">
                    <div class="accordion-body text-white">`;
                        //5 divova
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
                    FavouriteMatchService.displayShowFavouriteMatches();
                    $("#matchContainer").html(html);
                }
            },

            error: function (errorMessage) {
                RiotService.unhideMainPageOnFail();
                console.log(errorMessage);
                toastr.error(errorMessage.responseJSON.message);
            }
        });

    },
}
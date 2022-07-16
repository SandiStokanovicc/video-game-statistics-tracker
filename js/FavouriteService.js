var FavouriteService = {

    displayShowFavouritePlayers: function () {
        document.getElementById("background").style.backgroundImage = "url('Pictures/background-blur.png')";
        document.getElementById("main").classList.add('d-none');
        document.getElementById("matches").classList.add('d-none');
        document.getElementById("favourites").classList.remove('d-none');
    },

    addFavourite: function () {
        var user = new Object();
        user.summonerName = globalPlayerInput;
        user.serverId = globalRegion;
        if (typeof (parsedUser) != 'undefined') {
            user.userId = parsedUser.iduser
        };
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

            error: function (errorMessage) {
                console.log(errorMessage);
                toastr.error(errorMessage.responseJSON.message);
            }
        });
    },

    getFavouritePlayers: function () {
        var parsedUserData = new Object();
        if (typeof (parsedUser) != 'undefined') {
            parsedUserData = parsedUser
        };
        $.ajax({
            type: "POST",
            url: ' rest/favourites',
            data: JSON.stringify(parsedUserData),
            contentType: "application/json",
            dataType: "json",
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
            },

            success: function (data) {
                console.log(data);
                var html = "";
                html += `
                <div id="favouriteplayers">
                    <div class="container text-center">
                        <div class="row">
                            <h1 class="mt-5 mb-5">
                                FAVOURITE PLAYERS
                            </h1>
                        </div>`;
                for (var i = 0; i < data.length; i++) {
                    var info = {};
                    info = FavouriteService.getIcon(data[i].summonerName, data[i].serverId);
                    //console.log(info[0]);
                    //console.log(info[1]);
                    html += `
                        <div class="row mt-4 mb-4" id="favouriteplayer`+ (i + 1) + `">
                        <div class="col">
                        <img class="shadow profileicons mt-3" src="Pictures/profileIcons/` + info[0] + `.png" alt="profileicon"></p>
                            </div>
                            <div class="col">
                            <p class="players-text mt-2 mb-2"> ` + data[i].summonerName + `</p>
                            </div>
                            <div class="col">
                            <p class="players-text mt-2 mb-2"> Summoner Level: ` + info[1] + `</p>
                            </div>
                            <div class="col">
                            <p class="players-text mt-2 mb-2">` + data[i].serverId + `</p>
                            </div>
                            <button type="button" onclick="RiotService.getSummonerInfo('` + data[i].summonerName + `',' ` + data[i].serverId + `')" class="btn btn-danger mb-5;">Show matches</button>
                        </div>
                        `;
                }
                html += `
                    </div>
                </div>
                `;
                ;
                $("#favouritesContainer").html(html);
                FavouriteService.displayShowFavouritePlayers();
            },
            error: function (errorMessage) {
                console.log(errorMessage);
                toastr.error(errorMessage.responseJSON.message);
            }
        });

    },

    getIcon: function (summonerName, server) {
        var info = {};
        $.ajax({
            type: "GET",
            url: ' rest/favList/' + summonerName + '/' + server,
            contentType: "application/json",
            async: false,
            beforeSend: function (xhr) {
                xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
            },

            success: function (data) {

                info[0] = data.profileIconId;
                info[1] = data.summonerLevel;

            },

            error: function (errorMessage) {
                console.log(errorMessage);
                toastr.error(errorMessage.responseJSON.message);
            }
        });
        return info;
    }
}
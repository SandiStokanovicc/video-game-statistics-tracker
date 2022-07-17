var FavouriteService = {

    displaySpinner: function () {
        document.getElementById("background").style.backgroundImage = "url('Pictures/background-blur.png')";
        document.getElementById("main").classList.add('d-none');
        document.getElementById("matches").classList.add('d-none');
        document.getElementById("favourites").classList.add('d-none');
        document.getElementById("main-container").classList.remove('d-none');
    },

    displayShowFavouritePlayers: function () {
        document.getElementById("background").style.backgroundImage = "url('Pictures/background-blur.png')";
        document.getElementById("main-container").classList.add('d-none');
        document.getElementById("main").classList.add('d-none');
        document.getElementById("faq").classList.add('d-none');
        document.getElementById("copyright").classList.add('d-none');
        document.getElementById("about-us").classList.add('d-none');
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
        FavouriteService.displaySpinner();
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
            async: true,
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
                        <div class="row mt-4 mb-4 shadow" id="favouriteplayer` + (i + 1) + ` class=favouriteClass">
                        <div class="col">
                            <img class="shadow profileicons mt-3 mb-3 favmatch" src="Pictures/profileIcons/` + info[0] + `.png" alt="profileicon" onclick="RiotService.getSummonerInfo('` + data[i].summonerName + `',' ` + data[i].serverId + `')">
                        </div>
                        <div class="col">
                            <p class="players-text"> ` + data[i].summonerName + `</p>
                        </div>
                        <div class="col">
                            <p class="players-text"> Summoner Level: ` + info[1] + `</p>
                        </div>
                        <div class="col">
                            <p class="players-text">` + data[i].serverId + `</p>
                        </div>
                            <button type="button" onclick="FavouriteService.removeFavouriteSummoner('` + data[i].summonerName + `',' ` + data[i].serverId + `',' ` + i + `')" class="btn btn-danger mt-5;">Remove Favourite</button>
                        </div>
                        `;
                }
                html += `
                    </div>
                </div>
                `;
                FavouriteService.displayShowFavouritePlayers();
                $("#favouritesContainer").html(html);
            },
            error: function (errorMessage) {
                console.log(errorMessage);
                toastr.error(errorMessage.responseJSON.message);
                RiotService.unhideMainPageOnFail();
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
    },

    removeFavouriteSummoner: function (summonerName, serverId, favouriteIndex) {
        var old_html = $("#favouriteContainer").html();
        $('#favouriteplayer' + (favouriteIndex + 1)).remove();
        toastr.info("Removing in the background...");
        var user = new Object();
        if (typeof (parsedUser) != 'undefined') {
            user.userId = parsedUser.iduser;
            user.summonerName = summonerName;
            user.serverId = serverId;
            console.log(user);
            $.ajax({
                type: "DELETE",
                url: ' rest/removeFavourite',
                data: JSON.stringify(user),
                contentType: "application/json",
                dataType: "json",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
                },

                success: function (data) {
                    toastr.success("Removed from favourites");
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
        }
    }
}
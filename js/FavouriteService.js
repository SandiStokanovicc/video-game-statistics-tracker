var FavouriteService = {

    displaySpinner: function () {
        $("#background").css({"background-image": "url('Pictures/background-blur.png')"});
        $("#main").addClass('d-none');
        $("#matches").addClass('d-none');
        $("#favourites").addClass('d-none');
        $("#main-container").removeClass('d-none');
    },

    displayShowFavouritePlayers: function () {
        $("#background").css({"background-image": "url('Pictures/background-blur.png')"});
        $("#main-container").addClass('d-none');
        $("#main").addClass('d-none');
        $("#faq").addClass('d-none');
        $("#copyright").addClass('d-none');
        $("#about-us").addClass('d-none');
        $("#matches").addClass('d-none');
        $("#favourites").removeClass('d-none');
    },

    addFavourite: function () {
        var user = new Object();                //creating user object for easier transfer of data to backend
        user.summonerName = globalPlayerInput;
        user.serverId = globalRegion;
        if (typeof (parsedUser) != 'undefined') {
            user.userId = parsedUser.iduser
        };
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
                toastr.success("added");
            },

            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
                console.log(textStatus);
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
            }
        });
    },

    //displays favourite players
    getFavouritePlayers: function () {
        FavouriteService.displaySpinner();
        var parsedUserData = new Object();
        if (typeof (parsedUser) != 'undefined') { //if user is logged in
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
                    html += `
                        <div class="row mt-4 mb-4 shadow favouriteClass " id="favouriteplayer` + (i + 1) + `">
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
                RiotService.unhideMainPageOnFail();
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
                //gets data for summoner from api call and adds into array, sends array to get favourites
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

    //removes a summoner from favourites
    removeFavouriteSummoner: function (summonerName, serverId, favouriteIndex) {
        var old_html = $("#favouritesContainer").html();
        var favDel = '#favouriteplayer' + (parseInt(favouriteIndex) + 1); //Deleting the summoner in frontend
        $(favDel).remove();
        toastr.info("Removing in the background...");
        var user = new Object(); //object for backend
        if (typeof (parsedUser) != 'undefined') {
            user.userId = parsedUser.iduser;
            user.summonerName = summonerName;
            user.serverId = serverId;
            $.ajax({
                type: "DELETE",
                url: ' rest/removeFavourite',
                data: JSON.stringify(user),
                contentType: "application/json",
                dataType: "json",
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('Authorization', localStorage.getItem("token"));
                },

                success: function () {
                    toastr.success("Removed from favourites");
                    var favContainer = $('#favouritesContainer')[0];           //checking if user needs to be redirected since 0 summoners
                    var favClass = $('.favouriteClass')[0];     
                    if (!favContainer.contains(favClass)) {
                        toastr.info("Empty favourites, redirecting...");
                        setTimeout(() => { window.location.replace("index.html"); }, 3000);
                    }
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    $("#favouritesContainer").html(old_html);
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
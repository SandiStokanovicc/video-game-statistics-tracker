var FavouriteService = {

    displayShowFavouritePlayers: function () {
        document.getElementById("background").style.backgroundImage = "url('Pictures/background-blur.png')";
        document.getElementById("main").classList.add('d-none');
        document.getElementById("favourites").classList.remove('d-none');
    },

    addFavourite: function () {
        var user = new Object();
        user.summonerName = searchPlayerInput;
        user.serverId = regionButton;
        user.userId = parsedUser.iduser;
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


            error: function (XMLHttpRequest, textStatus, errorThrown) {
                //console.log(data);
                //toastr.error("error");
                toastr.error("User is already a favourite.");
                console.log(errorThrown);
                console.log(textStatus);
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
            }
        });
    },

    getFavouritePlayers: function () {
        $.ajax({
            type: "POST",
            url: ' rest/favourites',
            data: JSON.stringify(parsedUser),
            contentType: "application/json",
            dataType: "json",
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
                for (var i = 0; i < 5; i++) {
                    html += `
                        <div class="row mt-4 mb-4" id="favouriteplayer`+ (i + 1) + `">
                            <div class="col">
                            
                            </div>
                            <div class="col">
                                
                            </div>
                            <div class="col">
                                
                            </div>
                            <div class="col">
                                
                            </div>
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

    },
}
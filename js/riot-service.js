var RiotService = {
    getSummonerInfo: function () {
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
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                toastr.error(XMLHttpRequest.responseJSON.message);
                console.log(JSON.stringify(XMLHttpRequest));
                console.log(JSON.stringify(XMLHttpRequest.responseJSON));
            }
        });
    }

}
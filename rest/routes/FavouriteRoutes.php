<?php

/**
* @OA\POST(path="/addFavourite", tags={"favourite players"}, security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="User id and summoner info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="userId", type="integer", example=10, description="Id of the logged in user"),
*           @OA\Property(property="summonerName", type="string", example="Condemn for Stun", description="The summoner name of the favourite player"),
*           @OA\Property(property="serverId", type="string", example="eun1", description="The server that the favourite summoner to be is on"),
*     )
*     )),
*     @OA\Response(response="200", description="Match was added to favourites"),
*     @OA\Response(response="400", description="Reached max number of favourite matches | Match was already added to favourites"),
*     @OA\Response(response="403", description="Trying to access blocked data | Authorization is missing | Authorization token is not valid")
* )
*/

// ADD FAVOURITE
Flight::route("POST /addFavourite",  function(){
  $data = Flight::request()->data->getData(); 
  $summonerName = $data['summonerName'];
  $userId = $data['userId'];
  if($userId != Flight::get('user')['iduser']){ //security layer, so users can't change another users favs
    Flight::json(["message" => "Trying to access blocked data"], 403);
    die;
  }
  $currentUser = Flight::favouriteService()->getIdAndSummonerName($userId, $summonerName); //checks wheteher the user already has the summoner as a fav
  if(!isset($currentUser['userId'])){
    $favourite = Flight::favouriteService()->add($data); //if not,adds
    Flight::json($favourite);
  }
  else{  
    Flight::json(["message" => "User is already a favourite."], 400);
  }
});



/**
* @OA\POST(path="/favourites", tags={"favourite players"}, security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="user's id", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(@OA\Property(property="userId", type="integer", example=10, description="id of the logged in user"))
*     )),
*     @OA\Response(response="200", description="Fetch favourite summoners for user"),
*     @OA\Response(response="400", description="No favourites to display"),
*     @OA\Response(response="403", description="Trying to access blocked data | Authorization is missing | Authorization token is not valid")
* )
*/

//LIST ALL FAVOURITES FOR SUMMONER
 Flight::route("POST /favourites", function(){
   
   $user = Flight::request()->data->getData();
   $userId = $user['iduser'];
   $favourites = Flight::favouriteService()->getFavouriteById($userId); //gets all favourites for user with specific id
   if(sizeof($favourites) == 0){ 
    Flight::json(["message" => "No favourites to display"], 400); 
    die;
  }
   Flight::json($favourites);
  });


  Flight::route('GET /favList/@summonerName/@region', function($summonerName, $region){ 
    Flight::json(Flight::riotService()->getSummonerInfo($summonerName, $region)); //gets basic info of summoner, used to return icons and levels of fav summoners
  });

/**
* @OA\DELETE(path="/removeFavourite", tags={"favourite players"}, security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="User id and summoner info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="userId", type="integer", example=10, description="Id of the logged in user"),
*           @OA\Property(property="summonerName", type="string", example="Condemn For Stun", description="The summoner name of the favourite player"),
*           )
*     )),
*     @OA\Response(response="200", description="The player was from favourites"),
*     @OA\Response(response="400", description="Trying to delete a player that is not in favourites"),
*     @OA\Response(response="403", description="Trying to access blocked data | Authorization is missing | Authorization token is not valid")
* )
*/

//removes a summoner from favourites
  Flight::route('DELETE /removeFavourite',  function(){
    $data = Flight::request()->data->getData();
    $summonerName = $data['summonerName'];
    $userId = $data['userId'];
    if($userId != Flight::get('user')['iduser']){ //security layer
      Flight::json(["message" => "Trying to access blocked data"], 403);
      die;
    }
    $currentPlayer = Flight::favouriteService()->getIdAndSummonerName($userId, $summonerName); //checks if summoner exists
    if(isset($currentPlayer['userId'])){
    Flight::favouriteService()->delete($currentPlayer['id']); //if summoner exists and is in favs, delets from favs
    Flight::json(["message" => "Summoner was deleted from favourites"], 200);
    }
    else{
      Flight::json(["message" => "Trying to delete a player that is not in favourites"], 400);
    }
  });
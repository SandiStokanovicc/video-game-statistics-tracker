<?php

/**
* @OA\Post(path="/favouriteMatches", tags={"favourite matches"}, security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="user's id", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(@OA\Property(property="userId", type="integer", example=10, description="id of the logged in user"))
*     )),
*     @OA\Response(response="200", description="Fetch favourite matches for player"),
*     @OA\Response(response="400", description="No matches to display"),
*     @OA\Response(response="403", description="Trying to access blocked data | Authorization is missing | Authorization token is not valid")
* )
*/

Flight::route('POST /favouriteMatches', function(){ 
  $user = Flight::request()->data->getData();
  $userId = $user['userId'];
  if($userId != Flight::get('user')['iduser']){
    Flight::json(["message" => "Trying to access blocked data"], 403);
    die;
  }
  $favouriteMatches = Flight::favouriteMatchService()->getFavouriteMatchesByUserId($userId);
  if(sizeof($favouriteMatches) == 0){ 
    Flight::json(["message" => "No matches to display"], 400); 
    die;
  }
  Flight::json(Flight::riotService()->getFavouriteMatches($favouriteMatches));
});


/**
* @OA\Post(path="/addFavouriteMatch", tags={"favourite matches"}, security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="User id and match info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="userId", type="integer", example=10, description="Id of the logged in user"),
*           @OA\Property(property="APImatchID", type="string", example="EUN1_3168965332", description="Match ID assigned by the RIOT API"),
*           @OA\Property(property="continent", type="string", example="europe", description="Continent (server) on which the match was played"),
*           @OA\Property(property="mainPlayerPUUID", type="string", example="RrrjwpQHidYmA62ikOk7idGtxtuHpCgeBCbWCV1mKbaeRDF2i_IHBIFvTlHH0fWpmhFZT-K60HgIew", description="RIOT API ID for the user"),
*     )
*     )),
*     @OA\Response(response="200", description="Match was added to favourites"),
*     @OA\Response(response="400", description="Reached max number of favourite matches | Match was already added to favourites"),
*     @OA\Response(response="403", description="Trying to access blocked data | Authorization is missing | Authorization token is not valid")
* )
*/

Flight::route("POST /addFavouriteMatch",  function(){
    $data = Flight::request()->data->getData();
    $APIMatchID = $data['APImatchID'];
    $userId = $data['userId'];
    if($userId != Flight::get('user')['iduser']){
      Flight::json(["message" => "Trying to access blocked data"], 403);
      die;
    }
    $continent = $data['continent'];
    $currentMatch = Flight::favouriteMatchService()->getIdMatchIDContinent($userId, $APIMatchID, $continent);
    if(!isset($currentMatch['userId'])){
      $numOfFavsInDB = Flight::favouriteMatchService()->countFavMatchesByID($userId);
      if($numOfFavsInDB['count'] >= 5) {
        Flight::json(["message" => "Reached max number of favourite matches"], 400);
        die;
      }
      $favouriteMatch = Flight::favouriteMatchService()->add($data);
      Flight::json($favouriteMatch);
    }
    else{
      Flight::json(["message" => "Match was already added to favourites"], 400);
    }
});

/**
* @OA\Delete(path="/removeFavouriteMatch", tags={"favourite matches"}, security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="User id and match info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*           @OA\Property(property="userId", type="integer", example=10, description="Id of the logged in user"),
*           @OA\Property(property="APImatchID", type="string", example="EUN1_3168965332", description="Match ID assigned by the RIOT API"),
*           @OA\Property(property="continent", type="string", example="europe", description="Continent (server) on which the match was played"),
*           )
*     )),
*     @OA\Response(response="200", description="Match was removed from favourites"),
*     @OA\Response(response="400", description="Trying to delete non-existing match..."),
*     @OA\Response(response="403", description="Trying to access blocked data | Authorization is missing | Authorization token is not valid")
* )
*/

Flight::route("DELETE /removeFavouriteMatch",  function(){
  $data = Flight::request()->data->getData();
  $APIMatchID = $data['APImatchID'];
  $userId = $data['userId'];
  if($userId != Flight::get('user')['iduser']){
    Flight::json(["message" => "Trying to access blocked data"], 403);
    die;
  }
  $continent = $data['continent'];
  $currentMatch = Flight::favouriteMatchService()->getIdMatchIDContinent($userId, $APIMatchID, $continent);
  if(isset($currentMatch['userId'])){
  Flight::favouriteMatchService()->delete($currentMatch['id']);
  Flight::json(["message" => "Match was removed from favourites"], 200);
  }
  else{
    Flight::json(["message" => "Trying to delete non-existing match..."], 400);
  }
});
?>
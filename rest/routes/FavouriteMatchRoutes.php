<?php

/**
* @OA\POST(path="/favouriteMatches/", tags={"favourite matches"}, security={{"ApiKeyAuth": {}}},
*     @OA\RequestBody(description="Basic user info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(@OA\Property(property="userId", type="integer", example=10, description="id of the logged in user"))
*     )),
*     @OA\Response(response="200", description="Fetch favourite matches for player")
* )
*/

Flight::route('POST /favouriteMatches', function(){ 
  $user = Flight::request()->data->getData();
  $userId = $user['userId'];
  $favouriteMatches = Flight::favouriteMatchService()->getFavouriteMatchesByUserId($userId);
  if(sizeof($favouriteMatches) == 0){ 
    Flight::json(["message" => "No matches to display"], 500); 
    die;
  }
  Flight::json(Flight::riotService()->getFavouriteMatches($favouriteMatches));
});

Flight::route("POST /addFavouriteMatch",  function(){
    $data = Flight::request()->data->getData();
    //var_dump($data); die;
    $APIMatchID = $data['APImatchID'];
    $userId = $data['userId'];
    $continent = $data['continent'];
    $currentMatch = Flight::favouriteMatchService()->getIdMatchIDContinent($userId, $APIMatchID, $continent);
    if(!isset($currentMatch['userId'])){
    $favouriteMatch = Flight::favouriteMatchService()->add($data);
    Flight::json($favouriteMatch);
    }
    else{
      Flight::json(["message" => "Match was already added to favourites"], 500);
    }
});

Flight::route("DELETE /removeFavouriteMatch",  function(){
  $data = Flight::request()->data->getData();
  //var_dump($data); die;
  $APIMatchID = $data['APImatchID'];
  $userId = $data['userId'];
  $continent = $data['continent'];
  $currentMatch = Flight::favouriteMatchService()->getIdMatchIDContinent($userId, $APIMatchID, $continent);
  if(isset($currentMatch['userId'])){
  $favouriteMatch = Flight::favouriteMatchService()->deleteFavouriteMatch(Flight::get('user'), $APIMatchID, $continent);
  Flight::json($favouriteMatch);
  }
  else{
    Flight::json(["message" => "Trying to delete non-existing match..."], 500);
  }
});
   ?>
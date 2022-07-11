<?php

/**
* @OA\Get(path="/summoners/{summonerName}/{region}", tags={"riot"}, security={{"ApiKeyAuth": {}}},
*     @OA\Parameter(in="path", name="summonerName", example="Condemn for Stun", description="player's name"),
*     @OA\Parameter(in="path", name="region", example="eun1", description="player's server / region"),
*     @OA\Response(response="200", description="Fetch last 5 matches for player")
* )
*/

// NOT FINISHED!!! Napomeni me ako zaboravim zavrsiti :)
Flight::route('POST /favouriteMatches', function(){ 
  $user = Flight::request()->data->getData();
  $userId = $user['iduser'];
  $favouriteMatches = Flight::favouriteMatchService()->getFavouriteMatchesByUserId($userId);
  
  //Flight::json($favouriteMatches);
  Flight::json(Flight::riotService()->getFavouriteMatches($favouriteMatches));
});

Flight::route("POST /addFavouriteMatch",  function(){
    $data = Flight::request()->data->getData();
    //var_dump($data); die;
    $APIMatchID = $data['APImatchID'];
    $userId = $data['userId'];
    $currentMatch = Flight::favouriteMatchService()->getIdAndMatchID($userId, $APIMatchID);
    if(!isset($currentMatch['userId'])){
    $favouriteMatch = Flight::favouriteMatchService()->add($data);
    Flight::json($favouriteMatch);
  }
});

   

   ?>
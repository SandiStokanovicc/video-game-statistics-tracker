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
  
  Flight::json(Flight::riotService()->getFavouriteMatches($favouriteMatches));
});
Flight::map('result', function ($status, $result) {
  Flight::response()->status($status)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE,OPTIONS,PATCH')->header('Access-Control-Allow-Headers', 'Content-Type')->header('Content-Type', 'application/json')->write(utf8_decode(json_encode($result)))->send();
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
    else{
      Flight::json(["message" => "Match was already added to favourites"], 500);
    }
});

   

   ?>
<?php
/**
* @OA\Get(path="/tip", tags={"tips"},
*     @OA\Response(response="200", description="Fetch a random tip from the database"),
* )
*/

//gets a random tip on each call
Flight::route('GET /tip', function(){
    return Flight::json(Flight::tipsService()->getTip());
});
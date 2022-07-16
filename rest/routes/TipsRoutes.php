<?php
/**
* @OA\Get(path="/tip", tags={"tips"},
*     @OA\Response(response="200", description="Fetch a random tip from the database"),
* )
*/

Flight::route('GET /tip', function(){
    return Flight::json(Flight::tipsService()->getTip());
});
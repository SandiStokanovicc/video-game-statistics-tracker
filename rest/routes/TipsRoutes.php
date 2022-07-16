<?php
/**
* @OA\POST(path="/tip", tags={"favourite players"}, security={{"ApiKeyAuth": {}}},
*       @OA\MediaType(mediaType="application/json",
*     )),
*     @OA\Response(response="200", description="Returns a random tip from the database"),
*     @OA\Response(response="403", description="Trying to access blocked data | Authorization is missing | Authorization token is not valid")
* )
*/
Flight::route('GET /tip', function(){
    return Flight::json(Flight::tipsService()->getTip());
});
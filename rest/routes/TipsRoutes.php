<?php

Flight::route('GET /tip', function(){
    return Flight::json(Flight::tipsService()->getTip());
});
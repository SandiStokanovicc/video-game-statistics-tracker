<?php
    Flight::route('/register', function (){
    Flight::json(Flight::trackerDao()->register());
});
?>
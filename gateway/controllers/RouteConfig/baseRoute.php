<?php 
    
    require_once 'config.php';
    require_once './controllers/auth.php';

    Route::base('/', function(){
        echo 'Api is up and running';
        return;
    });

    Route::base('/index.php', function(){
        echo 'Api is up and running';
        return;
    });

    Route::isRouteValid()
?>
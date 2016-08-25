<?php

    /**
     * Initializes the application and database connection
     */

    $config = require 'config/config.php';
    require 'lib/Player.php';
    require 'lib/HashCheck.php';
    require 'lib/Spin.php';
    require 'lib/UserSafeException.php';
    
    $db = new PDO(
        'mysql:dbname=' . $config['database'] 
        . ';host=' . $config['host'] 
        . ';port=' . $config['port'],
        $config['username'], 
        $config['password']
    );
    
    Player::setDefaultDB($db);
    
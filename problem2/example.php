<?php

    require 'bootstrap.php';

    $playerId = 1;
    $coinsWon = 53;
    $coinsBet = 56;
    
    $player = new Player($playerId);
    
    $hashChecker = new HashCheck;
    $hash = $hashChecker->calculateHash($player->salt, $coinsWon, $coinsBet, $player->player_id);
    
    $url = $config['api_url'] 
        . '?player_id=' . $playerId 
        . '&coins_won=' . $coinsWon
        . '&coins_bet=' . $coinsBet
        . '&hash=' . $hash;
    
    echo "Requesting: $url <br />\n";
    echo file_get_contents($url);
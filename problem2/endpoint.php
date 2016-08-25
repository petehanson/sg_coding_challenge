<?php

    /**
     * An API endpoint for recording a spin for a player
     *
     * Assumes that any sender possessing the player's salt value has permission
     * to credit or debit the user's credit balance.
     *
     * Requires the parameters:
     * player_id - must be a valid player id
     * coins_won - must be an integer >= 0
     * coins_bet - must be an integer > 0 and less than the player's credit balance
     * hash      - must be a valid sha256 hmac hash of "coins_won,coins_bet,player_id"
     *
     * Returns a JSON object
     * On an error, the object contains a single key 'error' containg an error message
     * On success, the object contains the keys 'player_id', 'name', 'credits', 'lifetime_spins', 'lifetime_avg_return' representing the values of those fields after the spin
     */

    require 'bootstrap.php';

    $input = [
        'player_id' => isset($_GET['player_id']) ? $_GET['player_id'] : 0,
        'coins_won' => isset($_GET['coins_won']) ? $_GET['coins_won'] : 0,
        'coins_bet' => isset($_GET['coins_bet']) ? $_GET['coins_bet'] : 0,
        'hash' => isset($_GET['hash']) ? $_GET['hash'] : '',
    ];
    
    $response = [];
    
    try {
        $player = new Player($input['player_id']);
        
        $hashChecker = new HashCheck;
        $hashChecker->validateHash($player, $input['hash'], $input['coins_won'], $input['coins_bet']);
        
        $spin = new Spin($player);
        $spin->spin($input['coins_won'], $input['coins_bet']);
        
        $response = [
            'player_id' => $player->player_id,
            'name' => $player->name,
            'credits' => $player->credits,
            'lifetime_spins' => $player->lifetime_spins,
            'lifetime_avg_return' => $player->lifetimeAverageReturn(),
        ];
    } catch(UserSafeException $ex) {
        $response = [
            'error' => $ex->getMessage(),
        ];
    } catch (Exception $ex) {
        $response = [
            'error' => 'An error occurred while processing the request.',
        ];
    }
    
    echo json_encode($response);
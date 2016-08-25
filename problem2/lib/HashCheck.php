<?php

/**
 * Calculates and validates request signature hashes
 */
class HashCheck {
    
    /**
     * Calculate the signature hash for a request
     */
    public function calculateHash($salt, $coinsWon, $coinsBet, $playerId)
    {
        $sigBody = ((int) $coinsWon)
            . ','
            . ((int) $coinsBet)
            . ','
            . ((int) $playerId);
            
        return hash_hmac('sha256', $sigBody, $salt);
    }
    
    /**
     * Validate a hash for a request
     * Throws an exception if the hash is not valid or cannot be validated
     */
    public function validateHash(Player $player, $hash, $coinsWon, $coinsBet)
    {
        if (!$player || !$player->player_id || !$player->salt) {
            throw new UserSafeException("Invalid player");
        }
        
        if (!$hash) {
            throw new UserSafeException("Missing hash");
        }
        
        $expected = $this->calculateHash($player->salt, $coinsWon, $coinsBet, $player->player_id);
        
        if (!$expected) {
            throw new UserSafeException("Error during hash validation");
        }
        
        if ($expected !== $hash) {
            throw new UserSafeException("Invalid hash");
        }
    }
    
}
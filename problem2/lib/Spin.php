<?php

/**
 * Business logic for executing a spin
 */
class Spin {
   
    protected $player;
   
    public function __construct(Player $player) {
        $this->player = $player;
    }
    
    /**
     * Records a spin to the database
     * Throws an exception if any parameters are invalid
     */
    public function spin($coinsWon, $coinsBet) {
        if ($coinsWon < 0) {
            throw new UserSafeException("Coins won must be greater than or equal to 0");
        }
        
        if ($coinsBet <= 0) {
            throw new UserSafeException("Coins bet must be greater than 0");
        }
        
        if ($coinsBet > $this->player->credits) {
            throw new UserSafeException("Player cannot bet more coins than they own.");
        }
        
        $this->player->credits += $coinsWon;
        $this->player->credits -= $coinsBet;
        $this->player->lifetime_spins++;
        $this->player->lifetime_returns += $this->calculateReturn($coinsWon, $coinsBet);
        $this->player->save();
    }
    
    /**
     * Calculates the return-on-investment for this spin
     */
    protected function calculateReturn($coinsWon, $coinsBet) {
        return ($coinsWon - $coinsBet) / $coinsBet;
    }
}
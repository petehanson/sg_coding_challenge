<?php

/**
 * Model for storing player data
 */
class Player {
    static $defaultDB;
    
    /**
     * Set the PDO database connection to use for new Player objects
     */
    public static function setDefaultDB($defaultDB) {
        self::$defaultDB = $defaultDB;
    }
    
    /**
     * Retreive the default PDO database connection to use for new Player objects
     */
    public static function getDefaultDB() {
        return self::$defaultDB;
    }
    
    // Database fields
    public $player_id;
    public $name;
    public $credits;
    public $lifetime_spins;
    public $salt;
    public $lifetime_returns;
    
    protected $db;
    
    /**
     * Retrieves player data for the player with the given player ID
     * Throws an exception if retrieval of data fails
     */
    public function __construct($id, $db = null) {
        $this->db = $db ? $db : self::getDefaultDB();
        $this->setPlayerId($id);
        $this->getRecordData();
    }
    
    /**
     * Calculates the average return-on-investment per spin for the user across
     * all spins that user has made.  Returned as an unrounded percentage.
     */
    public function lifetimeAverageReturn() {
        return $this->lifetime_returns / $this->lifetime_spins * 100;
    }
    
    /**
     * Commit object data to the database
     */
    public function save() {
        $stmt = $this->db->prepare('UPDATE player SET name = :name, credits = :credits, lifetime_spins = :lifetime_spins, salt = :salt, lifetime_returns = :lifetime_returns WHERE player_id = :id');
        
        return $stmt->execute([
            'name' => $this->name,
            'credits' => $this->credits,
            'lifetime_spins' => $this->lifetime_spins,
            'salt' => $this->salt,
            'id' => $this->player_id,
            'lifetime_returns' => $this->lifetime_returns,
        ]);
    }
    
    /**
     * Validate and set the player ID for this object
     * Throws an exception if the ID is invalid
     */
    protected function setPlayerId($id) {
        $id = (int)$id;
        
        if (!$id) {
            throw new UserSafeException("Invalid Player ID");
        }
        
        $this->player_id = $id;
    }
    
    /**
     * Retrieve object data for this player from the database
     * Throws an exception if retrieval fails
     */
    protected function getRecordData() {
        $stmt = $this->db->prepare('SELECT player_id, name, credits, lifetime_spins, salt, lifetime_returns FROM player WHERE player_id = :id');
        $stmt->execute([':id' => $this->player_id]);
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        
        if ($row) {
            $this->player_id = $row->player_id;
            $this->name = $row->name;
            $this->credits = $row->credits;
            $this->lifetime_spins = $row->lifetime_spins;
            $this->salt = $row->salt;
            $this->lifetime_returns = $row->lifetime_returns;
        } else {
            throw new UserSafeException("Player does not exist");
        }
    }
}
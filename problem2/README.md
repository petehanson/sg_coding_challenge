Requirements
----------------
* PHP 5.6+
* MySQL 5.5+

Setup
----------------
1. Copy the file config/config.template.php to config/config.php and edit the values in it.
2. Use sql/schema.sql to populate the database structure.
3. Optionally, use sql/sample-data.sql to load sample player data.

API Endpoint
----------------
The API endpoint is endpoint.php.  It accepts a GET request with the following query string parameters and records a spin for the specified player.
```
player_id - must be a valid player id
coins_won - must be an integer >= 0
coins_bet - must be an integer > 0 and less than the player's credit balance
hash      - must be a valid sha256 hmac code of "coins_won,coins_bet,player_id" using the player's salt as the key
```

If all parameters validate, the database will be updated to record a spin for the specified player.  The players credit balance will be increased by coins_won and decreased by coins_bet, their lifetime spin counter will be increased by one, and their lifetime average return per spin will be updated.  The API will return a JSON object with the following keys:
```
player_id           - id of the updated player
name                - name of the updated player
credits             - credit balance of the updated player (after the spin)
lifetime_spins      - total number of lifetime spins for the player (including the most recent)
lifetime_avg_return - average return-on-investment per spin, as a percentage, for all spins made by the player
```

If an error occurs, a JSON object with a single key named 'error' will be returned.  The 'error' key contains a string error message.

Notes
----------------
The API assumes that any system possessing the player's salt has permission to update the player's credit balance.  I would not give this permission to any code running on an end-user's system, so I'm assuming the API request is being issued by another secure server that has calculated whether the spin is a win or not.

In a production system, I would add both a timestamp parameter and a non-reusable nonce parameter to the API request for better security.  The non-reusable nonce parameter would protect against a replay attack where an attacker intercepts and then reissues the same API request multiple times.  The timestamp would protect partially against a scenario where an attacker intercepts a valid API request that is never actually issued for some reason, and then attempts to issue it at a much later date/time.

If starting a new project, I would build this functionality in a framework like Laravel rather than plain PHP as I've done in this sample.
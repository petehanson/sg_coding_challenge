CREATE TABLE `player` (
  `player_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `credits` bigint(20) NOT NULL,
  `lifetime_spins` bigint(20) unsigned NOT NULL,
  `salt` char(32) NOT NULL,
  `lifetime_returns` numeric(32, 16) NOT NULL,
  PRIMARY KEY (`player_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
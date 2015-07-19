CREATE TABLE `address` (
  `id` int(11) NOT NULL,
  `address` varchar(128) NOT NULL,
  `label` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `data` (
  `id` int(11) NOT NULL,
  `bid` varchar(128) NOT NULL,
  `diff` int(11) NOT NULL,
  `txs` varchar(128) NOT NULL,
  `tx_sum` varchar(128) NOT NULL,
  `address` varchar(128) NOT NULL,
  `time` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `data_market` (
  `id` int(11) NOT NULL,
  `value` int(11) NOT NULL,
  `usd` varchar(128) NOT NULL,
  `time` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `global` (
  `id` int(11) NOT NULL,
  `hash` varchar(128) NOT NULL,
  `ghash` varchar(128) NOT NULL DEFAULT '0',
  `time` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mn_count` (
  `id` int(11) NOT NULL,
  `count` int(11) NOT NULL,
  `time` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `mn_data` (
  `id` int(11) NOT NULL,
  `ip` varchar(128) NOT NULL,
  `port` int(11) NOT NULL,
  `status` varchar(128) NOT NULL,
  `version` varchar(128) NOT NULL,
  `address` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `node` (
  `id` int(11) NOT NULL,
  `ip` varchar(128) NOT NULL,
  `country` varchar(128) NOT NULL,
  `users` int(11) NOT NULL,
  `hash` varchar(128) NOT NULL,
  `fee` varchar(128) NOT NULL,
  `uptime` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `params` (
  `id` int(11) NOT NULL,
  `key` varchar(128) NOT NULL,
  `value` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `price` (
  `id` int(11) NOT NULL,
  `price` varchar(128) NOT NULL,
  `time` varchar(128) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `hosting` (
  `id` int(11) NOT NULL,
  `ip` varchar(128) NOT NULL,
  `txid` varchar(128) DEFAULT NULL,
  `key` varchar(128) DEFAULT NULL,
  `out` int(11) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  `last` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `address`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `address` (`address`);

ALTER TABLE `data`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `bid` (`bid`), ADD KEY `address` (`address`);

ALTER TABLE `data_market`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `global`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mn_count`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `mn_data`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `ip` (`ip`);

ALTER TABLE `node`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `ip` (`ip`);

ALTER TABLE `params`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `price`
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE `hosting`
  ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `ip` (`ip`), ADD KEY `key` (`txid`);


ALTER TABLE `address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `data_market`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `global`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mn_count`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `mn_data`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `node`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `params`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `price`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `hosting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

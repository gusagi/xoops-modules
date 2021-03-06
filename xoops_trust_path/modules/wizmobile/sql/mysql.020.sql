DROP TABLE IF EXISTS `{prefix}_{_dirname_}_login`;
CREATE TABLE `{prefix}_{_dirname_}_login` (
    `wml_login_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    `wml_uid` MEDIUMINT UNSIGNED NOT NULL ,
    `wml_uniqid` VARCHAR(32) NOT NULL,
    `wml_init_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wml_update_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wml_delete_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    PRIMARY KEY  (`wml_login_id`)
) Type=MyISAM;
ALTER TABLE `{prefix}_{_dirname_}_login` ADD INDEX `wml_idx` (`wml_uid` , `wml_uniqid` , `wml_delete_datetime`) ;


DROP TABLE IF EXISTS `{prefix}_{_dirname_}_config`;
CREATE TABLE `{prefix}_{_dirname_}_config` (
    `wmc_config_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    `wmc_item` VARCHAR(64) NOT NULL,
    `wmc_value` VARCHAR(32) NOT NULL,
    `wmc_init_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wmc_update_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wmc_delete_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    PRIMARY KEY  (`wmc_config_id`)
) Type=MyISAM;
ALTER TABLE `{prefix}_{_dirname_}_config` ADD INDEX `wmc_idx` (`wmc_item`, `wmc_delete_datetime`) ;


DROP TABLE IF EXISTS `{prefix}_{_dirname_}_block`;
CREATE TABLE `{prefix}_{_dirname_}_block` (
    `wmb_block_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    `wmb_bid` MEDIUMINT UNSIGNED NOT NULL ,
    `wmb_init_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wmb_update_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wmb_delete_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    PRIMARY KEY  (`wmb_block_id`)
) Type=MyISAM;
ALTER TABLE `{prefix}_{_dirname_}_block` ADD INDEX `wmb_idx` (`wmb_bid`, `wmb_delete_datetime`) ;


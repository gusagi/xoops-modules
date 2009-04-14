ALTER TABLE `{prefix}_{_dirname_}_config` RENAME TO `{prefix}_{_dirname_}_configs`;
ALTER TABLE `{prefix}_{_dirname_}_module` RENAME TO `{prefix}_{_dirname_}_modules`;


DROP TABLE IF EXISTS `{prefix}_{_dirname_}_devices`;
CREATE TABLE `{prefix}_{_dirname_}_devices` (
    `wmd_device_id` MEDIUMINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    `wmd_uid` MEDIUMINT UNSIGNED NOT NULL ,
    `wmd_email` VARCHAR(60),
    `wmd_uniqid` VARBINARY(32) NOT NULL,
    `wmd_identifier` VARBINARY(16) NOT NULL,
    `wmd_init_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wmd_update_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wmd_delete_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    PRIMARY KEY  (`wmd_device_id`)
) Type=MyISAM;
ALTER TABLE `{prefix}_{_dirname_}_devices` ADD UNIQUE INDEX `wmd_idx_unique` (`wmd_uid` , `wmd_uniqid`) ;
ALTER TABLE `{prefix}_{_dirname_}_devices` ADD INDEX `wmd_idx_select` (`wmd_identifier`, `wmd_delete_datetime`) ;
INSERT INTO `{prefix}_{_dirname_}_devices` (`wmd_uid`, `wmd_uniqid`, `wmd_identifier`, `wmd_init_datetime`, `wmd_update_datetime`) SELECT `wml_uid` AS `wmd_uid`, `wml_uniqid` AS `wmd_uniqid`, '' AS `wmd_identifier`, `wml_init_datetime` AS `wmd_init_datetime`, `wml_update_datetime` AS `wmd_update_datetime` FROM `{prefix}_{_dirname_}_login`;
DROP TABLE IF EXISTS `{prefix}_{_dirname_}_login`;


DROP TABLE IF EXISTS `{prefix}_{_dirname_}_blocks`;
CREATE TABLE `{prefix}_{_dirname_}_blocks` (
    `wmb_block_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    `wmb_bid` MEDIUMINT UNSIGNED NOT NULL ,
    `wmb_visible` SMALLINT NOT NULL default 0,
    `wmb_weight` SMALLINT NOT NULL default 0,
    `wmb_init_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wmb_update_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wmb_delete_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    PRIMARY KEY  (`wmb_block_id`)
) Type=MyISAM;
# -- wmb_visible : 0->invisible, 1->visible;
ALTER TABLE `{prefix}_{_dirname_}_blocks` ADD INDEX `wmb_idx` (`wmb_bid`, `wmb_visible`, `wmb_weight`, `wmb_delete_datetime`) ;
INSERT INTO `{prefix}_{_dirname_}_blocks` (`wmb_bid`, `wmb_init_datetime`, `wmb_update_datetime`) SELECT `wmb_bid`, `wmb_init_datetime`, `wmb_update_datetime` FROM `{prefix}_{_dirname_}_block` WHERE `wmb_delete_datetime` = '0000-00-00 00:00:00';
DROP TABLE IF EXISTS `{prefix}_{_dirname_}_block`;


DROP TABLE IF EXISTS `{prefix}_{_dirname_}_themes`;
CREATE TABLE `{prefix}_{_dirname_}_themes` (
    `wmt_theme_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    `wmt_mid` MEDIUMINT NOT NULL,
    `wmt_groupid` MEDIUMINT NOT NULL,
    `wmt_theme_name` VARCHAR(100) NOT NULL ,
    `wmt_init_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wmt_update_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wmt_delete_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    PRIMARY KEY  (`wmt_theme_id`)
) Type=MyISAM;
# -- wmt_mid : -1->all module 0->top page;
# -- wmt_groupid : -1->guest 0->registered user;
ALTER TABLE `{prefix}_{_dirname_}_themes` ADD INDEX `wmt_idx` (`wmt_theme_name`, `wmt_delete_datetime`) ;


DROP TABLE IF EXISTS `{prefix}_{_dirname_}_atypical`;
CREATE TABLE `{prefix}_{_dirname_}_atypical` (
    `wma_atypical_id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT ,
    `wma_item` VARCHAR(64) NOT NULL,
    `wma_value` BLOB NOT NULL,
    `wma_init_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wma_update_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    `wma_delete_datetime` datetime NOT NULL default '0000-00-00 00:00:00',
    PRIMARY KEY  (`wma_atypical_id`)
) Type=MyISAM;
ALTER TABLE `{prefix}_{_dirname_}_atypical` ADD INDEX `wma_idx` (`wma_item`, `wma_delete_datetime`) ;

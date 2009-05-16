ALTER TABLE `{prefix}_{_dirname_}_devices` CHANGE `wmd_email` `wmd_email` VARCHAR(60) BINARY;
ALTER TABLE `{prefix}_{_dirname_}_devices` CHANGE `wmd_uniqid` `wmd_uniqid` VARCHAR(32) BINARY;
ALTER TABLE `{prefix}_{_dirname_}_devices` CHANGE `wmd_identifier` `wmd_identifier` VARCHAR(16) BINARY;
ALTER TABLE `{prefix}_{_dirname_}_configs` CHANGE `wmc_value` `wmc_value` VARCHAR(32) BINARY NOT NULL;

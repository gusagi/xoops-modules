<?php
/**
 * PHP Versions 4.4.X or upper version
 *
 * @package  WizMobile
 * @author  Makoto Hashiguchi a.k.a. gusagi<gusagi@gusagi.com>
 * @copyright 2008 Makoto Hashiguchi
 * @license GNU General Public License Version2
 *
 */

/**
 * GNU General Public License Version2
 *
 * Copyright (C) 2008  < Makoto Hashiguchi a.k.a. gusagi >
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 */

// direct access protect
$scriptFileName = getenv('SCRIPT_FILENAME');
if ($scriptFileName === __FILE__) {
    exit();
}

// init process
$xcRoot =& XCube_Root::getSingleton();
$xoopsTpl = WizXc_Util::getXoopsTpl();
$frontDirname = str_replace('_wizmobile_action', '', strtolower(get_class($this)));
$tplFile = 'db:' . $frontDirname . '_admin_block_setting.html';

// get block list
$blocks = array();
$existsBlocks = array();
$newBlocks = $this->getBlocks();
$wizmobileBlocks = $this->getWizMobileBlocks();
$wizmobileBlockKeys = array_keys($wizmobileBlocks);
foreach (array_keys($newBlocks) as $key) {
    $bid = $newBlocks[$key]['bid'];
    $existsBlocks[] = $bid;
    $newBlocks[$key]['wmb_bid'] = $newBlocks[$key]['bid'];
    $newBlocks[$key]['wmb_visible'] = $newBlocks[$key]['visible'];
    $newBlocks[$key]['wmb_weight'] = $newBlocks[$key]['weight'];
    if (in_array($newBlocks[$key]['bid'], $wizmobileBlockKeys)) {
        $newBlocks[$key]['wmb_visible'] = $wizmobileBlocks[$bid]['wmb_visible'];
        $newBlocks[$key]['wmb_weight'] = $wizmobileBlocks[$bid]['wmb_weight'];
    }
    if ($newBlocks[$key]['wmb_visible'] != 0) {
        $index = $newBlocks[$key]['wmb_visible'] .'_' .$newBlocks[$key]['wmb_weight'] .'_' .
            $newBlocks[$key]['mid'] .'_' .$newBlocks[$key]['bid'];
    } else {
        $index = '99_0_' .$newBlocks[$key]['mid'] .'_' .$newBlocks[$key]['bid'];
    }
    $blocks[$index] = $newBlocks[$key];
}
ksort($blocks);
unset($newBlocks);

// get default bid of config
$configs = $this->getConfigs();
if (isset($configs['default_bid'])) {
    $defaultBid = $configs['default_bid']['wmc_value'];
} else {
    $defaultBid = '';
}

// register and redirect
$method = getenv('REQUEST_METHOD');
if (strtolower($method) === 'post') {
    $gTicket = new XoopsGTicket();
    if (! $gTicket->check(true, $this->_sFrontDirName, false)) {
        $xcRoot->mController->executeRedirect(WIZXC_CURRENT_URI, 3,
            sprintf(Wizin_Util::constant('WIZMOBILE_ERR_TICKET_NOT_FOUND')));
    }
    $db =& XoopsDatabaseFactory::getDatabaseConnection();
    // update nondisplay block setting
    $blockTable = $db->prefix($this->_sFrontDirName . '_blocks');
    $newblocksTable = $db->prefix('newblocks');
    $sqlArray = array();
    $now = date('Y-m-d H:i:s');
    foreach ($existsBlocks as $bid) {
        $wmb_visible = isset($_REQUEST['wmb_visible'][$bid]) ? intval($_REQUEST['wmb_visible'][$bid]) : 0;
        $wmb_weight = isset($_REQUEST['wmb_weight'][$bid]) ? intval($_REQUEST['wmb_weight'][$bid]) : 0;
        if (in_array($bid, $wizmobileBlockKeys)) {
            // update
            $sqlArray[] = "UPDATE `$blockTable` SET `wmb_visible` = $wmb_visible," .
                " `wmb_weight` = $wmb_weight, `wmb_update_datetime` = '$now' " .
                " WHERE `wmb_delete_datetime` = '0000-00-00 00:00:00' AND `wmb_bid` = $bid";
        } else {
            // insert
            $sqlArray[] = "INSERT INTO `$blockTable` (`wmb_bid`, `wmb_visible`, " .
                " `wmb_weight`, `wmb_init_datetime`, `wmb_update_datetime`) " .
                " VALUES ($bid, $wmb_visible, $wmb_weight, '$now', '$now')";
        }
    }
    // delete not exists block setting
    if (! empty($wizmobileBlockKeys)) {
        foreach ($wizmobileBlockKeys as $wmb_bid) {
            if (! in_array($wmb_bid, $existsBlocks)) {
                $sqlArray[] = "UPDATE `$blockTable` SET `wmb_delete_datetime` = '$now'" .
                    " WHERE `wmb_delete_datetime` = '0000-00-00 00:00:00' AND" .
                    " `wmb_bid` = " .intval($wmb_bid);
            }
        }
    }
    // update default display block setting
    if (isset($_REQUEST['default_bid'])) {
        $configTable = $db->prefix($this->_sFrontDirName . '_configs');
        if ($_REQUEST['default_bid'] === '' || ! in_array($_REQUEST['default_bid'], $existsBlocks)) {
            // record exists ?
            $sql = "SELECT wmc_value FROM `$configTable` WHERE `wmc_delete_datetime` = '0000-00-00 00:00:00' " .
                "AND `wmc_item` = 'default_bid' LIMIT 1;";
            if ($resource = $db->query($sql)) {
                if ($result = $db->fetchArray($resource)) {
                    // delete record
                    $sqlArray[] = "UPDATE `$configTable` SET `wmc_value` = '', `wmc_update_datetime` = '$now' WHERE " .
                        "`wmc_delete_datetime` = '0000-00-00 00:00:00' AND `wmc_item` = 'default_bid';";
                } else {
                    // insert record
                    $sqlArray[] = "INSERT INTO `$configTable` (`wmc_item`, `wmc_value`, `wmc_init_datetime`, `wmc_update_datetime`) " .
                        " VALUES ('default_bid', '', '$now', '$now');";
                }
            }
        } else {
            // record exists ?
            $sql = "SELECT wmc_value FROM `$configTable` WHERE `wmc_delete_datetime` = '0000-00-00 00:00:00' AND " .
                "`wmc_item` = 'default_bid' LIMIT 1;";
            $defaultBid = intval($_REQUEST['default_bid']);
            if ($resource = $db->query($sql)) {
                if ($result = $db->fetchArray($resource)) {
                    // update record
                    $sqlArray[] = "UPDATE `$configTable` SET `wmc_value` = '$defaultBid', `wmc_update_datetime` = '$now' " .
                        " WHERE `wmc_delete_datetime` = '0000-00-00 00:00:00' AND `wmc_item` = 'default_bid';";
                } else {
                    // insert record
                    $sqlArray[] = "INSERT INTO `$configTable` (`wmc_item`, `wmc_value`, `wmc_init_datetime`, `wmc_update_datetime`) " .
                        " VALUES ('default_bid', '$defaultBid', '$now', '$now');";
                }
            }
        }
    }
    foreach ($sqlArray as $sql) {
        if (! $db->query($sql)) {
            $xcRoot->mController->executeRedirect(XOOPS_URL . '/modules/' .
                $this->_sFrontDirName . '/admin/admin.php?act=BlockSetting', 3,
                sprintf(Wizin_Util::constant('WIZMOBILE_MSG_UPDATE_BLOCK_SETTING_FAILED')));
        }
    }
    unset($sqlArray);
    $xcRoot->mController->executeRedirect(XOOPS_URL . '/modules/' .
        $this->_sFrontDirName . '/admin/admin.php?act=BlockSetting', 3,
        sprintf(Wizin_Util::constant('WIZMOBILE_MSG_UPDATE_BLOCK_SETTING_SUCCESS')));
}

//
// render admin view
//
// call header
require_once XOOPS_ROOT_PATH . '/header.php';

// display main templates
$xoopsTpl->assign('defaultBid', $defaultBid);
$xoopsTpl->assign('blocks', $blocks);
$xoopsTpl->display($tplFile);

// call footer
require_once XOOPS_ROOT_PATH . '/footer.php';

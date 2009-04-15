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
$tplFile = 'db:' . $frontDirname . '_admin_google_setting.html';

// register and redirect
$method = getenv('REQUEST_METHOD');
if (strtolower($method) === 'post') {
    $gTicket = new XoopsGTicket();
    if (! $gTicket->check(true, $this->_sFrontDirName, false)) {
        $xcRoot->mController->executeRedirect(WIZXC_CURRENT_URI, 3,
            sprintf(Wizin_Util::constant('WIZMOBILE_ERR_TICKET_NOT_FOUND')));
    }
    $db =& XoopsDatabaseFactory::getDatabaseConnection();
    $atypicalTable = $db->prefix($this->_sFrontDirName . '_atypical');
    $now = date('Y-m-d H:i:s');
    $allowItems = array('adsense_code');
    $sqlArray = array();
    $requestItems = (! empty($_REQUEST['wma_item']) && is_array($_REQUEST['wma_item'])) ?
        $_REQUEST['wma_item']: array();
    foreach ($requestItems as $wma_item => $wma_value) {
        if (! in_array($wma_item, $allowItems)) {
            continue;
        }
        $wma_item = mysql_real_escape_string($wma_item);
        $wma_value = mysql_real_escape_string($wma_value);
        $sql = "SELECT * FROM `$atypicalTable` WHERE `wma_item` = '$wma_item';";
        if ($resource = $db->query($sql)) {
            if ($result = $db->fetchArray($resource)) {
                $sqlArray[] = "UPDATE `$atypicalTable` SET `wma_value` = '$wma_value', `wma_update_datetime` = '$now' " .
                    " WHERE `wma_atypical_id` = " . $result['wma_atypical_id'] . ";";
            } else {
                $sqlArray[] = "INSERT INTO `$atypicalTable` (`wma_item`, `wma_value`, `wma_init_datetime`, `wma_update_datetime`) " .
                    " VALUES ('$wma_item', '$wma_value', '$now', '$now');";
            }
        }
    }
    foreach ($sqlArray as $sql) {
        if (! $db->query($sql)) {
            $xcRoot->mController->executeRedirect(XOOPS_URL . '/modules/' .
                $this->_sFrontDirName . '/admin/admin.php?act=GoogleSetting', 3,
                sprintf(Wizin_Util::constant('WIZMOBILE_MSG_UPDATE_GOOGLE_SETTING_FAILED')));
        }
    }
    unset($sqlArray);
    WizXc_Util::clearCompiledCache();
    if (! empty($requestItems['adsense_code'])) {
        touch(XOOPS_TRUST_PATH . '/cache/wizmobile_ads_flg_' . Wizin_Util::salt(XOOPS_SALT));
        $cacheObject = new Wizin_Cache('wizmobile_ads_cache_', Wizin_Util::salt(XOOPS_SALT));
        $cacheObject->clear();
        $params = $this->googleAdsParams($requestItems['adsense_code']);
        $cacheObject->save($params);
    }
    $xcRoot->mController->executeRedirect(XOOPS_URL . '/modules/' .
        $this->_sFrontDirName . '/admin/admin.php?act=GoogleSetting', 3,
        sprintf(Wizin_Util::constant('WIZMOBILE_MSG_UPDATE_GOOGLE_SETTING_SUCCESS')));
}

// get module config
$atypical = $this->getAtypical();

//
// render admin view
//
// call header
require_once XOOPS_ROOT_PATH . '/header.php';

// display main templates
$xoopsTpl->assign('atypical', $atypical);
$xoopsTpl->display($tplFile);

// call footer
require_once XOOPS_ROOT_PATH . '/footer.php';

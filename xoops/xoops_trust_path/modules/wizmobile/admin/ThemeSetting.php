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
$tplFile = 'db:' . $frontDirname . '_admin_theme_setting.html';

// include language file of legacy module
$language = empty($GLOBALS['xoopsConfig']['language']) ? 'english' : $GLOBALS['xoopsConfig']['language'];
if(file_exists(XOOPS_ROOT_PATH . '/modules/legacy/language/' . $language . '/admin.php')) {
    require_once XOOPS_ROOT_PATH . '/modules/legacy/language/' . $language . '/admin.php';
}

// get groups
$groups = array();
/*
$db =& XoopsDatabaseFactory::getDatabaseConnection();
$groupTable = $db->prefix('groups');
$sql = "SELECT `groupid`, `name` FROM `$groupTable` ORDER BY groupid;";
if ($resource = $db->query($sql)) {
    while ($result = $db->fetchArray($resource)) {
        $groupId = intval($result['groupid']);
        $groups[$groupId] = $result['name'];
    }
}
*/
$groups[0] = array('groupid' => 0, 'name' => _MEMBERS);
$groups[-1] = array('groupid' => -1, 'name' => _GUESTS);

// get modules
$modules = array();
$modules[0] = array('mid' => 0, 'name' => _AD_LEGACY_LANG_TOPPAGE);
$modules[-1] = array('mid' => -1, 'name' => _AD_LEGACY_LANG_ALL_MODULES);

// register and redirect
$method = getenv('REQUEST_METHOD');
if (strtolower($method) === 'post') {
    $gTicket = new XoopsGTicket();
    if (! $gTicket->check(true, $this->_sFrontDirName, false)) {
        $xcRoot->mController->executeRedirect(WIZXC_CURRENT_URI, 3,
            sprintf(Wizin_Util::constant('WIZMOBILE_ERR_TICKET_NOT_FOUND')));
    }
    $db =& XoopsDatabaseFactory::getDatabaseConnection();
    $sqlArray = array();
    $now = date('Y-m-d H:i:s');
    // update config table
    $configTable = $db->prefix($this->_sFrontDirName . '_configs');
    $allowItems = array('theme');
    $requestItems = (! empty($_REQUEST['wmc_item']) && is_array($_REQUEST['wmc_item'])) ?
        $_REQUEST['wmc_item']: array();
    foreach ($requestItems as $wmc_item => $wmc_value) {
        if (! in_array($wmc_item, $allowItems)) {
            continue;
        }
        $wmc_item = mysql_real_escape_string($wmc_item);
        $wmc_value = mysql_real_escape_string($wmc_value);
        $sql = "SELECT * FROM `$configTable` WHERE `wmc_item` = '$wmc_item';";
        if ($resource = $db->query($sql)) {
            if ($result = $db->fetchArray($resource)) {
                $sqlArray[] = "UPDATE `$configTable` SET `wmc_value` = '$wmc_value', `wmc_update_datetime` = '$now' " .
                    " WHERE `wmc_config_id` = " . $result['wmc_config_id'] . ";";
            } else {
                $sqlArray[] = "INSERT INTO `$configTable` (`wmc_item`, `wmc_value`, `wmc_init_datetime`, `wmc_update_datetime`) " .
                    " VALUES ('$wmc_item', '$wmc_value', '$now', '$now');";
            }
        }
    }
    // update theme table
    //
    // check pattern.
    //   1) group exists?
    //   2) module exists?
    //   3) theme exists?
    $params = array();
    $themeTable = $db->prefix($this->_sFrontDirName . '_themes');
    $requestThemes = (! empty($_REQUEST['wmt_theme']) && is_array($_REQUEST['wmt_theme'])) ?
        $_REQUEST['wmt_theme']: array();
    foreach ($requestThemes as $wmt_groupid => $wmt_theme) {
        $wmt_groupid = intval($wmt_groupid);
        foreach ($wmt_theme as $wmt_mid => $wmt_theme_name) {
            $wmt_mid = intval($wmt_mid);
            $sql = "SELECT * FROM `$themeTable` WHERE `wmt_mid` = $wmt_mid AND " .
                "`wmt_groupid` = $wmt_groupid AND `wmt_delete_datetime` IS NULL;";
            if ($resource = $db->query($sql)) {
                if ($result = $db->fetchArray($resource)) {
                    $wmt_theme_id = intval($result['wmt_theme_id']);
                    if (! is_null($wmt_theme_name) && $wmt_theme_name !== '') {
                        // update
                        if (! isset($params[$wmt_groupid])) {
                            $params[$wmt_groupid] = array();
                        }
                        $params[$wmt_groupid][$wmt_mid] = $wmt_theme_name;
                        $wmt_theme_name = mysql_real_escape_string($wmt_theme_name);
                        $sqlArray[] = "UPDATE `$themeTable` SET `wmt_theme_name` = '$wmt_theme_name'," .
                            " `wmt_update_datetime` = '$now' WHERE `wmt_theme_id` = $wmt_theme_id";
                    } else {
                        // delete
                        $sqlArray[] = "UPDATE `$themeTable` SET `wmt_delete_datetime` = '$now'" .
                            " WHERE `wmt_theme_id` = $wmt_theme_id";
                    }
                } else {
                    if (! is_null($wmt_theme_name) && $wmt_theme_name !== '') {
                        // insert
                        if (! isset($params[$wmt_groupid])) {
                            $params[$wmt_groupid] = array();
                        }
                        $params[$wmt_groupid][$wmt_mid] = $wmt_theme_name;
                        $wmt_theme_name = mysql_real_escape_string($wmt_theme_name);
                        $sqlArray[] = "INSERT INTO `$themeTable` (`wmt_mid`, `wmt_groupid`, `wmt_theme_name`, `wmt_init_datetime`, `wmt_update_datetime`) VALUES " .
                            " ($wmt_mid, $wmt_groupid, '$wmt_theme_name', '$now', '$now'); ";
                    }
                }
            }
        }
    }
    // delete
    $implodedGroups = implode(',', array_keys($groups));
    $sqlArray[] = "UPDATE `$themeTable` SET `wmt_delete_datetime` = '$now' WHERE " .
        " `wmt_groupid` NOT IN ($implodedGroups);";
    unset($implodedGroups);
    // execute sql
    foreach ($sqlArray as $sql) {
        if (! $db->query($sql)) {
            $xcRoot->mController->executeRedirect(XOOPS_URL . '/modules/' .
                $this->_sFrontDirName . '/admin/admin.php?act=ThemeSetting', 3,
                sprintf(Wizin_Util::constant('WIZMOBILE_MSG_UPDATE_THEMEL_SETTING_FAILED')));
        }
    }
    unset($sqlArray);
    WizXc_Util::clearCompiledCache();
    if (! empty($params)) {
        touch(XOOPS_TRUST_PATH . '/cache/wizmobile_theme_flg_' . Wizin_Util::salt(XOOPS_SALT));
        $cacheObject = new Wizin_Cache('wizmobile_theme_cache_', Wizin_Util::salt(XOOPS_SALT));
        $cacheObject->clear();
        $cacheObject->save($params);
    }

    $xcRoot->mController->executeRedirect(XOOPS_URL . '/modules/' .
        $this->_sFrontDirName . '/admin/admin.php?act=ThemeSetting', 3,
        sprintf(Wizin_Util::constant('WIZMOBILE_MSG_UPDATE_THEME_SETTING_SUCCESS')));
}

// get theme setting
$configs = $this->getConfigs();
$mobileThemes = $this->getMobileThemes();
$themes = $this->getThemes();

//
// render admin view
//
// call header
require_once XOOPS_ROOT_PATH . '/header.php';

// display main templates
$xoopsTpl->assign('groups', $groups);
$xoopsTpl->assign('modules', $modules);
$xoopsTpl->assign('configs', $configs);
$xoopsTpl->assign('themes', $themes);
$xoopsTpl->assign('mobileThemes', $mobileThemes);
$xoopsTpl->display($tplFile);

// call footer
require_once XOOPS_ROOT_PATH . '/footer.php';

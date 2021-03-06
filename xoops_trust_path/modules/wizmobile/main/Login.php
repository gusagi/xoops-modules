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
$wizMobile =& WizMobile::getSingleton();
$configs = $this->getConfigs();
$renderTarget =& $xcRoot->mContext->mModule->getRenderTarget();
$frontDirname = str_replace('_wizmobile_action', '', strtolower(get_class($this)));
$tplFile = $frontDirname . '_main_login.html';
$renderTarget->setTemplateName($tplFile);

// if login disabled
if (empty($configs['login']) || $configs['login']['wmc_value'] !== '1') {
    $wizMobile->denyAccessLoginPage();
}

// check login and redirect
$method = getenv('REQUEST_METHOD');
if (strtolower($method) === 'post') {
	if (! defined('_MD_LEGACY_ERROR_DBUPDATE_FAILED')) {
	    $language = empty($GLOBALS['xoopsConfig']['language']) ? 'english' : $GLOBALS['xoopsConfig']['language'];
	    if(file_exists(XOOPS_ROOT_PATH . '/modules/legacy/language/' . $language . '/main.php')) {
	        require_once XOOPS_ROOT_PATH . '/modules/legacy/language/' . $language . '/main.php';
	    }
	}
    $gTicket = new XoopsGTicket();
    if (! $gTicket->check(true, $this->_sFrontDirName, false)) {
        $xcRoot->mController->executeRedirect(XOOPS_URL .'/modules/' .$frontDirname .'/index.php?act=Login',
            2, sprintf(Wizin_Util::constant('WIZMOBILE_ERR_TICKET_NOT_FOUND')));
    }
    $db =& XoopsDatabaseFactory::getDatabaseConnection();
    $user = & Wizin_User::getSingleton();
    $user->checkClient(true);
    if (! $user->bIsMobile) {
    	header("Location: " . XOOPS_URL . '/user.php');
    	exit();
    } else {
        $loginTable = $db->prefix($this->_sFrontDirName . '_devices');
        $uniqId = md5($user->sUniqId . XOOPS_SALT);
        // TODO : use ORM
        $sql = "SELECT `wmd_uid` FROM `$loginTable` WHERE `wmd_uniqid` = '$uniqId' AND `wmd_delete_datetime` = '0000-00-00 00:00:00';";
        if ($resource = $db->query($sql)) {
            $result = $db->fetchArray($resource);
            if ($result !== false && ! empty($result)) {
                /** This code block copied from "User_LegacypageFunctions" >> */
                $handler =& xoops_gethandler('user');
                $xcUser =& $handler->get($result['wmd_uid']);
                $xcRoot->mContext->mXoopsUser =& $xcUser;

                //
                // Regist to session
                //
                $xcRoot->mSession->regenerate();
                $_SESSION = array();
                $_SESSION['xoopsUserId'] = $xcUser->get('uid');
                $_SESSION['xoopsUserGroups'] = $xcUser->getGroups();
                /** This code block copied from "User_LegacypageFunctions" << */
            }
        }
    }
    if (isset($xcUser) && is_object($xcUser)) {
        XCube_DelegateUtils::call('Site.CheckLogin.Success', new XCube_Ref($xcUser));
        $this->executeRedirect(XOOPS_URL, 1, XCube_Utils::formatMessage(_MD_LEGACY_MESSAGE_LOGIN_SUCCESS, $xcUser->get('uname')));
    } else {
        XCube_DelegateUtils::call('Site.CheckLogin.Fail', new XCube_Ref($xcUser));
        $this->executeRedirect(XOOPS_URL, 1, _MD_LEGACY_ERROR_INCORRECTLOGIN);
    }
}

// include language file of user module
if (! defined('_MB_USER_USERNAME')) {
	$language = empty($GLOBALS['xoopsConfig']['language']) ? 'english' : $GLOBALS['xoopsConfig']['language'];
	if(file_exists(XOOPS_ROOT_PATH . '/modules/user/language/' . $language . '/blocks.php')) {
	    require_once XOOPS_ROOT_PATH . '/modules/user/language/' . $language . '/blocks.php';
	}
}

// login check and get "user" module config
if (isset($GLOBALS['xoopsUser']) && is_object($GLOBALS['xoopsUser'])) {
    $xcRoot->mController->executeForward(XOOPS_URL);
}

// call header
require_once XOOPS_ROOT_PATH . '/header.php';

// call footer
require_once XOOPS_ROOT_PATH . '/footer.php';

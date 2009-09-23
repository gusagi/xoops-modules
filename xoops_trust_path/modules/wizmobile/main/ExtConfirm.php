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
$wizMobile =& WizMobile::getSingleton();
$user =& Wizin_User::getSingleton();
$frontDirname = str_replace('_wizmobile_action', '', strtolower(get_class($this)));
$tplFile = $frontDirname . '_main_extconfirm.html';
$blowfish =& Wizin_Crypt::getBlowfish(WIZMOBILE_EXTLINK_KEY);
$renderTarget =& $xcRoot->mContext->mModule->getRenderTarget();
$renderTarget->setTemplateName($tplFile);

/**
 * main process
 */
$extUrl = '';
$backUrl = '';
$linkKey = '';
if (isset($_GET[WIZMOBILE_EXTLINK_EXTKEY]) && isset($_GET[WIZMOBILE_EXTLINK_BACKKEY])) {
    $_extUrl = rtrim($blowfish->decrypt(base64_decode($_GET[WIZMOBILE_EXTLINK_EXTKEY])), "\0");
    $_backUrl = rtrim($blowfish->decrypt(base64_decode($_GET[WIZMOBILE_EXTLINK_BACKKEY])), "\0");
    $_urlArray = parse_url(XOOPS_URL);
    $pattern = '(http|https)(://' .$_urlArray['host'];
    if (isset($_urlArray['port'])) {
        $pattern .= ':' .$_urlArray['port'];
    }
    if (isset($_urlArray['path'])) {
        $pattern .= $_urlArray['path'];
    }
    $pattern .= ')';
    $pattern = strtr($pattern, array('/' => '\\/', '.' => '\\.'));
    if (preg_match('/^' .$pattern .'/i', $_backUrl)) {
        $extUrl = $_extUrl;
        if (strpos($extUrl, 'guid=on') !== false) {
            $extUrl = str_replace('guid=on', '', $extUrl);
            $extUrl = str_replace('?&amp;', '?', $extUrl);
            $extUrl = str_replace('?&', '?', $extUrl);
            if (substr($extUrl, -1, 1) === '?') {
                $extUrl = substr($extUrl, 0, strlen($extUrl) - 1);
            }
        }
        $backUrl = $_backUrl;
    }
}

/**
 * set variables
 */
$wizMobile->setReplaceLinkMode(false);
$xcRoot->mController->setDialogMode(true);
$renderTarget->setAttribute('wizmobileExtUrl', htmlspecialchars($extUrl, ENT_QUOTES));
$renderTarget->setAttribute('wizmobileBackUrl', htmlspecialchars($backUrl, ENT_QUOTES));

// call header
require_once XOOPS_ROOT_PATH . '/header.php';

// call footer
require_once XOOPS_ROOT_PATH . '/footer.php';
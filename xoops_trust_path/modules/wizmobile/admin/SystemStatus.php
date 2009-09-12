<?php
/**
 * WizMobile module admin index script
 *
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
$tplFile = 'db:' . $frontDirname . '_admin_system_status.html';

//
// system status
//
$systemStatus = array();

// exchange controller
$supportControllers = array('legacy_wizxccontroller', 'hdlegacy_controller');
$controllerClass = strtolower(get_class($xcRoot->mController));
if (in_array($controllerClass, $supportControllers)) {
    $systemStatus['controller']['result'] = Wizin_Util::constant('WIZMOBILE_LANG_ENABLE');
} else {
    $systemStatus['controller']['result'] = Wizin_Util::constant('WIZMOBILE_LANG_DISABLE');
    $systemStatus['controller']['messages'][] = Wizin_Util::constant('WIZMOBILE_MSG_CONTROLLER_IS_NOT_EXCHANGED');
    $systemStatus['controller']['messages'][] = Wizin_Util::constant('WIZMOBILE_MSG_CONTROLLER_PATCH');
    $systemStatus['controller']['code'] = '[RenderSystems]
        Legacy_WizMobileRenderSystem=Legacy_WizMobileRenderSystem

        [Legacy]
        AllowDBProxy=false

        [Legacy_Controller]
        root=XOOPS_TRUST_PATH
        path=/modules/wizxc/class
        class=Legacy_WizXcController

        [Legacy_WizMobileRenderSystem]
        root=XOOPS_TRUST_PATH
        path=/modules/wizmobile/class
        class=Legacy_WizMobileRenderSystem';
}

// image resize
$createDir = XOOPS_ROOT_PATH . '/uploads/wizmobile';
if (extension_loaded('gd') && file_exists($createDir) && is_dir($createDir) &&
        is_writable($createDir)) {
    $systemStatus['imageResize']['result'] = Wizin_Util::constant('WIZMOBILE_LANG_ENABLE');
} else {
    $systemStatus['imageResize']['result'] = Wizin_Util::constant('WIZMOBILE_LANG_DISABLE');
    if (! extension_loaded('gd')) {
        $systemStatus['imageResize']['messages'][] = Wizin_Util::constant('WIZMOBILE_MSG_GD_NOT_EXISTS');
    }
    if (! file_exists($createDir) || ! is_dir($createDir)) {
        $systemStatus['imageResize']['messages'][] = Wizin_Util::constant('WIZMOBILE_MSG_RESIZED_IMAGE_DIR_NOT_EXISTS');
    }
    if (! is_writable($createDir)) {
        $systemStatus['imageResize']['messages'][] = Wizin_Util::constant('WIZMOBILE_MSG_RESIZED_IMAGE_DIR_NOT_WRITABLE');
    }
}

// partition page
if (class_exists('DOMDocument') && class_exists('SimpleXMLElement') &&
        method_exists('SimpleXMLElement','getName')) {
    $systemStatus['partitionPage']['result'] = Wizin_Util::constant('WIZMOBILE_LANG_ENABLE');
} else {
    $systemStatus['partitionPage']['result'] = Wizin_Util::constant('WIZMOBILE_LANG_DISABLE');
    if (! class_exists('DOMDocument')) {
        $systemStatus['partitionPage']['messages'][] = Wizin_Util::constant('WIZMOBILE_MSG_DOM_NOT_EXISTS');
    }
    if (! class_exists('SimpleXMLElement')) {
        $systemStatus['partitionPage']['messages'][] = Wizin_Util::constant('WIZMOBILE_MSG_SIMPLEXML_NOT_EXISTS');
    }
}

// pictogram filter
if (! class_exists('Wizin_Filter_Pictogram') && intval(PHP_VERSION) >= 5) {
    if (file_exists(WIZIN_ROOT_PATH .'/src/filter/Pictogram.class.php')) {
        require WIZIN_ROOT_PATH .'/src/filter/Pictogram.class.php';
    }
}
if (class_exists('Wizin_Filter_Pictogram')) {
    $systemStatus['pictogramFilter']['result'] = Wizin_Util::constant('WIZMOBILE_LANG_ENABLE');
} else {
    $systemStatus['pictogramFilter']['result'] = Wizin_Util::constant('WIZMOBILE_LANG_DISABLE');
    if (function_exists('json_decode')) {
        $systemStatus['pictogramFilter']['messages'][] = Wizin_Util::constant('WIZMOBILE_MSG_PICTOGRAM_LIB_NOT_EXISTS');
        $systemStatus['pictogramFilter']['messages'][] = Wizin_Util::constant('WIZMOBILE_MSG_PLZ_PICTOGRAM_LIB_INSTALL');
    } else {
        $systemStatus['pictogramFilter']['messages'][] = Wizin_Util::constant('WIZMOBILE_MSG_JSON_EXT_NOT_EXISTS');
        $systemStatus['pictogramFilter']['messages'][] = Wizin_Util::constant('WIZMOBILE_MSG_PLZ_JSPHON_INSTALL');
    }
}
// CSS filter
if (! class_exists('Wizin_Filter_Css') && intval(PHP_VERSION) >= 5) {
    if (file_exists(WIZIN_ROOT_PATH .'/src/filter/Css.class.php')) {
        require WIZIN_ROOT_PATH .'/src/filter/Css.class.php';
    }
}
if (class_exists('Wizin_Filter_Css')) {
    $systemStatus['cssFilter']['result'] = Wizin_Util::constant('WIZMOBILE_LANG_ENABLE');
} else {
    $systemStatus['cssFilter']['result'] = Wizin_Util::constant('WIZMOBILE_LANG_DISABLE');
    if (! class_exists('HTML_CSS_Selector2XPath')) {
        $systemStatus['cssFilter']['messages'][] =
            Wizin_Util::constant('WIZMOBILE_MSG_CSS_XPATH_NOT_EXISTS');
    }
    if (! class_exists('HTML_Common')) {
        $systemStatus['cssFilter']['messages'][] =
            Wizin_Util::constant('WIZMOBILE_MSG_CSS_COMMON_NOT_EXISTS');
    }
    if (! class_exists('HTML_CSS')) {
        $systemStatus['cssFilter']['messages'][] =
            Wizin_Util::constant('WIZMOBILE_MSG_CSS_NOT_EXISTS');
    }
    if (class_exists('HTML_CSS_Selector2XPath') && class_exists('HTML_CSS') &&
            ! class_exists('HTML_CSS_Mobile')) {
        $systemStatus['cssFilter']['messages'][] =
            Wizin_Util::constant('WIZMOBILE_MSG_CSS_MOBILE_NOT_EXISTS');
    }
}

//
// render admin view
//
// call header
require_once XOOPS_ROOT_PATH . '/header.php';

// display main templates
$xoopsTpl->assign('systemStatus', $systemStatus);
$xoopsTpl->display($tplFile);

// call footer
require_once XOOPS_ROOT_PATH . '/footer.php';

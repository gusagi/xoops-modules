<?php
/**
 * WizMobile module index script for XOOPS Cube Legacy2.1
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

$scriptFileName = getenv('SCRIPT_FILENAME');
if ($scriptFileName === __FILE__) {
    exit();
}

$frontDirname = basename(dirname($frontFile));
require dirname(__FILE__) . '/init.php';

$handler =& xoops_gethandler('module');
$module =& $handler->getByDirname($frontDirname);
$version =& $module->getVar('version');

$adminmenu = array();
$adminmenu[] = array(
    'title' => Wizin_Util::constant('WIZMOBILE_LANG_SYSTEM_STATUS'),
    'link' => 'admin/admin.php?act=SystemStatus',
    'show' => true,
);
$adminmenu[] = array(
    'title' => Wizin_Util::constant('WIZMOBILE_LANG_BLOCK_CONTROL'),
    'link' => 'admin/admin.php?act=BlockSetting',
    'show' => true,
);
if (intval($version) >= 30) {
    $adminmenu[] = array(
        'title' => Wizin_Util::constant('WIZMOBILE_LANG_MODULE_CONTROL'),
        'link' => 'admin/admin.php?act=ModuleSetting',
        'show' => true,
   );
}
if (intval($version) >= 40) {
    $adminmenu[] = array(
        'title' => Wizin_Util::constant('WIZMOBILE_LANG_THEME_CONTROL'),
        'link' => 'admin/admin.php?act=ThemeSetting',
        'show' => true,
   );
    $adminmenu[] = array(
        'title' => Wizin_Util::constant('WIZMOBILE_LANG_GOOGLE_CONTROL'),
        'link' => 'admin/admin.php?act=GoogleSetting',
        'show' => true,
   );
}
$adminmenu[] = array(
    'title' => Wizin_Util::constant('WIZMOBILE_LANG_GENERAL_SETTING'),
    'link' => 'admin/admin.php?act=GeneralSetting',
    'show' => true,
);


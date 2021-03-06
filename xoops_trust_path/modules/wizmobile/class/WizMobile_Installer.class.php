<?php
/**
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

if (! class_exists('WizMobile_Installer')) {
    require_once XOOPS_TRUST_PATH . '/modules/wizxc/class/WizXc_Installer.class.php';

    class WizMobile_Installer extends WizXc_Installer
    {
        function executeInstall()
        {
            // php version check
            $phpVersion = floatval(PHP_VERSION);
            if ($phpVersion < 4.4) {
                $this->mLog->addError(Wizin_Util::constant('WIZMOBILE_ERR_PHP_VERSION'));
                return false;
            }
            // thumbnail directory permission check
            $thumbnailDir = XOOPS_ROOT_PATH . '/uploads/wizmobile';
            if (! file_exists($thumbnailDir) || ! is_dir($thumbnailDir)) {
                $this->mLog->addError("Failed to install : Please create '" . $thumbnailDir . "' directory.");
                return false;
            }
            if (! is_writable($thumbnailDir)) {
                $this->mLog->addError("Failed to install : " . $thumbnailDir . " needs writable permission. Please check it's permission.");
                return false;
            }
            // cache directory permission check
            $cacheDir = XOOPS_TRUST_PATH . '/cache';
            if (! file_exists($cacheDir) || ! is_dir($cacheDir)) {
                $this->mLog->addError("Failed to install : Please create '" . $cacheDir . "' directory.");
                return false;
            }
            if (! is_writable($cacheDir)) {
                $this->mLog->addError("Failed to install : " . $cacheDir . " needs writable permission. Please check it's permission.");
                return false;
            }
            return parent::executeInstall();
        }

        function _installModule()
        {
            parent::_installModule();
            //
            // Add a permission all group members and guest can read.
            //
            $memberHandler =& xoops_gethandler('member');
            $groupObjects =& $memberHandler->getGroups();
            $gpermHandler =& xoops_gethandler('groupperm');
            foreach ($groupObjects as $group) {
                $readPerm =& $gpermHandler->create();
                $readPerm->setVar('gperm_groupid', $group->getVar('groupid'));
                $readPerm->setVar('gperm_itemid', $this->_mXoopsModule->getVar('mid'));
                $readPerm->setVar('gperm_modid', 1);
                $readPerm->setVar('gperm_name', 'module_read');
                if (! $gpermHandler->insert($readPerm)) {
                    $this->mLog->addError(_AD_LEGACY_ERROR_COULD_NOT_SET_READ_PERMISSION);
                }
            }
        }
    }
}

$mod_dir = basename(dirname($frontFile));
$installerClass = ucfirst($mod_dir) . "_WizMobile_Installer";
if (! class_exists($installerClass)) {
    eval("class $className extends WizMobile_Installer {}");
}

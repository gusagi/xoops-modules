<?php
/**
 *
 * @package  WizXc
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

if (! class_exists('WizXc_Installer')) {
    require_once XOOPS_ROOT_PATH . '/modules/legacy/admin/class/ModuleInstaller.class.php';
    require_once dirname(__FILE__) . '/WizXc_Util.class.php';

    class WizXc_Installer extends Legacy_ModuleInstaller
    {
        function _installTemplates()
        {
            parent::_installTemplates();
            $myTrustDirFile = XOOPS_ROOT_PATH . '/modules/' . $this->_mXoopsModule->getVar('dirname') .
                '/mytrustdirname.php';
            if (file_exists($myTrustDirFile) && is_readable($myTrustDirFile)) {
                include $myTrustDirFile;
                $templatesDir = XOOPS_TRUST_PATH . '/modules/' . $mytrustdirname . '/templates';
                if (file_exists($templatesDir) && is_dir($templatesDir)) {
                    WizXc_Util::installD3Templates($this->_mXoopsModule, $this->mLog, $templatesDir);
                }
            }
        }

        function _installTables()
        {
            $myTrustDirFile = XOOPS_ROOT_PATH . '/modules/' . $this->_mXoopsModule->getVar('dirname') .
                '/mytrustdirname.php';
            if (file_exists($myTrustDirFile) && is_readable($myTrustDirFile)) {
                include $myTrustDirFile;
                $sqlFilePath = XOOPS_TRUST_PATH . '/modules/' . $mytrustdirname . '/sql/' .
                    strtolower(XOOPS_DB_TYPE) . '.sql';
                if (file_exists($sqlFilePath) && is_readable($sqlFilePath)) {
                    WizXc_Util::createTableByFile($this->_mXoopsModule, $this->mLog, $sqlFilePath);
                }
            }
        }
    }
}

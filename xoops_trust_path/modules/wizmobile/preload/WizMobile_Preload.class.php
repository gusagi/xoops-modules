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

if (! class_exists('WizMobile_Preload')) {
    class WizMobile_Preload extends XCube_ActionFilter
    {
        function preBlockFilter()
        {
            $this->_preBlockFilter();
            parent::preBlockFilter();
        }

        function postFilter()
        {
            $this->_postFilter();
            parent::postFilter();
        }

        function _preBlockFilter()
        {
            $wizMobile =& WizMobile::getSingleton();
            $wizMobile->oneTimeProcess();
        }

        function _postFilter()
        {
            $wizMobile =& WizMobile::getSingleton();
            $user = & Wizin_User::getSingleton();
            if ($user->bIsMobile) {
                $this->_mobileFilter();
            }
        }

        function _mobileFilter()
        {
            $xcRoot =& XCube_Root::getSingleton();
            $wizMobile =& WizMobile::getSingleton();
            $user = & Wizin_User::getSingleton();
            $wizMobileAction =& $wizMobile->getActionClass();
            // deny access module check
            $xoopsModule =& $xcRoot->mContext->mXoopsModule;
            if (isset($xoopsModule) && is_object($xoopsModule)) {
                $mid = $xoopsModule->getVar('mid');
                $denyAccessModules = $wizMobileAction->getDenyAccessModules();
                if (in_array($mid, $denyAccessModules)) {
                    $wizMobile->denyAccessModuleArea();
                }
            }
            $configs = $wizMobileAction->getConfigs();
            // add delegate
            if ((empty($configs['login']) || $configs['login']['wmc_value'] !== '1') &&
                    ! is_object($xcRoot->mContext->mXoopsUser)) {
                $xcRoot->mDelegateManager->add('Legacypage.User.Access',
                    array($wizMobile, 'denyAccessLoginPage'), XCUBE_DELEGATE_PRIORITY_FIRST);
            }
            $xcRoot->mDelegateManager->add('Site.CheckLogin.Success', array($wizMobile, 'directLoginSuccess'),
                XCUBE_DELEGATE_PRIORITY_FINAL + 1);
            $xcRoot->mDelegateManager->add('Site.CheckLogin.Success', array($wizMobile, 'resetUserTheme'),
                XCUBE_DELEGATE_PRIORITY_FINAL - 1);
            $xcRoot->mDelegateManager->add('Site.CheckLogin.Fail', array($wizMobile, 'directLoginFail'));
            $xcRoot->mDelegateManager->add('Site.Logout.Success', array($wizMobile, 'directLogout'),
                XCUBE_DELEGATE_PRIORITY_FINAL + 1);
            $xcRoot->mDelegateManager->add('Site.Logout.Fail', array($wizMobile, 'directLogout'),
                XCUBE_DELEGATE_PRIORITY_FINAL + 1);
            $xcRoot->mDelegateManager->add('Legacy_AdminControllerStrategy.SetupBlock',
                array($wizMobile, 'denyAccessAdminArea'), XCUBE_DELEGATE_PRIORITY_FIRST);
            // overwrite comment mode
            if (isset($xcRoot->mContext->mXoopsUser) && is_object($xcRoot->mContext->mXoopsUser)) {
                $xcRoot->mContext->mXoopsUser->setVar('umode', 'flat');
            } else {
                $GLOBALS['xoopsConfig']['com_mode'] = 'flat';
            }
            // check session security
            if (! $user->bIsBot) {
                // check session
                $wizMobile->checkMobileSession();
            }
            // exchange theme
            $wizMobile->overrideTheme();
        }
    }
}

$mod_dir = basename(dirname(dirname($frontFile)));
preg_match("/(\w+)\.class\.php/", strtolower(basename(__FILE__)), $matches);
$className = $mod_dir . "_" . $matches[1];
if (! class_exists($className)) {
    eval("class $className extends WizMobile_Preload {}");
}

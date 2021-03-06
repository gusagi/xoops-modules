<?php
/**
 *
 * PHP Versions 4
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

if (! class_exists('WizXc')) {
    class WizXc
    {
        function WizXc()
        {
            WizXc::_require();
            WizXc::_define();
            WizXc::_setup();
            WizXc::_init();
        }

        function &getSingleton()
        {
            static $instance;
            if (! isset($instance)) {
                $instance = new WizXc();
                // set PEAR path
                set_include_path(get_include_path() . PATH_SEPARATOR . WIZIN_PEAR_DIR);
            }
            return $instance;
        }

        function _require()
        {
            require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/wizin/src/Wizin.class.php';
            if (! defined('_LEGACY_PREVENT_LOAD_CORE_')) {
                require_once dirname(__FILE__) . '/WizXc_Util.class.php';
                require_once dirname(__FILE__) . '/gtickets.php';
            }
        }

        function _define()
        {
            if (! defined('WIZIN_URL')) {
                define('WIZIN_URL', XOOPS_URL);
            }
            if (! defined('WIZIN_CACHE_DIR')) {
                define('WIZIN_CACHE_DIR', XOOPS_TRUST_PATH . '/cache');
            }
            //if (! defined('WIZIN_PEAR_DIR')) {
            //    define('WIZIN_PEAR_DIR', XOOPS_TRUST_PATH . '/PEAR');
            //}
            $wizin =& Wizin::getSingleton();
            $parseUrl = parse_url(XOOPS_URL);
            if (! empty($parseUrl['path'])) {
                define('WIZXC_CURRENT_URI', str_replace($parseUrl['path'], '', XOOPS_URL) .
                    getenv('REQUEST_URI'));
            } else {
                define('WIZXC_CURRENT_URI', XOOPS_URL . getenv('REQUEST_URI'));
            }
            $queryString = getenv('QUERY_STRING');
            if (! empty($queryString)) {
                define('WIZXC_URI_CONNECTOR', '&');
            } else {
                define('WIZXC_URI_CONNECTOR', '?');
            }
        }

        function _setup()
        {
            Wizin::getSingleton();
            Wizin::salt(XOOPS_SALT);
        }

        function _init()
        {
            if (! defined('_LEGACY_PREVENT_LOAD_CORE_')) {
                $xcRoot =& XCube_Root::getSingleton();
                $xcRoot->mDelegateManager->add('XoopsTpl.New' , array($this , 'registerModifier')) ;
                $xcRoot->mDelegateManager->add('XoopsTpl.New' , array($this , 'registerFunction')) ;
                // Testing filter
                //$xcRoot->mDelegateManager->add('XoopsTpl.New' , array($this , 'registerPrefilter')) ;
            }
        }

        function registerModifier(&$xoopsTpl)
        {
            $xoopsTpl->register_modifier('wiz_constant', array('Wizin_Util', 'constant'));
        }

        function registerFunction(&$xoopsTpl)
        {
            $xoopsTpl->register_function('wiz_gticket', array('WizXc_Util', 'getGTicketHtml'));
        }

        function registerPrefilter(&$xoopsTpl)
        {
            $xoopsTpl->register_prefilter(array('WizXc_Util', 'replaceXclDelim'));
        }
    }
}

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

if ( ! class_exists('WizMobile_Action') ) {
    require_once XOOPS_TRUST_PATH . '/modules/wizxc/class/WizXc_Action.class.php';

    class WizMobile_Action extends WizXc_Action
    {
        function executeAdmin()
        {
            if ( empty($_REQUEST['act']) ) {
                $_REQUEST['act'] = 'SystemStatus';
            }
            parent::executeAdmin();
        }

        function _setup()
        {
            $this->_sModuleDir = XOOPS_TRUST_PATH . '/modules/wizmobile';
            $this->_sFrontDirName = str_replace( '_wizmobile_action', '', strtolower(get_class($this)) );
            $this->_sClassName = $this->_sFrontDirName . '_WizMobile_Action';
        }

        function &getSingletonByOwn()
        {
            static $instance;
            if ( ! isset($instance) ) {
                $className = $this->_sClassName;
                $instance = new $className();
            }
            return $instance;
        }

        function getBlocks($visible = '')
        {
            $xcRoot = XCube_Root::getSingleton();
            $db =& XoopsDatabaseFactory::getDatabaseConnection();
            $newblocksTable = $db->prefix( 'newblocks' );
            $modulesTable = $db->prefix( 'modules' );
            $blocks = array();
            // TODO : use ORM
            $sql = "SELECT ";
            $sql .= " $newblocksTable.*, $modulesTable.`name` AS module_name ";
            $sql .= " FROM `$newblocksTable` LEFT JOIN ";
            $sql .= " `$modulesTable` ON $modulesTable.`mid` = $newblocksTable.`mid` ";
            $sql .= " WHERE ";
            if (isset($visible) && $visible !== '') {
                $visible = intval($visible);
                $sql .= " $newblocksTable.`visible` = $visible AND ";
            }
            $sql .= " $newblocksTable.`isactive` = 1 ";
            $sql .= " ORDER BY $newblocksTable.`visible` DESC, $newblocksTable.`weight`, " .
                " $newblocksTable.`bid`";
            if ( $resource = $db->query($sql) ) {
                while ( $result = $db->fetchArray($resource) ) {
                    if ( $result !== false && ! empty($result) ) {
                        $blocks[] = $result;
                    }
                }
            }
            return $blocks;
        }

        function getWizMobileBlocks($visible = '')
        {
            $blocks = array();
            $db =& XoopsDatabaseFactory::getDatabaseConnection();
            $blockTable = $db->prefix( $this->_sFrontDirName . '_blocks' );
            // TODO : use ORM
            $sql = "SELECT * FROM `$blockTable` WHERE `wmb_delete_datetime` = '0000-00-00 00:00:00'";
            if ($visible !== '') {
                $sql .= " AND `wmb_visible` = " . intval($visible);
            }
            $sql .= " ORDER BY `$blockTable`.`wmb_visible` DESC, `$blockTable`.`wmb_weight`, " .
                " `$blockTable`.`wmb_bid`";
            if ( $resource = $db->query($sql) ) {
                while ( $result = $db->fetchArray($resource) ) {
                    if ( $result !== false && ! empty($result) ) {
                        $wmb_bid = intval($result['wmb_bid']);
                        $blocks[$wmb_bid] = $result;
                    }
                }
            }
            return $blocks;
        }

        function getConfigs()
        {
            static $configs;
            if ( isset($configs) ) {
                return $configs;
            }
            $xcRoot = XCube_Root::getSingleton();
            $db =& XoopsDatabaseFactory::getDatabaseConnection();
            $configTable = $db->prefix( $this->_sFrontDirName . '_configs' );
            $configs = array();
            // TODO : use ORM
            $sql = "SELECT * FROM `$configTable` WHERE `wmc_delete_datetime` = '0000-00-00 00:00:00';";
            if ( $resource = $db->query($sql) ) {
                while ( $result = $db->fetchArray($resource) ) {
                    if ( $result !== false && ! empty($result) ) {
                        $wmc_item = $result['wmc_item'];
                        $configs[$wmc_item] = $result;
                    }
                }
            }
            return $configs;
        }

        function getAtypical()
        {
            static $atypical;
            if ( isset($atypical) ) {
                return $atypical;
            }
            $xcRoot = XCube_Root::getSingleton();
            $db =& XoopsDatabaseFactory::getDatabaseConnection();
            $atypicalTable = $db->prefix( $this->_sFrontDirName . '_atypical' );
            $configs = array();
            // TODO : use ORM
            $sql = "SELECT * FROM `$atypicalTable` WHERE `wma_delete_datetime` = '0000-00-00 00:00:00';";
            if ( $resource = $db->query($sql) ) {
                while ( $result = $db->fetchArray($resource) ) {
                    if ( $result !== false && ! empty($result) ) {
                        $wma_item = $result['wma_item'];
                        $atypical[$wma_item] = $result;
                    }
                }
            }
            return $atypical;
        }

        function getThemes()
        {
            static $themes;
            if ( isset($themes) ) {
                return $themes;
            }
            $xcRoot = XCube_Root::getSingleton();
            $db =& XoopsDatabaseFactory::getDatabaseConnection();
            $themeTable = $db->prefix( $this->_sFrontDirName . '_themes' );
            $themes = array();
            // TODO : use ORM
            $sql = "SELECT * FROM `$themeTable` WHERE `wmt_delete_datetime` IS NULL;";
            if ( $resource = $db->query($sql) ) {
                while ( $result = $db->fetchArray($resource) ) {
                    $wmt_groupid = intval($result['wmt_groupid']);
                    $wmt_mid = intval($result['wmt_mid']);
                    if (! isset($themes[$wmt_groupid])) {
                        $themes[$wmt_groupid] = array();
                    }
                    $themes[$wmt_groupid][$wmt_mid] = $result['wmt_theme_name'];
                }
            }
            return $themes;
        }

        function getMobileThemes()
        {
            $themes = array();
            if ( $handler = opendir(XOOPS_THEME_PATH) ) {
                while ( ($dirname = readdir($handler)) !== false ) {
                    if ( $dirname === '.' || $dirname === '..' ) {
                        continue;
                    }

                    $themeDir = XOOPS_THEME_PATH . "/" . $dirname;
                    if ( is_dir($themeDir) ) {
                        if ( file_exists($themeDir . '/.legacy_wizmobilerendersystem') ) {
                            $themes[] = $dirname;
                        }
                    }
                }
                closedir($handler);
            }
            return $themes;
        }

        function getTemplateSet()
        {
            $xcRoot = XCube_Root::getSingleton();
            $db =& XoopsDatabaseFactory::getDatabaseConnection();
            $tplsetTable = $db->prefix( 'tplset' );
            $templateSets = array();
            // TODO : use ORM
            $sql = "SELECT ";
            $sql .= " $tplsetTable.`tplset_id`, $tplsetTable.`tplset_name` ";
            $sql .= " FROM `$tplsetTable` ";
            $sql .= " ORDER BY ";
            $sql .= " $tplsetTable.`tplset_id` ;";
            if ( $resource = $db->query($sql) ) {
                while ( $result = $db->fetchArray($resource) ) {
                    if ( $result !== false && ! empty($result) ) {
                        $tplsetId = intval( $result['tplset_id'] );
                        $templateSets[$tplsetId] = $result;
                    }
                }
            }
            return $templateSets;
        }

        function getDenyAccessModules()
        {
            $denyAccessModules = array();
            $db =& XoopsDatabaseFactory::getDatabaseConnection();
            $moduleTable = $db->prefix( $this->_sFrontDirName . '_modules' );
            // TODO : use ORM
            $sql = "SELECT `wmm_mid` FROM `$moduleTable` WHERE `wmm_delete_datetime` = '0000-00-00 00:00:00';";
            if ( $resource = $db->query($sql) ) {
                while ( $result = $db->fetchArray($resource) ) {
                    if ( $result !== false && ! empty($result) ) {
                        $denyAccessModules[] = intval( $result['wmm_mid'] );
                    }
                }
            }
            return $denyAccessModules;
        }

        function googleAdsParams($adsenseCode = '')
        {
            $params = array();
            $adsKeys = array('ad_type', 'channel', 'format', 'markup', 'output');
            $adsenseCode = strtr($adsenseCode, array("\r\n" => "\n", "\r" => "\n"));
            $lines = explode("\n", $adsenseCode);
            foreach ($lines as $line) {
                if (substr($line, 0, 18) === '$GLOBALS[' . "'google'" . ']') {
                    list($key, $value) = explode("=", $line);
                    $key = strtr($key, array('$GLOBALS[' . "'google'" . "]['" => '', "']" => ''));
                    $value = strtr($value, array("'" => '', ';' => '', "\r" => '', "\n" => ''));
                    if ($key === 'client') {
                        $value = 'ca-mb-' . $value;
                    } else if (! in_array($key, $adsKeys) && strpos($key, 'color_') !== 0) {
                        continue;
                    }
                    $params[$key] = $value;
                }
            }
            return $params;
        }
    }
}

$className = $frontDirname . "_" . 'WizMobile_Action';
if ( ! class_exists($className) ) {
    eval( "class $className extends WizMobile_Action {}" );
}

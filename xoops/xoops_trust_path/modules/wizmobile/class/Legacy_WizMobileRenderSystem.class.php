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

if (!defined('XOOPS_ROOT_PATH')) exit();

if ( !defined('LEGACY_RENDERSYSTEM_BANNERSETUP_BEFORE') ) {
    include_once( XOOPS_ROOT_PATH . '/modules/legacyRender/kernel/Legacy_RenderSystem.class.php' );
}
include_once( XOOPS_TRUST_PATH . '/modules/wizmobile/class/Legacy_WizMobileRenderTarget.class.php' );

if( ! class_exists( 'Legacy_WizMobileRenderSystem' ) ) {
    class Legacy_WizMobileRenderSystem extends Legacy_RenderSystem
    {
        /**
         * @deprecated
         */
        function sendHeader()
        {
            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
            header('Cache-Control: no-store, no-cache, must-revalidate');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
        }

        /**
         * @TODO This function is not cool!
         */
        function &getThemeRenderTarget($isDialog = false)
        {
            $screenTarget = $isDialog ? new Legacy_WizMobileDialogRenderTarget() : new Legacy_WizMobileThemeRenderTarget();
            return $screenTarget;
        }

        function renderTheme(&$target)
        {
            // init process
            $user =& Wizin_User::getSingleton();
            if ($user->bIsMobile) {
                $this->_renderThemeMobile( $target );
            }
            parent::renderTheme( $target );
        }

        function _renderThemeMobile(&$target)
        {
            // init process
            $xcRoot =& XCube_Root::getSingleton();
            $wizMobile =& WizMobile::getSingleton();
            $wizMobileAction =& $wizMobile->getActionClass();
            // if $xoops_contents is empty, display default setting block.
            $configs = $wizMobileAction->getConfigs();
            $xoopsContents = $target->getAttribute( 'xoops_contents' );
            $defaultBid = 0;
            if ( (! isset($xoopsContents) || $xoopsContents === '') &&
                    empty($_REQUEST['mobilebid']) ) {
                $defaultBid = isset($configs['default_bid']) ?
                    intval($configs['default_bid']['wmc_value']): 0;
            }
            $mobileBid = isset($_REQUEST['mobilebid']) ? intval($_REQUEST['mobilebid']) :
                (isset($defaultBid) ? $defaultBid : 0);
            /*
             * Deprecated logic block.
             * This block will delete...
             */
            $nondisplayBlocks = $wizMobileAction->getWizMobileBlocks(WIZMOBILE_BLOCK_INVISIBLE);
            $legacy_BlockContents =& $xcRoot->mContext->mAttributes['legacy_BlockContents'];
            $blockFlagMap = array( 'xoops_showlblock', 'xoops_showcblock', 'xoops_showcblock',
                'xoops_showcblock', 'xoops_showrblock' );
            if ( ! empty($legacy_BlockContents) ) {
                foreach ( $legacy_BlockContents as $index => $blockArea ) {
                    foreach ( $blockArea as $key => $block ) {
                        $blockId = intval( $block['id'] );
                        if ( ! in_array($blockId, $nondisplayBlocks) || $blockId === $defaultBid ) {
                            if ( $mobileBid === $blockId ) {
                                $this->mXoopsTpl->assign( 'wizMobileBlockTitle', $block['title'] );
                                $this->mXoopsTpl->assign( 'wizMobileBlockContents', $block['content'] );
                            }
                        } else {
                            unset( $xcRoot->mContext->mAttributes['legacy_BlockContents'][$index][$key] );
                        }
                    }
                    if ( count($xcRoot->mContext->mAttributes['legacy_BlockContents'][$index]) === 0 ) {
                        $xcRoot->mContext->mAttributes['legacy_BlockShowFlags'][$index] = false;
                    }
                }
            }
            // deprecated logic <<

            // get block
            $wizmobileBlocks = $wizMobileAction->getWizMobileBlocks(WIZMOBILE_BLOCK_VISIBLE);
            $wizmobileBlockKeys = array_keys($wizmobileBlocks);
            $blocks = $this->_getMobileBlocks($wizmobileBlockKeys);
            $this->mXoopsTpl->assign('blocks', $blocks);
            // get selected block
            if ($mobileBid !== 0) {
                $selectBlock = $this->_getMobileBlocks($mobileBid);
                if (! empty($selectBlock)) {
                    $this->mXoopsTpl->assign('selectBlock', array_shift($selectBlock));
                }
            }
            // display sub menu
            $subMenuContents = '';
            if ( function_exists('b_legacy_mainmenu_show') ) {
                $xoopsModule =& $xcRoot->mContext->mXoopsModule;
                if ( isset($xoopsModule) && is_object($xoopsModule) ) {
                    if ( $xoopsModule->getVar('hasmain') == 1 && $xoopsModule->getVar('weight') > 0 ) {
                        $dirname = $xoopsModule->getVar('dirname');
                        $modname = $xoopsModule->getVar('name');
                        // assign submenu(string.)
                        $subMenuContents .= '<a href="' . XOOPS_URL . '/modules/' . htmlspecialchars( $dirname, ENT_QUOTES ) .
                            '/">[' . htmlspecialchars( $modname, ENT_QUOTES ) . ']</a>&nbsp;';
                        $subLinks = $xoopsModule->subLink();
                        foreach ( $subLinks as $index => $subLink ) {
                            if ( $index !== 0 ) {
                                $subMenuContents .= "&nbsp;/&nbsp;";
                            }
                            $subMenuContents .= '<a href="' . $subLink['url'] . '">' . $subLink['name'] . '</a>';
                        }
                        $this->mXoopsTpl->assign( 'wizMobileSubMenuContents', $subMenuContents );
                        // assign submenu(not string.)
                        $this->mXoopsTpl->assign('wizMobileModuleName', $modname);
                        $this->mXoopsTpl->assign('wizMobileModuleLink', XOOPS_URL . '/modules/' . $dirname . '/');
                        if (! empty($subLinks)) {
                            $this->mXoopsTpl->assign('wizMobileModuleSubLinks', $subLinks);
                        } else {
                            $this->mXoopsTpl->assign('wizMobileModuleSubLinks', '');
                        }
                    }
                }
            }
        }

        function _getMobileBlocks($wizmobileBlockKeys = '')
        {
            // init process
            $blocks = array();
            if (empty($wizmobileBlockKeys)) {
                return $blocks;
            } else if (! is_array($wizmobileBlockKeys)) {
                $wizmobileBlockKeys = (array)$wizmobileBlockKeys;
            }
            $xcRoot =& XCube_Root::getSingleton();
            $groups = is_object($xcRoot->mContext->mXoopsUser) ?
                $xcRoot->mContext->mXoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
            $db =& XoopsDatabaseFactory::getDatabaseConnection();
            $wizMobile =& WizMobile::getSingleton();
            $wizMobileAction =& $wizMobile->getActionClass();
            $blockHandler =& xoops_gethandler('block');

            // get permitted blockid
            $sql = "SELECT DISTINCT gperm_itemid FROM ".$db->prefix('group_permission')." WHERE gperm_name = 'block_read' AND gperm_modid = 1";
            if ( is_array($groups) ) {
                $sql .= ' AND gperm_groupid IN ('.implode(',', array_map('intval', $groups)).')';
            } else {
                if (intval($groups) > 0) {
                    $sql .= ' AND gperm_groupid='.intval($groups);
                }
            }
            $result = $db->query($sql);
            $blockids = array();
            while ($myrow = $db->fetchArray($result)) {
                $blockids[] = $myrow['gperm_itemid'];
            }

            // get block objects
            foreach ($wizmobileBlockKeys as $wmb_bid) {
                if (in_array($wmb_bid, $blockids)) {
                    $blockObject =& $blockHandler->get($wmb_bid);
                    $blockProcedure =& Legacy_Utils::createBlockProcedure($blockObject);
                    if ($blockProcedure->prepare() !== false) {
                        // get block contents
                        $block = $this->_getMobileBlockContents($blockProcedure);
                        if (! empty($block)) {
                            $blocks[$wmb_bid] = $this->_getMobileBlockContents($blockProcedure);
                        }
                        unset($block);
                    }
                    unset($blockObject);
                    unset($blockProcedure);
                }
            }

            // end process
            unset($groups);
            unset($blockids);
            unset($wizmobileBlocks);
            unset($wizmobileBlockKeys);
            return $blocks;
        }

        function _getMobileBlockContents(& $blockProcedure)
        {
            $block = array();
            $xcRoot =& XCube_Root::getSingleton();
            $usedCacheFlag = false;
            $cacheInfo = null;
            // cache enabled
            if ($xcRoot->mController->isEnableCacheFeature() && $blockProcedure->isEnableCache()) {
                //
                // Reset the block cache information structure, and initialize.
                //
                $cacheInfo =& $blockProcedure->createCacheInfo();
                $xcRoot->mController->mSetBlockCachePolicy->call(new XCube_Ref($cacheInfo));
                $filepath = $cacheInfo->getCacheFilePath();

                //
                // If caching is enable and the cache file exists, load and use.
                //
                if ($cacheInfo->isEnableCache() &&
                        $xcRoot->mController->existActiveCacheFile($filepath, $blockProcedure->getCacheTime())) {
                    $content = $xcRoot->mController->loadCache($filepath);
                    if ($blockProcedure->isDisplay() && !empty($content)) {
                        $block = array(
                            'id' => $blockProcedure->getId(),
                            'name' => $blockProcedure->getName(),
                            'title'      => $blockProcedure->getTitle(),
                            'content' => $content,
                            'weight'  => $blockProcedure->getWeight()
                        );
                    }
                    $usedCacheFlag = true;
                }
            }
            // cache disabled
            if (!$usedCacheFlag) {
                $blockProcedure->execute();
                $renderBuffer = null;
                if ($blockProcedure->isDisplay()) {
                    $renderBuffer =& $blockProcedure->getRenderTarget();
                    $block = array(
                            'name' => $blockProcedure->getName(),
                            'title'=>$blockProcedure->getTitle(),
                            'content'=>$renderBuffer->getResult(),
                            'weight'=>$blockProcedure->getWeight(),
                            'id' => $blockProcedure->getId(),
                    );
                }
            }
            return $block;
        }
    }
}

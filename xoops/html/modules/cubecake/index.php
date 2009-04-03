<?php
/* SVN FILE: $Id: index.php 7945 2008-12-19 02:16:01Z gwoo $ */
/**
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision: 7945 $
 * @modifiedby    $LastChangedBy: gwoo $
 * @lastmodified  $Date: 2008-12-18 18:16:01 -0800 (Thu, 18 Dec 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */

if (! file_exists(dirname(dirname(__FILE__)) .'/mainfile.php')) {
    die('Please copy "app" directory into your "{XOOPS_ROOT_PATH}/modules" directory.');
}

require_once dirname(dirname(__FILE__)) .'/mainfile.php';
require_once XOOPS_ROOT_PATH . "/header.php";

define('DS', DIRECTORY_SEPARATOR);
define('WEBROOT_DIR', basename(dirname(__FILE__)));
define('WWW_ROOT', dirname(__FILE__) . DS);
define('CAKE_CORE_INCLUDE_PATH', XOOPS_TRUST_PATH.DS.'cubecake');
define('ROOT', dirname(dirname(__FILE__)));
define('APP_DIR', basename(dirname(__FILE__)));
define('APP_PATH', ROOT . DS . APP_DIR . DS);
define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
define('TMP', XOOPS_ROOT_PATH . DS . 'cache' . DS);

ob_start();
require WWW_ROOT.'webroot'.DS.'index.php';
$out = ob_get_contents();
ob_end_clean();

$root =& XCube_Root::getSingleton();
$target =& $root->mContext->mModule->getRenderTarget();
$target->setResult($out);
$target->setAttribute('legacy_buffertype', null);

$theme =& $root->mController->_mStrategy->getMainThemeObject();
$renderSystem =& $root->getRenderSystem($theme->get('render_system'));
$renderSystem->_commonPrepareRender();

if (isset($GLOBALS['xoopsUserIsAdmin'])) {
    $renderSystem->mXoopsTpl->assign('xoops_isadmin', $GLOBALS['xoopsUserIsAdmin']);
}
$renderSystem->mXoopsTpl->assign('xoops_block_header', '');
$renderSystem->mXoopsTpl->assign('xoops_module_header', '');

$theme =& $root->mController->_mStrategy->getMainThemeObject();
$renderSystem =& $root->getRenderSystem($theme->get('render_system'));
$renderSystem->_commonPrepareRender();

if (isset($GLOBALS['xoopsUserIsAdmin'])) {
    $renderSystem->mXoopsTpl->assign('xoops_isadmin', $GLOBALS['xoopsUserIsAdmin']);
}
$renderSystem->mXoopsTpl->assign('xoops_block_header', '');

require_once XOOPS_ROOT_PATH . "/footer.php";

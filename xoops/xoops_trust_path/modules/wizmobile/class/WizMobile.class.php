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

if (! class_exists('WizMobile')) {
    class WizMobile extends Wizin_StdClass
    {
        function __construct()
        {
            $this->_require();
            $this->_define();
            $this->_setup();
            $this->_init();
        }

        function &getSingleton()
        {
            static $instance;
            if (! isset($instance)) {
                $instance = new WizMobile();
            }
            return $instance;
        }

        function _require()
        {
            require_once XOOPS_TRUST_PATH . '/wizin/src/Wizin_User.class.php';
            require_once XOOPS_TRUST_PATH . '/wizin/src/filter/Mobile.class.php';
            require_once XOOPS_TRUST_PATH . '/wizin/src/util/Web.class.php';
        }

        function _define()
        {
            // block display mode
            define('WIZMOBILE_BLOCK_INVISIBLE', 0);
            define('WIZMOBILE_BLOCK_VISIBLE', 1);
            // connector string and current uri
            if (WIZXC_URI_CONNECTOR === '&') {
                if (! empty($_REQUEST['mobilebid'])) {
                    $queryString = getenv('QUERY_STRING');
                    $pattern = '(mobilebid=)([0-9]+)(&|&amp;)?';
                    $currentUri = preg_replace('/' . $pattern . '/', '', WIZXC_CURRENT_URI);
                    if (substr($currentUri, -1, 1) === '?' || substr($currentUri, -1, 1) === '&') {
                        $currentUri = substr($currentUri, 0, strlen($currentUri) - 1);
                    }
                    // countermeasure against bug which exists until version 0.1.4 >>
                    $method = getenv('REQUEST_METHOD');
                    if (strtolower($method) === 'get') {
                        preg_match_all('/(mobilebid)/i', $queryString, $matches, PREG_SET_ORDER);
                        if (count($matches) > 2) {
                            header("HTTP/1.1 301 Moved Permanently");
                            header("Location: " . $currentUri);
                            exit();
                        }
                    }
                    // countermeasure against bug which exists until version 0.1.4 <<
                    $queryString = preg_replace('/' . $pattern . '/', '', $queryString);
                    if (strlen($queryString) > 0) {
                        define('WIZMOBILE_BID_CONNECTOR', '&');
                    } else {
                        define('WIZMOBILE_BID_CONNECTOR', '?');
                    }
                    define('WIZMOBILE_CURRENT_URI', $currentUri);
                } else {
                    define('WIZMOBILE_CURRENT_URI', WIZXC_CURRENT_URI);
                    define('WIZMOBILE_BID_CONNECTOR', WIZXC_URI_CONNECTOR);
                }
            } else if (WIZXC_URI_CONNECTOR === '?') {
                define('WIZMOBILE_CURRENT_URI', WIZXC_CURRENT_URI);
                define('WIZMOBILE_BID_CONNECTOR', WIZXC_URI_CONNECTOR);
            }
        }

        function _setup()
        {
        }

        function _init()
        {
            ob_start();
            $xcRoot =& XCube_Root::getSingleton();
            $xcRoot->mDelegateManager->add('XoopsTpl.New' , array($this , 'assignVars')) ;
        }

        function & getActionClass()
        {
            $className = $this->sActionClassName;
            $class = new $className();
            $actionClass =& $class->getSingletonByOwn();
            unset($class);
            return $actionClass;
        }

        function oneTimeProcess()
        {
            static $callFlag;
            if (! isset($callFlag)) {
                $callFlag = true;
                $xcRoot =& XCube_Root::getSingleton();
                $user = & Wizin_User::getSingleton();
                $filter = & Wizin_Filter_Mobile::getSingleton();
                $actionClass =& $this->getActionClass();
                $configs = $actionClass->getConfigs();
                if (! empty($configs['lookup']) && $configs['lookup']['wmc_value'] === '1') {
                    $lookup = true;
                } else {
                    $lookup = false;
                }
                if (! empty($configs['otherMobile']) && $configs['otherMobile']['wmc_value'] === '1') {
                    $otherMobile = true;
                } else {
                    $otherMobile = false;
                }
                $user->checkClient($lookup);
                if ($user->sCarrier === 'othermobile') {
                    $user->bIsMobile = $otherMobile;
                }
                if ($user->bIsMobile) {
	                // add delegate
	                $xcRoot->mDelegateManager->add('XoopsTpl.New' , array($this , 'mobileTpl')) ;
                    // set session ini
                    if (! $user->bCookie) {
                        ini_set('session.use_cookies', "0");
                        ini_set('session.use_only_cookies', "0");
                        ini_set('session.use_trans_sid', "0");
                    }
                    // call input filter
                    $this->_inputFilter();
                    // exchange view
                    $this->_exchangeTheme();
                    $this->_exchangeTemplateSet();
                } else {
                    ini_set('default_charset', _CHARSET);
                    header('Content-Type:text/html; charset=' . _CHARSET);
                }
                // exchange render system
                $this->_exchangeRenderSystem();
            }
        }

        function _inputFilter()
        {
            $user = & Wizin_User::getSingleton();
            $filter =& Wizin_Filter_Mobile::getSingleton();
            $params = array();
            $filter->addInputFilter(array($filter, 'filterInputPictogramMobile'), $params);
            $params = array($user->sEncoding);
            $filter->addInputFilter(array($filter, 'filterInputEncoding'), $params);
            $params = array();
            $filter->addInputFilter(array($filter, 'filterInputMobile'), $params);
            $filter->executeInputFilter();
        }

        function checkMobileSession()
        {
            $xcRoot =& XCube_Root::getSingleton();
            $userAgent = getenv('HTTP_USER_AGENT');
            $user = & Wizin_User::getSingleton();
            $url = (! $user->bCookie) ? XOOPS_URL. '/' . '?' . SID : XOOPS_URL;
            if (empty($_SESSION['WIZ_USER_AGENT'])) {
                $_SESSION['WIZ_USER_AGENT'] = $userAgent;
            } else if ($_SESSION['WIZ_USER_AGENT'] !== $userAgent) {
                WizXc_Util::sessionDestroy();
                $_SESSION['redirect_message'] = Wizin_Util::constant('WIZMOBILE_MSG_SESSION_LIMIT_TIME');
                $_SESSION['WIZ_SESSION_ZERO_POINT'] = time();
                header("Location: " . $url);
                exit();
            }
            if (empty($_SESSION['WIZ_SESSION_ZERO_POINT'])) {
                $_SESSION['WIZ_SESSION_ZERO_POINT'] = time();
            } else if ($_SESSION['WIZ_SESSION_ZERO_POINT'] < time() - 300) {
                $encodeSession = session_encode();
                $_SESSION = array();
                session_regenerate_id();
                session_decode($encodeSession);
                $_SESSION['WIZ_SESSION_ZERO_POINT'] = time();
            }
            if (! empty($_SESSION['WIZ_SESSION_LAST_ACCESS']) &&
                $_SESSION['WIZ_SESSION_LAST_ACCESS'] < time() - 900 &&
                is_object($xcRoot->mContext->mXoopsUser)) {
                WizXc_Util::sessionDestroy();
                session_regenerate_id();
                $_SESSION['redirect_message'] = Wizin_Util::constant('WIZMOBILE_MSG_SESSION_LIMIT_TIME');
                $_SESSION['WIZ_SESSION_ZERO_POINT'] = time();
                $_SESSION['WIZ_SESSION_LAST_ACCESS'] = time();
                header("Location: " . $url);
                exit();
            }
            $_SESSION['WIZ_SESSION_LAST_ACCESS'] = time();
        }

        function _exchangeRenderSystem()
        {
            // exchange render system
            $user = & Wizin_User::getSingleton();
            $xcRoot =& XCube_Root::getSingleton();
            if ($user->bIsMobile) {
                $xcRoot->mDelegateManager->add('LegacyThemeHandler.GetInstalledThemes',
                    'LegacyWizMobileRender_DelegateFunctions::getInstalledThemes',
                    XOOPS_TRUST_PATH . '/modules/wizmobile/class/DelegateFunctions.class.php');
            }
            // if access area is not admin area, exchange render system
            if (! class_exists('Legacy_AdminControllerStrategy')) {
                $renderSystem = $xcRoot->mContext->mBaseRenderSystemName;
                $xcRoot->overrideSiteConfig(array($renderSystem => $xcRoot->mSiteConfig['Legacy_WizMobileRenderSystem']));
            }
        }

        function _exchangeTheme()
        {
            // init process
            $xcRoot =& XCube_Root::getSingleton();
            $actionClass =& $this->getActionClass();
            // get default theme
            $configs = $actionClass->getConfigs();
            if (! empty($configs) && ! empty($configs['theme']) && ! empty($configs['theme']['wmc_value'])) {
                $theme = $configs['theme']['wmc_value'];
            } else {
                $theme = 'mobile';
            }
            if (file_exists(XOOPS_THEME_PATH . '/' . $theme) && is_dir(XOOPS_THEME_PATH . '/' . $theme) &&
                    file_exists(XOOPS_THEME_PATH . '/' . $theme . '/theme.html')) {
                $xcRoot->mContext->setThemeName($theme);
            }
        }

        function _exchangeTemplateSet()
        {
            // exchange template set
            $xcRoot =& XCube_Root::getSingleton();
            $actionClass =& $this->getActionClass();
            $configs = $actionClass->getConfigs();
            if (! empty($configs) && ! empty($configs['template_set']) &&
                    ! empty($configs['template_set']['wmc_value'])) {
                $templateSet = $actionClass->getTemplateSet();
                $tplsetId = $configs['template_set']['wmc_value'];
                $templateSetName = $templateSet[$tplsetId]['tplset_name'];
                $xcRoot->mContext->mXoopsConfig['template_set'] = $templateSetName;
                $GLOBALS['xoopsConfig']['template_set'] = $templateSetName;
            }
        }

        function resetUserTheme()
        {
            unset($_SESSION['xoopsUserTheme']);
        }

        function directLoginSuccess()
        {
            $xcRoot =& XCube_Root::getSingleton();
            $xcRoot->mSession->regenerate();
            $user = & Wizin_User::getSingleton();
            $url = (! $user->bCookie) ? XOOPS_URL. '/' . '?' . SID : XOOPS_URL;
            $_SESSION['redirect_message'] = XCube_Utils::formatMessage(_MD_LEGACY_MESSAGE_LOGIN_SUCCESS,
                $xcRoot->mContext->mXoopsUser->get('uname'));
            header("Location: " . $url);
            exit();
        }

        function directLoginFail()
        {
            $xcRoot =& XCube_Root::getSingleton();
            $xcRoot->mSession->regenerate();
            $user = & Wizin_User::getSingleton();
            $url = (! $user->bCookie) ? XOOPS_URL. '/' . '?' . SID : XOOPS_URL;
            $_SESSION['redirect_message'] = _MD_LEGACY_ERROR_INCORRECTLOGIN;
            header("Location: " . $url);
            exit();
        }

        function directLogout()
        {
            WizXc_Util::sessionDestroy();
            $xcRoot =& XCube_Root::getSingleton();
            $xcRoot->mSession->regenerate();
            $user = & Wizin_User::getSingleton();
            $url = (! $user->bCookie) ? XOOPS_URL. '/' . '?' . SID : XOOPS_URL;
            $_SESSION['redirect_message'] = htmlspecialchars(_MD_LEGACY_MESSAGE_LOGGEDOUT, ENT_QUOTES) . '<br />';
            $_SESSION['redirect_message'] .= htmlspecialchars(_MD_LEGACY_MESSAGE_THANKYOUFORVISIT, ENT_QUOTES);
            header("Location: " . $url);
            exit();
        }

        function denyAccessLoginPage()
        {
            $user = & Wizin_User::getSingleton();
            $url = (! $user->bCookie) ? XOOPS_URL. '/' . '?' . SID : XOOPS_URL;
            $_SESSION['redirect_message'] = Wizin_Util::constant('WIZMOBILE_MSG_DENY_LOGIN_PAGE');
            header("Location: " . $url);
            exit();
        }

        function denyAccessAdminArea()
        {
            $user = & Wizin_User::getSingleton();
            $url = (! $user->bCookie) ? XOOPS_URL. '/' . '?' . SID : XOOPS_URL;
            $_SESSION['redirect_message'] = Wizin_Util::constant('WIZMOBILE_MSG_DENY_ADMIN_AREA');
            header("Location: " . $url);
            exit();
        }

        function denyAccessModuleArea()
        {
            $user = & Wizin_User::getSingleton();
            $url = (! $user->bCookie) ? XOOPS_URL. '/' . '?' . SID : XOOPS_URL;
            $_SESSION['redirect_message'] = Wizin_Util::constant('WIZMOBILE_MSG_DENY_ACCESS_MODULE_PAGE');
            header("Location: " . $url);
            exit();
        }

        function renderContents()
        {
            $contents = ob_get_clean();
            $user = & Wizin_User::getSingleton();
            $filter =& Wizin_Filter_Mobile::getSingleton();
            if ($user->bIsMobile) {
                $this->_filterMobile($filter, $contents);
                $actionClass =& $this->getActionClass();
                $configs = $actionClass->getConfigs();
                if (! empty($configs['content_type']) && $configs['content_type']['wmc_value'] === '1') {
                    $contentType = 'application/xhtml+xml';
                } else {
                    $contentType = 'text/html';
                }
                header('Content-Type:' . $contentType . '; charset=' . $user->sCharset);
            } else {
                $this->_filterUnknown($filter, $contents);
            }
            $filter->executeOutputFilter($contents);
            echo $contents;
        }

        function _filterMobile(& $filter, & $contents)
        {
            $user = & Wizin_User::getSingleton();
            if (! $user->bCookie) {
                $params = array(XOOPS_URL, WIZMOBILE_CURRENT_URI);
                $filter->addOutputFilter(array($filter, 'filterTransSid'), $params);
            }
            $params = array(XOOPS_URL, WIZMOBILE_CURRENT_URI, XOOPS_ROOT_PATH, XOOPS_ROOT_PATH . '/uploads/wizmobile');
            $filter->addOutputFilter(array($filter, 'filterOptimizeMobile'), $params);
            $params = array($user->sEncoding, $user->sCharset);
            $filter->addOutputFilter(array($filter, 'filterOutputEncoding'), $params);
            $actionClass =& $this->getActionClass();
            $frontDirName = str_replace('_wizmobile_action', '', strtolower(get_class($actionClass)));
            $params = array(XOOPS_URL . '/modules/' . $frontDirName . '/images/emoticons');
            $filter->addOutputFilter(array($filter, 'filterOutputPictogramMobile'), $params);
        }

        function _filterUnknown(& $filter, & $contents)
        {
            $actionClass =& $this->getActionClass();
            $frontDirName = str_replace('_wizmobile_action', '', strtolower(get_class($actionClass)));
            $params = array(XOOPS_URL . '/modules/' . $frontDirName . '/images/emoticons');
            $filter->addOutputFilter(array($filter, 'filterOutputPictogramMobile'), $params);
        }

        function checkSessionFixation()
        {
            if (strpos($_SERVER['REQUEST_URI'], session_name()) !== false && strpos(WIZXC_CURRENT_URI, XOOPS_URL . '/user.php') !== 0) {
                $urlArray = explode(session_name(), WIZXC_CURRENT_URI);
                $url = $urlArray[0];
                if (substr($url, -1, 1) === '?' || substr($url, -1, 1) === '&') {
                    $url = substr($url, 0, strlen($url) - 1);
                }
                header("HTTP/1.1 404 Not Found");
                exit();
            }
        }

        function directRedirect($tplSource, & $xoopsTpl)
        {
            $tplFile = basename($xoopsTpl->_current_file);
            $tplFileArray = explode(':', $tplFile);
            if (count($tplFileArray) > 1) {
                $tplFile = array_pop($tplFileArray);
            }
            if ($tplFile === 'system_redirect.html' || $tplFile === 'legacy_redirect.html') {
                $url = $xoopsTpl->get_template_vars('url');
                $url = urldecode($url);
                $url = str_replace('&amp;', '&', $url);
                $url = strtr($url, array('?&' => '?', '&&' => '&'));
                $sessionName = ini_get('session.name');
                if (! empty($_GET[$sessionName]) || ! empty($_POST[$sessionName])) {
                    if (! strpos($url, $sessionName)) {
                        $user =& Wizin_User::getSingleton();
                        $urlFirstChar = substr($url, 0, 1);
                        if (! $user->bCookie) {
                            if (strpos($url, XOOPS_URL) === 0 || $urlFirstChar === '.' ||
                                    $urlFirstChar === '/' || $urlFirstChar === '#') {
                                if (!strstr($url, '?')) {
                                    $connector = '?';
                                } else {
                                    $connector = '&';
                                }
                                if (strstr($url, '#')) {
                                    $urlArray = explode('#', $url);
                                    $url = $urlArray[0] . $connector . SID;
                                    if (! empty($urlArray[1])) {
                                        $url .= '#' . $urlArray[1];
                                    }
                                } else {
                                    $url .= $connector . SID;
                                }
                            }
                        }
                    }
                }
                $message = $xoopsTpl->get_template_vars('message');
                $_SESSION['redirect_message'] = $message;
                header("Location: " . $url);
                exit();
            }
            return $tplSource;
        }

        function assignVars(& $xoopsTpl)
        {
            $wizMobile = & WizMobile::getSingleton();
            $user = & Wizin_User::getSingleton();
            $carrier = $user->sCarrier;
            $xoopsTpl->assign('wizmobile_carrier', $carrier);
            $isMobile = $user->bIsMobile;
            $xoopsTpl->assign('wizmobile_ismobile', $isMobile);
            $uniqId = $user->sUniqId;
            $xoopsTpl->assign('wizmobile_uniqid', $uniqId);
            $actionClass =& $wizMobile->getActionClass();
            $frontDirName = str_replace('_wizmobile_action', '', strtolower(get_class($actionClass)));
            $xoopsTpl->assign('wizmobile_dirname', $frontDirName);
            $configs = $actionClass->getConfigs();
            $xoopsTpl->assign('wizmobile_configs', $configs);
            if (! empty($configs['content_type']) && $configs['content_type']['wmc_value'] === '1') {
                $contentType = 'application/xhtml+xml';
                $headerTag = '<?xml version="1.0" encoding="' . $user->sCharset . '" ?>' . "\n";
				$headerTag .= $user->sDoctype . "\n";
				$headerTag .= '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . _LANGCODE .
					'" lang="' . _LANGCODE . '">';
            } else {
                $contentType = 'text/html';
                $headerTag = '<html>';
            }
            $xoopsTpl->assign('wizmobile_contentsType', $contentType);
            $xoopsTpl->assign('wizmobile_headerTag', $headerTag);
        }

        function mobileTpl(& $xoopsTpl)
        {
            $wizMobile = & WizMobile::getSingleton();
            $user = & Wizin_User::getSingleton();
            $xoopsTpl->register_postfilter(array($wizMobile, 'directRedirect'));
            $xoopsTpl->register_postfilter(array($wizMobile, 'filterMainMenu'));
            $xoopsTpl->compile_id .= '_' . $user->sCarrier;
            $actionClass =& $wizMobile->getActionClass();
            $configs = $actionClass->getConfigs();
            if (! empty($configs['pager']) && $configs['pager']['wmc_value'] === '1') {
                $pager = true;
            } else {
                $pager = false;
            }
            if ($pager) {
                $xoopsTpl->register_modifier('wiz_pager', array('Wizin_Filter_Mobile', 'filterMobilePager'));
            } else {
                $xoopsTpl->register_modifier('wiz_pager', array('WizMobile', 'dummyModifier'));
            }
            $xoopsTpl->register_function('wizmobile_google_ads', array('WizMobile', 'googleAds'));
        }

        function dummyModifier($string, $maxKbyte = 0)
        {
            return $string;
        }

        function filterMainMenu($tplSource, & $xoopsTpl)
        {
            $tplFile = basename($xoopsTpl->_current_file);
            $tplFileArray = explode(':', $tplFile);
            if (count($tplFileArray) > 1) {
                $tplFile = array_pop($tplFileArray);
            }
            if ($tplFile === 'legacy_block_mainmenu.html' || $tplFile === 'system_block_mainmenu') {
                $tplSource = '<?php WizMobile::modifyMainMenu($this); ?>' . "\n" . $tplSource;
            }
            return $tplSource;
        }

        function modifyMainMenu(& $xoopsTpl)
        {
            $block = $xoopsTpl->get_template_vars('block');
            $modules =& $block['modules'];
            $wizMobile = & WizMobile::getSingleton();
            $actionClass =& $wizMobile->getActionClass();
            $denyAccessModules = $actionClass->getDenyAccessModules();
            if (! empty($modules) && is_array($modules)) {
                foreach ($modules as $mid => $module) {
                    if (in_array($mid, $denyAccessModules)) {
                        unset($modules[$mid]);
                    }
                }
            }
            $xoopsTpl->assign('block', $block);
        }

        function googleAds()
        {
            $flgFile = XOOPS_TRUST_PATH . '/cache/wizmobile_ads_flg_' . Wizin_Util::salt(XOOPS_SALT);
            if (! file_exists($flgFile)) {
                touch($flgFile);
            }
            $cacheObject = new Wizin_Cache('wizmobile_ads_cache_', Wizin_Util::salt(XOOPS_SALT));
            if ($cacheObject->isCached($flgFile)) {
                $params = $cacheObject->load();
            } else {
                $wizMobile = & WizMobile::getSingleton();
                $actionClass =& $wizMobile->getActionClass();
                $atypical = $actionClass->getAtypical();
                if (! isset($atypical['adsense_code'])) {
                    return '';
                }
                $adsenseCode = $atypical['adsense_code']['wma_value'];
                $params = $actionClass->googleAdsParams($adsenseCode);
                $cacheObject->save($params);
            }
            $user = & Wizin_User::getSingleton();
            $params['https'] = getenv('HTTPS');
            $params['host'] = str_replace(getenv('REQUEST_URI'), '', WIZMOBILE_CURRENT_URI);
            $params['ip'] = $_SERVER['REMOTE_ADDR'];
            $params['ref'] = '';
            $params['url'] = $params['host'] . getenv('SCRIPT_NAME');
            $queryString = getenv('QUERY_STRING');
            $queryString = str_replace(SID, '', $queryString);
            if (! empty($queryString)) {
                $params['url'] .= '?' . $queryString;
            }
            $params['useragent'] = getenv('HTTP_USER_AGENT');
            switch ($user->sCharset) {
                case 'shift_jis':
                    $params['oe'] = 'sjis';
                    break;
                case 'euc-jp':
                    $params['oe'] = 'euc-jp';
                    break;
                case 'utf-8':
                default:
                    $params['oe'] = 'utf8';
                    break;
            }
            $dt = round(1000 * array_sum(explode(' ', microtime())));
            //$params['dt'] = $dt;
            $params = array_map('urlencode', $params);
            $adsUrl = 'http://pagead2.googlesyndication.com/pagead/ads?dt=' . $dt;
            // get screen info
            $screen_res = @ $_SERVER['HTTP_UA_PIXELS'];
            $delimiter = 'x';
            if (empty($screen_res)) {
                $screen_res = @ $_SERVER['HTTP_X_UP_DEVCAP_SCREENPIXELS'];
                $delimiter = ',';
            }
            if (! empty($screen_res)) {
                $res_array = explode($delimiter, $screen_res);
                if (count($res_array) == 2) {
                    $adsUrl .= '&u_w=' . $res_array[0] . '&u_h=' . $res_array[1];
                }
            }
            // get imode-id
            $dcmguid = getenv('HTTP_X_DCMGUID');
            if (! empty($dcmguid)) {
                $adsUrl .= '&dcmguid=' . $dcmguid;
            }
            foreach ($params as $key => $value) {
                $adsUrl .= '&' . $key . '=' . $value;
            }
            $adsTag = Wizin_Util_Web::getContentsByHttp($adsUrl);
            $adsTag = mb_convert_encoding($adsTag, mb_internal_encoding(), $user->sEncoding);
            return $adsTag;
        }

        function overrideTheme()
        {
            // init process
            $xcRoot =& XCube_Root::getSingleton();
            // get theme setting by cache
            $flgFile = XOOPS_TRUST_PATH . '/cache/wizmobile_theme_flg_' . Wizin_Util::salt(XOOPS_SALT);
            if (! file_exists($flgFile)) {
                touch($flgFile);
            }
            $cacheObject = new Wizin_Cache('wizmobile_theme_cache_', Wizin_Util::salt(XOOPS_SALT));
            if ($cacheObject->isCached($flgFile)) {
                $themes = $cacheObject->load();
            } else {
                $actionClass =& $this->getActionClass();
                $themes = $actionClass->getThemes();
                $cacheObject->save($themes);
            }
            // check group-module theme setting
            if (isset($xcRoot->mContext->mXoopsUser) && is_object($xcRoot->mContext->mXoopsUser)) {
                $groupid = 0;
            } else {
                $groupid = -1;
            }
            if (isset($xcRoot->mContext->mModule) && is_object($xcRoot->mContext->mModule)) {
                $mid = -1;
            } else {
                $mid = 0;
            }
            if (isset($themes[$groupid]) && isset($themes[$groupid][$mid])) {
                if (file_exists(XOOPS_THEME_PATH . '/' . $themes[$groupid][$mid]) &&
                        is_dir(XOOPS_THEME_PATH . '/' . $themes[$groupid][$mid]) &&
                        file_exists(XOOPS_THEME_PATH . '/' . $themes[$groupid][$mid] . '/theme.html')) {
                    $xcRoot->mContext->setThemeName($themes[$groupid][$mid]);
                }
            }
        }
    }
}

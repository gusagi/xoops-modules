<?php
/**
 *
 * PHP Versions 4
 *
 * @package  WizMobile
 * @author  gusagi<gusagi@gusagi.com>
 * @copyright  2007 - 2008 gusagi
 *
 */

if ( ! class_exists('WizMobile') ) {
    class WizMobile extends Wizin_StdClass
    {
        function WizMobile()
        {
            $this->_require();
            $this->_define();
            $this->_setup();
            $this->_mobileFilter();
        }

        function &getSingleton()
        {
            static $instance;
            if ( ! isset($instance) ) {
                $instance = new WizMobile();
            }
            return $instance;
        }

        function _require()
        {
            require_once XOOPS_TRUST_PATH . '/wizin/src/user/Wizin_User.class.php';
            require_once XOOPS_TRUST_PATH . '/wizin/src/session/Wizin_Session.class.php';
            require_once XOOPS_TRUST_PATH . '/wizin/src/filter/Wizin_Filter.class.php';
            $language = empty( $GLOBALS['xoopsConfig']['language'] ) ? 'english' : $GLOBALS['xoopsConfig']['language'];
            if( file_exists( dirname(dirname(__FILE__)) . '/language/' . $language . '/main.php' ) ) {
                require dirname(dirname(__FILE__)) . '/language/' . $language . '/main.php';
            }
        }

        function _define()
        {
            if ( ! empty($_REQUEST['mobilebid']) ) {
                $connecter = substr( WIZXC_CURRENT_URI, strpos(WIZXC_CURRENT_URI, 'mobilebid') - 1, 1 );
                if ( $connecter === '?' ) {
                    $deleteStr = 'mobilebid=' . $_REQUEST['mobilebid'] . '&';
                } else if ( $connecter === '&' ) {
                    $deleteStr = '&mobilebid=' . $_REQUEST['mobilebid'];
                }
                define( 'WIZMOBILE_CURRENT_URI', str_replace($deleteStr, '', WIZXC_CURRENT_URI) );
            } else {
                define( 'WIZMOBILE_CURRENT_URI', WIZXC_CURRENT_URI );
            }
        }

        function _setup()
        {
            $xcRoot =& XCube_Root::getSingleton();
            $user = & Wizin_User::getSingleton();
            $filter = & Wizin_Filter::getSingleton();
            @ $mobileConfig = $xcRoot->getSiteConfig( 'Mobile' );
            $lookup = false;
            $otherMobile = false;
            if ( ! empty($mobileConfig) ) {
                if ( ! empty($mobileConfig['lookup']) && $mobileConfig['lookup'] == true ) {
                    $lookup = true;
                }
                if ( ! empty($mobileConfig['othermobile']) && $mobileConfig['othermobile'] == true ) {
                    $otherMobile = true;
                }
            }
            $user->checkClient( $lookup );
            if ( $user->sCarrier === 'othermobile' ) {
                $user->bIsMobile = $otherMobile;
            }
        }

        function _mobileFilter()
        {
            $user = & Wizin_User::getSingleton();
            if ( $user->bIsMobile ) {
                Wizin_Session::overrideSessionIni( false );
                $this->_inputFilter();
                $this->_outputFilter();
                $this->_exchangeRenderSystem();
                register_shutdown_function( array($this, '_sendHeader') );
            }
            ini_set( 'default_charset', _CHARSET );
        }

        function _inputFilter()
        {
            $filter =& Wizin_Filter::getSingleton();
            $filter->filterInputEncoding();
        }

        function _outputFilter()
        {
            $user = & Wizin_User::getSingleton();
            $filter =& Wizin_Filter::getSingleton();
            $filter->filterOutputEncoding( $user->sEncoding, $user->sCharset );
        }

        function checkMobileSession()
        {
            $xcRoot =& XCube_Root::getSingleton();
            $userAgent = getenv( 'HTTP_USER_AGENT' );
            if ( empty($_SESSION['WIZ_USER_AGENT']) ) {
                $_SESSION['WIZ_USER_AGENT'] = $userAgent;
            } else if ( $_SESSION['WIZ_USER_AGENT'] !== $userAgent ) {
                WizXcUtil::sessionDestroy();
                $_SESSION["redirect_message"] = WIZMOBILE_MSG_SESSION_LIMIT_TIME;
                $_SESSION['WIZ_SESSION_ZERO_POINT'] = time();
                header("Location: " . XOOPS_URL. '/' . '?' . SID );
                exit();
            }
            if ( empty($_SESSION['WIZ_SESSION_ZERO_POINT']) ) {
                $_SESSION['WIZ_SESSION_ZERO_POINT'] = time();
            } else if ( $_SESSION['WIZ_SESSION_ZERO_POINT'] < time() - 300 ) {
                $encodeSession = session_encode();
                $_SESSION = array();
                session_regenerate_id();
                session_decode( $encodeSession );
                if ( $_SESSION['WIZ_SESSION_ZERO_POINT'] < time() - 600 && is_object($xcRoot->mContext->mXoopsUser) ) {
                    WizXcUtil::sessionDestroy();
                    session_regenerate_id();
                    $_SESSION["redirect_message"] = WIZMOBILE_MSG_SESSION_LIMIT_TIME;
                    $_SESSION['WIZ_SESSION_ZERO_POINT'] = time();
                    header("Location: " . XOOPS_URL. '/' . '?' . SID );
                    exit();
                }
                $_SESSION['WIZ_SESSION_ZERO_POINT'] = time();
            }
        }

        function _exchangeRenderSystem()
        {
            $xcRoot =& XCube_Root::getSingleton();
            $xcRoot->mDelegateManager->add( 'LegacyThemeHandler.GetInstalledThemes',
                'LegacyWizMobileRender_DelegateFunctions::getInstalledThemes',
                XOOPS_TRUST_PATH . '/modules/wizmobile/class/DelegateFunctions.class.php' );
        }

        function exchangeTheme()
        {
            $xcRoot =& XCube_Root::getSingleton();
            @ $mobileConfig = $xcRoot->getSiteConfig( 'Mobile' );
            if ( ! empty($mobileConfig) ) {
                // theme
                if ( ! empty($mobileConfig['theme']) && $mobileConfig['theme'] !== 'mobile' ) {
                    $theme = $mobileConfig['theme'];
                } else {
                    $theme = 'mobile';
                }
            } else {
                $theme = 'mobile';
            }
            if ( file_exists(XOOPS_THEME_PATH . '/' . $theme) && is_dir(XOOPS_THEME_PATH . '/' . $theme) &&
                file_exists(XOOPS_THEME_PATH . '/' . $theme . '/theme.html') ) {
                $xcRoot->mContext->setThemeName( $theme );
                $GLOBALS['xoopsConfig']['theme_set'] = $theme;
            }
        }

        function sessionRegenerateId()
        {
            session_regenerate_id();
        }

        function directLogout()
        {
            WizXcUtil::sessionDestroy();
            session_regenerate_id();
            $_SESSION["redirect_message"] = htmlspecialchars( _MD_LEGACY_MESSAGE_LOGGEDOUT, ENT_QUOTES ) . '<br />';
            $_SESSION["redirect_message"] .= htmlspecialchars( _MD_LEGACY_MESSAGE_THANKYOUFORVISIT, ENT_QUOTES );
            header("Location: " . XOOPS_URL. '/' . '?' . SID );
            exit();
        }

        function denyAccessAdminArea()
        {
            $_SESSION["redirect_message"] = WIZMOBILE_MSG_DENY_ADMIN_AREA;
            header("Location: " . XOOPS_URL. '/' . '?' . SID );
            exit();
        }

        function _obTransSid( $buf )
        {
            // get method
            $pattern = '(<a)(.*)(href=)([\"\'])(\S*)([\"\'])';
            preg_match_all( "/" .$pattern ."/i", $buf, $matches, PREG_SET_ORDER );
            if ( ! empty($matches) ) {
                foreach ( $matches as $key => $match) {
                    $href = '';
                    $hrefArray = array();
                    $value = $match[5];
                    $check = strstr( $value, XOOPS_URL );
                    if ( $check !== false ) {
                        if ( ! strpos($value, session_name()) ) {
                            if ( ! strpos($value, '?') ) {
                                $connector = '?';
                            } else {
                                $connector = '&';
                            }
                            if ( strpos($value, '#') !== false ) {
                                $hrefArray = explode( '#', $value );
                                $href .= $hrefArray[0] . $connector . SID;
                                if ( ! empty($hrefArray[1]) ) {
                                    $href .= '#' . $hrefArray[1];
                                }
                            } else {
                                $href = $value . $connector . SID;
                            }
                            $href = str_replace( XOOPS_URL, '', $href );
                            $buf = str_replace( 'href="' .$value .'"', 'href="' .$href .'"', $buf );
                            $buf = str_replace( "href='" .$value ."'", "href='" .$href ."'", $buf );
                        }
                    }
                }
            }
            // post method
            // pattern 1 ( "method=, action=" pattern )
            $pattern = '(<form)(.*)(method=)([\"\'])(post|get)([\"\'])(.*)(action=)([\"\'])(\S*)([\"\'])(.*)(>)';
            preg_match_all( "/" .$pattern ."/i", $buf, $matches, PREG_SET_ORDER );
            if ( ! empty($matches) ) {
                foreach ( $matches as $key => $match) {
                    $action = $match[10];
                    $check = strstr( $action, XOOPS_URL );
                    if ( $check !== false ) {
                        $tag = '<input type="hidden" name="' . session_name() . '" value="' . session_id() . '">';
                        $actionTag = $match[8] . $match[9] . str_replace( XOOPS_URL, '', $match[10] ) . $match[11];
                        $formTag = str_replace( $match[8] . $match[9] . $match[10] . $match[11], $actionTag, $match[0] );
                        $buf = str_replace( $match[0], $formTag . $tag, $buf );
                    }
                    $action = '';
                }
            }
            // pattern 2 ( "action=, method=" pattern )
            $pattern = '(<form)(.*)(action=)([\"\'])(\S*)([\"\'])(.*)(method=)([\"\'])(post|get)([\"\'])(.*)(>)';
            preg_match_all( "/" .$pattern ."/i", $buf, $matches, PREG_SET_ORDER );
            if ( ! empty($matches) ) {
                foreach ( $matches as $key => $match) {
                    $action = $match[5];
                    $check = strstr( $action, XOOPS_URL );
                    if ( $check !== false ) {
                        $tag = '<input type="hidden" name="' . session_name() . '" value="' . session_id() . '">';
                        $actionTag = $match[3] . $match[4] . str_replace( XOOPS_URL, '', $match[5] ) . $match[6];
                        $formTag = str_replace( $match[3] . $match[4] . $match[5] . $match[6], $actionTag, $match[0] );
                        $buf = str_replace( $match[0], $formTag . $tag, $buf );
                    }
                    $action = '';
                }
            }
            // replace input type "password" => "text"
            $pattern = '(<input)(.*)(type=)([\"\'])(password)([\"\'])(.*)(>)';
            $inputStyle = 'style="-wap-input-format:&quot;*&lt;ja:en&gt;&quot;;-wap-input-format:*m;"';
            $replacement = '${1}${2}${3}${4}text${6} ' . $inputStyle . '${7}${8}';
            $buf = preg_replace( "/" .$pattern ."/i", $replacement, $buf );
            return $buf;
        }

        function _sendHeader()
        {
            $user = & Wizin_User::getSingleton();
            header( 'Content-Type:text/html; charset=' . $user->sCharset );
        }

        function execute()
        {
            $act = $_REQUEST['act'];
            if ( method_exists($this, '_execute' . ucfirst($act)) ) {
                $function = '_execute' . ucfirst( $act );
                $this->$function();
            }
        }

    }
}
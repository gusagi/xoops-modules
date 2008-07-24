<?php
/**
 * Wizin framework filter class
 *
 * PHP Versions 4
 *
 * @package  Wizin
 * @author  Makoto Hashiguchi a.k.a. gusagi<gusagi@gusagi.com>
 * @copyright 2008 Makoto Hashiguchi
 * @license http://www.opensource.org/licenses/bsd-license.php The BSD License
 *
 */

if ( ! class_exists('Wizin_Filter_Common') ) {
    require dirname( dirname(__FILE__) ) . '/Wizin.class.php';
    require_once 'src/util/Web.class.php';

    class Wizin_Filter_Common extends Wizin_StdClass
    {
        function __construct()
        {
            if ( extension_loaded('mbstring') ) {
                ini_set( 'mbstring.http_input', 'pass' );
                ini_set( 'mbstring.http_output', 'pass' );
                ini_set( 'mbstring.encoding_translation', 0 );
                ini_set( 'mbstring.substitute_character', null );
            }
        }

        function &getSingleton()
        {
            static $instance;
            if ( ! isset($instance) ) {
                $instance = new Wizin_Filter();
            }
            return $instance;
        }

        function executeInputFilter()
        {
            $inputFilter = $this->_aInputFilter;
            for ( $index = 0; $index < count($inputFilter); $index ++ ) {
                $filter = & $inputFilter[$index];
                $function =& $filter[0];
                $params =& $filter[1];
                Wizin_Util::callUserFuncArrayReference( $function, $params );
                unset( $filter );
                unset( $function );
                unset( $params );
            }
            $this->_aInputFilter = array();
        }

        function executeOutputFilter( & $contents )
        {
            $outputFilter = $this->_aOutputFilter;
            for ( $index = 0; $index < count($outputFilter); $index ++ ) {
                $filter = & $outputFilter[$index];
                $function =& $filter[0];
                $params = array();
                $params[] =& $contents;
                for ( $argIndex = 0; $argIndex < count($filter[1]); $argIndex ++ ) {
                    $params[] =& $filter[1][$argIndex];
                }
                Wizin_Util::callUserFuncArrayReference( $function, $params );
                unset( $filter );
                unset( $function );
                unset( $params );
            }
            $this->_aOutputFilter = array();
        }

        function filterInputEncoding( $inputEncoding = '' )
        {
            if ( extension_loaded('mbstring') ) {
                if ( empty($inputEncoding) ) {
                    $inputEncoding = mb_detect_encoding( serialize($_REQUEST), 'auto' );
                }
                $internalEncoding = mb_internal_encoding();
                mb_convert_variables( $internalEncoding, $inputEncoding, $_GET );
                mb_convert_variables( $internalEncoding, $inputEncoding, $_POST );
                mb_convert_variables( $internalEncoding, $inputEncoding, $_REQUEST );
                mb_convert_variables( $internalEncoding, $inputEncoding, $_COOKIE );
            }
        }

        function filterOutputEncoding( & $contents, $outputEncoding, $outputCharset )
        {
            if ( extension_loaded('mbstring') ) {
                if ( ! empty($outputEncoding) && ! empty($outputEncoding) ) {
                    $contents = str_replace( 'charset=' ._CHARSET, 'charset=' . $outputCharset, $contents );
                    $pattern = '(=)([\"\'])(' . _CHARSET . ')([\"\'])';
                    $replacement = '${1}${2}' . $outputCharset . '${4}';
                    $contents = preg_replace( "/" .$pattern ."/", $replacement, $contents );
                    $internalEncoding = mb_internal_encoding();
                    if ( in_array(strtolower($internalEncoding), array('sjis', 'shift_jis', 'ms_kanji',
                            'csshift_jis')) ) {
                        $internalEncoding = 'sjis-win';
                    } else if ( in_array(strtolower($internalEncoding), array('euc-jp',
                            'extended_unix_code_packed_format_for_japanese', 'cseucpkdfmtjapanese')) ) {
                        $internalEncoding = 'eucjp-win';
                    }
                    mb_convert_variables( $outputEncoding, $internalEncoding, $contents );
                    ini_set( 'default_charset', $outputCharset );
                }
            }
            return $contents;
        }

        function filterOptimizeMobile( & $contents, $baseUri, $currentUri, $basePath, $createDir = WIZIN_CACHE_DIR )
        {
            $maxWidth = 220;
            Wizin_Filter::filterResizeImage( $contents, $baseUri, $currentUri, $basePath, $createDir, $maxWidth );
            // replace input type "password" => "text"
            $pattern = '(<input)([^>]*)(type=)([\"\'])(password)([\"\'])([^>]*)(>)';
            $replacement = '${1}${2}${3}${4}text${6} ${7}${8}';
            $contents = preg_replace( "/" .$pattern ."/i", $replacement, $contents );
            Wizin_Filter::filterDeleteTags( $contents );
            Wizin_Filter::filterInsertAnchor( $contents, $baseUri, $currentUri );
            // convert from zenkaku to hankaku
            if ( extension_loaded('mbstring') ) {
                $contents = mb_convert_kana( $contents, 'knr' );
            }
            return $contents;
        }

        function filterDeleteTags( & $contents )
        {
            static $callFlag;
            if ( ! isset($callFlag) ) {
                $callFlag = true;
                // delete script tags
                $pattern = '@<script[^>]*?>.*?<\/script>@si';
                $replacement = '';
                $contents = preg_replace( $pattern, $replacement, $contents );
                // delete del tags
                $pattern = '@<del[^>]*?>.*?<\/del>@si';
                $replacement = '';
                $contents = preg_replace( $pattern, $replacement, $contents );
                // delete comment
                $pattern = '<!--[\s\S]*?-->';
                $replacement = '';
                $contents = preg_replace( "/" .$pattern ."/", $replacement, $contents );
                // delete "nobr" tag
                $pattern = '<\/?nobr>';
                $replacement = '';
                $contents = preg_replace( "/" .$pattern ."/", $replacement, $contents );
            }
        }

        function filterInsertAnchor( & $contents, $baseUri, $currentUri )
        {
            static $callFlag;
            if ( ! isset($callFlag) ) {
                $callFlag = true;
                $pattern = '(<a)([^>]*)(href=)([\"\'])(\S*)([\"\'])([^>]*)(>)';
                preg_match_all( "/" .$pattern ."/i", $contents, $matches, PREG_SET_ORDER );
                if ( ! empty($matches) ) {
                    foreach ( $matches as $key => $match) {
                        $href = '';
                        $hrefArray = array();
                        $url = $match[5];
                        if ( substr($url, 0, 4) !== 'http' ) {
                            if ( strpos($url, ':') !== false ) {
                                continue;
                            } else if ( substr($url, 0, 1) === '#' ) {
                                continue;
                                /*
                                $urlArray = explode( '#', $currentUri );
                                $url = $urlArray[0] . $url;
                                */
                            } else if ( substr($url, 0, 1) === '/' ) {
                                $parseUrl = parse_url( $baseUri );
                                if ( ! empty($parseUrl['path']) ) {
                                    $url = str_replace( $parseUrl['path'], '', $baseUri ) . $url;
                                } else {
                                    $url = $baseUri . $url;
                                }
                            } else {
                                $url = dirname( $currentUri ) . '/' . $url;
                            }
                        }
                        if ( strstr($url, '#') === false ) {
                            continue;
                        }
                        $urlArray = explode( '#', $url );
                        $parseUrl = parse_url( $url );
                        if ( ! empty($parseUrl['query']) ) {
                            $url = $urlArray[0] . '&amp;wiz_anchor=' . $parseUrl['fragment'] . '#' . $urlArray[1];
                        } else {
                            $url = $urlArray[0] . '?wiz_anchor=' . $parseUrl['fragment'] . '#' . $urlArray[1];
                        }
                        $contents = str_replace( $match[3] . $match[4] .$match[5] . $match[6],
                            $match[3] . $match[4] . $url . $match[6], $contents );
                    }
                }
            }
        }

        function filterTransSid( & $contents, $baseUri, $currentUri )
        {
            // get method
            $pattern = '(<a)([^>]*)(href=)([\"\'])(\S*)([\"\'])([^>]*)(>)';
            preg_match_all( "/" .$pattern ."/i", $contents, $matches, PREG_SET_ORDER );
            if ( ! empty($matches) ) {
                foreach ( $matches as $key => $match) {
                    $href = '';
                    $hrefArray = array();
                    $url = $match[5];
                    if ( substr($url, 0, 4) !== 'http' ) {
                        if ( strpos($url, ':') !== false ) {
                            continue;
                        } else if ( substr($url, 0, 1) === '#' ) {
                            continue;
                            /*
                            $urlArray = explode( '#', $currentUri );
                            $url = $urlArray[0] . $url;
                            */
                        } else if ( substr($url, 0, 1) === '/' ) {
                            $parseUrl = parse_url( $baseUri );
                            $url = str_replace( $parseUrl['path'], '', $baseUri ) . $url;
                        } else {
                            $url = dirname( $currentUri ) . '/' . $url;
                        }
                    }
                    $check = strstr( $url, $baseUri );
                    if ( $check !== false ) {
                        if ( ! strpos($url, session_name()) ) {
                            if ( ! strstr($url, '?') ) {
                                $connector = '?';
                            } else {
                                $connector = '&';
                            }
                            if ( strstr($url, '#') ) {
                                $hrefArray = explode( '#', $url );
                                $href .= $hrefArray[0] . $connector . SID;
                                if ( ! empty($hrefArray[1]) ) {
                                    $href .= '#' . $hrefArray[1];
                                }
                            } else {
                                $href = $url . $connector . SID;
                            }
                            $contents = str_replace( $match[3] . $match[4] .$match[5] . $match[6],
                                $match[3] . $match[4] . $href . $match[6], $contents );
                        }
                    }
                }
            }
            //
            // form
            //
            // pattern 1 ( "method=, action=" pattern )
            $pattern = '(<form)([^>]*)(method=)([\"\'])(post|get)([\"\'])([^>]*)(action=)([\"\'])(\S*)([\"\'])([^>]*)(>)';
            preg_match_all( "/" .$pattern ."/i", $contents, $matches, PREG_SET_ORDER );
            if ( ! empty($matches) ) {
                foreach ( $matches as $key => $match) {
                    if ( ! empty($match[10]) ) {
                        $form = $match[0];
                        $action = $match[10];
                        if ( substr($action, 0, 4) !== 'http' ) {
                            if ( strpos($action, ':') !== false ) {
                                continue;
                            } else if ( substr($action, 0, 1) === '#' ) {
                                $urlArray = explode( '#', $currentUri );
                                $action = $urlArray[0] . $action;
                            } else if ( substr($action, 0, 1) === '/' ) {
                                $parseUrl = parse_url( $baseUri );
                                $action = str_replace( $parseUrl['path'], '', $baseUri ) . $action;
                            } else {
                                $action = dirname( $currentUri ) . '/' . $action;
                            }
                        }
                    } else {
                        $url = dirname( $currentUri );
                        $url .= basename( getenv('SCRIPT_NAME') );
                        $queryString = getenv( 'QUERY_STRING' );
                        if ( isset($queryString) && $queryString !== '' ) {
                            $queryString = str_replace( '&' . SID, '', $queryString );
                            $queryString = str_replace( SID, '', $queryString );
                            if ( $queryString !== '' ) {
                                $url .= '?' . $queryString;
                            }
                        }
                        $form = str_replace( $match[8] . $match[9] . $match[10] . $match[11],
                            $match[8] . $match[9] . $url . $match[11], $match[0] );
                        $action = $url;
                    }
                    $check = strstr( $action, $baseUri );
                    if ( $check !== false ) {
                        $tag = '<input type="hidden" name="' . session_name() . '" value="' . session_id() . '" />';
                        $contents = str_replace( $match[0], $form . $tag, $contents );
                    }
                    $action = '';
                }
            }
            // pattern 2 ( "action=, method=" pattern )
            $pattern = '(<form)([^>]*)(action=)([\"\'])(\S*)([\"\'])([^>]*)(method=)([\"\'])(post|get)([\"\'])([^>]*)(>)';
            preg_match_all( "/" .$pattern ."/i", $contents, $matches, PREG_SET_ORDER );
            if ( ! empty($matches) ) {
                foreach ( $matches as $key => $match) {
                    if ( ! empty($match[5]) ) {
                        $form = $match[0];
                        $action = $match[5];
                        if ( substr($action, 0, 4) !== 'http' ) {
                            if ( strpos($action, ':') !== false ) {
                                continue;
                            } else if ( substr($action, 0, 1) === '#' ) {
                                $urlArray = explode( '#', $currentUri );
                                $action = $urlArray[0] . $action;
                            } else if ( substr($action, 0, 1) === '/' ) {
                                $parseUrl = parse_url( $baseUri );
                                $action = str_replace( $parseUrl['path'], '', $baseUri ) . $action;
                            } else {
                                $action = dirname( $currentUri ) . '/' . $action;
                            }
                        }
                    } else {
                        $url = dirname( $currentUri );
                        $url .= basename( getenv('SCRIPT_NAME') );
                        $queryString = getenv( 'QUERY_STRING' );
                        if ( isset($queryString) && $queryString !== '' ) {
                            $queryString = str_replace( '&' . SID, '', $queryString );
                            $queryString = str_replace( SID, '', $queryString );
                            if ( $queryString !== '' ) {
                                $url .= '?' . $queryString;
                            }
                        }
                        $form = str_replace( $match[3] . $match[4] . $match[5] . $match[6],
                            $match[3] . $match[4] . $url . $match[6], $match[0] );
                        $action = $url;
                    }
                    $check = strstr( $action, $baseUri );
                    if ( $check !== false ) {
                        $tag = '<input type="hidden" name="' . session_name() . '" value="' . session_id() . '" />';
                        $contents = str_replace( $match[0], $form . $tag, $contents );
                    }
                    $action = '';
                }
            }
            // delete needless strings
            $contents = str_replace( '?&', '?', $contents );
            $contents = str_replace( '&&', '&', $contents );
            return $contents;
        }

        function filterResizeImage ( & $contents, $baseUri, $currentUri, $basePath, $createDir = null, $maxWidth = 0 )
        {
            if ( is_null($createDir) ) {
                if ( defined('WIZIN_CACHE_DIR') ) {
                    $createDir = WIZIN_CACHE_DIR;
                } else {
                    $createDir = dirname( dirname(dirname(__FILE__)) ) . '/work/cache';
                }
            }
            // image resize
            if ( extension_loaded('gd') ) {
                clearstatcache();
                $allowImageFormat = array( IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG );
                $pattern = '(<img)([^>]*)(src=)([\"\'])(\S*)([\"\'])([^>]*)(>)';
                preg_match_all( "/" .$pattern ."/i", $contents, $matches, PREG_SET_ORDER );
                if ( ! empty($matches) ) {
                    foreach ( $matches as $key => $match) {
                        $maxImageWidth = $maxWidth;
                        $getFileFlag = false;
                        $imageUrl = $match[5];
                        if ( substr($imageUrl, 0, 4) !== 'http' ) {
                            if ( substr($imageUrl, 0, 1) === '/' ) {
                                $parseUrl = parse_url( $baseUri );
                                $imageUrl = str_replace( $parseUrl['path'], '', $baseUri ) . $imageUrl;
                            } else {
                                $imageUrl = dirname( $currentUri ) . '/' . $imageUrl;
                            }
                        }
                        if ( strpos($imageUrl, $baseUri) === 0 ) {
                            $imagePath = str_replace( $baseUri, $basePath, $imageUrl );
                            if ( ! file_exists($imagePath) ) {
                                $imagePath = Wizin_Util_Web::getFileByHttp( $imageUrl );
                                if ( $imagePath === '' ) {
                                    continue;
                                }
                                $getFileFlag = true;
                            }
                            $ext = array_pop( explode('.', basename($imagePath)) );
                            $newImagePath = $createDir . '/' . basename( $imagePath, $ext );
                            if ( function_exists('imagegif') ) {
                                $newExt = 'gif';
                            } else {
                                $newExt = 'jpg';
                            }
                            $newImagePath .= $newExt;
                            $newImageUrl = str_replace( $basePath, $baseUri, $newImagePath );
                            $imageSizeInfo = getimagesize( $imagePath );
                            $width = $imageSizeInfo[0];
                            $height = $imageSizeInfo[1];
                            $format = $imageSizeInfo[2];
                            if ( $width == 0 || $height == 0 ) {
                                // Maybe the file is the script which send image, get file by http.
                                $imagePath = Wizin_Util_Web::getFileByHttp( $imageUrl );
                                if ( $imagePath === '' ) {
                                    continue;
                                }
                                $getFileFlag = true;
                                $imageSizeInfo = getimagesize( $imagePath );
                                $width = $imageSizeInfo[0];
                                $height = $imageSizeInfo[1];
                                $format = $imageSizeInfo[2];
                            }
                            if ( $getFileFlag && $width <= $maxImageWidth ) {
                                $maxImageWidth = $width - 1;
                            }
                            if ( $width !== 0 && $height !== 0 ) {
                                if ( $width > $maxImageWidth && in_array($format, $allowImageFormat) ) {
                                    if ( ! file_exists($newImagePath) ||
                                            (filemtime($newImagePath) <= filemtime($imagePath)) ) {
                                        Wizin_Util_Web::createThumbnail( $imagePath, $width, $height,
                                            $format, $newImagePath, $maxImageWidth );
                                    }
                                    $imageTag = str_replace( $match[3] . $match[4] .$match[5] . $match[6],
                                        $match[3] . $match[4] . $newImageUrl . $match[6], $match[0] );
                                    $replaceArray = array( "'" => "\'", '"' => '\"', '\\' => '\\\\',
                                        '/' => '\/', '(' => '\(', ')' => '\)', '.' => '\.', '?' => '\?' );
                                    $linkCheckPattern = '(<a)([^>]*)(href=)([^>]*)(>)((?:(?!<\/a>).)*)('
                                        . strtr($match[0], $replaceArray) . ')';
                                    if ( preg_match("/" .$linkCheckPattern ."/is", $contents) ) {
                                        $contents = str_replace( $match[0], $imageTag, $contents );
                                    } else {
                                        $imageLink = '<a href="' . $imageUrl . '">' . $imageTag . '</a>';
                                        $contents = str_replace( $match[0], $imageLink, $contents );
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

    }
}

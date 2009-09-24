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

/**
 * module info
 */
Wizin_Util::define('WIZMOBILE_MODINFO_NAME', 'WizMobile');
Wizin_Util::define('WIZMOBILE_MODINFO_DESC', 'Web site which with XOOPS Cube Legacy was made can be utilized by the portable telephone.');

/**
 * message
 */

// main area / all
Wizin_Util::define('WIZMOBILE_MSG_DENY_LOGIN_PAGE', 'Sorry, it cannot login from the portable telephone,<br /> we request the operation with PC');
Wizin_Util::define('WIZMOBILE_MSG_DENY_ADMIN_AREA', 'Sorry, it cannot operate Admin Area from the portable telephone,<br /> we request the operation with PC');
Wizin_Util::define('WIZMOBILE_MSG_SESSION_LIMIT_TIME', 'It passed the existence time of session for portable telephone.<br />There is no excuse, but login please do again to do once more.');
Wizin_Util::define('WIZMOBILE_MSG_DENY_ACCESS_MODULE_PAGE', 'Sorry, it cannot access this module from the portable telephone,<br /> we request the operation with PC');

// main area / simple login
Wizin_Util::define('WIZMOBILE_MSG_SIMPLE_LOGIN_CAUTION', 'When you utilize simple login, it is necessary to do the "Register of terminal specific ID" after the login.');

// admin area / system status
Wizin_Util::define('WIZMOBILE_MSG_CONTROLLER_IS_NOT_EXCHANGED', 'Controller class is not exchanged.');
Wizin_Util::define('WIZMOBILE_MSG_CONTROLLER_PATCH', 'Please write this code in ' . XOOPS_ROOT_PATH . '/settings/site_custom.ini.php .');
Wizin_Util::define('WIZMOBILE_MSG_GD_NOT_EXISTS', 'Because the GD library does not exist, resize function of the image has become invalid.');
Wizin_Util::define('WIZMOBILE_MSG_RESIZED_IMAGE_DIR_NOT_EXISTS', 'Because ' . XOOPS_ROOT_PATH . '/uploads/wizmobile directory does not exist, resize function of the image has become invalid.');
Wizin_Util::define('WIZMOBILE_MSG_RESIZED_IMAGE_DIR_NOT_WRITABLE', 'Because ' . XOOPS_ROOT_PATH . '/uploads/wizmobile directory is not writable, resize function of the image has become invalid.');
Wizin_Util::define('WIZMOBILE_MSG_DOM_NOT_EXISTS', 'Because DOMDocument class does not exist, page divided function has become invalid.');
Wizin_Util::define('WIZMOBILE_MSG_SIMPLEXML_NOT_EXISTS', 'Because SimpleXMLElement class does not exist, page divided function has become invalid.');
Wizin_Util::define('WIZMOBILE_MSG_JSON_EXT_NOT_EXISTS', 'Emoticon filter has become invalid, because JSON extension is disabled.');
Wizin_Util::define('WIZMOBILE_MSG_PLZ_JSPHON_INSTALL', 'Get Jsphon-1.0.1.tgz from coderepos.org, please uncompress under xoops_trust_path/wizin/lib/PEAR');
Wizin_Util::define('WIZMOBILE_MSG_PICTOGRAM_LIB_NOT_EXISTS', 'Emoticon filter has become invalid, because PEAR package "Text_Pictogram_Mobile" does not exist.');
Wizin_Util::define('WIZMOBILE_MSG_PLZ_PICTOGRAM_LIB_INSTALL', 'Please install by "pear install" or upload into xoops_trust_path/wizin/lib/PEAR.');
Wizin_Util::define('WIZMOBILE_MSG_CSS_XPATH_NOT_EXISTS', 'Css filter has become invalid, because PEAR package "HTML_CSS_Selector2XPath" does not exist.');
Wizin_Util::define('WIZMOBILE_MSG_CSS_COMMON_NOT_EXISTS', 'Css filter has become invalid, because PEAR package "HTML_Common" does not exist.');
Wizin_Util::define('WIZMOBILE_MSG_CSS_NOT_EXISTS', 'Css filter has become invalid, because PEAR package "HTML_CSS" does not exist.');
Wizin_Util::define('WIZMOBILE_MSG_CSS_MOBILE_NOT_EXISTS', 'Css filter has become invalid, because PEAR package "HTML_CSS_Mobile" does not exist.');

// main area / register uniq id
Wizin_Util::define('WIZMOBILE_MSG_REGISTER_UNIQID_SUCCESS', '%s of terminal specific ID completed.');
Wizin_Util::define('WIZMOBILE_MSG_REGISTER_UNIQID_FAILED', 'It failed in %s of terminal specific ID.');
Wizin_Util::define('WIZMOBILE_MSG_REGISTER_UNIQID', 'The terminal specific ID which is utilized with simple login is registered. (In case of the register being completed terminal specific ID is updated).<br />When terminal specific ID is registered, it reaches the point which just clicks the simple login button, can do login.');
Wizin_Util::define('WIZMOBILE_MSG_CANNOT_GET_UNIQID', 'It cannot get The terminal specific ID.<br />Please check whether it does not prohibit the terminal specific ID.');

// admin area / block setting
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_BLOCK_SETTING_SUCCESS', 'Update of block control setting completed.');
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_BLOCK_SETTING_FAILED', 'It failed in update of block control setting.');

// admin area / module setting
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_MODULE_SETTING_SUCCESS', 'Update of module control setting completed.');
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_MODULE_SETTING_FAILED', 'It failed in update module control setting.');

// admin area / theme setting
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_THEME_SETTING_SUCCESS', 'Update of theme control setting completed.');
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_THEME_SETTING_FAILED', 'It failed in update theme control setting.');

// admin area / theme setting
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_GOOGLE_SETTING_SUCCESS', 'Update of the service of google control setting completed.');
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_GOOGLE_SETTING_FAILED', 'It failed in update the service of google control setting.');

// admin area / general setting
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_GENERAL_SETTING_SUCCESS', 'Update of generality setting completed.');
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_GENERAL_SETTING_FAILED', 'It failed in update of generality setting.');


/**
 * error message
 */
Wizin_Util::define('WIZMOBILE_ERR_PHP_VERSION', 'Sorry, this module cannot install, because it needs PHP4.4.X or upper version.');
Wizin_Util::define('WIZMOBILE_ERR_TICKET_NOT_FOUND', 'The one-time ticket is not found.<br />Sorry, but we ask operation once more, please.');

/**
 * language for main area
 */
// main area / login
Wizin_Util::define('WIZMOBILE_LANG_SIMPLE_LOGIN', 'Simple Login');
Wizin_Util::define('WIZMOBILE_LANG_REGISTER_UNIQID', 'Register terminal specific ID');

// main area / extconfirm
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_CONFIRM', 'It will be go to other web site.');
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_INVALID', 'It failed in get url of external link.');
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_URL_DISPLAY', 'URL');
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_URL_COPY', 'Copy URL');
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_GO_EXTERNAL', 'Go to other web site.');
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_BACK_INTERNAL', 'Back to before page.');

/**
 * language for admin area
 */
Wizin_Util::define('WIZMOBILE_LANG_SYSTEM_STATUS', 'System status');
Wizin_Util::define('WIZMOBILE_LANG_BLOCK_CONTROL', 'Block control');
Wizin_Util::define('WIZMOBILE_LANG_MODULE_CONTROL', 'Module control');
Wizin_Util::define('WIZMOBILE_LANG_THEME_CONTROL', 'Theme control');
Wizin_Util::define('WIZMOBILE_LANG_GOOGLE_CONTROL', 'The service of google control');
Wizin_Util::define('WIZMOBILE_LANG_GENERAL_SETTING', 'Generality setting');

// system status
Wizin_Util::define('WIZMOBILE_LANG_EXCHANGE_CONTROLLER', 'Exchange Controller');
Wizin_Util::define('WIZMOBILE_LANG_IMAGE_RESIZE', 'Resize of image');
Wizin_Util::define('WIZMOBILE_LANG_PARTITION_PAGE', 'Page division');
Wizin_Util::define('WIZMOBILE_LANG_PICTOGRAM_FILTER', 'Pictogram filter for portable telephone');
Wizin_Util::define('WIZMOBILE_LANG_CSS_FILTER', 'Css filter for portable telephone');

// block setting
Wizin_Util::define('WIZMOBILE_LANG_BLOCK_SETTING', 'Block setting');
Wizin_Util::define('WIZMOBILE_LANG_BLOCK_TITLE', 'Block title');
Wizin_Util::define('WIZMOBILE_LANG_MODULE_NAME', 'Module name');
Wizin_Util::define('WIZMOBILE_LANG_DIRNAME', 'Directory');
Wizin_Util::define('WIZMOBILE_LANG_VISIBLE', 'Visible');
Wizin_Util::define('WIZMOBILE_LANG_INVISIBLE', 'Invisible');
Wizin_Util::define('WIZMOBILE_LANG_WEIGHT', 'Weight');

// default display block setting
Wizin_Util::define('WIZMOBILE_LANG_DEFAULT_BLOCK', 'Default display block setting');
Wizin_Util::define('WIZMOBILE_LANG_DEFAULT_BLOCK_DESC', 'Please choose a block to display,<br />when there are page contents and neither block choosing together');

// deny access module setting
Wizin_Util::define('WIZMOBILE_LANG_DENY_ACCESS_MODULE_SETTING', 'Deny access module setting');
Wizin_Util::define('WIZMOBILE_LANG_DENY_ACCESS', 'Deny access');

// default display theme setting
Wizin_Util::define('WIZMOBILE_LANG_DEFAULT_THEME', 'Default theme for portable telephone.');
Wizin_Util::define('WIZMOBILE_LANG_DEFAULT_THEME_DESC', 'Please select the theme which is indicated in when there is no individual setting.<br />* You choosing with theme of generality setting, it does not care,');
Wizin_Util::define('WIZMOBILE_LANG_DISPLAY_TARGET', 'Display target');
Wizin_Util::define('WIZMOBILE_LANG_DISPLAY_TARGET_DESC', 'Please select the theme which is indicated in every group, in the respective page.');

// google adsense and analytics setting
Wizin_Util::define('WIZMOBILE_LANG_ADSENSE_CODE', 'Google AdSense code');
Wizin_Util::define('WIZMOBILE_LANG_ADSENSE_CODE_DESC', 'Please stick the AdSense code for the Mobile contents.');
Wizin_Util::define('WIZMOBILE_LANG_ADSENSE_HOW_TO', 'Please write &lt;{wizmobile_google_ads}&gt; on theme or template.');
Wizin_Util::define('WIZMOBILE_LANG_ADSENSE_NOTICE', '- With the access which cannot acquire terminal specific ID, adsense can\'t display.');

// general setting
Wizin_Util::define('WIZMOBILE_LANG_ITEM', 'Item');
Wizin_Util::define('WIZMOBILE_LANG_VALUE', 'Value');
Wizin_Util::define('WIZMOBILE_LANG_LOGIN', 'Login');
Wizin_Util::define('WIZMOBILE_LANG_LOGIN_DESC', 'Login functional setting for mobile.<br />When it makes enable, also simple login becomes available.');
Wizin_Util::define('WIZMOBILE_LANG_THEME', 'Theme');
Wizin_Util::define('WIZMOBILE_LANG_THEME_DESC', 'Theme setting for mobile.');
Wizin_Util::define('WIZMOBILE_LANG_TPLSET', 'Template set');
Wizin_Util::define('WIZMOBILE_LANG_TPLSET_DESC', 'Template set setting for mobile.');
Wizin_Util::define('WIZMOBILE_LANG_LOOKUP', 'Lookup host name');
Wizin_Util::define('WIZMOBILE_LANG_LOOKUP_DESC', 'Lookup host name, you verify whether access from mobile.<br />Instead of being able prevent the disguise of the user agent, performance decreases.');
Wizin_Util::define('WIZMOBILE_LANG_OTHERMOBILE', 'Correspondence of other mobile terminals');
Wizin_Util::define('WIZMOBILE_LANG_OTHERMOBILE_DESC', 'When it corresponds portably vis-a-vis the terminal of part such as smart phone, please select enable');
Wizin_Util::define('WIZMOBILE_LANG_PAGER', 'Page division');
Wizin_Util::define('WIZMOBILE_LANG_PAGER_DESC', 'If you would like to enable pagenate, please select enable');
Wizin_Util::define('WIZMOBILE_LANG_CONTENT_TYPE', 'Content-type');
Wizin_Util::define('WIZMOBILE_LANG_CONTENT_TYPE_DESC', 'Whether access from mobile, please select the type which forwards the contents');
Wizin_Util::define('WIZMOBILE_LANG_EMOJI_SUPPORT', 'Emoticon input support');
Wizin_Util::define('WIZMOBILE_LANG_EMOJI_SUPPORT_DESC', 'If you would like to enable emoticon input support, please check "Enable".');
Wizin_Util::define('WIZMOBILE_LANG_TRUST_IP', 'Trust IP');
Wizin_Util::define('WIZMOBILE_LANG_TRUST_IP_DESC', 'The IP address which does not check session strictly<br />Dividing with |, please describe. Also regular expression is available.');
Wizin_Util::define('WIZMOBILE_MSG_TRUST_IP_NOTICE', 'If with access from other than the IP address which is set here, it is not cell phone, session is cancelled mandatorily');
Wizin_Util::define('WIZMOBILE_LANG_SESSION_LIFETIME', 'Session lifetime');
Wizin_Util::define('WIZMOBILE_LANG_SESSION_LIFETIME_DESC', 'Please appoint the time when session is maintained when it does not have access.<br />Initial value is 15 minutes');
Wizin_Util::define('WIZMOBILE_MSG_SESSION_LIFETIME_NOTICE', 'Because danger of the extent session hijack which enlarges value rises note.');


/**
 * language for all area
 */
Wizin_Util::define('WIZMOBILE_LANG_SETTING', 'Setting');
Wizin_Util::define('WIZMOBILE_LANG_REGISTER', 'Register');
Wizin_Util::define('WIZMOBILE_LANG_UPDATE', 'Update');
Wizin_Util::define('WIZMOBILE_LANG_DELETE', 'Delete');
Wizin_Util::define('WIZMOBILE_LANG_ENABLE', 'Enable');
Wizin_Util::define('WIZMOBILE_LANG_DISABLE', 'Disable');
Wizin_Util::define('WIZMOBILE_LANG_NONE_SETTING', 'None');
Wizin_Util::define('WIZMOBILE_LANG_EMOTICON', 'Emoticon');
Wizin_Util::define('WIZMOBILE_LANG_MINUTE', 'Minutes');

/**
 * language for theme
 */
Wizin_Util::define('WIZMOBILE_LANG_LOGIN', 'Login');
Wizin_Util::define('WIZMOBILE_LANG_LOGOUT', 'Logout');
Wizin_Util::define('WIZMOBILE_LANG_PAGE_TOP', 'Page Top');
Wizin_Util::define('WIZMOBILE_LANG_PAGE_BOTTOM', 'Page Bottom');
Wizin_Util::define('WIZMOBILE_LANG_MAIN_CONTENTS', 'Main Contents');
Wizin_Util::define('WIZMOBILE_LANG_SEARCH', 'Search');

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
Wizin_Util::define('WIZMOBILE_MODINFO_NAME', '携帯対応モジュール');
Wizin_Util::define('WIZMOBILE_MODINFO_DESC', 'XOOPS Cube Legacyで構築したサイトを携帯でも利用可能にするモジュール');

/**
 * message
 */

// main area / all
Wizin_Util::define('WIZMOBILE_MSG_DENY_LOGIN_PAGE', '申し訳ありませんが、このサイトは携帯からはログイン出来ません<br />PCでの操作をお願い致します');
Wizin_Util::define('WIZMOBILE_MSG_DENY_ADMIN_AREA', '申し訳ありませんが、携帯から管理画面は操作出来ません<br />PCでの操作をお願い致します');
Wizin_Util::define('WIZMOBILE_MSG_SESSION_LIMIT_TIME', '携帯端末で有効なセッションの継続時間を過ぎました<br />申し訳ありませんが、もう一度ログインし直して下さい');
Wizin_Util::define('WIZMOBILE_MSG_DENY_ACCESS_MODULE_PAGE', '申し訳ありませんが、このモジュールは携帯からはアクセス出来ません<br />PCでの操作をお願い致します');

// main area / simple login
Wizin_Util::define('WIZMOBILE_MSG_SIMPLE_LOGIN_CAUTION', '簡単ログインをご利用になる場合、ログイン後に「機種ID登録」を行っている必要があります。');

// admin area / system status
Wizin_Util::define('WIZMOBILE_MSG_CONTROLLER_IS_NOT_EXCHANGED', 'コントローラが換装されていません');
Wizin_Util::define('WIZMOBILE_MSG_CONTROLLER_PATCH', '以下のコードを ' . XOOPS_ROOT_PATH . '/settings/site_custom.ini.php に書き込んで下さい');
Wizin_Util::define('WIZMOBILE_MSG_GD_NOT_EXISTS', 'GDライブラリが存在しないため、画像のリサイズ機能は無効となっています');
Wizin_Util::define('WIZMOBILE_MSG_RESIZED_IMAGE_DIR_NOT_EXISTS', XOOPS_ROOT_PATH . '/uploads/wizmobile が存在しないため、画像のリサイズ機能は無効となっています');
Wizin_Util::define('WIZMOBILE_MSG_RESIZED_IMAGE_DIR_NOT_WRITABLE', XOOPS_ROOT_PATH . '/uploads/wizmobile に書き込み権限がないため、画像のリサイズ機能は無効となっています');
Wizin_Util::define('WIZMOBILE_MSG_DOM_NOT_EXISTS', 'DOM拡張が存在しないため、ページ分割機能は無効となっています');
Wizin_Util::define('WIZMOBILE_MSG_SIMPLEXML_NOT_EXISTS', 'SimpleXMLが存在しないため、ページ分割機能は無効となっています');
Wizin_Util::define('WIZMOBILE_MSG_JSON_EXT_NOT_EXISTS', 'JSON拡張モジュール（PHP5.2.0以降で利用可能）が見つからないため、絵文字フィルター機能は無効となっています');
Wizin_Util::define('WIZMOBILE_MSG_PLZ_JSPHON_INSTALL', 'coderepos.orgからJsphon-1.0.1.tgzを取得して、xoops_trust_path/wizin/lib/PEARの下に展開して下さい');
Wizin_Util::define('WIZMOBILE_MSG_PICTOGRAM_LIB_NOT_EXISTS', 'PEARパッケージ"Text_Pictogram_Mobile"が見つからないため、絵文字フィルター機能は無効となっています');
Wizin_Util::define('WIZMOBILE_MSG_PLZ_PICTOGRAM_LIB_INSTALL', 'pearコマンドでインストールするか、xoops_trust_path/wizin/lib/PEARの下に展開して下さい');
Wizin_Util::define('WIZMOBILE_MSG_CSS_XPATH_NOT_EXISTS', 'PEARパッケージ"HTML_CSS_Selector2XPath"が見つからないため、CSSフィルター機能は無効となっています');
Wizin_Util::define('WIZMOBILE_MSG_CSS_COMMON_NOT_EXISTS', 'PEARパッケージ"HTML_Common"が見つからないため、CSSフィルター機能は無効となっています');
Wizin_Util::define('WIZMOBILE_MSG_CSS_NOT_EXISTS', 'PEARパッケージ"HTML_CSS"が見つからないため、CSSフィルター機能は無効となっています');
Wizin_Util::define('WIZMOBILE_MSG_CSS_MOBILE_NOT_EXISTS', 'PEARパッケージ"HTML_CSS_Mobile"が見つからないため、CSSフィルター機能は無効となっています');

// main area / register uniq id
Wizin_Util::define('WIZMOBILE_MSG_REGISTER_UNIQID_SUCCESS', '機種IDの%sが完了しました');
Wizin_Util::define('WIZMOBILE_MSG_REGISTER_UNIQID_FAILED', '機種IDの%sに失敗しました');
Wizin_Util::define('WIZMOBILE_MSG_REGISTER_UNIQID', '簡単ログインで利用する携帯の機種IDを登録します。（登録済みの場合は機種IDを更新します）<br />機種IDを登録すると、簡単ログインボタンをクリックするだけで、ログインが出来るようになります。');
Wizin_Util::define('WIZMOBILE_MSG_CANNOT_GET_UNIQID', '機種IDが取得出来ません<br />機種IDの送信を禁止していないか、確認して下さい');

// admin area / block setting
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_BLOCK_SETTING_SUCCESS', 'ブロック制御設定の更新が完了しました');
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_BLOCK_SETTING_FAILED', 'ブロック制御設定の更新に失敗しました');

// admin area / module setting
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_MODULE_SETTING_SUCCESS', 'モジュール制御設定の更新が完了しました');
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_MODULE_SETTING_FAILED', 'モジュール制御設定の更新に失敗しました');

// admin area / theme setting
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_THEME_SETTING_SUCCESS', 'テーマ制御設定の更新が完了しました');
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_THEME_SETTING_FAILED', 'テーマ制御設定の更新に失敗しました');

// admin area / theme setting
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_GOOGLE_SETTING_SUCCESS', 'グーグル関連制御設定の更新が完了しました');
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_GOOGLE_SETTING_FAILED', 'グーグル関連制御設定の更新に失敗しました');

// admin area / general setting
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_GENERAL_SETTING_SUCCESS', '一般設定の更新が完了しました');
Wizin_Util::define('WIZMOBILE_MSG_UPDATE_GENERAL_SETTING_FAILED', '一般設定の更新に失敗しました');


/**
 * error message
 */
Wizin_Util::define('WIZMOBILE_ERR_PHP_VERSION', 'このモジュールは、PHP4.4以上でなければインストール出来ません');
Wizin_Util::define('WIZMOBILE_ERR_TICKET_NOT_FOUND', 'ワンタイムチケットが見つかりません<br />お手数ですが、もう一度操作をお願いします');

/**
 * language for main area
 */
// main area / all
Wizin_Util::define('WIZMOBILE_LANG_DISPLAY_BLOCK_CONTENTS', 'ブロックの内容を表示する');

// main area / login
Wizin_Util::define('WIZMOBILE_LANG_SIMPLE_LOGIN', '簡単ログイン');
Wizin_Util::define('WIZMOBILE_LANG_REGISTER_UNIQID', '機種ID登録');

// main area / extconfirm
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_CONFIRM', '別のサイトに移動しようとしています');
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_INVALID', 'リンク先の取得に失敗しました');
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_URL_DISPLAY', 'リンク先URL');
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_URL_COPY', 'URLをコピー');
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_GO_EXTERNAL', 'リンク先に移動する');
Wizin_Util::define('WIZMOBILE_LANG_EXTLINK_BACK_INTERNAL', '元のページに戻る');

/**
 * language for admin area
 */
Wizin_Util::define('WIZMOBILE_LANG_SYSTEM_STATUS', 'システムの状況');
Wizin_Util::define('WIZMOBILE_LANG_BLOCK_CONTROL', 'ブロック制御');
Wizin_Util::define('WIZMOBILE_LANG_MODULE_CONTROL', 'モジュール制御');
Wizin_Util::define('WIZMOBILE_LANG_THEME_CONTROL', 'テーマ制御');
Wizin_Util::define('WIZMOBILE_LANG_GOOGLE_CONTROL', 'グーグル関連制御');
Wizin_Util::define('WIZMOBILE_LANG_GENERAL_SETTING', '一般設定');

// system status
Wizin_Util::define('WIZMOBILE_LANG_EXCHANGE_CONTROLLER', 'コントローラの換装');
Wizin_Util::define('WIZMOBILE_LANG_IMAGE_RESIZE', '画像のリサイズ');
Wizin_Util::define('WIZMOBILE_LANG_PARTITION_PAGE', 'ページ分割');
Wizin_Util::define('WIZMOBILE_LANG_PICTOGRAM_FILTER', '絵文字フィルター');
Wizin_Util::define('WIZMOBILE_LANG_CSS_FILTER', 'CSSフィルター');

// block setting
Wizin_Util::define('WIZMOBILE_LANG_BLOCK_SETTING', 'ブロックの設定');
Wizin_Util::define('WIZMOBILE_LANG_BLOCK_TITLE', 'ブロックタイトル');
Wizin_Util::define('WIZMOBILE_LANG_MODULE_NAME', 'モジュール名');
Wizin_Util::define('WIZMOBILE_LANG_DIRNAME', 'ディレクトリ');
Wizin_Util::define('WIZMOBILE_LANG_VISIBLE', '表示');
Wizin_Util::define('WIZMOBILE_LANG_VISIBLE_TITLE', 'タイトルのみ表示');
Wizin_Util::define('WIZMOBILE_LANG_VISIBLE_ALL', '全て表示');
Wizin_Util::define('WIZMOBILE_LANG_INVISIBLE', '非表示');
Wizin_Util::define('WIZMOBILE_LANG_WEIGHT', '並び順');

// default display block setting
Wizin_Util::define('WIZMOBILE_LANG_DEFAULT_BLOCK', '標準表示ブロックの設定');
Wizin_Util::define('WIZMOBILE_LANG_DEFAULT_BLOCK_DESC', 'ページコンテンツ、選択中のブロックの両方が存在しない場合に表示するブロックを選択して下さい');

// deny access module setting
Wizin_Util::define('WIZMOBILE_LANG_DENY_ACCESS_MODULE_SETTING', 'アクセス除外モジュールの設定');
Wizin_Util::define('WIZMOBILE_LANG_DENY_ACCESS', '除外');

// default display theme setting
Wizin_Util::define('WIZMOBILE_LANG_DEFAULT_THEME', '携帯用の標準テーマ');
Wizin_Util::define('WIZMOBILE_LANG_DEFAULT_THEME_DESC', '個別の設定がない場合に表示するテーマを選択して下さい<br />※一般設定の"テーマ"で選んで頂いても構いません');
Wizin_Util::define('WIZMOBILE_LANG_DISPLAY_TARGET', '表示対象');
Wizin_Util::define('WIZMOBILE_LANG_DISPLAY_TARGET_DESC', 'グループ毎に、それぞれのページで表示するテーマを選択して下さい');

// google adsense and analytics setting
Wizin_Util::define('WIZMOBILE_LANG_ADSENSE_CODE', 'Google AdSense コード');
Wizin_Util::define('WIZMOBILE_LANG_ADSENSE_CODE_DESC', 'モバイルコンテンツ向けのAdSenseコードを貼り付けて下さい');
Wizin_Util::define('WIZMOBILE_LANG_ADSENSE_HOW_TO', '携帯用のテーマやテンプレートに&lt;{wizmobile_google_ads}&gt;と記述して下さい');
Wizin_Util::define('WIZMOBILE_LANG_ADSENSE_NOTICE', '※機種IDが取得できないアクセスでは、広告は表示されません');

// general setting
Wizin_Util::define('WIZMOBILE_LANG_ITEM', '項目');
Wizin_Util::define('WIZMOBILE_LANG_VALUE', '設定値');
Wizin_Util::define('WIZMOBILE_LANG_LOGIN', 'ログイン');
Wizin_Util::define('WIZMOBILE_LANG_LOGIN_DESC', '携帯用のログイン機能設定<br />有効にすることで、簡単ログインも利用可能になります');
Wizin_Util::define('WIZMOBILE_LANG_THEME', 'テーマ');
Wizin_Util::define('WIZMOBILE_LANG_THEME_DESC', '携帯用のテーマ設定');
Wizin_Util::define('WIZMOBILE_LANG_TPLSET', 'テンプレートセット');
Wizin_Util::define('WIZMOBILE_LANG_TPLSET_DESC', '携帯用のテンプレートセット設定');
Wizin_Util::define('WIZMOBILE_LANG_LOOKUP', 'ホスト名の逆引き');
Wizin_Util::define('WIZMOBILE_LANG_LOOKUP_DESC', '携帯からのアクセスかどうか、ホスト名を逆引きして確認<br />ユーザエージェントの偽装を防ぐことが出来る代わり、パフォーマンスは低下します');
Wizin_Util::define('WIZMOBILE_LANG_OTHERMOBILE', 'その他端末の携帯対応');
Wizin_Util::define('WIZMOBILE_LANG_OTHERMOBILE_DESC', 'スマートフォンなど一部の端末に対して携帯対応を行う場合は、有効を選択して下さい');
Wizin_Util::define('WIZMOBILE_LANG_PAGER', 'ページ分割');
Wizin_Util::define('WIZMOBILE_LANG_PAGER_DESC', '携帯からのアクセスで、コンテンツ部分のページ分割を行う場合は、有効を選択して下さい');
Wizin_Util::define('WIZMOBILE_LANG_CONTENT_TYPE', 'コンテンツタイプ');
Wizin_Util::define('WIZMOBILE_LANG_CONTENT_TYPE_DESC', '携帯からのアクセスに対して、コンテンツを送出するタイプを選択して下さい');
Wizin_Util::define('WIZMOBILE_LANG_EMOJI_SUPPORT', '絵文字入力支援');
Wizin_Util::define('WIZMOBILE_LANG_EMOJI_SUPPORT_DESC', '絵文字の入力支援を利用する場合は、有効を選択して下さい');
Wizin_Util::define('WIZMOBILE_LANG_TRUST_IP', '信用できるIP');
Wizin_Util::define('WIZMOBILE_LANG_TRUST_IP_DESC', 'セッションの厳密なチェックを行わないIPアドレス<br />| で区切って記述して下さい。正規表現も利用可能です');
Wizin_Util::define('WIZMOBILE_MSG_TRUST_IP_NOTICE', 'ここで設定したIPアドレス以外からのアクセスでは、実機でない限りセッションは強制的に破棄されます');
Wizin_Util::define('WIZMOBILE_LANG_SESSION_LIFETIME', 'セッション生存時間');
Wizin_Util::define('WIZMOBILE_LANG_SESSION_LIFETIME_DESC', 'アクセスがない状態でセッションが維持される時間を分単位で指定して下さい<br />初期値は15分です');
Wizin_Util::define('WIZMOBILE_MSG_SESSION_LIFETIME_NOTICE', '値を大きくするほどセッションハイジャックの危険性が上がりますのでご注意下さい');


/**
 * language for all area
 */
Wizin_Util::define('WIZMOBILE_LANG_SETTING', '設定');
Wizin_Util::define('WIZMOBILE_LANG_REGISTER', '登録');
Wizin_Util::define('WIZMOBILE_LANG_UPDATE', '更新');
Wizin_Util::define('WIZMOBILE_LANG_DELETE', '削除');
Wizin_Util::define('WIZMOBILE_LANG_ENABLE', '有効');
Wizin_Util::define('WIZMOBILE_LANG_DISABLE', '無効');
Wizin_Util::define('WIZMOBILE_LANG_NONE_SETTING', '設定なし');
Wizin_Util::define('WIZMOBILE_LANG_EMOTICON', '絵文字');
Wizin_Util::define('WIZMOBILE_LANG_MINUTE', '分');

/**
 * language for theme
 */
Wizin_Util::define('WIZMOBILE_LANG_LOGIN', 'ログイン');
Wizin_Util::define('WIZMOBILE_LANG_LOGOUT', 'ログアウト');
Wizin_Util::define('WIZMOBILE_LANG_PAGE_TOP', '▲上へ');
Wizin_Util::define('WIZMOBILE_LANG_PAGE_BOTTOM', '▼下へ');
Wizin_Util::define('WIZMOBILE_LANG_MAIN_CONTENTS', 'メインコンテンツ');
Wizin_Util::define('WIZMOBILE_LANG_SEARCH', '検索');

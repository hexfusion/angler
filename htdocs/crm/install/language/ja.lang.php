<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/
/*********************************************************************************
 * $Id: ja.lang.php,v 1.7 2006/09/05 17:59:07 max Exp $
 * Description:
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc. All Rights
 * Reserved. Contributor(s): ______________________________________..
 * *******************************************************************************/

$mod_strings = array (









































	
	'DEFAULT_CHARSET'					=> 'UTF-8',	
	'ERR_ADMIN_PASS_BLANK'				=> 'SugarCRM管理者のパスワードは空欄にできません。',
	'ERR_CHECKSYS_CALL_TIME'			=> 'Allow Call Time Pass ReferenceがOffになっています（php.iniでOnに設定してください）',
	'ERR_CHECKSYS_CURL'					=> '見つかりません: Sugarスケジューラの機能は制限付きで動作します。',
	'ERR_CHECKSYS_IMAP'					=> '見つかりません: インバウンド電子メールとキャンペーン電子メールを利用するためにはIMAPライブラリが必要です。この２つは動作しません。',
	'ERR_CHECKSYS_MSSQL_MQGPC'			=> 'SQL Serverを使用する場合はMagic Quotes GPCをOnにできません。',
	'ERR_CHECKSYS_MBSTRING'				=> '見つかりません: SugarCRMはマルチバイトを使用できません。このためUTF-8以外で電子メールを受信する際に文字化けする場合があります。',
	'ERR_CHECKSYS_MEM_LIMIT_1'			=> '警告:  $memory_limit （php.iniで',
	'ERR_CHECKSYS_MEM_LIMIT_2'			=> 'M以上に設定してください。) ',
	'ERR_CHECKSYS_MYSQL_VERSION' 		=> 'バージョン4.1.2以上 - 見つかりました: ',
	'ERR_CHECKSYS_NO_SESSIONS'			=> 'セッション変数の読み込みと書き込みに失敗しました。インストールを続けることができません。',
	'ERR_CHECKSYS_NOT_VALID_DIR'		=> '正しいディレクトリではありません',
	'ERR_CHECKSYS_NOT_WRITABLE'			=> '警告: 書き込み不可',
	'ERR_CHECKSYS_PHP_INVALID_VER'		=> 'このPHPのバージョンは正しくありません:  ( ver',
	'ERR_CHECKSYS_PHP_JSON'				=> '見つかりません: PHP-JSONモジュールはSugarCRMのパフォーマンスを大きく向上させます。',
	'ERR_CHECKSYS_PHP_JSON_VERSION'		=> 'SugarCRMでは PHP-JSON version 1.1.1 のみをサポートしています。PHP-JSONをご利用になるには必要なバージョンをご用意ください。SugarCRMは代わりにAJAXスタイルのPHPコードを使用しますがパフォーマンスは遅くなります。',
	'ERR_CHECKSYS_PHP_UNSUPPORTED'		=> 'このPHPのバージョンはサポート外です:  ( ver',
	'ERR_CHECKSYS_SAFE_MODE'			=> 'Safe ModeがOnになっています (php.iniでOffに設定してください) ',
	'ERR_CHECKSYS_ZLIB'					=> '見つかりません: zlib圧縮はSugarCRMのパフォーマンスを大きく向上させます。',
	'ERR_DB_ADMIN'						=> 'データベース管理者のユーザ名・パスワードが正しくありません。 (エラー ',
	'ERR_DB_EXISTS_NOT'					=> '指定されたデータベースがありません。',
	'ERR_DB_EXISTS_WITH_CONFIG'			=> 'データベースの設定は既にコンフィグデータに存在します。指定したデータベースでインストールする場合は再度インストーラを実行させ、「既存のSugarテーブルを削除して新しく作成しなおしますか？」にチェックを入れてください。アップグレードする場合は管理コンソールのアップグレードウィザードをご利用ください。アップグレードのドキュメントは <a href="http://docs.sugarforum.jp/" target="_new">こちら</a>を参照してください。',
	'ERR_DB_EXISTS'						=> 'そのデータベース名は既に存在します。--同じ名前で複数作成することはできません。',
	'ERR_DB_HOSTNAME'					=> 'ホスト名は空欄にできません。',
	'ERR_DB_INVALID'					=> '不正なデータベースタイプが選択されています。',
	'ERR_DB_LOGIN_FAILURE_MYSQL'		=> 'SugarCRMデータベースのユーザ名・パスワードが正しくありません。 (エラー ',
	'ERR_DB_LOGIN_FAILURE_MSSQL'		=> 'SugarCRMデータベースのユーザ名・パスワードが正しくありません。',
	'ERR_DB_MYSQL_VERSION1'				=> 'MySQL ',
	'ERR_DB_MYSQL_VERSION2'				=> 'はサポート外です。4.1.x以上をサポートしています。',
	'ERR_DB_NAME'						=> 'データベース名は空欄にできません。',
	'ERR_DB_NAME2'						=> '「\\」、「/」、「.」を含むデータベース名を使用することはできません。',
	'ERR_DB_PASSWORD'					=> 'SugarCRMのパスワードがマッチしません。',
	'ERR_DB_PRIV_USER'					=> 'データベース管理者のユーザ名が必要です。',
	'ERR_DB_USER_EXISTS'				=> 'そのSugarCRMユーザ名は既に存在します。--同じ名前で複数作成することはできません。',
	'ERR_DB_USER'						=> 'SugarCRMユーザ名は空欄にできません。',
	'ERR_DBCONF_VALIDATION'				=> '次へ進む前に以下のエラーを修正してください: ',
	'ERR_ERROR_GENERAL'					=> '以下のエラーが見つかりました: ',
	'ERR_LANG_CANNOT_DELETE_FILE'		=> 'ファイルを削除できません: ',
	'ERR_LANG_MISSING_FILE'				=> 'ファイルが見つかりません: ',
	'ERR_LANG_NO_LANG_FILE'			 	=> 'include/language内に言語パックが見つかりません: ',
	'ERR_LANG_UPLOAD_1'					=> 'アップロードに問題がありました。再度実行してください。',
	'ERR_LANG_UPLOAD_2'					=> '言語パックはZIPファイルである必要があります。',
	'ERR_LANG_UPLOAD_3' 				=> 'PHPは仮ファイルをupgradeディレクトリへ移動できませんでした。',
	'ERR_LICENSE_MISSING'				=> '必要なファイルがありません。',
	'ERR_LICENSE_NOT_FOUND'				=> 'ライセンスファイルが見つかりません！',
	'ERR_LOG_DIRECTORY_NOT_EXISTS'		=> '指定されたログディレクトリは正しくありません。',
	'ERR_LOG_DIRECTORY_NOT_WRITABLE'	=> '指定されたログディレクトリは書き込み不可です。',
	'ERR_LOG_DIRECTORY_REQUIRED'		=> 'カスタムログディレクトリを使用したい場合はディレクトリパスの指定が必要です。',
	'ERR_NO_DIRECT_SCRIPT'				=> '直接スクリプトを実行できません。',
	'ERR_PASSWORD_MISMATCH'				=> 'SugarCRM管理者のパスワードがマッチしません。',
	'ERR_PERFORM_CONFIG_PHP_1'			=> '<span class=stop>config.php</span>ファイルに書き込みできません。',
	'ERR_PERFORM_CONFIG_PHP_2'			=> '以下に表示されている設定情報を記述したconfig.phpを手動で作成することでインストールを続行できますが、<strong>次のステップに進む前にconfig.phpを作成する</strong>必要があります。',
	'ERR_PERFORM_CONFIG_PHP_3'			=> 'config.phpファイルは作成済みですか？',
	'ERR_PERFORM_CONFIG_PHP_4'			=> '警告: config.phpファイルに書き込みできませんでした。config.phpがあるかどうか確認してください。',
	'ERR_PERFORM_HTACCESS_1'			=> '',
	'ERR_PERFORM_HTACCESS_2'			=> 'ファイルに書き込みできません。',
	'ERR_PERFORM_HTACCESS_3'			=> 'ログファイルをブラウザからのアクセスからセキュアな状態にしたい場合は、以下のコードを記述した .htaccessファイルをログディレクトリ内に作成してください。: ',
	'ERR_PERFORM_NO_TCPIP'				=> '<b>インターネットに接続できません。</b> SugarCRMを登録するには<a href=\"http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register\">http://www.sugarcrm.com/home/index.php?option=com_extended_registration&task=register</a>にアクセスしてください。SugarCRMの用途などをお知らせいただくことによって、今後ご要望に沿ったプロダクトを提供するための参考とさせていただきます。',
	'ERR_SESSION_DIRECTORY_NOT_EXISTS'	=> '指定されたセッションディレクトリは正しくありません。',
	'ERR_SESSION_DIRECTORY'				=> '指定されたセッションディレクトリは書き込み不可です。',
	'ERR_SESSION_PATH'					=> 'カスタムセッションディレクトリを使用したい場合は、ディレクトリの指定が必要です。',
	'ERR_SI_NO_CONFIG'					=> 'config_si.phpがドキュメントルートにないか、config.php内で$sugar_config_siが設定されていません。',
	'ERR_SITE_GUID'						=> 'カスタムアプリケーションIDを使用したい場合はIDを指定する必要があります。',
	'ERR_UPLOAD_MAX_FILESIZE'			=> '警告：PHPでアップロードできるファイルのサイズを6MB以上に設定する必要があります。',
	'ERR_URL_BLANK'						=> 'URLは空欄にできません',
	'ERR_UW_NO_UPDATE_RECORD'			=> 'インストール情報が確認できません: ',
	'ERROR_FLAVOR_INCOMPATIBLE'			=> 'アップロードしたファイルはこのSugar Suite (Open Source、Professional、Enterprise) と互換性がありません: ',
	'ERROR_LICENSE_EXPIRED'				=> 'エラー: ライセンスは',
	'ERROR_LICENSE_EXPIRED2'			=> '日前に期限が切れました。管理画面の<a href=\'index.php?action=LicenseSettings&module=Administration\'>「ライセンス管理」</a> から新しいライセンスキーを入力してください。30日以内にキーを入力しない場合はアプリケーションにログインすることができなくなります。',
	'ERROR_MANIFEST_TYPE'				=> 'マニフェストファイルはパッケージタイプを指定する必要があります。',
	'ERROR_PACKAGE_TYPE'				=> 'マニフェストファイルは不明のパッケージタイプを指定しています。',
	'ERROR_VALIDATION_EXPIRED'			=> 'エラー: 認証キーは',
	'ERROR_VALIDATION_EXPIRED2'			=> '日前に期限が切れました。管理画面の<a href=\'index.php?action=LicenseSettings&module=Administration\'>「ライセンス管理」</a>から新しい認証キーを入力してください。30日以内にキーを入力しない場合はアプリケーションにログインすることができなくなります。',
	'ERROR_VERSION_INCOMPATIBLE'		=> 'アップロードしたファイルはこのSugar Suiteのバージョンと互換性がありません。: ',
	
	'LBL_BACK'							=> '戻る',
	'LBL_CHECKSYS_1'					=> '正しくSugarCRMをインストールするために以下のチェック項目がすべて緑になっているかどうか確認してください。赤い項目がありましたら修正してください。',
	'LBL_CHECKSYS_CACHE'				=> 'Cacheサブディレクトリへの書き込み',
	'LBL_CHECKSYS_CALL_TIME'			=> 'PHPのAllow Call Time Pass ReferenceをOnに設定',
	'LBL_CHECKSYS_COMPONENT'			=> 'コンポーネント',
	'LBL_CHECKSYS_COMPONENT_OPTIONAL'	=> 'オプショナルコンポーネント',
	'LBL_CHECKSYS_CONFIG'				=> 'SugarCRM設定ファイル (config.php) への書き込み',
	'LBL_CHECKSYS_CURL'					=> 'cURLモジュール',
	'LBL_CHECKSYS_CUSTOM'				=> 'Customディレクトリへの書き込み',
	'LBL_CHECKSYS_DATA'					=> 'Dataサブディレクトリへの書き込み',
	'LBL_CHECKSYS_IMAP'					=> 'IMAPモジュール',
	'LBL_CHECKSYS_MQGPC'				=> 'Magic Quotes GPC',
	'LBL_CHECKSYS_MBSTRING'				=> 'MB Stringsモジュール',
	'LBL_CHECKSYS_MEM_OK'				=> 'OK (無制限) ',
	'LBL_CHECKSYS_MEM_UNLIMITED'		=> 'OK (無制限) ',
	'LBL_CHECKSYS_MEM'					=> 'PHP Memory Limit >= ',
	'LBL_CHECKSYS_MODULE'				=> 'Modulesディレクトリ、サブディレクトリ、ファイルへの書き込み',
	'LBL_CHECKSYS_MYSQL_VERSION' 		=> 'MySQLバージョン',
	'LBL_CHECKSYS_NOT_AVAILABLE'		=> '問題あり',
	'LBL_CHECKSYS_OK'					=> 'OK',
	'LBL_CHECKSYS_PHP_INI'				=> '<b>備考: </b> PHP設定ファイル (php.ini) は以下の場所にあります: ',
	'LBL_CHECKSYS_PHP_JSON'				=> 'PHP-JSONモジュール', 
	'LBL_CHECKSYS_PHP_OK'				=> 'OK (ver ',
	'LBL_CHECKSYS_PHPVER'				=> 'PHPバージョン',
	'LBL_CHECKSYS_RECHECK'				=> '再チェック',
	'LBL_CHECKSYS_SAFE_MODE'			=> 'PHPのSafe ModeをOffに設定',
	'LBL_CHECKSYS_SESSION'				=> 'セッションディレクトリへの書き込み (',
	'LBL_CHECKSYS_STATUS'				=> 'ステータス',
	'LBL_CHECKSYS_TITLE'				=> 'システムチェック',
	'LBL_CHECKSYS_VER'					=> '見つかりました: ( ver ',
	'LBL_CHECKSYS_XML'					=> 'XML Parsing',
	'LBL_CHECKSYS_ZLIB'					=> 'ZLIB圧縮モジュール',
	'LBL_CLOSE'							=> '閉じる',
	'LBL_CONFIRM_BE_CREATED'			=> '',
	'LBL_CONFIRM_DB_TYPE'				=> 'データベースタイプ',
	'LBL_CONFIRM_DIRECTIONS'			=> '以下の設定を確認してください。設定を変更したい場合は「戻る」をクリックしてください。「次へ」をクリックしますとインストールが開始されます。',
	'LBL_CONFIRM_LICENSE_TITLE'			=> 'ライセンス情報',
	'LBL_CONFIRM_NOT'					=> 'されません',
	'LBL_CONFIRM_TITLE'					=> '設定を確認',
	'LBL_CONFIRM_WILL'					=> '作成',
	'LBL_DBCONF_CREATE_DB'				=> 'データベースを作成',
	'LBL_DBCONF_CREATE_USER'			=> 'ユーザを作成',
	'LBL_DBCONF_DB_DROP_CREATE_WARN'	=> '警告 (重要) : ここをチェックするとすべてのSugarデータが削除されます。',
	'LBL_DBCONF_DB_DROP_CREATE'			=> '既存のSugarテーブルを削除して新しく作成しなおしますか？',
	'LBL_DBCONF_DB_NAME'				=> 'データベース名',
	'LBL_DBCONF_DB_PASSWORD'			=> 'データベースパスワード',
	'LBL_DBCONF_DB_PASSWORD2'			=> 'データベースパスワードを再入力',
	'LBL_DBCONF_DB_USER'				=> 'データベースユーザ名',
	'LBL_DBCONF_DEMO_DATA'				=> 'データベースにデモデータを追加しますか？',
	'LBL_DBCONF_HOST_NAME'				=> 'ホスト名',
	'LBL_DBCONF_INSTRUCTIONS'			=> 'データベース設定を以下に入力してください。よくわからない場合はデフォルト値の使用を推奨します。',
	'LBL_DBCONF_MB_DEMO_DATA'			=> 'デモデータにマルチバイトを使用？',
	'LBL_DBCONF_PRIV_PASS'				=> '特権ユーザのパスワード',
	'LBL_DBCONF_PRIV_USER_2'			=> '上記のデータベースアカウントは 特権ユーザですか？',
	'LBL_DBCONF_PRIV_USER_DIRECTIONS'	=> 'このユーザはデータベースを作成・削除し、他のユーザを作成する権限を持った特権ユーザである必要があります。このユーザの情報は、インストール時においてこのステップのみでしか使用されません。権限を持ったユーザであれば、上記で指定したユーザと同じユーザを使用してもかまいません。',
	'LBL_DBCONF_PRIV_USER'				=> '特権ユーザ名',
	'LBL_DBCONF_TITLE'					=> 'データベース設定',
	'LBL_DISABLED_DESCRIPTION_2'		=> '変更後、インストールを開始するには「開始」をクリックしてください。<i>インストール完了後は「installer_locked」を「true」にする必要があります。</i>',
	'LBL_DISABLED_DESCRIPTION'			=> 'インストーラは実行済みです。セキュリティ上の理由から再インストールの実行を無効にしています。再インストールしたい場合はconfig.phpファイル内の「installer_locked」を以下のように「false」に変更してください。 : ',
	'LBL_DISABLED_HELP_1'				=> 'SugarCRMインストールのヘルプは、SugarCRM',
	'LBL_DISABLED_HELP_2'				=> 'サポートフォーラムでご覧いただけます。',
	'LBL_DISABLED_TITLE_2'				=> 'SugarCRMはインストール不可に設定されています',
	'LBL_DISABLED_TITLE'				=> 'SugarCRMはインストール不可です',
	'LBL_EMAIL_CHARSET_DESC'			=> '電子メール文字コード',
	'LBL_EMAIL_CHARSET_TITLE'			=> 'アウトバウンド電子メール文字コード',
	'LBL_HELP'							=> 'ヘルプ',
	'LBL_LANG_1'						=> 'US-English以外の言語パックをインストールしたい場合は以下を設定してください。デフォルトのままでよければ 「次へ」をクリックして次のステップへ進んでください。',
	'LBL_LANG_BUTTON_COMMIT'			=> 'インストール',
	'LBL_LANG_BUTTON_REMOVE'			=> '削除',
	'LBL_LANG_BUTTON_UNINSTALL'			=> 'アンインストール',
	'LBL_LANG_BUTTON_UPLOAD'			=> 'アップロード',
	'LBL_LANG_NO_PACKS'					=> 'なし',
	'LBL_LANG_PACK_INSTALLED'			=> '以下の言語パックがインストール済みです: ',
	'LBL_LANG_PACK_READY'				=> '以下の言語パックがインストール待ちです: ',
	'LBL_LANG_SUCCESS'					=> '言語パックは正しくアップロードされました。',
	'LBL_LANG_TITLE'			   		=> '言語パック',
	'LBL_LANG_UPLOAD'					=> '言語パックをアップロード',
	'LBL_LICENSE_ACCEPTANCE'			=> 'ライセンス確認',
	'LBL_LICENSE_DIRECTIONS'			=> 'ライセンス情報をお持ちの場合は以下のフィールドに入力してください。',
	'LBL_LICENSE_DOWNLOAD_KEY'			=> 'ダウンロードキー',
	'LBL_LICENSE_EXPIRY'				=> '有効期限',
	'LBL_LICENSE_I_ACCEPT'				=> '同意します',
	'LBL_LICENSE_NUM_USERS'				=> 'ユーザ数',
	'LBL_LICENSE_OC_DIRECTIONS'			=> '購入したオフラインクライアントの数を入力してください。',
	'LBL_LICENSE_OC_NUM'				=> 'オフラインクライアントライセンスの数',
	'LBL_LICENSE_OC'					=> 'オフラインクライアントライセンス数',
	'LBL_LICENSE_PRINTABLE'				=> '印刷用',
	'LBL_LICENSE_TITLE_2'				=> 'SugarCRMライセンス',
	'LBL_LICENSE_TITLE'					=> 'ライセンス情報',
	'LBL_LICENSE_USERS'					=> 'ライセンスユーザ',
	
	'LBL_LOCALE_CURRENCY'				=> '通貨設定',
	'LBL_LOCALE_CURR_DEFAULT'			=> 'デフォルト通貨',
	'LBL_LOCALE_CURR_SYMBOL'			=> '通貨シンボル',
	'LBL_LOCALE_CURR_ISO'				=> '通貨コード (ISO 4217)',
	'LBL_LOCALE_CURR_1000S'				=> '1000位セパレータ',
	'LBL_LOCALE_CURR_DECIMAL'			=> '小数点シンボル',
	'LBL_LOCALE_CURR_EXAMPLE'			=> '例',
	'LBL_LOCALE_CURR_SIG_DIGITS'		=> '通貨の精度',
	'LBL_LOCALE_DATEF'					=> 'デフォルト日付フォーマット',
	'LBL_LOCALE_DESC'					=> '以下のSugarCRMロケールを設定',
	'LBL_LOCALE_EXPORT'					=> 'vCard・CSV用文字コード',
	'LBL_LOCALE_EXPORT_DELIMITER'		=> 'CSV用区切り記号',
	'LBL_LOCALE_EXPORT_TITLE'			=> 'エクスポート設定',
	'LBL_LOCALE_LANG'					=> 'デフォルト言語',
	'LBL_LOCALE_NAMEF'					=> 'デフォルト氏名フォーマット',
	'LBL_LOCALE_NAMEF_DESC'				=> '"l" 姓<br />"f" 名<br />"s" 敬称',
	'LBL_LOCALE_NAME_FIRST'				=> '太郎',
	'LBL_LOCALE_NAME_LAST'				=> 'デモ',
	'LBL_LOCALE_NAME_SALUTATION'		=> 'Dr.',
	'LBL_LOCALE_TIMEF'					=> 'デフォルト時間フォーマット',
	'LBL_LOCALE_TITLE'					=> 'ロケール設定',
	'LBL_LOCALE_UI'						=> 'ユーザインターフェース',

	'LBL_ML_ACTION'						=> 'アクション',
	'LBL_ML_DESCRIPTION'				=> '詳細',
	'LBL_ML_INSTALLED'					=> 'インストール日',
	'LBL_ML_NAME'						=> '名前',
	'LBL_ML_PUBLISHED'					=> 'パブリッシュ日',
	'LBL_ML_TYPE'						=> 'タイプ',
	'LBL_ML_UNINSTALLABLE'				=> 'アンインストール',
	'LBL_ML_VERSION'					=> 'バージョン',
	'LBL_MSSQL'							=> 'SQL Server',
	'LBL_MYSQL'							=> 'MySQL',
	'LBL_NEXT'							=> '次へ',
	'LBL_NO'							=> 'いいえ',
	'LBL_ORACLE'						=> 'Oracle',
	'LBL_PERFORM_ADMIN_PASSWORD'		=> 'サイト管理者のパスワードを設定しています',
	'LBL_PERFORM_AUDIT_TABLE'			=> '監査テーブル / ',
	'LBL_PERFORM_CONFIG_PHP'			=> 'Sugar設定ファイルを作成しています',
	'LBL_PERFORM_CREATE_DB_1'			=> 'データベースを作成しています',
	'LBL_PERFORM_CREATE_DB_2'			=> ' ',
	'LBL_PERFORM_CREATE_DB_USER'		=> 'データベースのユーザ名・パスワードを作成しています...',
	'LBL_PERFORM_CREATE_DEFAULT'		=> 'デフォルトのSugarデータを作成しています',
	'LBL_PERFORM_CREATE_LOCALHOST'		=> 'ローカルホスト用データベースのユーザ名・パスワードを作成しています...',
	'LBL_PERFORM_CREATE_RELATIONSHIPS'	=> 'Sugar関連テーブルを作成しています',
	'LBL_PERFORM_CREATING'				=> '作成中 / ',
	'LBL_PERFORM_DEFAULT_REPORTS'		=> 'デフォルトのレポートを作成しています',
	'LBL_PERFORM_DEFAULT_SCHEDULER'		=> 'デフォルトのスケジューラーを作成しています',
	'LBL_PERFORM_DEFAULT_SETTINGS'		=> 'デフォルトの設定を挿入しています',
	'LBL_PERFORM_DEFAULT_USERS'			=> 'デフォルトのユーザを作成しています',
	'LBL_PERFORM_DEMO_DATA'				=> 'デモデータをデータベーステーブルに追加しています (少々お待ちください) ...',
	'LBL_PERFORM_DONE'					=> '完了<br>',
	'LBL_PERFORM_DROPPING'				=> '削除中 / ',
	'LBL_PERFORM_FINISH'				=> '完了',
	'LBL_PERFORM_LICENSE_SETTINGS'		=> 'ライセンス情報をアップデートしています',
	'LBL_PERFORM_OUTRO_1'				=> 'Sugar ',
	'LBL_PERFORM_OUTRO_2'				=> 'のセットアップが完了しました',
	'LBL_PERFORM_OUTRO_3'				=> '実行時間: ',
	'LBL_PERFORM_OUTRO_4'				=> '秒',
	'LBL_PERFORM_OUTRO_5'				=> 'メモリ使用: 約',
	'LBL_PERFORM_OUTRO_6'				=> 'バイト',
	'LBL_PERFORM_OUTRO_7'				=> 'システムのインストールと設定が完了しました。',
	'LBL_PERFORM_REL_META'				=> '関連メタ ...',
	'LBL_PERFORM_SUCCESS'				=> '成功！',
	'LBL_PERFORM_TABLES'				=> 'Sugarアプリケーションテーブル、監査テーブル、関連メタデータを作成しています...',
	'LBL_PERFORM_TITLE'					=> 'セットアップ実行',
	'LBL_PRINT'							=> '印刷',
	'LBL_REG_CONF_1'					=> 'SugarCRMをご登録ください。SugarCRMの用途などをお知らせいただくことによって、今後ご要望に沿ったプロダクトを提供するための参考とさせていただきます。',
	'LBL_REG_CONF_2'					=> 'ご登録にはお名前とメールアドレスの入力が必要です。他の項目はオプションですが参考のためご記入をお願いいたします。ご提供いただいた情報を第三者に販売、貸出、譲渡することは一切ありません。',
	'LBL_REG_CONF_3'					=> 'ご登録ありがとうございました。完了ボタンをクリックし、SugarCRMにログインしてください。初回は 「admin」 にステップ2で設定したパスワードでログインいただけます。',
	'LBL_REG_TITLE'						=> '登録',
	'LBL_REQUIRED'						=> '* 必須項目',
	'LBL_SITECFG_ADMIN_PASS_2'			=> '<em>管理者</em>パスワードを再入力',
	'LBL_SITECFG_ADMIN_PASS_WARN'		=> '警告（重要）: 再インストールの場合は前回設定した管理者パスワードを上書きします。',
	'LBL_SITECFG_ADMIN_PASS'			=> 'Sugar<em>管理者</em>パスワード',
	'LBL_SITECFG_APP_ID'				=> 'アプリケーションID',
	'LBL_SITECFG_CUSTOM_ID_DIRECTIONS'	=> '自動生成のアプリケーションIDを指定のIDで上書きします。 アプリケーションIDはセッションを別のセッションと区別するために使用します。Sugarをクラスターでインストールする場合は、同じアプリケーションIDを設定してください。',
	'LBL_SITECFG_CUSTOM_ID'				=> 'カスタムアプリケーションID',
	'LBL_SITECFG_CUSTOM_LOG_DIRECTIONS'	=> 'Sugarのログを保存するディレクトリを指定します。ログファイルの場所がどこであっても、.htaccessによるリダイレクトによりブラウザからこのファイルにアクセスすることはできません。',
	'LBL_SITECFG_CUSTOM_LOG'			=> 'カスタムログディレクトリを使用',
	'LBL_SITECFG_CUSTOM_SESSION_DIRECTIONS'	=> 'Sugarのセッション情報を保存するディレクトリはセキュアなディレクトリを指定してください。これは、セッションデータを共有サーバで使用することによる脆弱性から守るためです。',
	'LBL_SITECFG_CUSTOM_SESSION'		=> 'Sugar用のカスタムセッションディレクトリを使用',
	'LBL_SITECFG_DIRECTIONS'			=> '以下にサイト設定情報を入力してください。よくわからない場合はデフォルト値の使用を推奨します。',
	'LBL_SITECFG_FIX_ERRORS'			=> '次へ進む前に以下のエラーを修正してください: ',
	'LBL_SITECFG_LOG_DIR'				=> 'ログディレクトリ',
	'LBL_SITECFG_SESSION_PATH'			=> 'セッションディレクトリパス<br> (書き込み可である必要があります) ',
	'LBL_SITECFG_SITE_SECURITY'			=> 'サイトセキュリティ（オプション）',
	'LBL_SITECFG_SUGAR_UP_DIRECTIONS'	=> 'ここを有効に設定しますと、今お使いのシステムは定期的に最新のバージョンがリリースされているかどうかをチェックします。',
	'LBL_SITECFG_SUGAR_UP'				=> '自動的にアップデートをチェック？',
	'LBL_SITECFG_SUGAR_UPDATES'			=> 'Sugarアップデート設定',
	'LBL_SITECFG_TITLE'					=> 'サイト設定',
	'LBL_SITECFG_URL'					=> 'SugarインスタンスのURL',
	'LBL_SITECFG_USE_DEFAULTS'			=> 'デフォルトを使用？',
	'LBL_SITECFG_ANONSTATS'             => '匿名の利用統計情報を送信？',
	'LBL_SITECFG_ANONSTATS_DIRECTIONS'  => 'ここを有効に設定しますと、Sugarはインストール情報をSugarCRM Incに送信し、最新のバージョンがリリースされているかどうかをチェックします。これは匿名の統計情報で個人情報が送信されることはありません。得られた情報は製品の向上に役立てられます。',
	'LBL_START'							=> '開始',
	'LBL_STEP'							=> 'ステップ',
	'LBL_TITLE_WELCOME'					=> 'SugarCRMへようこそ ',
	'LBL_WELCOME_1'						=> 'このインストーラはSugarCRMデータベーステーブルを作成し、必要な変数を設定します。全プロセスを完了するのに必要な時間は10分程度です。',
	'LBL_WELCOME_2'						=> 'SugarCRMインストール方法の詳細は <a href="http://docs.sugarforum.jp/" target="_blank">SugarCRM日本語ドキュメントプロジェクト</a>でご覧いただけます。',
	'LBL_WELCOME_CHOOSE_LANGUAGE'		=> '言語を選択してください',
	'LBL_WELCOME_SETUP_WIZARD'			=> 'セットアップウィザード',
	'LBL_WELCOME_TITLE_WELCOME'			=> 'SugarCRMへようこそ ',
	'LBL_WELCOME_TITLE'					=> 'SugarCRMセットアップウィザード',
	'LBL_WIZARD_TITLE'					=> 'SugarCRMセットアップウィザード: ステップ ',
	'LBL_YES'							=> 'はい',
	// OOTB Scheduler Job Names:
	'LBL_OOTB_WORKFLOW'					=> 'ワークフロータスク実行',
	'LBL_OOTB_REPORTS'					=> 'レポート生成の時間指定タスクを実行',
	'LBL_OOTB_IE'						=> 'インバウンド電子メール受信箱の確認',
	'LBL_OOTB_BOUNCE'					=> 'バウンスしたキャンペーン電子メールの処理を夜間に実行',
	'LBL_OOTB_CAMPAIGN'					=> 'キャンペーン電子メール送信を夜間に実行',
	'LBL_OOTB_PRUNE'					=> '月初め（１日）にデータベースを最適化',
	'LBL_OC_INSTAL_ADMIN_NAME' => '管理者ユーザ名',
	'LBL_OC_INSTAL_SERVER_URL' => 'SugarサーバのURL',
);

?>

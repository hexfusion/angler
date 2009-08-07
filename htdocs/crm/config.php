<?php
// created: 2007-03-18 19:11:53
$sugar_config = array (
  'admin_export_only' => false,
  'cache_dir' => 'cache/',
  'calculate_response_time' => true,
  'create_default_user' => false,
  'currency' => '',
  'dashlet_display_row_options' => 
  array (
    0 => '1',
    1 => '3',
    2 => '5',
    3 => '10',
  ),
  'date_formats' => 
  array (
    'Y-m-d' => '2006-12-23',
    'm-d-Y' => '12-23-2006',
    'd-m-Y' => '23-12-2006',
    'Y/m/d' => '2006/12/23',
    'm/d/Y' => '12/23/2006',
    'd/m/Y' => '23/12/2006',
    'Y.m.d' => '2006.12.23',
    'd.m.Y' => '23.12.2006',
    'm.d.Y' => '12.23.2006',
  ),
  'datef' => 'Y-m-d',
  'dbconfig' => 
  array (
    'db_host_name' => 'localhost',
    'db_host_instance' => '',
    'db_user_name' => 'root',
    'db_password' => 's1a2mayfly',
    'db_name' => 'sugarcrm',
    'db_type' => 'mysql',
  ),
  'dbconfigoption' => 
  array (
    'persistent' => true,
    'autofree' => false,
    'debug' => 0,
    'seqname_format' => '%s_seq',
    'portability' => 0,
    'ssl' => false,
  ),
  'default_action' => 'index',
  'default_charset' => 'UTF-8',
  'default_currencies' => 
  array (
    'AUD' => 
    array (
      'name' => 'Austrailian Dollars',
      'iso4217' => 'AUD',
      'symbol' => '$',
    ),
    'BRL' => 
    array (
      'name' => 'Brazilian Reais',
      'iso4217' => 'BRL',
      'symbol' => 'R$',
    ),
    'GBP' => 
    array (
      'name' => 'British Pounds',
      'iso4217' => 'GBP',
      'symbol' => '£',
    ),
    'CAD' => 
    array (
      'name' => 'Candian Dollars',
      'iso4217' => 'CAD',
      'symbol' => '$',
    ),
    'CNY' => 
    array (
      'name' => 'Chinese Yuan',
      'iso4217' => 'CNY',
      'symbol' => '元',
    ),
    'EUR' => 
    array (
      'name' => 'Euro',
      'iso4217' => 'EUR',
      'symbol' => '€',
    ),
    'HKD' => 
    array (
      'name' => 'Hong Kong Dollars',
      'iso4217' => 'HKD',
      'symbol' => '$',
    ),
    'INR' => 
    array (
      'name' => 'Indian Rupees',
      'iso4217' => 'INR',
      'symbol' => '₨',
    ),
    'KRW' => 
    array (
      'name' => 'Korean Won',
      'iso4217' => 'KRW',
      'symbol' => '₩',
    ),
    'YEN' => 
    array (
      'name' => 'Japanese Yen',
      'iso4217' => 'JPY',
      'symbol' => '¥',
    ),
    'MXM' => 
    array (
      'name' => 'Mexican Pesos',
      'iso4217' => 'MXM',
      'symbol' => '$',
    ),
    'SGD' => 
    array (
      'name' => 'Singaporean Dollars',
      'iso4217' => 'SGD',
      'symbol' => '$',
    ),
    'CHF' => 
    array (
      'name' => 'Swiss Franc',
      'iso4217' => 'CHF',
      'symbol' => 'SFr.',
    ),
    'THB' => 
    array (
      'name' => 'Thai Baht',
      'iso4217' => 'THB',
      'symbol' => '฿',
    ),
    'USD' => 
    array (
      'name' => 'US Dollars',
      'iso4217' => 'USD',
      'symbol' => '$',
    ),
  ),
  'default_currency_iso4217' => 'USD',
  'default_currency_name' => 'US Dollars',
  'default_currency_significant_digits' => '2',
  'default_currency_symbol' => '$',
  'default_date_format' => 'm-d-Y',
  'default_decimal_seperator' => '.',
  'default_email_charset' => 'ISO-8859-1',
  'default_email_client' => 'sugar',
  'default_email_editor' => 'html',
  'default_export_charset' => 'CP1252',
  'default_language' => 'en_us',
  'default_locale_name_format' => 's f l',
  'default_max_subtabs' => '12',
  'default_max_tabs' => '12',
  'default_module' => 'Home',
  'default_navigation_paradigm' => 'm',
  'default_number_grouping_seperator' => ',',
  'default_password' => '',
  'default_subpanel_links' => false,
  'default_subpanel_tabs' => true,
  'default_swap_last_viewed' => false,
  'default_swap_shortcuts' => false,
  'default_theme' => 'Sugar',
  'default_time_format' => 'h:ia',
  'default_user_is_admin' => false,
  'default_user_name' => '',
  'disable_export' => false,
  'disable_persistent_connections' => 'false',
  'display_email_template_variable_chooser' => false,
  'display_inbound_email_buttons' => false,
  'dump_slow_queries' => false,
  'email_default_client' => 'sugar',
  'email_default_editor' => 'html',
  'export_delimiter' => ',',
  'history_max_viewed' => 10,
  'host_name' => 'www.westbranchresortcom',
  'i18n_test' => false,
  'import_dir' => 'cache/import/',
  'import_max_execution_time' => 3600,
  'installer_locked' => true,
  'js_custom_version' => '',
  'js_lang_version' => 1,
  'languages' => 
  array (
    'en_us' => 'US English',
  ),
  'large_scale_test' => false,
  'list_max_entries_per_page' => 20,
  'list_max_entries_per_subpanel' => 10,
  'lock_default_user_name' => false,
  'lock_homepage' => false,
  'lock_subpanels' => false,
  'log_dir' => '.',
  'log_file' => 'sugarcrm.log',
  'log_memory_usage' => false,
  'login_nav' => false,
  'max_dashlets_homepage' => '15',
  'require_accounts' => true,
  'rss_cache_time' => '10800',
  'save_query' => 'all',
  'session_dir' => '',
  'site_url' => 'http://www.westbranchresortcom/crm',
  'slow_query_time_msec' => '100',
  'sugar_version' => '4.5.0',
  'sugarbeet' => true,
  'time_formats' => 
  array (
    'H:i' => '23:00',
    'h:ia' => '11:00pm',
    'h:iA' => '11:00PM',
    'H.i' => '23.00',
    'h.ia' => '11.00pm',
    'h.iA' => '11.00PM',
  ),
  'timef' => 'H:i',
  'tmp_dir' => 'cache/xml/',
  'translation_string_prefix' => false,
  'unique_key' => '10dca169c5ccce29c283e020e66ccab9',
  'upload_badext' => 
  array (
    0 => 'php',
    1 => 'php3',
    2 => 'php4',
    3 => 'php5',
    4 => 'pl',
    5 => 'cgi',
    6 => 'py',
    7 => 'asp',
    8 => 'cfm',
    9 => 'js',
    10 => 'vbs',
    11 => 'html',
    12 => 'htm',
  ),
  'upload_dir' => 'cache/upload/',
  'upload_maxsize' => 3000000,
  'use_php_code_json' => true,
  'verify_client_ip' => true,
);
?>

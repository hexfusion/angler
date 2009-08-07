{*

/**
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
 */

// $Id: EditView.tpl,v 1.25 2006/08/23 00:09:59 awu Exp $

*}


<BR>
<form name="ConfigureSettings" enctype='multipart/form-data' method="POST" action="index.php?action=EditView&module=Configurator" onSubmit="return (add_checks(document.ConfigureSettings) && check_form('ConfigureSettings'));">
<span class='error'>{$error.main}</span>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
			
	<td style="padding-bottom: 2px;">
		<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button"  type="submit" name="save" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " >
		&nbsp;<input title="{$MOD.LBL_SAVE_BUTTON_TITLE}"  class="button"  type="submit" name="restore" value="  {$MOD.LBL_RESTORE_BUTTON_LABEL}  " > 
		&nbsp;<input title="{$MOD.LBL_CANCEL_BUTTON_TITLE}"  onclick="document.location.href='index.php?module=Administration&action=index'" class="button"  type="button" name="cancel" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " > </td>	
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr><td>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel">{$MOD.DEFAULT_SYSTEM_SETTINGS}</h4></th>
	</tr><tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td  class="dataLabel">{$MOD.LIST_ENTRIES_PER_LISTVIEW}: </td>
		<td  class="dataField">
			<input type='text' size='4' name='list_max_entries_per_page' value='{$config.list_max_entries_per_page}'>
		</td>
		<td  class="dataLabel">{$MOD.LIST_ENTRIES_PER_SUBPANEL}: </td>
		<td  class="dataField">
			<input type='text' size='4' name='list_max_entries_per_subpanel' value='{$config.list_max_entries_per_subpanel}'>
		</td>
	</tr>
	<tr>
		<td  class="dataLabel">{$MOD.DISPLAY_RESPONSE_TIME}: </td>
		{if !empty($config.calculate_response_time )}
			{assign var='calculate_response_time_checked' value='CHECKED'}
		{else}
			{assign var='calculate_response_time_checked' value=''}
		{/if}
		<td class="dataField"><input type='hidden' name='calculate_response_time' value='false'><input name='calculate_response_time'  type="checkbox" value="true" {$calculate_response_time_checked}></td>
	
		<td  class="dataLabel">{$MOD.DISPLAY_LOGIN_NAV}: </td>
		{if !empty($config.login_nav)}
			{assign var='login_nav_checked' value='CHECKED'}
		{else}
			{assign var='login_nav_checked' value=''}
		{/if}
		<td class="dataField"><input type='hidden' name='login_nav' value='false'><input name='login_nav'  type="checkbox" value="true" {$login_nav_checked}></td>
	</tr>
	<tr>
		<td  class="dataLabel">{$MOD.LOCK_HOMEPAGE}: </td>
		<td  class="dataField">
			{if !empty($config.lock_homepage)}
				{assign var='lock_homepage_checked' value='CHECKED'}
			{else}
				{assign var='lock_homepage_checked' value=''}
			{/if}
			<input type='hidden' name='lock_homepage' value='false'>
			<input type='checkbox' name='lock_homepage' value='true' {$lock_homepage_checked}>
		</td>
		<td  class="dataLabel">{$MOD.LOCK_SUBPANELS}: </td>
		<td  class="dataField">
			{if !empty($config.lock_subpanels)}
				{assign var='lock_subpanels_checked' value='CHECKED'}
			{else}
				{assign var='lock_subpanels_checked' value=''}
			{/if}
			<input type='hidden' name='lock_subpanels' value='false'>
			<input type='checkbox' name='lock_subpanels' value='true' {$lock_subpanels_checked}>
		</td>
	</tr>
	<tr>
		<td  class="dataLabel">{$MOD.MAX_DASHLETS}: </td>
		<td  class="dataField">
			<input type='text' size='4' name='max_dashlets_homepage' value='{$config.max_dashlets_homepage}'>
		</td>
		<td  class="dataLabel">{$MOD.LBL_USE_REAL_NAMES}: </td>
		{if !empty($config.use_real_names)}
			{assign var='use_real_names' value='CHECKED'}
		{else}
			{assign var='use_real_names' value=''}
		{/if}
		<td class="dataField">
			<input type='hidden' name='use_real_names' value='false'>
			<input name='use_real_names'  type="checkbox" value="true" {$use_real_names}>
		</td>
	</tr>
</table>
</td></tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel">{$MOD.IMAGES}</h4></th>
	</tr><tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td  class="dataLabel" nowrap>{$MOD.CURRENT_LOGO}: </td>
		<td  class="dataField">
			<img src='include/images/company_logo.png' height="40" width="212">
		</td>
	</tr>
	
	<tr>
		<td  class="dataLabel" nowrap>{$MOD.NEW_LOGO}: </td>
		<td  class="dataField">
			<input type='file' name='company_logo'>
		</td>
	</tr>














</table>
</td>
</tr>
</table>
<br>
{if !empty($settings.system_ldap_enabled)}
		{assign var='system_ldap_enabled_checked' value='CHECKED'}
		{assign var='ldap_display' value='inline'}
	{else}
		{assign var='system_ldap_enabled_checked' value=''}
		{assign var='ldap_display' value='none'}
{/if}
	
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr><td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><th align="left" class="dataLabel" colspan='3'><h4 class="dataLabel">{$MOD.LBL_LDAP_TITLE}</h4></th>
	</tr>
	<tr><td width="15%">
	{$MOD.LBL_LDAP_ENABLE}
	</td><td width="35%"><input type='hidden' name="system_ldap_enabled" value="0" ><input name="system_ldap_enabled" value="1" class="checkbox" tabindex='1' type="checkbox" {$system_ldap_enabled_checked} onclick='toggleDisplay("ldap_display")'></td><td>&nbsp;</td><td>&nbsp;</td></tr>
	<tr><td colspan='4'>
	<table  cellspacing='0' cellpadding='0' id='ldap_display' style='display:{$ldap_display}' width='100%'>	
	<tr>
		<td class="dataLabel" valign='top' nowrap>{$MOD.LBL_LDAP_SERVER_HOSTNAME}</td>{$settings.proxy_host}
		<td align="left" class="dataField" valign='top'><input name="ldap_hostname" size='25' type="text" value="{$settings.ldap_hostname}"></td>
		<td align="left" class="dataField" valign='top'>{$MOD.LBL_LDAP_SERVER_HOSTNAME_DESC}</td>
	</tr>
	<tr>
		<td class="dataLabel" valign='middle' nowrap>{$MOD.LBL_LDAP_BASE_DN}</td>
		<td align="left" class="dataField" valign='middle'><input name="ldap_base_dn" size='35' type="text" value="{$settings.ldap_base_dn}"></td>
		 <td align="left" class="dataField" valign='middle'><em>{$MOD.LBL_LDAP_BASE_DN_DESC}</em></td>
	</tr>
	<tr>
		<td class="dataLabel" valign='top' nowrap>{$MOD.LBL_LDAP_BIND_ATTRIBUTE}</td>
		<td align="left" class="dataField" valign='top'><input name="ldap_bind_attr" size='25' type="text" value="{$settings.ldap_bind_attr}"> </td>
		<td align="left" class="dataField" valign='top'><em>{$MOD.LBL_LDAP_BIND_ATTRIBUTE_DESC}</em></td>
	</tr>
	
	<tr>
		<td class="dataLabel" valign='middle' nowrap>{$MOD.LBL_LDAP_LOGIN_ATTRIBUTE}</td>
		<td align="left" class="dataField" valign='middle'><input name="ldap_login_attr" size='25' type="text" value="{$settings.ldap_login_attr}"></td>
		 <td align="left" class="dataField" valign='middle'><em>{$MOD.LBL_LDAP_LOGIN_ATTRIBUTE_DESC}</em></td>
	</tr>

	<tr>
		<td class="dataLabel" valign='top'nowrap>{$MOD.LBL_LDAP_ADMIN_USER}</td>
		<td align="left" class="dataField" valign='top'><input name="ldap_admin_user" size='35' type="text" value="{$settings.ldap_admin_user}"></td>
		<td align="left" class="dataField" valign='top'><em>{$MOD.LBL_LDAP_ADMIN_USER_DESC}</em></td>
	</tr>
	<tr>
		<td class="dataLabel" valign='middle' nowrap>{$MOD.LBL_LDAP_ADMIN_PASSWORD}</td>
		<td align="left" class="dataField" valign='middle'><input name="ldap_admin_password" size='35' type="password" value="{$settings.ldap_admin_password}"> </td>
		<td align="left" class="dataField" valign='top'><em>{$MOD.LBL_LDAP_ADMIN_PASSWORD_DESC}</em></td>
	</tr>
	

	<tr>
		<td class="dataLabel" valign='top' nowrap>{$MOD.LBL_LDAP_AUTO_CREATE_USERS}</td>
		{if !empty($settings.ldap_auto_create_users)}
			{assign var='ldap_auto_create_users_checked' value='CHECKED'}
		{else}
			{assign var='ldap_auto_create_users_checked' value=''}
		{/if}
		<td align="left" class="dataField" valign='top'><input type='hidden' name='ldap_auto_create_users' value='0'><input name="ldap_auto_create_users" value="1" class="checkbox" type="checkbox" {$ldap_auto_create_users_checked}></td>
		<td align="left" class="dataField" valign='top'> <em>{$MOD.LBL_LDAP_AUTO_CREATE_USERS_DESC}</em></td>
	</tr>

	
</table>
</td></tr></table></td></tr></table>
<BR>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr><td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel">{$MOD.LBL_PROXY_TITLE}</h4></th>
	</tr><tr>
	<td width="15%" class="dataLabel" valign='middle'>{$MOD.LBL_PROXY_ON}</td>
		{if !empty($settings.proxy_on)}
		{assign var='proxy_on_checked' value='CHECKED'}
	{else}
		{assign var='proxy_on_checked' value=''}
	{/if}
	<td width="85%" align="left" class="dataField" valign='middle' colspan='3'><input type='hidden' name='proxy_on' value='0'><input name="proxy_on" value="1" class="checkbox" tabindex='1' type="checkbox" {$proxy_on_checked} onclick='toggleDisplay("proxy_config_display")'> <em>{$MOD.LBL_PROXY_ON_DESC}</em></td>
	</tr><tr>
	<td colspan="4">
	<div id="proxy_config_display" style='display:{$PROXY_CONFIG_DISPLAY}'>
		<table width="100%" cellpadding="0" cellspacing="0"><tr>
		<td width="15%" class="dataLabel">{$MOD.LBL_PROXY_HOST}<span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
		<td width="35%" class="dataField"><input type="text" name="proxy_host" size="25"  value="{$settings.proxy_host}" tabindex='1' ></td>
		<td width="15%" class="dataLabel">{$MOD.LBL_PROXY_PORT}<span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
		<td width="35%" class="dataField"><input type="text" name="proxy_port" size="6"  value="{$settings.proxy_port}" tabindex='1' ></td>
		</tr><tr>
		<td width="15%" class="dataLabel" valign='middle'>{$MOD.LBL_PROXY_AUTH}</td>
	{if !empty($settings.proxy_auth)}
		{assign var='proxy_auth_checked' value='CHECKED'}
	{else}
		{assign var='proxy_auth_checked' value=''}
	{/if}
		<td width="35%" align="left" class="dataField" valign='middle' ><input type='hidden' name='proxy_auth' value='0'><input name="proxy_auth" value="1" class="checkbox" tabindex='1' type="checkbox" {$proxy_auth_checked} onclick='toggleDisplay("proxy_auth_display")'> </td>
		</tr></table>
		
		<div id="proxy_auth_display" style='display:{$PROXY_AUTH_DISPLAY}'>
		
		<table width="100%" cellpadding="0" cellspacing="0"><tr>
		<td width="15%" class="dataLabel">{$MOD.LBL_PROXY_USERNAME}<span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
		
		<td width="35%" class="dataField"><input type="text" name="proxy_username" size="25"  value="{$settings.proxy_username}" tabindex='1' ></td>
		<td width="15%" class="dataLabel">{$MOD.LBL_PROXY_PASSWORD}<span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span></td>
		<td width="35%" class="dataField"><input type="password" name="proxy_password" size="25"  value="{$settings.proxy_password}" tabindex='1' ></td>
		</tr></table>
		</div>
	</div>
  </td></tr></table>
</td></tr></table>
<BR>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr><td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel">{$MOD.LBL_PORTAL_TITLE}</h4></th>
	</tr><tr>
	<td width="25%" class="dataLabel" valign='middle'>{$MOD.LBL_PORTAL_ON}</td>
	{if !empty($settings.portal_on)}
		{assign var='portal_on_checked' value='CHECKED'}
	{else}
		{assign var='portal_on_checked' value=''}
	{/if}
		<td width="75%" align="left" class="dataField" valign='middle'><input type='hidden' name='portal_on' value='0'><input name="portal_on" value="1" class="checkbox" tabindex='1' type="checkbox" {$portal_on_checked}> <em>{$MOD.LBL_PORTAL_ON_DESC}</em></td>
	</tr><tr>
	<td colspan="4">
	<div id="portal_config">
		<table width="100%" cellpadding="0" cellspacing="0"><tr>
		<td width="15%" class="dataLabel">&nbsp;</td>
		<td width="35%" class="dataField">&nbsp;</td>
		</tr></table>
	</div>
  </td></tr></table>
</td></tr></table>
<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
<tr><td>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel">{$MOD.LBL_SKYPEOUT_TITLE}</h4></th>
	</tr><tr>
	<td width="25%" class="dataLabel" valign='middle'>{$MOD.LBL_SKYPEOUT_ON}</td>
	{if !empty($settings.system_skypeout_on)}
		{assign var='system_skypeout_on_checked' value='CHECKED'}
	{else}
		{assign var='system_skypeout_on_checked' value=''}
	{/if}
	<td width="75%" align="left" class="dataField" valign='middle'><input type='hidden' name='system_skypeout_on' value='0'><input name="system_skypeout_on" value="1" class="checkbox" tabindex='1' type="checkbox" {$system_skypeout_on_checked} > <em>{$MOD.LBL_SKYPEOUT_ON_DESC}</em></td>
	</tr><tr>
	<td colspan="4">
	<div id="portal_config">
		<table width="100%" cellpadding="0" cellspacing="0"><tr>
		<td width="15%" class="dataLabel">&nbsp;</td>
		<td width="35%" class="dataField">&nbsp;</td>
		</tr></table> 
	</div>
  </td></tr></table>
</td></tr></table>



































<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel">{$MOD.EXPORT}</h4></th>
	</tr><tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td nowrap width="10%" class="dataLabel">{$MOD.EXPORT_DELIMITER}: </td>
		<td width="25%" class="dataField">
			<input type='text' name='export_delimiter' size="5" value='{$config.export_delimiter}'>
		</td>
		<td nowrap width="10%" class="dataLabel">{$MOD.EXPORT_CHARSET}: </td>
		<td width="25%" class="dataField">
			<select name="default_export_charset">{$exportCharsets}</select>
		</td>

		<td nowrap width="10%" class="dataLabel">{$MOD.DISABLE_EXPORT}: </td>
		{if !empty($config.disable_export)}
			{assign var='disable_export_checked' value='CHECKED'}
		{else}
			{assign var='disable_export_checked' value=''}
		{/if}
		<td width="25%" class="dataField"><input type='hidden' name='disable_export' value='false'><input name='disable_export'  type="checkbox" value="true" {$disable_export_checked}></td>
		<td nowrap width="10%" class="dataLabel">{$MOD.ADMIN_EXPORT_ONLY}: </td>
		{if !empty($config.admin_export_only)}
			{assign var='admin_export_only_checked' value='CHECKED'}
		{else}
			{assign var='admin_export_only_checked' value=''}
		{/if}
		<td width="20%" class="dataField"><input type='hidden' name='admin_export_only' value='false'><input name='admin_export_only'  type="checkbox" value="true" {$admin_export_only_checked}></td>
		
	</tr>
</table>
</td></tr>
</table>

<br>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="tabForm">
	<tr><th align="left" class="dataLabel" colspan="4"><h4 class="dataLabel">{$MOD.ADVANCED}</h4></th>
	</tr><tr>
<td>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td  class="dataLabel">{$MOD.VERIFY_CLIENT_IP}: </td>
		{if !empty($config.verify_client_ip)}
			{assign var='verify_client_ip_checked' value='CHECKED'}
		{else}
			{assign var='verify_client_ip_checked' value=''}
		{/if}
		<td  class="dataField"><input type='hidden' name='verify_client_ip' value='false'><input name='verify_client_ip'  type="checkbox" value="1" {$verify_client_ip_checked}></td>
	
		<td  class="dataLabel">{$MOD.LOG_MEMORY_USAGE}: </td>
		{if !empty($config.log_memory_usage)}
			{assign var='log_memory_usage_checked' value='CHECKED'}
		{else}
			{assign var='log_memory_usage_checked' value=''}
		{/if}
		<td  class="dataField"><input type='hidden' name='log_memory_usage' value='false'><input name='log_memory_usage'  type="checkbox" value='true' {$log_memory_usage_checked}></td>
		
	</tr>
	<tr>
		<td  class="dataLabel">{$MOD.LOG_SLOW_QUERIES}: </td>
		{if !empty($config.dump_slow_queries)}
			{assign var='dump_slow_queries_checked' value='CHECKED'}
		{else}
			{assign var='dump_slow_queries_checked' value=''}
		{/if}
		<td class="dataField"><input type='hidden' name='dump_slow_queries' value='false'><input name='dump_slow_queries'  type="checkbox" value='true' {$dump_slow_queries_checked}></td>
	
		<td  class="dataLabel">{$MOD.SLOW_QUERY_TIME_MSEC}: </td>
		<td  class="dataField">
			<input type='text' size='5' name='slow_query_time_msec' value='{$config.slow_query_time_msec}'>
		</td>
		
	</tr>
	<tr>
		<td  class="dataLabel">{$MOD.UPLOAD_MAX_SIZE}: </td>
		<td  class="dataField">
			<input type='text' size='8' name='upload_maxsize' value='{$config.upload_maxsize}'>
		</td>
		<td  class="dataLabel">{$MOD.STACK_TRACE_ERRORS}: </td>
		{if !empty($config.stack_trace_errors)}
			{assign var='stack_trace_errors_checked' value='CHECKED'}
		{else}
			{assign var='stack_trace_errors_checked' value=''}
		{/if}
		<td class="dataField"><input type='hidden' name='stack_trace_errors' value='false'><input name='stack_trace_errors'  type="checkbox" value='true' {$stack_trace_errors_checked}></td>
	
		
		
	</tr>








	
</table>
</td></tr>
</table>
<br />
<div style="padding-top: 2px;">
<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" class="button"  type="submit" name="save" value="  {$APP.LBL_SAVE_BUTTON_LABEL}  " />
		&nbsp;<input title="{$MOD.LBL_SAVE_BUTTON_TITLE}"  class="button"  type="submit" name="restore" value="  {$MOD.LBL_RESTORE_BUTTON_LABEL}  " /> 
		&nbsp;<input title="{$MOD.LBL_CANCEL_BUTTON_TITLE}"  onclick="document.location.href='index.php?module=Administration&action=index'" class="button"  type="button" name="cancel" value="  {$APP.LBL_CANCEL_BUTTON_LABEL}  " />
</div>
{$JAVASCRIPT}
</form>

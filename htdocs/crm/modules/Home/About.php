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

//NOTE: Under the Sugar Public License referenced above, you are required to leave in all copyright statements in both
//the code and end-user application.

global $sugar_config;
?>
<span class="body">
<p><img src="include/images/sugarsales_lg.png" alt="Sugar Suite" width="425" height="30"><br>
<b>Version <?php echo $sugar_version; ?> (Build <?php echo $sugar_build; ?>)
<?php
    if( is_file( "custom_version.php" ) ){
        include( "custom_version.php" );
        print( "&nbsp;&nbsp;&nbsp;" . $custom_version );
    }
?>
</b></p>

<p>Copyright &copy; 2004-2006 <A href="http://www.sugarcrm.com" target="_blank" class="body">SugarCRM Inc.</A> All Rights Reserved. 
<?php




echo "<A href='http://www.sugarcrm.com/SPL' target='_blank' class='body'>View License Agreement</A><br>";



?>
SugarCRM<span class="tm">TM</span>, 
<?php



echo "Sugar Open Source<span class='tm'>TM</span> ";




?>
and Sugar Suite<span class="tm">TM</span> are
<a href="http://www.sugarcrm.com/crm/open-source/trademark-information.html"
	target="_blank" class="body">trademarks</a> of SugarCRM Inc.</p>

<p><table cellspacing="0" cellpadding="0" border="0" class="contentBox">
<tr>
    <td class="body" style="padding-right: 10px;" valign="top"><B>Silicon Valley Corporate Office</B><br>

<IMG src="include/images/corp_office.jpg" alt="Silicon Valley Corporate Office" usemap="#office" border="0">
<map name="office">
<area alt="" shape="poly" coords="27,89,123,94,123,117,25,112" onclick='return window.open("index.php?module=Home&action=PopupSugar","test","width=300,height=400,resizable=0,scrollbars=0");'>
</map></td>
    <td class="body" valign="top" style="padding-right: 10px;">
<BR><BR>
<p>	<B>SugarCRM Inc.</B><BR>
        10050 North Wolfe Road<BR>
        Suite SW2-130<BR>
		Cupertino, CA 95014 USA</p>
<p>
<a href="http://www.sugarcrm.com" target="_blank" class="body">http://www.sugarcrm.com</a><BR>
</p>
<p>
<BR>
<B>Founders</B>
</p>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
<tr>
    <td class="body"><LI>John Roberts</LI>
          <LI>Clint Oram</LI>
          <LI>Jacob Taylor</LI>
          </td>
</tr>
</table>

          
	</td>

</tr>
</table></p>

</span>

<p><B>Thanks to the following developers for their contributions:</B>
<LI>Marcelo Leite of AnySoft, Inc. (<A href="http://www.anysoft.com.br/sugarsuite" target="_blank">www.anysoft.com.br</A>) - Contributed Upgrade Wizard enhancements and many other minor fixes and features.</LI>
<LI>Ryuhei Uchida of CareBrains, Inc. (<A HREF="http://www.carebrains.co.jp" target="_blank">www.carebrains.co.jp</A>) - Contributed shared calendar enhancement.</LI>
<LI>Mike Dawson of Gamma Code Corporation (<a href="http://www.gammacode.com/" target="_blank">www.gammacode.com</a>) - Contributed enhancements to e-mail notification feature.</LI>
<LI>Erik Mitchell and Ray Gauss II of the OpenLDAP/Active Directory Authentication project (<a href="http://www.sugarforge.org/projects/ldapauth" target="_blank">www.sugarforge.org/projects/ldapauth</a>) - Contributed integration to support LDAP and Active Directory.</LI>
<LI>RPS Technology (<A HREF="http://www.rpstechnology.com" target="_blank">www.rpstechnology.com</A>) - Contributed original porting work for Microsoft SQL Server support.</LI>
<LI>Andrew Whitehead of Info At Hand (<A HREF="http://infoathand.com/">www.infoathand.com</A>)(SugarForge.org: <A HREF="http://www.sugarforge.org/projects/fudge/">www.sugarforge.org/projects/fudge</A>) - Contributed enhancement for theme color selection.</LI>
<LI>Installer Language Pack - Giovanni Calmon (Brazilian), Vincent Rollin (French), Juergen Schramm (German), and <A HREF="http://www.carebrains.co.jp" target="_blank">Naoko Kondo</A>(Japanese). 
<LI>The Sugar Developer Community (<A HREF="http://www.sugarforge.org" target="_blank">www.sugarforge.org</A>)- bug reports (with fixes!), outstanding feature requests and unbelievable support and input.</LI>

<P>&nbsp;</p>
<P><B>Source Code</B></p>
<LI>Sugar Suite - The world's most popular sales force automation application created by SugarCRM Inc. (<A href="http://www.sugarcrm.com" target="_blank">http://www.sugarcrm.com</A>)</LI>
<LI>XTemplate - A template engine for PHP created by Barnabás Debreceni (<A href="http://sourceforge.net/projects/xtpl" target="_blank">http://sourceforge.net/projects/xtpl</A>)</LI>
<LI>Log4php - A PHP port of Log4j, the most popular Java logging framework, created by Ceki Gülcü (<a href="http://www.vxr.it/log4php" target="_blank">http://www.vxr.it/log4php</a>)</LI>
<LI>NuSOAP - A set of PHP classes that allow developers to create and consume web services created by NuSphere Corporation and Dietrich Ayala (<a href="http://dietrich.ganx4.com/nusoap" target="_blank">http://dietrich.ganx4.com/nusoap</a>)</LI>
<LI>JS Calendar - A calendar for entering dates created by Mihai Bazon (<a href="http://www.dynarch.com/mishoo/calendar.epl" target="_blank">http://www.dynarch.com/mishoo/calendar.epl</a>)</LI>
<LI>PHP PDF - A library for creating PDF documents created by Wayne Munro (<a href="http://ros.co.nz/pdf/" target="_blank">http://ros.co.nz/pdf/</a>)
<LI>DOMIT! - An xml parser for PHP based on the Document Object Model (DOM) Level 2 Spec. (<a href="http://sourceforge.net/projects/domit-xmlparser/" target="_blank">http://sourceforge.net/projects/domit-xmlparser</a>)</LI>
<LI>DOMIT RSS - An RSS feed parser based on the DOMIT pure PHP XML parser. (<a href="http://sourceforge.net/projects/domit-rssparser/" target="_blank">http://sourceforge.net/projects/domit-rssparser</a>)</LI>
<LI>JSON.php - A PHP script to convert to and from JSON data format by Michal Migurski. (<a href="http://mike.teczno.com/json.html" target="_blank">http://mike.teczno.com/json.html</a>)</LI>
<LI>JSON.js - A JSON parser and JSON stringifier in JavaScript. (<a href="http://www.json.org/js.html" target="_blank">http://www.json.org/js.html</a>)</LI>
<LI>HTTP_WebDAV_Server - A WebDAV Server Implementation in PHP. (<a href="http://pear.php.net/package/HTTP_WebDAV_Server" target="_blank">http://pear.php.net/package/HTTP_WebDAV_Server</a>)</LI>
<LI>JavaScript O Lait - A library of reusable modules and components to enhance JavaScript by Jan-Klaas Kollhof. (<a href="http://jsolait.net/" target="_blank">http://jsolait.net/</a>)</LI>
<LI>PclZip - library offers compression and extraction functions for Zip formatted archives by Vincent Blavet (<a href="http://www.phpconcept.net/pclzip/index.en.php" target="_blank">http://www.phpconcept.net/pclzip/index.en.php/</a>)</LI>
<LI>Smarty - A template engine for PHP. (<a href="http://smarty.php.net/" target="_blank">http://smarty.php.net/</a>)</LI>
<LI>Overlibmws - JavaScript library for client-side windowing. (<a href="http://www.macridesweb.com/oltest/" target="_blank">http://www.macridesweb.com/oltest/</a>)</LI>
<LI>WICK: Web Input Completion Kit - JavaScript type ahead control (<a href="http://wick.sourceforge.net/" target="_blank">http://wick.sourceforge.net/</a>)</LI>
<LI>FCKeditor - The text editor for Internet, by Frederico Caldeira Knabben (<a href="http://www.fckeditor.net/" target="_blank">http://www.fckeditor.net/</a>)</LI>
<LI>Yahoo! User Interface Library - The UI Library Utilities facilitate the implementation of rich client-side features. (<a href="http://developer.yahoo.net/yui/" target="_blank">http://developer.yahoo.net/yui/</a>)</LI>
<LI>PHPMailer - A full featured email transfer class for PHP (<a href="http://phpmailer.sourceforge.net/" target="_blank">http://phpmailer.sourceforge.net/</a>)</LI>

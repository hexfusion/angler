<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

 // $Id: MyEmailsDashlet.php,v 1.4 2006/08/29 23:12:04 wayne Exp $


require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Emails/Email.php');
require_once('MyEmailsDashlet.data.php');

class MyEmailsDashlet extends DashletGeneric { 
    function MyEmailsDashlet($id, $def = null) {
        global $current_user, $app_strings, $dashletData;
        
        parent::DashletGeneric($id, $def);
        
        if(empty($def['title'])) 
            $this->title = translate('LBL_LIST_TITLE_MY_INBOX', 'Emails').":".translate('LBL_UNREAD_HOME', 'Emails');

        $this->searchFields = $dashletData['MyEmailsDashlet']['searchFields'];
        $this->hasScript = true;  // dashlet has javascript attached to it
        
        $this->columns = $dashletData['MyEmailsDashlet']['columns'];
                
        $this->seedBean = new Email();        
    }
    
    function process() {
        global $current_language, $app_list_strings, $image_path, $current_user;        
        //$where = 'emails.deleted = 0 AND emails.assigned_user_id = \''.$current_user->id.'\' AND emails.type = \'inbound\' AND emails.status = \'unread\'';
        $mod_strings = return_module_language($current_language, 'Emails');
        
        $this->filters['assigned_user_id'] = $current_user->id;
        $this->filters['type'] = array("inbound");
        $this->filters['status'] = array("unread");
   
        parent::process();
    }
    
    function displayScript() { 
        global $current_language;
        
        $mod_strings = return_module_language($current_language, 'Emails');
        $script = <<<EOQ
        <script>
        function quick_create_overlib(id, theme) {
            return overlib('<a style=\'width: 150px\' class=\'menuItem\' onmouseover=\'hiliteItem(this,"yes");\' onmouseout=\'unhiliteItem(this);\' href=\'index.php?module=Cases&action=EditView&inbound_email_id=' + id + '\'>' +
            "<img border='0' src='themes/" + theme + "/images/Cases.gif' style='margin-right:5px'>" + '{$mod_strings['LBL_LIST_CASE']}' + '</a>' +
            "<a style='width: 150px' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' href='index.php?module=Leads&action=EditView&inbound_email_id=" + id + "'>" +
                    "<img border='0' src='themes/" + theme + "/images/Leads.gif' style='margin-right:5px'>"
                    + '{$mod_strings['LBL_LIST_LEAD']}' + "</a>" +
             "<a style='width: 150px' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' href='index.php?module=Contacts&action=EditView&inbound_email_id=" + id + "'>" +
                    "<img border='0' src='themes/" + theme + "/images/Contacts.gif' style='margin-right:5px'>"
                    + '{$mod_strings['LBL_LIST_CONTACT']}' + "</a>" +
             "<a style='width: 150px' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' href='index.php?module=Bugs&action=EditView&inbound_email_id=" + id + "'>"+
                    "<img border='0' src='themes/" + theme + "/images/Bugs.gif' style='margin-right:5px'>"            
                    + '{$mod_strings['LBL_LIST_BUG']}' + "</a>" +
             "<a style='width: 150px' class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' href='index.php?module=Tasks&action=EditView&inbound_email_id=" + id + "'>" +
                    "<img border='0' src='themes/" + theme + "/images/Tasks.gif' style='margin-right:5px'>"
                   + '{$mod_strings['LBL_LIST_TASK']}' + "</a>"
            , CAPTION, '{$mod_strings['LBL_QUICK_CREATE']}'
            , STICKY, MOUSEOFF, 3000, CLOSETEXT, '<img border=0 src="themes/' + theme + '/images/close_inline.gif">', WIDTH, 150, CLOSETITLE, SUGAR.language.get('app_strings', 'LBL_ADDITIONAL_DETAILS_CLOSE_TITLE'), CLOSECLICK, FGCLASS, 'olOptionsFgClass', 
            CGCLASS, 'olOptionsCgClass', BGCLASS, 'olBgClass', TEXTFONTCLASS, 'olFontClass', CAPTIONFONTCLASS, 'olOptionsCapFontClass', CLOSEFONTCLASS, 'olOptionsCloseFontClass');
        }
        </script>
EOQ;
        return $script;
    }        
}

?>

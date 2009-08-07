/**
 * SubPanelTiles javascript file
 *
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
 
// $Id: SubPanelTiles.js,v 1.57 2006/09/06 00:52:46 jenny Exp $

var request_id = 0;
var current_child_field = '';
var current_subpanel_url = '';
var child_field_loaded = new Object();
var request_map = new Object();

function get_module_name()
{
	if(typeof(window.document.forms['DetailView']) == 'undefined') {
		return '';
	} else {
		return window.document.forms['DetailView'].elements['module'].value;
	}
}

function get_record_id()
{
	return window.document.forms['DetailView'].elements['record'].value;
}
function get_layout_def_key()
{
	if(typeof(window.document.forms['DetailView'].elements['layout_def_key']) == 'undefined')return '';
	return window.document.forms['DetailView'].elements['layout_def_key'].value;
}

function save_finished(args)
{
	var child_field = request_map[args.request_id];
	delete (child_field_loaded[child_field] );
	showSubPanel(child_field);
}

function set_return_and_save_background(popup_reply_data)
{
	var form_name = popup_reply_data.form_name;
	var name_to_value_array = popup_reply_data.name_to_value_array;
	var passthru_data = popup_reply_data.passthru_data;
	// construct the POST request
	var query_array =  new Array();
	if (name_to_value_array != 'undefined') {
		for (var the_key in name_to_value_array)
		{
			if(the_key == 'toJSON')
			{
				/* just ignore */
			}
			else
			{
				query_array.push(the_key+"="+name_to_value_array[the_key]);
			}
		}
	}
  	//construct the muulti select list
	var selection_list = popup_reply_data.selection_list;
	if (selection_list != 'undefined') {
		for (var the_key in selection_list)
		{
			query_array.push('subpanel_id[]='+selection_list[the_key])
		}  	
	}
	var module = get_module_name();
	var id = get_record_id();

	query_array.push('value=DetailView');
	query_array.push('module='+module);
	query_array.push('http_method=get');
	query_array.push('return_module='+module);
	query_array.push('return_id='+id);
	query_array.push('record='+id);
	query_array.push('isDuplicate=false');
	query_array.push('action=Save2');
	query_array.push('inline=1');
	var refresh_page = escape(passthru_data['refresh_page']);
	for (prop in passthru_data) {
		if (prop=='link_field_name') {
			query_array.push('subpanel_field_name='+escape(passthru_data[prop]));	
		} else {
			if (prop=='module_name') {
				query_array.push('subpanel_module_name='+escape(passthru_data[prop]));	
			} else {
				query_array.push(prop+'='+escape(passthru_data[prop]));	
			}
		}
	}	

	var query_string = query_array.join('&');
	request_map[request_id] = passthru_data['child_field'];

	var returnstuff = http_fetch_sync('index.php',query_string);
	request_id++;
 	got_data(returnstuff, true);
 	if(refresh_page == 1){
 		document.location.reload(true);
 	}
}

function got_data(args, inline)
{
	var list_subpanel = document.getElementById('list_subpanel_'+request_map[args.request_id].toLowerCase());
	//this function assumes that we are always working with a subpanel..
	//add a null check to prevent failures when we are not.
	if (list_subpanel != null) {
		var subpanel = document.getElementById('subpanel_'+request_map[args.request_id].toLowerCase());
		var child_field = request_map[args.request_id].toLowerCase();
		if(inline){
			child_field_loaded[child_field] = 2;
			list_subpanel.innerHTML='';
			list_subpanel.innerHTML=args.responseText;
		}else{
			child_field_loaded[child_field] = 1;
			subpanel.innerHTML='';
			subpanel.innerHTML=args.responseText;
			
			/* walk into the DOM and insert the list_subpanel_* div */
			var inlineTable = subpanel.getElementsByTagName('table');
			inlineTable = inlineTable[1];
			inlineTable = subpanel.removeChild(inlineTable);
			var listDiv = document.createElement('div');
			listDiv.id = 'list_subpanel_'+request_map[args.request_id].toLowerCase();
			subpanel.appendChild(listDiv);
			listDiv.appendChild(inlineTable);
		}
		subpanel.style.display = '';
		set_div_cookie(subpanel.cookie_name, '');

		if (current_child_field != '' && child_field != current_child_field)
		{
			// commented out for now.  this was originally used by tab UI of subpanels
			//hideSubPanel(current_child_field);
		}
		current_child_field = child_field;
	}
}

function showSubPanel(child_field,url,force_load)
{
	var inline = 1;
	if(!child_field_loaded[child_field.toLowerCase()]){
		inline = 0;
	}
	if ( typeof(force_load) == 'undefined')
	{
		force_load = false;
	}
	
	if (force_load || typeof( child_field_loaded[child_field] ) == 'undefined')
	{
		request_map[request_id] = child_field;
		if ( typeof (url) == 'undefined' || url == null)
		{
			var module = get_module_name();
			var id = get_record_id();
			var layout_def_key = get_layout_def_key();
			
			url = 'index.php?sugar_body_only=1&module='+module+'&subpanel='+child_field+'&action=SubPanelViewer&inline=' + inline + '&record='+id + '&layout_def_key='+ layout_def_key;
		}

		if ( url.indexOf('http://') != 0  && url.indexOf('https://') != 0)
		{
			url = ''+url ;
		}

		current_subpanel_url = url;
		// http_fetch_async(url,got_data,request_id++);
		var returnstuff = http_fetch_sync(url+ '&inline=' + inline);
		request_id++;
		got_data(returnstuff, inline);
	}
	else
	{
		var subpanel = document.getElementById('subpanel_'+child_field);
		subpanel.style.display = '';
		
		set_div_cookie(subpanel.cookie_name, '');

		if (current_child_field != '' && child_field != current_child_field)
		{
			hideSubPanel(current_child_field);
		}

		current_child_field = child_field;
	}
	if(typeof(url) != 'undefined' && url.indexOf('refresh_page=1') > 0){
		document.location.reload();
	}

}

function markSubPanelLoaded(child_field){
	child_field_loaded[child_field] = 2;
}
function hideSubPanel(child_field)
{
	var subpanel = document.getElementById('subpanel_'+child_field);
	subpanel.style.display = 'none';
	set_div_cookie(subpanel.cookie_name, 'none');
}
var sub_cookie_name = get_module_name() + '_divs';
var temp = Get_Cookie(sub_cookie_name);
var div_cookies = new Array();

if(temp && typeof(temp) != 'undefined'){
	div_cookies = get_sub_cookies(temp);
}
function set_div_cookie(name, display){
	div_cookies[name] = display;
	Set_Cookie(sub_cookie_name, subs_to_cookie(div_cookies), 3000, false, false,false);
}


function local_open_popup(name, width, height,arg1, arg2, arg3, params)
{
	return open_popup(name, width, height,arg1,arg2,arg3, params);
}

SUGAR.subpanelUtils = function() {
	var originalLayout = null;
	var subpanelContents = new Array();
	
	return {
		// get the current subpanel layout
		getLayout: function(asString, ignoreHidden) {
		    subpanels = document.getElementById('subpanel_list');
		    subpanelIds = new Array();
		    for(wp = 0; wp < subpanels.childNodes.length; wp++) {
		      if(typeof subpanels.childNodes[wp].id != 'undefined' && subpanels.childNodes[wp].id.match(/whole_subpanel_[\w-]*/) && (typeof ignoreHidden == 'undefined' || subpanels.childNodes[wp].style.display != 'none')) {
				subpanelIds.push(subpanels.childNodes[wp].id.replace(/whole_subpanel_/,''));
		      }
		    }
			if(asString) return subpanelIds.join(',');
			else return subpanelIds;
		},

		// called when subpanel is picked up
		onDrag: function(e, id) {
			originalLayout = SUGAR.subpanelUtils.getLayout(true, true);   	
		},
		
		// called when subpanel is dropped
		onDrop: function(e, id) {	
			newLayout = SUGAR.subpanelUtils.getLayout(true, true);
		  	if(originalLayout != newLayout) { // only save if the layout has changed
				SUGAR.subpanelUtils.saveLayout(newLayout);
		  	}
		},
		
		// save the layout of the subpanels  
		saveLayout: function(order) {
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING_LAYOUT'));
			
			if(typeof SUGAR.subpanelUtils.currentSubpanelGroup != 'undefined') {
				var orderList = SUGAR.subpanelUtils.getLayout(false, true);
				var currentGroup = SUGAR.subpanelUtils.currentSubpanelGroup;
			}
			var success = function(data) {
				ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVED_LAYOUT'));
				window.setTimeout('ajaxStatus.hideStatus()', 2000);
				if(typeof SUGAR.subpanelUtils.currentSubpanelGroup != 'undefined') {
					SUGAR.subpanelUtils.reorderSubpanelSubtabs(currentGroup, orderList);
				}
			}
			
			url = 'index.php?module=Home&action=SaveSubpanelLayout&layout=' + order + '&layoutModule=' + currentModule;
			if(typeof SUGAR.subpanelUtils.currentSubpanelGroup != 'undefined') {
				url = url + '&layoutGroup=' + SUGAR.subpanelUtils.currentSubpanelGroup;
			}
			var cObj = YAHOO.util.Connect.asyncRequest('GET', url, {success: success, failure: success});					  
		},
		
		// call for a after a inline create is saved
		inlineSave: function(theForm, subpanel) {
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING'));
			var success = function(data) {
				if(subpanel == 'projects') subpanel = 'project';
				SUGAR.subpanelUtils.cancelCreate('subpanel_' + subpanel);

				var module = get_module_name();
				var id = get_record_id();
				var layout_def_key = get_layout_def_key();
				try {
					eval('result = ' + data.responseText);
				}
				catch (err) {
				}
				if (typeof(result) != 'undefined' && result != null && typeof(result['status']) != 'undefined' && result['status'] !=null && result['status'] == 'dupe') {
					document.location.href = "index.php?" + result['get'];
					return;
				}
				else {
					showSubPanel(subpanel, null, true);
					ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVED'));
					window.setTimeout('ajaxStatus.hideStatus()', 1000);
				}
			}

			YAHOO.util.Connect.setForm(theForm); 			
			var cObj = YAHOO.util.Connect.asyncRequest('POST', 'index.php', {success: success, failure: success});					  
			return false;
		},

		sendAndRetrieve: function(theForm, theDiv, loadingStr, subpanel) {
			function success(data) {
				theDivObj = document.getElementById(theDiv);
				subpanelContents[theDiv] = new Array();
				subpanelContents[theDiv]['list'] = theDivObj;

				
				subpanelContents[theDiv]['newDiv'] = document.createElement('div');
				subpanelContents[theDiv]['newDiv'].innerHTML = data.responseText; // fill the div
				
				theDivObj.style.display = 'none';
				theDivObj.parentNode.insertBefore(subpanelContents[theDiv]['newDiv'], theDivObj);
				
				// if IE, evaluate the script on return
				if(isIE) SUGAR.util.evalScript(data.responseText);
				ajaxStatus.hideStatus();
			}
			if(typeof loadingStr == 'undefined') loadingStr = SUGAR.language.get('app_strings', 'LBL_LOADING');
			ajaxStatus.showStatus(loadingStr);
			YAHOO.util.Connect.setForm(theForm); 
			var cObj = YAHOO.util.Connect.asyncRequest('POST', 'index.php', {success: success, failure: success});
			return false;
		},
		
		cancelCreate: function(theDiv) {
			subpanelContents[theDiv]['newDiv'].parentNode.removeChild(subpanelContents[theDiv]['newDiv']);
			subpanelContents[theDiv]['list'].style.display = '';

			return false;
		},
		
		loadSubpanelGroupFromMore: function(group){
			SUGAR.subpanelUtils.updateSubpanelMoreTab(group);
			SUGAR.subpanelUtils.loadSubpanelGroup(group);
		},
		
		updateSubpanelMoreTab: function(group){
			// Update Tab
			var moreTab = document.getElementById(SUGAR.subpanelUtils.subpanelMoreTab + '_sp_tab');
			moreTab.id = group + '_sp_tab';
			moreTab.getElementsByTagName('a')[0].innerHTML = group;
			moreTab.getElementsByTagName('a')[0].href = "javascript:SUGAR.subpanelUtils.loadSubpanelGroup('"+group+"');";
			
			// Update Menu
			var menuLink = document.getElementById(group+'_sp_mm');
			menuLink.id = SUGAR.subpanelUtils.subpanelMoreTab+'_sp_mm';
			menuLink.href = "javascript:SUGAR.subpanelUtils.loadSubpanelGroupFromMore('"+SUGAR.subpanelUtils.subpanelMoreTab+"');";
			menuLink.innerHTML = SUGAR.subpanelUtils.subpanelMoreTab;
			
			SUGAR.subpanelUtils.subpanelMoreTab = group;
		},
		
		/* loadSubpanels:
		/* construct set of needed subpanels */
		/* if we have not yet loaded this subpanel group, */
		/*     set loadedGroups[group] */
		/*     for each subpanel in subpanelGroups[group] */
		/*         if document.getElementById('whole_subpanel_'+subpanel) doesn't exist */
		/*         then add subpanel to set of needed subpanels */
		/*     if we need to load any subpanels, send a request for them */
		/*	      with updateSubpanels as the callback. */
		/* otherwise call updateSubpanels */
		/* call setGroupCookie */
		
		loadSubpanelGroup: function(group){
			if(group == SUGAR.subpanelUtils.currentSubpanelGroup) return;
			if(SUGAR.subpanelUtils.loadedGroups[group]){
				SUGAR.subpanelUtils.updateSubpanel(group);
			}else{
				SUGAR.subpanelUtils.loadedGroups.push(group);
				var needed = Array();
				for(group_sp in SUGAR.subpanelUtils.subpanelGroups[group]){
					if(!document.getElementById('whole_subpanel_'+SUGAR.subpanelUtils.subpanelGroups[group][group_sp])){
						needed.push(SUGAR.subpanelUtils.subpanelGroups[group][group_sp]);
					}
				}
				var success = function(){
					SUGAR.subpanelUtils.updateSubpanelEventHandlers(needed);
					SUGAR.subpanelUtils.updateSubpanels(group);
				};
				/* needed to retrieve each of the specified subpanels and install them ...*/
				/* load them in bulk, insert via innerHTML, then sort nodes later. */
				if(needed.length){
					ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_LOADING'));
					SUGAR.util.retrieveAndFill(SUGAR.subpanelUtils.requestUrl + needed.join(','),'subpanel_list', null, success, null, true);
				}else{
					SUGAR.subpanelUtils.updateSubpanels(group);
				}
			}
			SUGAR.subpanelUtils.setGroupCookie(group);
		},
		
		/* updateSubpanels:
		/* for each child node of subpanel_list */
		/*     let subpanel name be id.match(/whole_subpanel_(\w*)/) */
		/*     if the subpanel name is in the list of subpanels for the current group, show it */
		/*     otherwise hide it */
		/* swap nodes to suit user's order */
		/* call updateSubpanelTabs */
		
		updateSubpanels: function(group){
			var sp_list = document.getElementById('subpanel_list');
			for(sp in sp_list.childNodes){
				if(sp_list.childNodes[sp].id){
					sp_list.childNodes[sp].style.display = 'none';
				}
			}
			for(group_sp in SUGAR.subpanelUtils.subpanelGroups[group]){
				var cur = document.getElementById('whole_subpanel_'+SUGAR.subpanelUtils.subpanelGroups[group][group_sp]);
				cur.style.display = 'block';
				/* use YDD swapNodes this and first, second, etc. */
				try{
					YAHOO.util.DDM.swapNode(cur, sp_list.getElementsByTagName('LI')[group_sp]);
				}catch(e){
					
				}
			}
			SUGAR.subpanelUtils.updateSubpanelTabs(group);
		},
		
		updateSubpanelTabs: function(group){
			if(SUGAR.subpanelUtils.showLinks){
				SUGAR.subpanelUtils.updateSubpanelSubtabs(group);
				document.getElementById('subpanelSubTabs').innerHTML = SUGAR.subpanelUtils.subpanelSubTabs[group];
			}
			
			oldTab = document.getElementById(SUGAR.subpanelUtils.currentSubpanelGroup+'_sp_tab');
			if(oldTab){
				oldTab.className = '';
				oldTab.getElementsByTagName('a')[0].className = '';
			}
			
			mainTab = document.getElementById(group+'_sp_tab');
			mainTab.className = 'active';
			mainTab.getElementsByTagName('a')[0].className = 'current';
			
			SUGAR.subpanelUtils.currentSubpanelGroup = group;
			ajaxStatus.hideStatus();
		},
	
		updateSubpanelEventHandlers: function(){
			if(SubpanelInitTabNames){
				SubpanelInitTabNames(SUGAR.subpanelUtils.getLayout(false));
			}
		},
		
		reorderSubpanelSubtabs: function(group, order){
			SUGAR.subpanelUtils.subpanelGroups[group] = order;
			if(SUGAR.subpanelUtils.showLinks==1){
				SUGAR.subpanelUtils.updateSubpanelSubtabs(group);
				if(SUGAR.subpanelUtils.currentSubpanelGroup == group){
					document.getElementById('subpanelSubTabs').innerHTML = SUGAR.subpanelUtils.subpanelSubTabs[group];
				}
			}
		},
		
		// Re-renders the contents of subpanelSubTabs[group].
		// Does not immediately affect what's on the screen.
		updateSubpanelSubtabs: function(group){
			var notFirst = 0;
			var preMore = SUGAR.subpanelUtils.subpanelGroups[group].slice(0, SUGAR.subpanelUtils.subpanelMaxSubtabs);
			
			SUGAR.subpanelUtils.subpanelSubTabs[group] = '<table border="0" cellpadding="0" cellspacing="0" height="20" width="100%" class="subTabs"><tr>';
			
			for(var sp_key = 0; sp_key < preMore.length; sp_key++){
				if(notFirst != 0){
					SUGAR.subpanelUtils.subpanelSubTabs[group] += '<td width="1"> | </td>';
				}else{
					notFirst = 1;
				}
				SUGAR.subpanelUtils.subpanelSubTabs[group] += '<td nowrap="nowrap"><a href="#'+preMore[sp_key]+'" class="subTabLink">'+SUGAR.subpanelUtils.subpanelTitles[preMore[sp_key]]+'</a></td>';
			}
			if(document.getElementById('MoreSub'+group+'PanelMenu')){
				SUGAR.subpanelUtils.subpanelSubTabs[group] += '<td nowrap="nowrap"> | &nbsp;<span class="subTabMore" id="MoreSub'+group+'PanelHandle" style="margin-left:2px; cursor: pointer; cursor: hand;" align="absmiddle" onmouseover="SUGAR.subpanelUtils.menu.tbspButtonMouseOver(this.id,\'\',\'\',0);">&gt;&gt;</span></td>';
			}
			SUGAR.subpanelUtils.subpanelSubTabs[group] += '<td width="100%">&nbsp;</td></tr></table>';
			
			// Update the more menu for the current group
			var postMore = SUGAR.subpanelUtils.subpanelGroups[group].slice(SUGAR.subpanelUtils.subpanelMaxSubtabs);
			var subpanelMenu = document.getElementById('MoreSub'+group+'PanelMenu');
			
			if(postMore && subpanelMenu){
				subpanelMenu.innerHTML = '';
				for(var sp_key = 0; sp_key < postMore.length; sp_key++){
					subpanelMenu.innerHTML += '<a href="#'+postMore[sp_key]+'" class="menuItem" parentid="MoreSub'+group+'PanelMenu" onmouseover="hiliteItem(this,\'yes\'); closeSubMenus(this);" onmouseout="unhiliteItem(this);">'+SUGAR.subpanelUtils.subpanelTitles[postMore[sp_key]]+'</a>';
				}
				subpanelMenu += '</div>';
			}
		},
		
		setGroupCookie: function(group){
			Set_Cookie(SUGAR.subpanelUtils.tabCookieName, group, 3000, false, false,false);
		}
	};
}();

SUGAR.subpanelUtils.menu = function(){
	return {
		tbspButtonMouseOver : function(id,top,left,leftOffset){ //*//
			closeMenusDelay = eraseTimeout(closeMenusDelay);
			if (openMenusDelay == null){
				openMenusDelay = window.setTimeout("SUGAR.subpanelUtils.menu.spShowMenu('"+id+"','"+top+"','"+left+"','"+leftOffset+"')", delayTime);
			}
		},
		spShowMenu : function(id,top,left,leftOffset){ //*//
			openMenusDelay = eraseTimeout(openMenusDelay);
			var menuName = id.replace(/Handle/i,'Menu');
			var menu = getLayer(menuName);
			//if (menu) menu.className = 'tbButtonMouseOverUp';
			if (currentMenu){
				closeAllMenus();
			}
			SUGAR.subpanelUtils.menu.spPopupMenu(id, menu, top,left,leftOffset);
		},
		spPopupMenu : function(handleID, menu, top, left, leftOffset){ //*//
			var bw = checkBrowserWidth();
			var menuName = handleID.replace(/Handle/i,'Menu');
			var menuWidth = 120;
			var imgWidth = document.getElementById(handleID).width;
			if (menu){
				var menuHandle = getLayer(handleID);
				var p=menuHandle;
				if (left == "") {
					var left = 0;
					while(p&&p.tagName.toUpperCase()!='BODY'){
						left+=p.offsetLeft;
						p=p.offsetParent;
					}
					left+=parseInt(leftOffset);
				}
				if (top == "") {
					var top = 0;
					p=menuHandle;
					top+=p.offsetHeight;
					while(p&&p.tagName.toUpperCase()!='BODY'){
						top+=p.offsetTop;
						p=p.offsetParent;
					}
				}
				if (left+menuWidth>bw) {
					left = left-menuWidth+imgWidth;
				}
				setMenuVisible(menu, left, top, false);
			}
		}
	};
}();

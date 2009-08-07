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

// $Id: home.js,v 1.25 2006/08/22 22:08:37 awu Exp $
SUGAR.sugarHome = function() {
	var originalLayout = null;
	var configureDashletId = null;
	var currentDashlet = null;
	var leftColumnInnerHTML = null;
	var leftColObj = null;
	var maxCount;
	var warningLang;
	
	return {
		// get the current dashlet layout
		getLayout: function(asString) {
			columns = new Array();
			for(je = 0; je < 2; je++) {
			    dashlets = document.getElementById('col' + je);
			
			    dashletIds = new Array();
			    for(wp = 0; wp < dashlets.childNodes.length; wp++) {
			      if(typeof dashlets.childNodes[wp].id != 'undefined' && dashlets.childNodes[wp].id.match(/dashlet_[\w-]*/)) {
					dashletIds.push(dashlets.childNodes[wp].id.replace(/dashlet_/,''));
			      }
			    }
				if(asString) columns[je] = dashletIds.join(',');
				else columns[je] = dashletIds;
			}
			if(asString) return columns.join('|');
			else return columns;
		},

		// called when dashlet is picked up
		onDrag: function(e, id) {
			originalLayout = SUGAR.sugarHome.getLayout(true);   	
		},
		
		// called when dashlet is dropped
		onDrop: function(e, id) {	
			newLayout = SUGAR.sugarHome.getLayout(true);
		  	if(originalLayout != newLayout) { // only save if the layout has changed
				SUGAR.sugarHome.saveLayout(newLayout);
		  	}
		},
		
		// save the layout of the dashlet  
		saveLayout: function(order) {
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVING_LAYOUT'));
			var success = function(data) {
				ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_SAVED_LAYOUT'));
				window.setTimeout('ajaxStatus.hideStatus()', 2000);
			}
			
			url = 'index.php?module=Home&action=SaveLayout&layout=' + order;
			var cObj = YAHOO.util.Connect.asyncRequest('GET', url, {success: success, failure: success});					  
		},

		uncoverPage: function($id) {
			configureDlg.hide();
			SUGAR.sugarHome.retrieveDashlet(SUGAR.sugarHome.configureDashletId);
		},
		
		// call to configure a Dashlet
		configureDashlet: function(id) {
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_LOADING'));
			configureDlg = new YAHOO.widget.SimpleDialog("dlg", { visible:false, width: "510", effect:[{effect:YAHOO.widget.ContainerEffect.SLIDETOP, duration:0.5},{effect:YAHOO.widget.ContainerEffect.FADE,duration:0.5}], fixedcenter:true, modal:true, draggable:false});
			
			fillInConfigureDiv = function(data) {
				ajaxStatus.hideStatus();
				// uncomment the line below to debug w/ FireBug
				// console.log(data.responseText); 
				try {
					eval(data.responseText);
				}
				catch(e) {
					result = new Array();
					result['header'] = 'error';
					result['body'] = 'There was an error handling this request.';
				}
				configureDlg.setHeader(result['header']);
				configureDlg.setBody(result['body']);
				var listeners = new YAHOO.util.KeyListener(document, { keys : 27 }, {fn: function() {this.hide();}, scope: configureDlg, correctScope:true} );
				configureDlg.cfg.queueProperty("keylisteners", listeners);

				configureDlg.render(document.body);
				configureDlg.show();
				configureDlg.configFixedCenter(null, false) ;
				SUGAR.util.evalScript(result['body']);
			}

			SUGAR.sugarHome.configureDashletId = id; // save the id of the dashlet being configured
			var cObj = YAHOO.util.Connect.asyncRequest('GET','index.php?to_pdf=1&module=Home&action=ConfigureDashlet&id=' + id, 
													  {success: fillInConfigureDiv, failure: fillInConfigureDiv}, null);
		
		},
				
		/** returns dashlets contents
		 * if url is defined, dashlet will be retrieve with it, otherwise use default url
		 *
		 * @param string id id of the dashlet to refresh
		 * @param string url url to be used
		 * @param function callback callback function after refresh
		 * @param bool dynamic does the script load dynamic javascript, set to true if you user needs to refresh the dashlet after load
		 */
		retrieveDashlet: function(id, url, callback, dynamic) {
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_LOADING'));
			if(!url) {
				url = 'index.php?action=DisplayDashlet&module=Home&to_pdf=1&id=' + id;
			}
			if(dynamic) {
				url += '&dynamic=true';
			}

		 	var fillInDashlet = function(data) {
		 		ajaxStatus.hideStatus();
				if(data) {
					SUGAR.sugarHome.currentDashlet.innerHTML = data.responseText;
				}
				SUGAR.util.evalScript(data.responseText);
				if(callback) callback();
			}
			
			SUGAR.sugarHome.currentDashlet = document.getElementById('dashlet_entire_' + id);
			var cObj = YAHOO.util.Connect.asyncRequest('GET', url,
												  {success: fillInDashlet, failure: fillInDashlet}, null);
			return false;
		},
		
		// for the display columns widget
		setChooser: function() {		
			var displayColumnsDef = new Array();
			var hideTabsDef = new Array();

		    var left_td = document.getElementById('display_tabs_td');	
		    var right_td = document.getElementById('hide_tabs_td');			
	
		    var displayTabs = left_td.getElementsByTagName('select')[0];
		    var hideTabs = right_td.getElementsByTagName('select')[0];
			
			for(i = 0; i < displayTabs.options.length; i++) {
				displayColumnsDef.push(displayTabs.options[i].value);
			}
			
			if(typeof hideTabs != 'undefined') {
				for(i = 0; i < hideTabs.options.length; i++) {
			         hideTabsDef.push(hideTabs.options[i].value);
				}
			}
			
			document.getElementById('displayColumnsDef').value = displayColumnsDef.join('|');
			document.getElementById('hideTabsDef').value = hideTabsDef.join('|');
		},
		
		deleteDashlet: function(id) {
			if(confirm(SUGAR.language.get('Home', 'LBL_REMOVE_DASHLET_CONFIRM'))) {
				ajaxStatus.showStatus(SUGAR.language.get('Home', 'LBL_REMOVING_DASHLET'));
				
				del = function() {
					var success = function(data) {
						dashlet = document.getElementById('dashlet_' + id);
						dashlet.parentNode.removeChild(dashlet);
						ajaxStatus.showStatus(SUGAR.language.get('Home', 'LBL_REMOVED_DASHLET'));
						window.setTimeout('ajaxStatus.hideStatus()', 2000);
					}
				
					
					var cObj = YAHOO.util.Connect.asyncRequest('GET','index.php?to_pdf=1&module=Home&action=DeleteDashlet&id=' + id, 
															  {success: success, failure: success}, null);
				}
				
				var anim = new YAHOO.util.Anim('dashlet_entire_' + id, { height: {to: 1} }, .5 );					
				anim.onComplete.subscribe(del);					
				document.getElementById('dashlet_entire_' + id).style.overflow = 'hidden';
				anim.animate();
				
				return false;
			}
			return false;
		},
		
		addDashlet: function(id) {
			columns = SUGAR.sugarHome.getLayout();
			if((columns[0].length + columns[1].length) >= SUGAR.sugarHome.maxCount) {
				alert(SUGAR.language.get('Home', 'LBL_MAX_DASHLETS_REACHED'));
				return;
			}
			ajaxStatus.showStatus(SUGAR.language.get('Home', 'LBL_ADDING_DASHLET'));
			var success = function(data) {
				colZero = document.getElementById('col0');
				newDashlet = document.createElement('li'); // build the list item
				newDashlet.id = 'dashlet_' + data.responseText;
				newDashlet.className = 'noBullet';
				// hide it first, but append to getRegion
				newDashlet.innerHTML = '<div style="position: absolute; top: -1000px; overflow: hidden;" id="dashlet_entire_' + data.responseText + '"></div>';

				colZero.insertBefore(newDashlet, colZero.firstChild); // insert it into the first column
				
				var finishRetrieve = function() {
					dashletEntire = document.getElementById('dashlet_entire_' + data.responseText);
					dd = new ygDDList('dashlet_' + data.responseText); // make it draggable
					dd.setHandleElId('dashlet_header_' + data.responseText);
					dd.onMouseDown = SUGAR.sugarHome.onDrag;  
					dd.onDragDrop = SUGAR.sugarHome.onDrop;

					ajaxStatus.showStatus(SUGAR.language.get('Home', 'LBL_ADDED_DASHLET'));
					dashletRegion = YAHOO.util.Dom.getRegion(dashletEntire);
					dashletEntire.style.position = 'relative';
					dashletEntire.style.height = '1px';
					dashletEntire.style.top = '0px';

					var anim = new YAHOO.util.Anim('dashlet_entire_' + data.responseText, { height: {to: dashletRegion.bottom - dashletRegion.top} }, .5 );
					anim.onComplete.subscribe(function() { document.getElementById('dashlet_entire_' + data.responseText).style.height = '100%'; });	
					anim.animate();
					
					window.setTimeout('ajaxStatus.hideStatus()', 2000);
				}
				SUGAR.sugarHome.retrieveDashlet(data.responseText, null, finishRetrieve, true); // retrieve it from the server
				
				
			}
			var cObj = YAHOO.util.Connect.asyncRequest('GET','index.php?to_pdf=1&module=Home&action=AddDashlet&id=' + id, 
													  {success: success, failure: success}, null);
			return false;
		},
		
		showDashletsTree: function() {
			columns = SUGAR.sugarHome.getLayout();
			if((columns[0].length + columns[1].length) >= SUGAR.sugarHome.maxCount) {
				alert(SUGAR.language.get('Home', 'LBL_MAX_DASHLETS_REACHED'));
				return;
			}
			ajaxStatus.showStatus(SUGAR.language.get('app_strings', 'LBL_LOADING'));
			document.getElementById('add_dashlets').style.display = 'none';
			// grab the td that contains the entire left column
			leftColObj = SUGAR.util.getLeftColObj();
			leftColumnInnerHTML = leftColObj.innerHTML;
			var success = function(data) {
				eval(data.responseText);
				
				/* Open the leftCol if it's hidden */
				leftColDiv = document.getElementById('leftCol');
				if(leftColDiv.style.display = 'none') {
					hideLeftCol("leftCol");
				}
				
				leftColObj.innerHTML = response['html'];
				eval(response['script']);
				ajaxStatus.hideStatus();
			}
			var cObj = YAHOO.util.Connect.asyncRequest('GET', 'index.php?to_pdf=true&module=Home&action=DashletsTree', {success: success, failure: success});			
			return false;
		},
		
		doneAddDashlets: function() {
			document.getElementById('add_dashlets').style.display = '';
			leftColObj.innerHTML = leftColumnInnerHTML;
			return false;
		}
		
	 };
	 
}();

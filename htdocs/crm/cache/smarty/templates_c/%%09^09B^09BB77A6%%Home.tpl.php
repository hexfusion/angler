<?php /* Smarty version 2.6.11, created on 2007-03-18 19:13:49
         compiled from modules/Home/Home.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'modules/Home/Home.tpl', 60, false),)), $this); ?>


<script type="text/javascript" src="include/javascript/overlibmws.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
"></script>
<script type="text/javascript" src="include/javascript/overlibmws_iframe.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
"></script>
<script type="text/javascript" src="include/javascript/dashlets.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
"></script>
<script type="text/javascript" src="include/javascript/yui/container.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
"></script>
<script type="text/javascript" src="include/javascript/yui/PanelEffect.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
"></script>
<script type="text/javascript" src='include/ytree/TreeView/TreeView.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
'></script>
<script type="text/javascript" src='include/ytree/TreeView/Node.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
'></script>
<script type="text/javascript" src='include/ytree/TreeView/TextNode.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
'></script>
<script type="text/javascript" src='include/ytree/TreeView/RootNode.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
'></script>
<script type="text/javascript" src='include/ytree/treeutil.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
'></script>
<script type="text/javascript" src='include/JSON.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
'></script>
<script type='text/javascript' src='modules/Home/home.js?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
'></script>
<link rel='stylesheet' href='include/ytree/TreeView/css/folders/tree.css?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
'>
<link rel='stylesheet' href='include/javascript/yui/assets/container.css?s=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&c=<?php echo $this->_tpl_vars['jsCustomVersion']; ?>
'>

<table cellspacing='5' cellpadding='0' border='0' valign='top' width='100%'>
	<tr><td align='left'>
	<?php if (! $this->_tpl_vars['lock_homepage']): ?>
		<input type='button' class='button'' id='add_dashlets' onclick='return SUGAR.sugarHome.showDashletsTree();' value='<?php echo $this->_tpl_vars['lblAddDashlets']; ?>
'>
	<?php endif; ?>
	&nbsp;
	</td>
	<td align='right'>
		<a href='#' onclick="window.open('index.php?module=Administration&action=SupportPortal&view=documentation&version=<?php echo $this->_tpl_vars['sugarVersion']; ?>
&edition=<?php echo $this->_tpl_vars['sugarFlavor']; ?>
&lang=<?php echo $this->_tpl_vars['currentLanguage']; ?>
&help_module=Home&help_action=index&key=<?php echo $this->_tpl_vars['serverUniqueKey']; ?>
','helpwin','width=600,height=600,status=0,resizable=1,scrollbars=1,toolbar=0,location=1'); return false" class='utilsLink'>
			<img src='<?php echo $this->_tpl_vars['imagePath']; ?>
help.gif' width='13' height='13' alt=<?php echo $this->_tpl_vars['lblLnkHelp']; ?>
' border='0' align='absmiddle'>
			<?php echo $this->_tpl_vars['lblLnkHelp']; ?>

		</a>
	</td>
	</tr>
	<tr>
		<?php echo smarty_function_counter(array('assign' => 'hiddenCounter','start' => 0,'print' => false), $this);?>

		<?php $_from = $this->_tpl_vars['columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['colNum'] => $this->_tpl_vars['data']):
?>
		<td valign='top' width=<?php echo $this->_tpl_vars['data']['width']; ?>
>
			<ul class='noBullet' id='col<?php echo $this->_tpl_vars['colNum']; ?>
'>
				<li id='hidden<?php echo $this->_tpl_vars['hiddenCounter']; ?>
b' style='height: 5px' class='noBullet'>&nbsp;&nbsp;&nbsp;</li>
		        <?php $_from = $this->_tpl_vars['data']['dashlets']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['dashlet']):
?>		
				<li class='noBullet' id='dashlet_<?php echo $this->_tpl_vars['id']; ?>
'>
					<div id='dashlet_entire_<?php echo $this->_tpl_vars['id']; ?>
'>
						<?php echo $this->_tpl_vars['dashlet']['script']; ?>

						<?php echo $this->_tpl_vars['dashlet']['display']; ?>

					</div>
				</li>
				<?php endforeach; endif; unset($_from); ?>
				<li id='hidden<?php echo $this->_tpl_vars['hiddenCounter']; ?>
' style='height: 5px' class='noBullet'>&nbsp;&nbsp;&nbsp;</li>
			</ul>
		</td>
		<?php echo smarty_function_counter(array(), $this);?>

		<?php endforeach; endif; unset($_from); ?>
	</tr>
</table>
<?php if (! $this->_tpl_vars['lock_homepage']): ?>
<?php echo '
<script type="text/javascript">
SUGAR.sugarHome.maxCount = 	';  echo $this->_tpl_vars['maxCount'];  echo ';
SUGAR.sugarHome.init = function () {
	homepage_dd = new Array();
	j = 0;
	'; ?>

	dashletIds = <?php echo $this->_tpl_vars['dashletIds']; ?>
;
	<?php echo '
	for(i in dashletIds) {
		homepage_dd[j] = new ygDDList(\'dashlet_\' + dashletIds[i]);
		homepage_dd[j].setHandleElId(\'dashlet_header_\' + dashletIds[i]);
		homepage_dd[j].onMouseDown = SUGAR.sugarHome.onDrag;  
		homepage_dd[j].afterEndDrag = SUGAR.sugarHome.onDrop;
		j++;
	}
	for(var wp = 0; wp <= ';  echo $this->_tpl_vars['hiddenCounter'];  echo '; wp++) {
	    homepage_dd[j++] = new ygDDListBoundary(\'hidden\' + wp);
	}

	YAHOO.util.DDM.mode = 1;
}

YAHOO.util.Event.addListener(window, \'load\', SUGAR.sugarHome.init);  

</script>
'; ?>

<?php endif; ?>
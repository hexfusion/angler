<?php /* Smarty version 2.6.11, created on 2006-09-15 15:40:04
         compiled from include/Dashlets/DashletGenericDisplay.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'include/Dashlets/DashletGenericDisplay.tpl', 66, false),array('modifier', 'lower', 'include/Dashlets/DashletGenericDisplay.tpl', 68, false),array('function', 'sugar_translate', 'include/Dashlets/DashletGenericDisplay.tpl', 68, false),array('function', 'counter', 'include/Dashlets/DashletGenericDisplay.tpl', 104, false),array('function', 'sugar_evalcolumn', 'include/Dashlets/DashletGenericDisplay.tpl', 110, false),array('function', 'sugar_currency_format', 'include/Dashlets/DashletGenericDisplay.tpl', 112, false),)), $this); ?>


<table cellpadding='0' cellspacing='0' width='100%' border='0' class='listView'>
    <tr>
        <td colspan='<?php echo $this->_tpl_vars['colCount']+1; ?>
' align='right'>
            <table border='0' cellpadding='0' cellspacing='0' width='100%'>
                <tr>
                    <td align='left' class='listViewPaginationTdS1'>&nbsp;</td>
                    <td class='listViewPaginationTdS1' align='right' nowrap='nowrap' id='listViewPaginationButtons'>                    
                        <?php if ($this->_tpl_vars['pageData']['urls']['startPage']): ?>
                            <a href='#' onclick='return SUGAR.sugarHome.retrieveDashlet("<?php echo $this->_tpl_vars['dashletId']; ?>
", "<?php echo $this->_tpl_vars['pageData']['urls']['startPage']; ?>
")' class='listViewPaginationLinkS1'><img src='<?php echo $this->_tpl_vars['imagePath']; ?>
start.gif' alt='<?php echo $this->_tpl_vars['navStrings']['start']; ?>
' align='absmiddle' border='0' width='13' height='11'>&nbsp;<?php echo $this->_tpl_vars['navStrings']['start']; ?>
</a>&nbsp;
                        <?php else: ?>
                            <img src='<?php echo $this->_tpl_vars['imagePath']; ?>
start_off.gif' alt='<?php echo $this->_tpl_vars['navStrings']['start']; ?>
' align='absmiddle' border='0' width='13' height='11'>&nbsp;<?php echo $this->_tpl_vars['navStrings']['start']; ?>
&nbsp;&nbsp;
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['pageData']['urls']['prevPage']): ?>
                            <a href='#' onclick='return SUGAR.sugarHome.retrieveDashlet("<?php echo $this->_tpl_vars['dashletId']; ?>
", "<?php echo $this->_tpl_vars['pageData']['urls']['prevPage']; ?>
")' class='listViewPaginationLinkS1'><img src='<?php echo $this->_tpl_vars['imagePath']; ?>
previous.gif' alt='<?php echo $this->_tpl_vars['navStrings']['previous']; ?>
' align='absmiddle' border='0' width='8' height='11'>&nbsp;<?php echo $this->_tpl_vars['navStrings']['previous']; ?>
</a>&nbsp;
                        <?php else: ?>
                            <img src='<?php echo $this->_tpl_vars['imagePath']; ?>
previous_off.gif' alt='<?php echo $this->_tpl_vars['navStrings']['previous']; ?>
' align='absmiddle' border='0' width='8' height='11'>&nbsp;<?php echo $this->_tpl_vars['navStrings']['previous']; ?>
&nbsp;
                        <?php endif; ?>
                            <span class='pageNumbers'>(<?php if ($this->_tpl_vars['pageData']['offsets']['lastOffsetOnPage'] == 0): ?>0<?php else:  echo $this->_tpl_vars['pageData']['offsets']['current']+1;  endif; ?> - <?php echo $this->_tpl_vars['pageData']['offsets']['lastOffsetOnPage']; ?>
 <?php echo $this->_tpl_vars['navStrings']['of']; ?>
 <?php if ($this->_tpl_vars['pageData']['offsets']['totalCounted']):  echo $this->_tpl_vars['pageData']['offsets']['total'];  else:  echo $this->_tpl_vars['pageData']['offsets']['total'];  if ($this->_tpl_vars['pageData']['offsets']['lastOffsetOnPage'] != $this->_tpl_vars['pageData']['offsets']['total']): ?>+<?php endif;  endif; ?>)</span>
                        <?php if ($this->_tpl_vars['pageData']['urls']['nextPage']): ?>
                            &nbsp;<a href='#' onclick='return SUGAR.sugarHome.retrieveDashlet("<?php echo $this->_tpl_vars['dashletId']; ?>
", "<?php echo $this->_tpl_vars['pageData']['urls']['nextPage']; ?>
")' class='listViewPaginationLinkS1'><?php echo $this->_tpl_vars['navStrings']['next']; ?>
&nbsp;<img src='<?php echo $this->_tpl_vars['imagePath']; ?>
next.gif' alt='<?php echo $this->_tpl_vars['navStrings']['next']; ?>
' align='absmiddle' border='0' width='8' height='11'></a>&nbsp;
                        <?php else: ?>
                            &nbsp;<?php echo $this->_tpl_vars['navStrings']['next']; ?>
&nbsp;<img src='<?php echo $this->_tpl_vars['imagePath']; ?>
next_off.gif' alt='<?php echo $this->_tpl_vars['navStrings']['next']; ?>
' align='absmiddle' border='0' width='8' height='11'>
                        <?php endif; ?>
						<?php if ($this->_tpl_vars['pageData']['urls']['endPage'] && $this->_tpl_vars['pageData']['offsets']['total'] != $this->_tpl_vars['pageData']['offsets']['lastOffsetOnPage']): ?>
                            <a href='#' onclick='return SUGAR.sugarHome.retrieveDashlet("<?php echo $this->_tpl_vars['dashletId']; ?>
", "<?php echo $this->_tpl_vars['pageData']['urls']['endPage']; ?>
")' class='listViewPaginationLinkS1'><?php echo $this->_tpl_vars['navStrings']['end']; ?>
&nbsp;<img src='<?php echo $this->_tpl_vars['imagePath']; ?>
end.gif' alt='<?php echo $this->_tpl_vars['navStrings']['end']; ?>
' align='absmiddle' border='0' width='13' height='11'></a></td>
						<?php elseif (! $this->_tpl_vars['pageData']['offsets']['totalCounted'] || $this->_tpl_vars['pageData']['offsets']['total'] == $this->_tpl_vars['pageData']['offsets']['lastOffsetOnPage']): ?>
                            &nbsp;<?php echo $this->_tpl_vars['navStrings']['end']; ?>
&nbsp;<img src='<?php echo $this->_tpl_vars['imagePath']; ?>
end_off.gif' alt='<?php echo $this->_tpl_vars['navStrings']['end']; ?>
' align='absmiddle' border='0' width='13' height='11'>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr height='20'>
        <?php $_from = $this->_tpl_vars['displayColumns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['colHeader'] => $this->_tpl_vars['params']):
?>
	        <td scope='col' width='<?php echo $this->_tpl_vars['params']['width']; ?>
%' class='listViewThS1' nowrap>
				<div style='white-space: nowrap;'width='100%' align='<?php echo ((is_array($_tmp=@$this->_tpl_vars['params']['align'])) ? $this->_run_mod_handler('default', true, $_tmp, 'left') : smarty_modifier_default($_tmp, 'left')); ?>
'>
                <?php if (((is_array($_tmp=@$this->_tpl_vars['params']['sortable'])) ? $this->_run_mod_handler('default', true, $_tmp, true) : smarty_modifier_default($_tmp, true))): ?> 
	                <a href='#' onclick='return SUGAR.sugarHome.retrieveDashlet("<?php echo $this->_tpl_vars['dashletId']; ?>
", "<?php echo $this->_tpl_vars['pageData']['urls']['orderBy'];  echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['params']['orderBy'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['colHeader']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['colHeader'])))) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)); ?>
&sugar_body_only=1&id=<?php echo $this->_tpl_vars['dashletId']; ?>
")' class='listViewThLinkS1'><?php echo smarty_function_sugar_translate(array('label' => $this->_tpl_vars['params']['label'],'module' => $this->_tpl_vars['pageData']['bean']['moduleDir']), $this);?>
&nbsp;&nbsp;
	                <?php if (((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['params']['orderBy'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['colHeader']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['colHeader'])))) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)) == $this->_tpl_vars['pageData']['ordering']['orderBy']): ?>
	                    <?php if ($this->_tpl_vars['pageData']['ordering']['sortOrder'] == 'ASC'): ?>
	                        <img border='0' src='<?php echo $this->_tpl_vars['imagePath']; ?>
arrow_down.<?php echo $this->_tpl_vars['arrowExt']; ?>
' width='<?php echo $this->_tpl_vars['arrowWidth']; ?>
' height='<?php echo $this->_tpl_vars['arrowHeight']; ?>
' align='absmiddle' alt='<?php echo $this->_tpl_vars['arrowAlt']; ?>
'>
	                    <?php else: ?>
	                        <img border='0' src='<?php echo $this->_tpl_vars['imagePath']; ?>
arrow_up.<?php echo $this->_tpl_vars['arrowExt']; ?>
' width='<?php echo $this->_tpl_vars['arrowWidth']; ?>
' height='<?php echo $this->_tpl_vars['arrowHeight']; ?>
' align='absmiddle' alt='<?php echo $this->_tpl_vars['arrowAlt']; ?>
'>
	                    <?php endif; ?>
	                <?php else: ?>
	                    <img border='0' src='<?php echo $this->_tpl_vars['imagePath']; ?>
arrow.<?php echo $this->_tpl_vars['arrowExt']; ?>
' width='<?php echo $this->_tpl_vars['arrowWidth']; ?>
' height='<?php echo $this->_tpl_vars['arrowHeight']; ?>
' align='absmiddle' alt='<?php echo $this->_tpl_vars['arrowAlt']; ?>
'>
	                <?php endif; ?>
	                </a>
	           <?php else: ?>
	           		<?php echo smarty_function_sugar_translate(array('label' => $this->_tpl_vars['params']['label'],'module' => $this->_tpl_vars['pageData']['bean']['moduleDir']), $this);?>

	           <?php endif; ?>
			   </div>
            </td>
        <?php endforeach; endif; unset($_from); ?>
		<?php if (! empty ( $this->_tpl_vars['quickViewLinks'] )): ?>
		<td scope='col' class='listViewThS1' nowrap width='1%'>&nbsp;</td>
		<?php endif; ?>
    </tr>
        
	<?php $_from = $this->_tpl_vars['data']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['rowIteration'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['rowIteration']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['id'] => $this->_tpl_vars['rowData']):
        $this->_foreach['rowIteration']['iteration']++;
?>
		<?php if ((1 & $this->_foreach['rowIteration']['iteration'])): ?>
			<?php $this->assign('_bgColor', $this->_tpl_vars['bgColor'][0]); ?>
			<?php $this->assign('_rowColor', $this->_tpl_vars['rowColor'][0]); ?>
		<?php else: ?>
			<?php $this->assign('_bgColor', $this->_tpl_vars['bgColor'][1]); ?>
			<?php $this->assign('_rowColor', $this->_tpl_vars['rowColor'][1]); ?>
		<?php endif; ?>
		<tr height='20' onmouseover="setPointer(this, '<?php echo $this->_tpl_vars['id']; ?>
', 'over', '<?php echo $this->_tpl_vars['_bgColor']; ?>
', '<?php echo $this->_tpl_vars['bgHilite']; ?>
', '');" onmouseout="setPointer(this, '<?php echo ((is_array($_tmp=@$this->_tpl_vars['rowData'][$this->_tpl_vars['params']['id']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['rowData']['ID']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['rowData']['ID'])); ?>
', 'out', '<?php echo $this->_tpl_vars['_bgColor']; ?>
', '<?php echo $this->_tpl_vars['bgHilite']; ?>
', '');" onmousedown="setPointer(this, '<?php echo $this->_tpl_vars['id']; ?>
', 'click', '<?php echo $this->_tpl_vars['_bgColor']; ?>
', '<?php echo $this->_tpl_vars['bgHilite']; ?>
', '');">
			<?php if ($this->_tpl_vars['prerow']): ?>
			<td width='1%' class='<?php echo $this->_tpl_vars['_rowColor']; ?>
S1' bgcolor='<?php echo $this->_tpl_vars['_bgColor']; ?>
' nowrap>
					<input onclick='sListView.check_item(this, document.MassUpdate)' type='checkbox' class='checkbox' name='mass[]' value='<?php echo ((is_array($_tmp=@$this->_tpl_vars['rowData'][$this->_tpl_vars['params']['id']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['rowData']['ID']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['rowData']['ID'])); ?>
'>
			</td>
			<?php endif; ?>
			<?php echo smarty_function_counter(array('start' => 0,'name' => 'colCounter','print' => false,'assign' => 'colCounter'), $this);?>

			<?php $_from = $this->_tpl_vars['displayColumns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['col'] => $this->_tpl_vars['params']):
?>
				<td scope='row' align='<?php echo ((is_array($_tmp=@$this->_tpl_vars['params']['align'])) ? $this->_run_mod_handler('default', true, $_tmp, 'left') : smarty_modifier_default($_tmp, 'left')); ?>
' valign=top class='<?php echo $this->_tpl_vars['_rowColor']; ?>
S1' bgcolor='<?php echo $this->_tpl_vars['_bgColor']; ?>
'><span sugar="sugar<?php echo $this->_tpl_vars['colCounter']; ?>
b">
					<?php if ($this->_tpl_vars['params']['link'] && ! $this->_tpl_vars['params']['customCode']): ?>				
						<<?php echo ((is_array($_tmp=@$this->_tpl_vars['pageData']['tag'][$this->_tpl_vars['id']][$this->_tpl_vars['params']['ACLTag']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['pageData']['tag'][$this->_tpl_vars['id']]['MAIN']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['pageData']['tag'][$this->_tpl_vars['id']]['MAIN'])); ?>
 href='index.php?action=<?php echo ((is_array($_tmp=@$this->_tpl_vars['params']['action'])) ? $this->_run_mod_handler('default', true, $_tmp, 'DetailView') : smarty_modifier_default($_tmp, 'DetailView')); ?>
&module=<?php if ($this->_tpl_vars['params']['dynamic_module']):  echo $this->_tpl_vars['rowData'][$this->_tpl_vars['params']['dynamic_module']];  else:  echo ((is_array($_tmp=@$this->_tpl_vars['params']['module'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['pageData']['bean']['moduleDir']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['pageData']['bean']['moduleDir']));  endif; ?>&record=<?php echo ((is_array($_tmp=@$this->_tpl_vars['rowData'][$this->_tpl_vars['params']['id']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['rowData']['ID']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['rowData']['ID'])); ?>
&offset=<?php echo $this->_tpl_vars['pageData']['offsets']['current']+$this->_foreach['rowIteration']['iteration']; ?>
&stamp=<?php echo $this->_tpl_vars['pageData']['stamp']; ?>
' class='listViewTdLinkS1'><?php echo $this->_tpl_vars['rowData'][$this->_tpl_vars['col']]; ?>
</<?php echo ((is_array($_tmp=@$this->_tpl_vars['pageData']['tag'][$this->_tpl_vars['id']][$this->_tpl_vars['params']['ACLTag']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['pageData']['tag'][$this->_tpl_vars['id']]['MAIN']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['pageData']['tag'][$this->_tpl_vars['id']]['MAIN'])); ?>
>
					<?php elseif ($this->_tpl_vars['params']['customCode']): ?>
						<?php echo smarty_function_sugar_evalcolumn(array('var' => $this->_tpl_vars['params']['customCode'],'rowData' => $this->_tpl_vars['rowData']), $this);?>

					<?php elseif ($this->_tpl_vars['params']['currency_format']): ?>
						<?php echo smarty_function_sugar_currency_format(array('var' => $this->_tpl_vars['rowData'][$this->_tpl_vars['col']],'round' => $this->_tpl_vars['params']['currency_format']['round'],'decimals' => $this->_tpl_vars['params']['currency_format']['decimals'],'symbol' => $this->_tpl_vars['params']['currency_format']['symbol']), $this);?>

					<?php elseif ($this->_tpl_vars['params']['type'] == 'bool'): ?>
							<input type='checkbox' disabled=disabled class='checkbox'
							<?php if (! empty ( $this->_tpl_vars['rowData'][$this->_tpl_vars['col']] )): ?>
								checked=checked
							<?php endif; ?>
							/>
					
					<?php else: ?>	
						<?php echo $this->_tpl_vars['rowData'][$this->_tpl_vars['col']]; ?>

					<?php endif; ?>
				</span sugar='sugar<?php echo $this->_tpl_vars['colCounter']; ?>
b'></td>
				<?php echo smarty_function_counter(array('name' => 'colCounter'), $this);?>

			<?php endforeach; endif; unset($_from); ?>
			<?php if (! empty ( $this->_tpl_vars['quickViewLinks'] )): ?>
			<td width='1%' class='<?php echo $this->_tpl_vars['_rowColor']; ?>
S1' bgcolor='<?php echo $this->_tpl_vars['_bgColor']; ?>
' nowrap>
				<?php if ($this->_tpl_vars['pageData']['access']['edit']): ?>
					<a title='<?php echo $this->_tpl_vars['editLinkString']; ?>
' href='index.php?action=EditView&module=<?php echo ((is_array($_tmp=@$this->_tpl_vars['params']['module'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['pageData']['bean']['moduleDir']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['pageData']['bean']['moduleDir'])); ?>
&record=<?php echo ((is_array($_tmp=@$this->_tpl_vars['rowData'][$this->_tpl_vars['params']['id']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['rowData']['ID']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['rowData']['ID'])); ?>
&offset=<?php echo $this->_tpl_vars['pageData']['offsets']['current']+$this->_foreach['rowIteration']['iteration']; ?>
&stamp=<?php echo $this->_tpl_vars['pageData']['stamp']; ?>
&return_module=Home&return_action=index'><img border=0 src=<?php echo $this->_tpl_vars['imagePath']; ?>
edit_inline.gif></a>
				<?php endif; ?>
				<?php if ($this->_tpl_vars['pageData']['access']['view']): ?>
					<a title='<?php echo $this->_tpl_vars['viewLinkString']; ?>
' href='index.php?action=DetailView&module=<?php echo ((is_array($_tmp=@$this->_tpl_vars['params']['module'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['pageData']['bean']['moduleDir']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['pageData']['bean']['moduleDir'])); ?>
&record=<?php echo ((is_array($_tmp=@$this->_tpl_vars['rowData'][$this->_tpl_vars['params']['id']])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['rowData']['ID']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['rowData']['ID'])); ?>
&offset=<?php echo $this->_tpl_vars['pageData']['offsets']['current']+$this->_foreach['rowIteration']['iteration']; ?>
&stamp=<?php echo $this->_tpl_vars['pageData']['stamp']; ?>
&return_module=Home&return_action=index'><img border=0 src=<?php echo $this->_tpl_vars['imagePath']; ?>
view_inline.gif></a>
				<?php endif; ?>
			</td>
			<?php endif; ?>
	    	</tr>
	 	<tr><td colspan='20' class='listViewHRS1'></td></tr>
	<?php endforeach; endif; unset($_from); ?>
</table>
<br/> 
<?php /* Smarty version 2.6.11, created on 2006-09-15 22:07:22
         compiled from modules/ACLRoles/DetailViewBody.tpl */ ?>


<TABLE width='100%' class='tabDetailView' border='0' cellpadding=0 cellspacing = 1  >
<TR>
<td></td>
<?php $_from = $this->_tpl_vars['ACTION_NAMES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ACTION_NAME']):
?>
	<td nowrap class="tabDetailViewDL" style="text-align: center;"><b><?php echo $this->_tpl_vars['ACTION_NAME']; ?>
</b></td>
<?php endforeach; else: ?>

          <td colspan="2">&nbsp;</td>

<?php endif; unset($_from); ?>
</TR>

<?php $_from = $this->_tpl_vars['CATEGORIES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['CATEGORY_NAME'] => $this->_tpl_vars['TYPES']):
?>

<TR>
<td nowrap width='1%' class="tabDetailViewDL" ><b><?php echo $this->_tpl_vars['APP_LIST']['moduleList'][$this->_tpl_vars['CATEGORY_NAME']]; ?>
</b></td>
<?php $_from = $this->_tpl_vars['TYPES']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['TYPE_NAME'] => $this->_tpl_vars['ACTIONS']):
?>
	<?php $_from = $this->_tpl_vars['ACTIONS']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ACTION']):
?>


	<td  class="tabDetailViewDF" width='<?php echo $this->_tpl_vars['TDWIDTH']; ?>
%' align='center'><div align='center' class="acl<?php echo $this->_tpl_vars['ACTION']['accessName']; ?>
"><b><?php echo $this->_tpl_vars['ACTION']['accessName']; ?>
</b></div></td>
	<?php endforeach; endif; unset($_from); ?>
<?php endforeach; endif; unset($_from); ?>

</TR>
	<?php endforeach; else: ?>

         <tr> <td colspan="2">No Actions</td></tr>

<?php endif; unset($_from); ?>
</TABLE>
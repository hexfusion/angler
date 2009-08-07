<?php /* Smarty version 2.6.10, created on 2006-03-08 18:22:04
         compiled from gallery:modules/debug/templates/ShowTreeEntity.tpl */ ?>
<ul>
  <li>
    <?php if (isset ( $this->_tpl_vars['ShowTree']['parentIds'][$this->_tpl_vars['parentIndex']] )): ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/debug/templates/ShowTreeEntityLink.tpl", 'smarty_include_vars' => array('entityId' => $this->_tpl_vars['ShowTree']['parentIds'][$this->_tpl_vars['parentIndex']])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/debug/templates/ShowTreeEntity.tpl", 'smarty_include_vars' => array('parentIndex' => $this->_tpl_vars['parentIndex']+1)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    <?php else: ?>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/debug/templates/ShowTreeEntityLink.tpl", 'smarty_include_vars' => array('entityId' => $this->_tpl_vars['ShowTree']['entityId'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

            <table>
	<?php $this->assign('entityId', $this->_tpl_vars['ShowTree']['entityId']); ?>
	<?php $_from = $this->_tpl_vars['ShowTree']['entityTable'][$this->_tpl_vars['entityId']]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
?>
	  <?php if (strcmp ( $this->_tpl_vars['key'] , '_className' )): ?>
	  <tr>
	    <td>
	      <i><?php echo $this->_tpl_vars['key']; ?>
</i>
	    </td><td>
	      <?php echo $this->_tpl_vars['value']; ?>

	    </td>
	  </tr>
	  <?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
      </table>

            <ul>
	<?php $_from = $this->_tpl_vars['ShowTree']['childIds']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['childId']):
?>
	  <li>
	    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/debug/templates/ShowTreeEntityLink.tpl", 'smarty_include_vars' => array('entityId' => $this->_tpl_vars['childId'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	  </li>
	<?php endforeach; endif; unset($_from); ?>
      </ul>
    <?php endif; ?>
  </li>
</ul>
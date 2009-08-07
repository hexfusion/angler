<?php /* Smarty version 2.6.10, created on 2006-03-08 18:22:04
         compiled from gallery:themes/PGtheme/templates/module.tpl */ ?>
<table width="100%" cellspacing="0" cellpadding="0">
  <tr valign="top">
    <td id="gsSidebarCol">
      <?php echo $this->_reg_objects['g'][0]->theme(array('include' => "sidebar.tpl"), $this);?>

    </td>
    <td>
      <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['theme']['moduleTemplate']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['theme']['moduleL10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </td>
  </tr>
</table>
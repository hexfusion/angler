<?php /* Smarty version 2.6.10, created on 2006-03-08 18:18:12
         compiled from gallery:themes/PGtheme/templates/admin.tpl */ ?>
<?php if ($this->_tpl_vars['user']['isAdmin'] && $this->_tpl_vars['theme']['guestPreviewMode'] != 1): ?>
<table border="0" align="center" width="100%"><tr><td>
<table id="helplink" align="right"><tr><td>
<a href="javascript:void(0)" onclick="javascript:openHelp()" title="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'How to set PG Theme'), $this);?>
">
<?php echo $this->_reg_objects['g'][0]->text(array('text' => ' PG Theme HELP '), $this);?>

</a>
</td></tr></table></td></tr></table>
<?php endif; ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['theme']['adminTemplate']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['theme']['adminL10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
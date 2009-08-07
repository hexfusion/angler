<?php /* Smarty version 2.6.10, created on 2006-03-08 18:22:04
         compiled from gallery:modules/debug/templates/ShowTreeEntityLink.tpl */ ?>
<a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=debug.ShowTree",'arg2' => "entityId=".($this->_tpl_vars['entityId'])), $this);?>
">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => "%d: (%s)",'arg1' => $this->_tpl_vars['entityId'],'arg2' => $this->_tpl_vars['ShowTree']['entityTable'][$this->_tpl_vars['entityId']]['_className']), $this);?>

</a>

<?php if (! empty ( $this->_tpl_vars['ShowTree']['isItem'][$this->_tpl_vars['entityId']] )): ?>
  &nbsp;
  <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "itemId=".($this->_tpl_vars['entityId'])), $this);?>
">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "[browse]"), $this);?>

  </a>
<?php endif; ?>
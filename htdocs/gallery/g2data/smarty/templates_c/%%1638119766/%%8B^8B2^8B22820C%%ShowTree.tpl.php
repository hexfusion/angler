<?php /* Smarty version 2.6.10, created on 2006-03-08 18:22:04
         compiled from gallery:modules/debug/templates/ShowTree.tpl */ ?>
<div id="gsContent" class="gcBorder1">
  <div class="gbBlock gcBackground1">
    <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Debug Tree'), $this);?>
 </h2>
  </div>

  <div class="gbBlock">
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:modules/debug/templates/ShowTreeEntity.tpl", 'smarty_include_vars' => array('parentIndex' => 0)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
  </div>
</div>
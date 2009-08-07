<?php /* Smarty version 2.6.10, created on 2006-03-08 17:05:50
         compiled from gallery:modules/core/templates/AdminModulesVerifyUninstall.tpl */ ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Confirm module uninstall'), $this);?>
 </h2>
</div>

<div class="gbBlock">
  <h3>
    <?php ob_start(); ?><b><?php echo $this->_tpl_vars['AdminModulesVerifyUninstall']['module']['name']; ?>
</b><?php $this->_smarty_vars['capture']['moduleName'] = ob_get_contents(); ob_end_clean(); ?>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Do you really want to uninstall the %s module?",'arg1' => $this->_smarty_vars['capture']['moduleName']), $this);?>

  </h3>
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "This will also remove any permissions and clean up any temporary data created by this module."), $this);?>

  </p>
</div>

<div class="gbBlock gcBackground1">
  <input type="hidden"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'moduleId'), $this);?>
" value="<?php echo $this->_tpl_vars['AdminModulesVerifyUninstall']['module']['id']; ?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][uninstall]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Uninstall'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
</div>
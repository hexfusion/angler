<?php /* Smarty version 2.6.10, created on 2006-06-13 12:26:34
         compiled from gallery:modules/core/templates/ItemEditPhoto.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'gallery:modules/core/templates/ItemEditPhoto.tpl', 15, false),)), $this); ?>
<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Resized Photos'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "These sizes are alternate resized versions of the original you would like to have available for viewing."), $this);?>

  </p>

  <?php if ($this->_tpl_vars['ItemEditPhoto']['editSizes']['can']['createResizes']): ?>
    <?php echo smarty_function_counter(array('start' => 0,'assign' => 'index'), $this);?>

    <?php $_from = $this->_tpl_vars['form']['resizes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['resize']):
?>
      <input type="checkbox"<?php if ($this->_tpl_vars['form']['resizes'][$this->_tpl_vars['index']]['active']): ?> checked="checked"<?php endif; ?>
       name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[resizes][".($this->_tpl_vars['index'])."][active]"), $this);?>
"/>
      <?php echo $this->_reg_objects['g'][0]->dimensions(array('formVar' => "form[resizes][".($this->_tpl_vars['index'])."]",'width' => $this->_tpl_vars['form']['resizes'][$this->_tpl_vars['index']]['width'],'height' => $this->_tpl_vars['form']['resizes'][$this->_tpl_vars['index']]['height']), $this);?>

      <br/>

      <?php if (! empty ( $this->_tpl_vars['form']['error']['resizes'][$this->_tpl_vars['index']]['size']['missing'] )): ?>
      <div class="giError">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'You must enter a valid size'), $this);?>

      </div>
      <?php endif; ?>
      <?php if (! empty ( $this->_tpl_vars['form']['error']['resizes'][$this->_tpl_vars['index']]['size']['invalid'] )): ?>
      <div class="giError">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => "You must enter a number (greater than zero)"), $this);?>

      </div>
      <?php endif; ?>
      <?php echo smarty_function_counter(array(), $this);?>

    <?php endforeach; endif; unset($_from); ?>
  <?php else: ?>
  <b>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "There are no graphics toolkits enabled that support this type of photo, so we cannot create or modify resized versions."), $this);?>

    <?php if ($this->_tpl_vars['user']['isAdmin']): ?>
      <a href="<?php echo $this->_reg_objects['g'][0]->url(array('arg1' => "view=core.SiteAdmin",'arg2' => "subView=core.AdminModules"), $this);?>
">
	<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'site admin'), $this);?>

      </a>
    <?php endif; ?>
  </b>
  <?php endif; ?>
</div>

<?php $_from = $this->_tpl_vars['ItemEdit']['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['option']):
?>
  <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "gallery:".($this->_tpl_vars['option']['file']), 'smarty_include_vars' => array('l10Domain' => $this->_tpl_vars['option']['l10Domain'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endforeach; endif; unset($_from); ?>

<div class="gbBlock gcBackground1">
  <input type="hidden" name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => 'mode'), $this);?>
" value="editSizes"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][save]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Save'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][undo]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Reset'), $this);?>
"/>
</div>
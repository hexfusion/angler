<?php /* Smarty version 2.6.10, created on 2006-03-09 21:05:38
         compiled from gallery:modules/core/templates/ItemMakeHighlight.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'counter', 'gallery:modules/core/templates/ItemMakeHighlight.tpl', 18, false),array('modifier', 'markup', 'gallery:modules/core/templates/ItemMakeHighlight.tpl', 20, false),array('modifier', 'indent', 'gallery:modules/core/templates/ItemMakeHighlight.tpl', 20, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Album Highlight'), $this);?>
 </h2>
</div>

<div class="gbBlock">
  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You can make this item the thumbnail for its parent or any ancestor album."), $this);?>

  </p>
  <p>
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Highlight for:"), $this);?>

    <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[parentId]"), $this);?>
">
    <?php echo smarty_function_counter(array('start' => 0,'assign' => 'count'), $this);?>

    <?php $_from = $this->_tpl_vars['ItemMakeHighlight']['parentList']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['parent']):
?>
      <option value="<?php echo $this->_tpl_vars['parent']['id']; ?>
"> <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['parent']['title'])) ? $this->_run_mod_handler('markup', true, $_tmp) : smarty_modifier_markup($_tmp)))) ? $this->_run_mod_handler('indent', true, $_tmp, $this->_tpl_vars['count'], "-- ") : smarty_modifier_indent($_tmp, $this->_tpl_vars['count'], "-- ")); ?>
 </option>
      <?php echo smarty_function_counter(array('assign' => 'count'), $this);?>

    <?php endforeach; endif; unset($_from); ?>
    </select>
  </p>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][makeHighlight]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Highlight'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
</div>
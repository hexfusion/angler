<?php /* Smarty version 2.6.10, created on 2008-06-29 16:20:04
         compiled from gallery:modules/core/templates/ItemMoveSingle.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'repeat', 'gallery:modules/core/templates/ItemMoveSingle.tpl', 27, false),array('modifier', 'default', 'gallery:modules/core/templates/ItemMoveSingle.tpl', 28, false),)), $this); ?>
<div class="gbBlock gcBackground1">
  <h2> <?php echo $this->_reg_objects['g'][0]->text(array('text' => "Move %s",'arg1' => $this->_tpl_vars['ItemMoveSingle']['itemTypeNames']['0']), $this);?>
 </h2>
</div>

<?php if (isset ( $this->_tpl_vars['status']['moved'] )): ?>
<div class="gbBlock"><h2 class="giSuccess">
  <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Successfully moved'), $this);?>

</h2></div>
<?php endif; ?>

<div class="gbBlock">
  <h3> <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Destination'), $this);?>
 </h3>

  <p class="giDescription">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Choose a destination album'), $this);?>

  </p>

  <select name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[destination]"), $this);?>
" onchange="checkPermissions(this.form)">
    <?php $_from = $this->_tpl_vars['ItemMoveSingle']['albumTree']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['album']):
?>
      <option value="<?php echo $this->_tpl_vars['album']['data']['id']; ?>
" <?php if (( $this->_tpl_vars['album']['data']['id'] == $this->_tpl_vars['form']['destination'] )): ?>selected="selected"<?php endif; ?>>
	<?php echo ((is_array($_tmp="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;")) ? $this->_run_mod_handler('repeat', true, $_tmp, $this->_tpl_vars['album']['depth']) : smarty_modifier_repeat($_tmp, $this->_tpl_vars['album']['depth'])); ?>
--
	<?php echo ((is_array($_tmp=@$this->_tpl_vars['album']['data']['title'])) ? $this->_run_mod_handler('default', true, $_tmp, @$this->_tpl_vars['album']['data']['pathComponent']) : smarty_modifier_default($_tmp, @$this->_tpl_vars['album']['data']['pathComponent'])); ?>

      </option>
    <?php endforeach; endif; unset($_from); ?>
  </select>

  <?php if (isset ( $this->_tpl_vars['form']['error']['destination']['empty'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => 'No destination chosen'), $this);?>

  </div>
  <?php endif; ?>
  <?php if (isset ( $this->_tpl_vars['form']['error']['destination']['selfMove'] )): ?>
  <div class="giError">
    <?php echo $this->_reg_objects['g'][0]->text(array('text' => "You cannot move an album into its own subtree."), $this);?>

  </div>
  <?php endif; ?>
</div>

<div class="gbBlock gcBackground1">
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][move]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Move'), $this);?>
"/>
  <input type="submit" class="inputTypeSubmit"
   name="<?php echo $this->_reg_objects['g'][0]->formVar(array('var' => "form[action][cancel]"), $this);?>
" value="<?php echo $this->_reg_objects['g'][0]->text(array('text' => 'Cancel'), $this);?>
"/>
</div>